<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Razorpay\Api\Api;
use Illuminate\Support\Str;
use App\Model\Order\Order;
use App\Model\Setting\PaymentSetting;
use App\Model\Currency;
use App\AllStatic;

class RazorpayController extends Controller
{
    private $razorpayId = '';
    private $razorpayKey = '';

     public function __construct()
	{
        /** Razor api credential **/
		$cred = PaymentSetting::where(['provider' => 'Razorpay','status' => 1])->first();

	    $this->razorpayId = $cred->client_id;
	    $this->razorpayKey = $cred->client_secret;
	}

    public function Initiate(Request $request,$order_id)
    {
        // return $this->razorpayId.'------>-----'.$this->razorpayKey;
        // Let's see the documentation for creating the order
    	$order_data    = Order::find($order_id);
        // Generate random receipt id
        $receiptId = $order_id;
        $currency  = Currency::where('currency_status',1)->first();

        $api = new Api($this->razorpayId, $this->razorpayKey);

        // In razorpay you have to convert rupees into paise we multiply by 100
        // Currency will be INR
        // Creating order
        $final_amount = ($order_data->total_amount + $order_data->shipping_amount ) - $order_data->coupon_discount;

        $order = $api->order->create(array(
            'receipt' => $receiptId,
            'amount' => $final_amount * 100,
            'currency' => 'INR'
            )
        );

        // Let's return the response 
        $user_orderid = $order_data->id;
        // Let's create the razorpay payment page
        $response = [
            'orderId' => $order['id'],
            'razorpayId' => $this->razorpayId,
            'amount' =>   $final_amount * 100,
            'name' => $order_data->customer_name,
            'currency' => "$currency->code",
            'email' => $order_data->email,
            'contactNumber' => $order_data->phone,
            'address' => $order_data->address,
            'description' => 'Stay With Us!',
        ];

        // Let's checkout payment page is it working
        return view('front.payment.razor',compact('response','user_orderid'));
    }


    public function Complete(Request $request)
    {
        // Let's print the payment response data
        $dataId = $request->all()['user_orderid'];
        // Now verify the signature is correct . We create the private function for verify the signature
        $signatureStatus = $this->SignatureVerify(
            $request->all()['rzp_signature'],
            $request->all()['rzp_paymentid'],
            $request->all()['rzp_orderid']
        );

        // we send the response to Success page if payment successfully made
        if($signatureStatus == true)
        {
        	$order = Order::find($dataId);

		    $order->payment_status = AllStatic::$paid;
		    $order->payment_method = AllStatic::$razor;
		    $order->payer_id       = $request->all()['rzp_orderid'];
            $order->payment_id     = $request->all()['rzp_paymentid'];
            $order->validation_id  = $request->all()['rzp_signature'];
            $order->payment_date   = date('Y-m-d');
		    $order->update();

            return redirect()->route('order.completed',[
                
                'flug'   => 1,
                'status' => 'success',
                'message' => 'Payment sucess for  #'.$dataId.'.to see your order 
                click on My Order button',
            ]);
        }
        else{
            return redirect()->route('order.completed',[
			
		        'flug'   => 1,
		        'status' => 'error',
		        'message' => 'Payment Failed   for  order #'.$dataId.'.It\'s currently on cash on delivery 
		        if u want to pay    
		        click on My Order button',
		    ]);
        }
    }

    // In this function we return boolean if signature is correct
    private function SignatureVerify($_signature,$_paymentId,$_orderId)
    {
        try
        {
            $api = new Api($this->razorpayId, $this->razorpayKey);
            $attributes  = array('razorpay_signature'  => $_signature,  'razorpay_payment_id'  => $_paymentId ,  'razorpay_order_id' => $_orderId);
            $order  = $api->utility->verifyPaymentSignature($attributes);
            return true;
        }
        catch(\Exception $e)
        {
            // If Signature is not correct its give a excetption so we use try catch
            return false;
        }
    }
}
