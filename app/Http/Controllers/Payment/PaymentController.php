<?php

namespace App\Http\Controllers\Payment;

use App\AllStatic;
use App\Http\Controllers\Controller;
use App\Model\Currency;
use App\Model\Order\Order;
use App\Model\Order\OrderDetails;
use App\Model\Product;
use App\Model\Setting\PaymentSetting;
use Illuminate\Http\Request;
use Auth;

class PaymentController extends Controller
{
    public function sslCommerz(Request $request,$order_id)
    {
        $order = Order::with('user', 'shipping_area')->find($order_id);

        $order_details = OrderDetails::with('product:id,product_name,selling_price')->where('order_id', $order_id)->get();
// payment gateway data
        $payment_gateway = PaymentSetting::where('provider', '=', 'ssl-commerz')->first();
// getting current currency
        $currency = Currency::where('currency_status', 1)->first();

        $post_data = array();
        $post_data['store_id'] = $payment_gateway->client_id;
        $post_data['store_passwd'] = $payment_gateway->client_secret;
        $post_data['total_amount'] = ($order->total_amount + $order->shipping_amount ) - $order->coupon_discount;
        $post_data['currency'] = $currency->code;
        $post_data['tran_id'] = $order_id;
        $post_data['success_url'] = route('ssl.success');
        $post_data['fail_url'] = route('ssl.failed');
        $post_data['cancel_url'] = route('ssl.cancel');
# $post_data['multi_card_name'] = "mastercard,visacard,amexcard";  # DISABLE TO DISPLAY ALL AVAILABLE

# EMI INFO
        $post_data['emi_option'] = "1";
        $post_data['emi_max_inst_option'] = "9";
        $post_data['emi_selected_inst'] = "9";

# CUSTOMER INFORMATION
        $post_data['cus_name'] = $order->user->name;
        $post_data['cus_email'] = $order->user->email;
        $post_data['cus_add1'] = $order->shipping_area->city;
        $post_data['cus_add2'] = $order->shipping_area->city;
        $post_data['cus_city'] = $order->shipping_area->city;
        $post_data['cus_state'] = $order->shipping_area->city;
        $post_data['cus_postcode'] = "1000";
        $post_data['cus_country'] = "Bangladesh";
        $post_data['cus_phone'] = $order->user->phone ? $order->user->phone : $order->phone;
        $post_data['cus_fax'] = "01711111111";

# SHIPMENT INFORMATION
        $post_data['ship_name'] = "testlimme56kq";
        $post_data['shipping_method'] = "NO";
        $post_data['ship_add1 '] = "Dhaka";
        $post_data['ship_add2'] = "Dhaka";
        $post_data['ship_city'] = "Dhaka";
        $post_data['ship_state'] = "Dhaka";
        $post_data['ship_postcode'] = "1000";
        $post_data['ship_country'] = "Bangladesh";

# OPTIONAL PARAMETERS
        $post_data['value_a'] = Auth::user()->id;
        // $post_data['value_b '] = "ref002";
        // $post_data['value_c'] = "ref003";
        // $post_data['value_d'] = "ref004";

# CART PARAMETERS
        $product_name = [];

        foreach ($order_details as $value) {
            $arr = [];
            $arr['product']  =  $value->product->product_name;
            $arr['amount']   =  $value->total_selling_amount;

            array_push($product_name, $arr);
        }

        $post_data['product_name'] = json_encode($product_name);

        $post_data['product_category'] = "online shop";
        $post_data['product_profile'] = "general";
        $post_data['product_amount'] = ($order->total_amount + $order->shipping_amount ) - $order->coupon_discount;
        $post_data['vat'] = "0";
        $post_data['discount_amount'] = "0";
        $post_data['convenience_fee'] = "0";
        if($payment_gateway->live_status == 1 )
        {
        $direct_api_url = "https://securepay.sslcommerz.com/gwprocess/v4/api.php";
        }
        else
        {
        $direct_api_url = "https://sandbox.sslcommerz.com/gwprocess/v4/api.php";
        }

        $handle = curl_init();
        curl_setopt($handle, CURLOPT_URL, $direct_api_url);
        curl_setopt($handle, CURLOPT_TIMEOUT, 30);
        curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($handle, CURLOPT_POST, 1);
        curl_setopt($handle, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false); # KEEP IT FALSE IF YOU RUN FROM LOCAL PC

        $content = curl_exec($handle);

        $code = curl_getinfo($handle, CURLINFO_HTTP_CODE);

        if ($code == 200 && !(curl_errno($handle))) {
            curl_close($handle);
            $sslcommerzResponse = $content;
        } else {
            curl_close($handle);
            echo "FAILED TO CONNECT WITH SSLCOMMERZ API";
            exit;
        }

# PARSE THE JSON RESPONSE
        $sslcz = json_decode($sslcommerzResponse, true);

        if (isset($sslcz['GatewayPageURL']) && $sslcz['GatewayPageURL'] != "") {

            // redirect to the ssl commerz generated url for taking payment

            return redirect($sslcz['GatewayPageURL']);


            exit;
        } else {

            return redirect()->route('order.completed',[
                'flug'    => 1,
                'status'  => 'error',
                'message' => 'pyament failed for json parse error but your order currenty on  cash on delivery
                if u want to pay click on My Order button',
            ]);
        }

    }

    public function sslCommerzSuccess(Request $request)
    {
                $status   = '';
                $message  = '';

        if ($request->status == "VALID") {

            $order = Order::find($request->tran_id);

            $order->payment_status = AllStatic::$paid;
            $order->payment_method = AllStatic::$ssl;
            $order->card_type = $request->card_type;
            $order->validation_id = $request->val_id;
            $order->payment_date =  date('Y-m-d');
            $order->update();

            if(!Auth::check()){
               
                Auth::loginUsingId($request->value_a);
            }
            $status = 'success';
            $message = 'your order no: ' . $request->tran_id . ' payment successfully
             done if you want to check your  order click on My Order button';

        }
         elseif ($request->status == 'FAILED') 
         {

            $status = 'error';
            $message = 'your order no: ' . $request->tran_id . ' payment 
             failed. but your order  on  cash on delivery if you want to pay now click on My Order button';
        } 
        else 
        {
    
            $status = 'error';
            $message = 'your order no: ' . $request->tran_id . ' payment 
            canceled. But order  taken as cash on delivery if you want to pay now click on My Order button';
        }

        return redirect()->route('order.completed',[
            'flug'    => 1,
            'status'  => $status,
            'message' => $message,
        ]);

    }

    public function sslCommerzFailed(Request $request)
    {

        $status  = 'error';
        $message = '';

        if(!Auth::check()){
               
            Auth::loginUsingId($request->value_a);
        }
        if ($request->status == 'FAILED') {

            $message = 'payment failed for order #'.$request->tran_id.' due to ' . $request->error. ' but order taken as 
            cash on delivery if u wants to pay click on My Order button';

        } 
        else {

            $message = 'something went wrong and your payment failed  for order #'.$request->tran_id.' but order taken as 
            cash on delivery if u wants to pay click on My Order button';
        }

        return redirect()->route('order.completed',[

            'flug'   => 1,
            'status' => $status,
            'message' => $message,
        ]);

    }

    public function sslCommerzCancel(Request $request)
    {

        // return $request->all();
        if(!Auth::check()){
               
            Auth::loginUsingId($request->value_a);
        }
        return redirect()->route('order.completed',[

            'flug'   => 1,
            'status' => 'error',
            'message' => 'the payment hasbeen canceled for order #'.$request->tran_id.'.but order taken as 
            cash on delivery if u wants to pay click on My Order button',
        ]);

    }

}
