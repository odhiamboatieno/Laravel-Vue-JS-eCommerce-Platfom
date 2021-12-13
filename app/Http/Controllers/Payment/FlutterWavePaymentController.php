<?php

namespace App\Http\Controllers\Payment;

use App\AllStatic;
use App\Http\Controllers\Controller;
use App\Model\Order\Order;
use App\Model\Setting\PaymentSetting;
use Illuminate\Http\Request;

class FlutterWavePaymentController extends Controller
{
    //

    public function getPayment($order_id){
     
     $order = Order::with('user')->find($order_id);
     $currency = getCurrentCurrency();
     $payment_info =  PaymentSetting::find(6);
     return view('front.checkout.flutter',[ 'order'=>$order,'currency' => $currency,'payment_info' => $payment_info ]);

    }

    public function successUrl(Request $request)
    {


    if ($request->status == 'successful' || $request->status == 'completed') 
     {
        $order = Order::find($request->tx_ref);
        
        $order->payment_status = AllStatic::$paid;
        $order->payment_method = AllStatic::$flutterwave;
        $order->validation_id  = $request->transaction_id;
        $order->payment_date   =  date('Y-m-d');
        $order->update();

        $status = 'success';
        $message = 'your order no: ' . $request->tx_ref . ' payment successfully
         done if you want to check your  order click on My Order button';
     } 
    else 
     {

        $status = 'error';
        $message = 'your order no: ' . $request->tx_ref . ' payment 
        canceled. But order  taken as cash on delivery if you want to pay now click on My Order button';
     }

    return redirect()->route('order.completed',[
        'flug'    => 1,
        'status'  => $status,
        'message' => $message,
    ]);

    }


}
