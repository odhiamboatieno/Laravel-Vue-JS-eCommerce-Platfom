<?php

namespace App\Http\Controllers\Cart;

use App\AllStatic;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Model\Coupon\UserCoupon;
use App\Model\Order\Order;
use App\Model\Order\OrderDetails;
use App\Model\Order\TrialProduct;
use App\Model\Product;
use App\Model\Setting\PaymentSetting;
use App\Model\Setting\ShippingCost;
use App\User;
use Auth;
use Cart;
use DB;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function shippingAmount()
    {

        $shipping = ShippingCost::find(1);
        return $shipping;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function CartItem()
    {

        $cart_items = Cart::instance('shopping')->content();
        $cart_total = Cart::instance('shopping')->subtotal();
        $cart_count = Cart::instance('shopping')->count();

        return response()->json([
            'cart_items' => $cart_items,
            'cart_total' => (float) $cart_total,
            'cart_count' => $cart_count,
        ]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        try {

            $id           = $request->id;
            $product_name = $request->product_name;
            $qty          = $request->qty;
            $price        = $request->price;
            $current_qty  = $request->current_qty;
            $image        = $request->product_image;
            $qty_unit     = $request->qty_unit;
            $discount     = $request->discount;

            if ($qty_unit != '') {
                $product_name = $product_name . '(' . $qty_unit . ')';
            }

            // checking available in stock
            if ($qty > $current_qty) {
                return response()->json(['status' => 'error', 'message' => 'Product out of Stock']);

                return 'error';

            }
            $cart = Cart::instance('shopping')->content()->where('id', $id)->first();

            // checking if cart have the product alredy
            if ($cart) {

                if ($cart->qty + $qty > $current_qty) {
                    return response()->json(['status' => 'error', 'message' => 'Product out of Stock']);
                }

            }

            Cart::instance('shopping')->add(['id' => $id,
                'name'                                => $product_name,
                'qty'                                 => $qty,
                'price'                               => $price,
                'weight'                              => 0,
                'options'                             =>
                [
                    'image'      => $image,
                    'stock'      => $current_qty,
                    'discount'   => (float) $discount,
                    'size_id'    => $request->size_id,
                    'size_name'  => $request->size_name,
                    'color_id'   => $request->color_id,
                    'color_name' => $request->color_name,
                    'color_code' => $request->color_code,
                ]
                , 'discount' => 0]);

            return response()->json(['status' => 'success', 'message' => 'Product Added To Cart']);

        } catch (\Exception $e) {

            return $e;

        }
    }

    public function checkOutPage()
    {
        $payment_method = PaymentSetting::select('id', 'provider')->where('status', '=', 1)->get();
        return view('front.checkout.checkout', [
            'payment_method' => $payment_method,
        ]);

    }

    public function checkoutStore(OrderRequest $request)
    {
        try
        {

            $shipping_cost = ShippingCost::orderBy('id')->first();

            if ($request->cart_total < $shipping_cost->minimum_order_amount) {

                \Session::flash('error', 'minimum shipping amount is ' . $shipping_cost->minimum_order_amount);
                return redirect()->back();

            }

            // if discount amount greater than shopping amount

            if ($request->cart_total < $shipping_cost->coupon_discount) {

                \Session::flash('error', 'Opps! discount can\'t be greater than shopping amount');
                return redirect()->back();

            }

            $order_id = generate_order_no();

            DB::beginTransaction();

            $total_discount      = 0;
            $total_buying_amount = 0;

            foreach (Cart::instance('shopping')->content() as $value) {

                $product = Product::find($value->id);

                $details = new OrderDetails;

                $details->order_id            = $order_id;
                $details->category_id         = $product->category_id;
                $details->sub_category_id     = $product->sub_category_id;
                $details->sub_sub_category_id = $product->sub_sub_category_id;
                $details->brand_id            = $product->brand_id ? $product->brand_id : 0;
                $details->product_id          = $value->id;
                $details->size_id             = $value->options->size_id;
                $details->color_id            = $value->options->color_id;
                $details->user_id             = Auth::user()->id;
                $details->quantity            = $value->qty;
                $details->selling_price       = $value->price;
                $details->buying_price        = $product->buying_price;
                $details->total_buying_price  = $product->buying_price * $value->qty;
                $details->total_selling_price = $value->price * $value->qty;
                $details->unit_discount       = $value->options->discount;
                $details->total_discount      = $value->options->discount * $value->qty;

                $details->save();

                // total discount
                $total_discount += $value->qty * $value->options->discount;
                // total buying amount
                $total_buying_amount += $value->qty * $product->buying_price;
                // minus from stock
                $product->current_quantity = $product->current_quantity - $value->qty;
                $product->total_sold       = $product->total_sold + $value->qty;

                $product->update();

            }

            // if trial cart have product

            if (Cart::instance('trial')->count() > 0) {

                foreach (Cart::instance('trial')->content() as $trial_value) {

                    $tr_product = Product::find($trial_value->id);

                    $trial = new TrialProduct;

                    $trial->order_id            = $order_id;
                    $trial->category_id         = $tr_product->category_id;
                    $trial->sub_category_id     = $tr_product->sub_category_id;
                    $trial->sub_sub_category_id = $tr_product->sub_sub_category_id;
                    $trial->brand_id            = $tr_product->brand_id ? $tr_product->brand_id : 0;
                    $trial->product_id          = $trial_value->id;
                    $trial->size_id             = $trial_value->options->size_id;
                    $trial->color_id            = $trial_value->options->color_id;
                    $trial->user_id             = Auth::user()->id;
                    $trial->quantity            = $trial_value->qty;
                    $trial->selling_price       = $trial_value->price;
                    $trial->buying_price        = $tr_product->buying_price;
                    $trial->total_buying_price  = $tr_product->buying_price * $trial_value->qty;
                    $trial->total_selling_price = $trial_value->price * $trial_value->qty;
                    $trial->unit_discount       = $trial_value->options->discount;
                    $trial->total_discount      = $trial_value->options->discount * $trial_value->qty;

                    $trial->save();

                }

            }

            // Saving invoice infromation
            $order = new Order;

            $order->id                     = $order_id;
            $order->user_id                = Auth::user()->id;
            $order->shipping_area_id       = $request->delivery_area;
            $order->customer_name          = $request->name;
            $order->phone                  = $request->phone;
            $order->email                  = $request->email;
            $order->address                = $request->address;
            $order->note                   = $request->note;
            $order->shipping_amount        = $request->delivery_cost;
            $order->total_item             = Cart::instance('shopping')->count();
            $order->total_amount           = $request->cart_total;
            $order->coupon_discount        = $request->coupon_discount;
            $order->cupon                  = $request->coupon_code;
            $order->discount               = $total_discount;
            $order->total_buying_amount    = $total_buying_amount;
            $order->payment_status         = 0;
            $order->payment_method         = 0;
            $order->order_date             = date('Y-m-d');
            $order->customer_delivery_date = $request->delivery_date;
            $order->customer_delivery_time = $request->delivery_time;
            $order->status                 = 0;
            $order->save();

            // if any coupon code used then update user coupon
            if ($request->coupon_code != '') {
                UserCoupon::where('user_id', '=', Auth::user()->id)
                    ->where('coupon_code', '=', $request->coupon_code)
                    ->update(['is_used' => 1]);
            }

            // if information checked to update for next time in profile

            if ($request->profile_update == 1) {
                $user              = User::find(Auth::user()->id);
                $user->name        = $request->name;
                $user->location_id = $request->delivery_area;
                $user->phone       = $request->phone;
                $user->address     = $request->address;
                $user->update();

            }

            DB::commit();

            Cart::instance('trial')->destroy();
            Cart::instance('shopping')->destroy();

            if ($request->payment_method == AllStatic::$cash) {
                return redirect()->route('order.completed', [
                    'flug'    => 1,
                    'status'  => 'success',
                    'message' => 'Order no #' . $order_id . '. taken as  cash on delivery
                    if u want to pay now
                    click on My Order button ',
                ]);
            } elseif ($request->payment_method == AllStatic::$paypal) {
                return redirect()->route('paypal', $order_id);
            } elseif ($request->payment_method == AllStatic::$stripe) {
                // return $request->payment_method;
                return redirect()->route('addmoney.stripe',
                    [
                        'order_id'     => $order_id,
                        'cvc'          => $request->cvvNumber,
                        'card_no'      => $request->card_no,
                        'expire_month' => $request->expire_month,
                        'expire_year'  => $request->expire_year,
                        'state'        => 'order',
                    ]);
            } elseif ($request->payment_method == AllStatic::$ssl) {
                return redirect()->route('payment.ssl', $order_id);
            } elseif ($request->payment_method == AllStatic::$razor) {
                return redirect()->route('Razorpay', $order_id);
            } elseif ($request->payment_method == AllStatic::$flutterwave) {
                return redirect()->route('flutter.payment', $order_id);
            } else {

                // default order will save as cash on delivery

                return redirect()->route('order.completed', [
                    'flug'    => 1,
                    'status'  => 'success',
                    'message' => 'Order no #' . $order_id . '. taken as  cash on delivery
                    if u want to pay now
                    click on My Order button',
                ]);
            }

        } catch (\Exception $e) {

            DB::rollback();
            return response()->json(['status' => 'success', 'message' => $e->getMessage()]);

        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id, $status)
    {
        $cart = Cart::instance('shopping')->get($id);

        if ($status == 'decrement') {
            $qty = $cart->qty - 1;

            Cart::instance('shopping')->update($id, $qty);

            return response()->json(['status' => 'success', 'message' => 'Item Decreased']);
        } else {

            if ($cart->qty + 1 > $cart->options->stock) {
                return response()->json(['status' => 'error', 'message' => 'out of stock']);
            } else {

                Cart::instance('shopping')->update($id, $cart->qty + 1);
                return response()->json(['status' => 'success', 'message' => 'Item Increased']);

            }

        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {

            Cart::instance('shopping')->remove($id);

            return response()->json(['status' => 'success', 'message' => 'item removed']);
        } catch (\Exception $e) {
            return $e;
        }
    }

}
