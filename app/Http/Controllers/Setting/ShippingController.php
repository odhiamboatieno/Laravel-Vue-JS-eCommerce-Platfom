<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Setting\ShippingCost;

class ShippingController extends Controller
{
    public function index()
    {
        return view('admin.setting.shipping.shipping');
    }

    public function getShipping()
    {
    	return ShippingCost::find(1);
    }

    public function setShipping(Request $request)
    {
    	// $cost = $request->shipping_amount ? $request->shipping_amount : 0;
    	$request->validate([
            'shipping_amount' => 'nullable|regex:/^\d+(\.\d{1,2})?$/',
    		'minimum_order_amount' => 'nullable|regex:/^\d+(\.\d{1,2})?$/',
    		'order_amount' => 'required',
    		'discount_amount' => 'nullable|regex:/^\d+(\.\d{1,2})?$/',
    	]);
    	
        if ($request->shipping_amount < $request->discount_amount) {
           return response()->json(['status'=> 'error', 'message' => 'Discount amount may not grater then Shipping amount']);
        }
    	$result = ShippingCost::find(1);
        $result->shipping_amount = $request->shipping_amount;
    	$result->minimum_order_amount = $request->minimum_order_amount;
    	$result->order_amount 	 = $request->order_amount;
    	$result->discount_amount = $request->discount_amount;
    	$result->shipping_status = $request->shipping_status;
    	$result->discount_status = $request->discount_status;
    	$result->update();
    	return response()->json(['status'=> 'success', 'message' => 'Data Updated Succefull']);
    }

    public function setShippingStatus()
    {
    	$result = ShippingCost::find(1);
    	if($result->shipping_status == 1){
    		$result->shipping_status = 0;
    		$message = 'Shipping Charge Deactivated!';
    	} else {
    		$result->shipping_status = 1;
			$message = 'Shipping Charge Activated Successfully!';
    	}
		$result->update();
		return response()->json(['status' => 'success', 'message' => $message]);
    }

    public function setDiscountStatus()
    {
    	$result = ShippingCost::find(1);
    	if($result->discount_status == 1){
    		$result->discount_status = 0;
    		$message = 'Discount Deactivated!';
    	} else {
    		$result->discount_status = 1;
			$message = 'Discount Activated Successfully!';
    	}
		$result->update();
		return response()->json(['status' => 'success', 'message' => $message]);
    }
}
