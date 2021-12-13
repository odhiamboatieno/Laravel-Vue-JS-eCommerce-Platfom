<?php

namespace App\Http\Controllers\Order;

use App\AllStatic;
use App\Http\Controllers\Controller;
use App\Model\Order\Order;
use App\Model\Order\OrderDetails;
use App\Model\Order\TrialProduct;
use App\Model\Product;
use App\Model\Setting\ShippingArea;
use App\Model\Setting\ShopSetting;
use DB;
use Illuminate\Http\Request;
use PDF;
use \Mail;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return view('admin.order.order');
    }

    public function viewAreaOrder()
    {

        return view('admin.order.areaOrder');
    }

    public function pdfLoad($id)
    {

        $order = Order::with(['user:id,name,email', 'order_details.product', 'trial_product.product'])->find($id);

        // return view('admin.order.pdf.invoicepdf',['order' => $order]);
        $pdf = PDF::loadView('admin.order.pdf.invoicepdf', ['order' => $order]);

        $pdf->setPaper('A4', 'landscape');
        return $pdf->download("invoice-Pdf-" . $id . ".pdf");
    }

    public function printLoad($id)
    {

        $order = Order::with(['user:id,name,email', 'order_details.product', 'trial_product.product'])->find($id);
        return view('admin.order.pdf.invoiceprint', ['order' => $order]);
    }

    public function orderList(Request $request)
    {

        $order = Order::with(['user:id,name', 'shipping_area:id,city'])
            ->orderBy('id', 'desc');

        if ($request->status != '') {
            $order->where('status', '=', $request->status);
        }

        if ($request->order_id != '') {
            $order->where('id', 'LIKE', '%' . $request->order_id . '%');
        }

        if ($request->city != 'undefined') {
            $order->where('shipping_area_id', '=', $request->city);
        }

        if ($request->payment != '') {
            $order->where('payment_status', '=', $request->payment);
        }

        if ($request->user_id != '') {
            $order->where('user_id', '=', $request->user_id);
        }
        // $order = $order->toSql();
        $order = $order->paginate(10);

        return $order;

    }

    public function getCity()
    {
        return ShippingArea::whereIn('id', Order::distinct()->get(['shipping_area_id']))->get();
    }

    public function show($id)
    {
        $order = Order::with(['user:id,name,email', 'order_details.product', 'trial_product.product'])->find($id);

        return $order;
    }

    public function changePaymentStatus($id)
    {
        $data = Order::find($id);
        if ($data->payment_status == 1) {
            $data->payment_status = AllStatic::$inactive;
            $data->payment_date   = null;
            $data->payment_method = AllStatic::$not_paid;
            $message              = 'Order Change as  Unpaid';
        } else {
            $data->payment_status = AllStatic::$active;
            $data->payment_method = AllStatic::$cash;
            $data->payment_date   = date('Y-m-d');
            $message              = 'Order Change as Paid';
        }
        $data->update();
        return response()->json(['status' => 'success', 'message' => $message]);
    }

    public function changeProcessStatus($value, $id)
    {
        $data         = Order::find($id);
        $data->status = $value;
        if ($value == 3) {
            $data->delivery_date = date('Y-m-d');
        }
        $data->update();
        return response()->json(['status' => 'success', 'message' => 'Delivary status is changed!']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
            DB::beginTransaction();
            $details = OrderDetails::where('order_id', $id)->get();
            foreach ($details as $value) {
                $product = Product::find($value->product_id);
                $product->current_quantity += $value->quantity;
                $product->update();
            }
            $data = Order::find($id)->delete();
            OrderDetails::where('order_id', $id)->delete();
            TrialProduct::where('order_id', $id)->delete();

            DB::commit();

            return response()->json(['status' => 'success', 'message' => 'Order Has been Deleted!']);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => 'Something Went Wrong!']);
        }
    }

    // delete single product on order invoice

    public function deleteSingleOrder($order_id, $id)
    {
        try {
            DB::beginTransaction();
            // the single item which will be deleted
            $order_item = OrderDetails::find($id);

            // stock in the deleted item
            $product = Product::find($order_item->product_id);
            $product->current_quantity += $order_item->quantity;
            $product->update();

            $order_item->delete();

            // calculate current order value and update order table
            $order_details = OrderDetails::select(DB::raw('sum(total_buying_price) as buying_amount,
                             sum(quantity) as total_quantity,sum(total_selling_price) as total_amount,
                             sum(total_discount) as discount'))
                ->groupBy('order_id')
                ->where('order_id', '=', $order_id)
                ->first();

            if ($order_details) {
                $order = Order::find($order_id);

                $order->total_item          = $order_details->total_quantity;
                $order->total_amount        = $order_details->total_amount;
                $order->total_buying_amount = $order_details->buying_amount;
                $order->discount            = $order_details->discount;
                $order->update();

            }

            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'One Item Removed']);
        } catch (\Exception $e) {
            DB::rollBack();
            // return $e;
            return response()->json(['status' => 'error', 'message' => 'Something Went Wrong!']);
        }

    }

    // increment or decrement single product on order invoice

    public function increamentSingleItem(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            // the single item which will be deleted
            $order_item = OrderDetails::find($id);

            // stock in the deleted item
            $product = Product::find($order_item->product_id);
            if ($request->type == 'increment') {

                // if not in stock

                if ($product->current_quantity <= 0) {

                    return response()->json(['status' => 'error', 'message' => 'Stock Not Available !']);

                }

                $product->current_quantity -= 1;
            } else {
                $product->current_quantity += 1;
            }

            $product->update();

            // updating order details table
            if ($request->type == 'increment') {

                $order_item->quantity += 1;

            } else {
                $order_item->quantity -= 1;

            }

            $order_item->total_selling_price = ($order_item->selling_price * ($order_item->quantity));
            $order_item->total_buying_price  = ($order_item->buying_price * ($order_item->quantity));

            $order_item->total_discount = ($order_item->unit_discount * ($order_item->quantity));

            $order_item->update();

            // calculate current order value and update order table
            $order_details = OrderDetails::select(DB::raw('sum(total_buying_price) as buying_amount,
                             sum(quantity) as total_quantity,sum(total_selling_price) as total_amount,
                             sum(total_discount) as discount'))
                ->groupBy('order_id')
                ->where('order_id', '=', $order_item->order_id)
                ->first();

            if ($order_details) {
                $order = Order::find($order_item->order_id);

                $order->total_item          = $order_details->total_quantity;
                $order->total_amount        = $order_details->total_amount;
                $order->total_buying_amount = $order_details->buying_amount;
                $order->discount            = $order_details->discount;
                $order->update();

            }

            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Qty Updated']);
        } catch (\Exception $e) {
            DB::rollBack();
            // return $e;
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }

    }

    // add to invoice from trial

    public function trialToInvoice(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            // the trial item which will be added to order details
            $trial_item = TrialProduct::find($id);

            // stock out new item
            $product = Product::find($trial_item->product_id);
            $product->current_quantity -= 1;
            $product->update();

            // confirm this trial item is invoiced

            $trial_item->invoiced = 1;
            $trial_item->update();

            // add new item into order details table

            $details                      = new OrderDetails;
            $details->order_id            = $trial_item->order_id;
            $details->category_id         = $trial_item->category_id;
            $details->sub_category_id     = $trial_item->sub_category_id;
            $details->sub_sub_category_id = $trial_item->sub_sub_category_id;
            $details->brand_id            = $trial_item->brand_id ? $trial_item->brand_id : 0;
            $details->product_id          = $trial_item->product_id;
            $details->size_id             = $trial_item->size_id;
            $details->color_id            = $trial_item->color_id;
            $details->user_id             = $trial_item->user_id;
            $details->quantity            = 1;
            $details->selling_price       = $trial_item->selling_price;
            $details->buying_price        = $trial_item->buying_price;
            $details->total_buying_price  = $trial_item->total_buying_price;
            $details->total_selling_price = $trial_item->total_selling_price;
            $details->unit_discount       = $trial_item->unit_discount;
            $details->total_discount      = $trial_item->total_discount;
            $details->save();

            // update order table with latest price

            $order = Order::find($trial_item->order_id);
            $order->total_item += 1;
            $order->total_amount += $trial_item->total_selling_price;
            $order->total_buying_amount += $trial_item->total_buying_price;
            $order->discount += $trial_item->discount;
            $order->update();

            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Product Added To Invoice']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }

    }

    // delete single trial item

    public function deleteTrialItem($id)
    {
        try {

            $trial_item = TrialProduct::find($id);
            $trial_item->delete();
            return response()->json(['status' => 'success', 'message' => 'Item Deleted']);

        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    // order tracking

    public function orderTrack()
    {
        return view('front.order-track.order_tracking');
    }

    public function orderTrackPost(Request $request)
    {
        $order = Order::with(['user:id,name,email', 'order_details.product'])
            ->whereId($request->order_id)
            ->first();

        return $order;
    }

    // order sucess return

    public function orderSuccess(Request $request)
    {
        // preventing visiting order page while not on checkout
        if ($request->has('flug')) {

            return view('front.checkout.order_completed',
                [
                    'status'  => $request->status,
                    'message' => $request->message,
                ]
            );

        } else {
            return redirect('/');
        }

    }

    public function orderDetails($id)
    {
        return OrderDetails::with('product:id,product_name,product_image')->where('order_id', '=', $id)->get();
    }

    public function sendMail(Request $request)
    {
        $data = Order::with('user')->find($request->order_id);
        $to   = $data->user['email'];
        if (!$to) {
            return response()->json(['status' => 'error', 'message' => 'User Not Having an Email !']);
        } else {
            $shop_info = ShopSetting::orderBy('id', 'desc')->first();
            $subject   = $request->subject;
            $text_body = $request->text_body;
            $email     = $shop_info->email;
            $name      = $shop_info->shop_name;
            $subject   = 'Order Invoice No-' . $request->order_id . ' from ' . $name;

            Mail::send('admin.email.invoice_email_temp', ['sms' => $request->message, 'orderdetails' => $this->orderDetails($request->order_id), 'data' => $data],
                function ($message) use ($to, $email, $name, $subject) {
                    $message->from($email, $name);
                    $message->to($to)->subject($subject);
                });
            return response()->json(['status' => 'success', 'message' => 'Invoice Send Successfull']);
        }
    }
}
