<?php

namespace App\Http\Controllers\Trial;

use App\Http\Controllers\Controller;
use App\Model\Setting\Trial;
use Cart;
use Illuminate\Http\Request;

class TrialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return Cart::instance('trial')->content();

    }

    public function TrialItem()
    {

        $cart_items = Cart::instance('trial')->content();
        $cart_total = Cart::instance('trial')->subtotal();
        $cart_count = Cart::instance('trial')->count();

        return response()->json([
            'trial_items' => $cart_items,
            'tiral_total' => (float) $cart_total,
            'trial_count' => $cart_count,
        ]);

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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        try
        {

            $trial_setting = Trial::orderBy('id', 'desc')->first();
            // Cart::instance('trial')->destroy();
            $id           = $request->id;
            $product_name = $request->product_name;
            $qty          = $request->qty;
            $price        = $request->price;
            $current_qty  = $request->current_qty;
            $image        = $request->product_image;
            $qty_unit     = $request->qty_unit;
            $discount     = $request->discount;

            // check cart item before trial
            if (Cart::instance('shopping')->count() <= $trial_setting->product_in_cart) {

                return response()->json(['status' => 'error', 'message' => 'Please add atleast 1 product in cart to get  trial facility']);
            }

            // checking total trial item

            if (Cart::instance('trial')->count() >= $trial_setting->max_trial_item) {

                $trial_over_message = 'Opps! Maximum trial product is ' . $trial_setting->max_trial_item;
                return response()->json(['status' => 'error', 'message' => $trial_over_message]);
            }

            $cart = Cart::instance('trial')->content()->where('id', $id)->first();

            if ($cart) {
                return response()->json(['status' => 'error', 'message' => 'Product Already added in trial']);
            }

            if ($qty_unit != '') {
                $product_name = $product_name . '(' . $qty_unit . ')';
            }

            Cart::instance('trial')->add(
                [
                    'id'      => $id,
                    'name'    => $product_name,
                    'qty'     => $qty,
                    'price'   => $price,
                    'weight'  => 0,
                    'options' =>
                    [
                        'image'      => $image,
                        'discount'   => (float) $discount,
                        'size_id'    => $request->size_id,
                        'size_name'  => $request->size_name,
                        'color_id'   => $request->color_id,
                        'color_name' => $request->color_name,
                        'color_code' => $request->color_code,
                    ]
                    , 'discount' => 0]);

            return response()->json(['status' => 'success', 'message' => 'Added To Trial']);

        } catch (\Exception $e) {

            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);

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
    public function update(Request $request, $id)
    {

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

            Cart::instance('trial')->remove($id);

            return response()->json(['status' => 'success', 'message' => 'item removed']);
        } catch (\Exception $e) {
            return $e;
        }
    }
}
