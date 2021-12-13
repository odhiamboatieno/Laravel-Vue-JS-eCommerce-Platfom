<?php
namespace App\Http\Controllers\Payment;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;

/** All Paypal Details class **/
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
use Redirect;
use Session;
use URL;
use App\Model\Currency;
use App\Model\Order\Order;
use App\Model\Order\OrderDetails;
use App\Model\Setting\PaymentSetting;
use App\AllStatic;

class PaypalController extends Controller
{
    private $_api_context;
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct()
	{
        /** PayPal api context **/
		$cred = PaymentSetting::where(['provider' => 'paypal','status' => 1])->first();
        $paymode = $cred->live_status == 1 ? 'live' : 'sandbox';
	        /** PayPal api context **/
        $settings = [ 
                array(
                    'mode' => $paymode,
                    'http.ConnectionTimeOut' => 60,
                    'log.LogEnabled' => true,
                    'log.FileName' => storage_path() . '/logs/paypal.log',
                    'log.LogLevel' => 'ERROR'
                ),
            ];
	        $client_id = $cred->client_id;
	        $secret    = $cred->client_secret;
	        $this->_api_context = new ApiContext(new OAuthTokenCredential(
	           $client_id,
	           $secret)
	        );
	        $this->_api_context->setConfig($settings);
	}

    public function payWithpaypal(Request $request,$order_id)
    {


    	$order_data    = Order::find($order_id);
        $details_data  = OrderDetails::with('product:id,product_name,selling_price')
                         ->where('order_id',$order_id)
                         ->get();
    	$currency      = Currency::where('currency_status',1)->first();

        $payer = new Payer();
        $payer->setPaymentMethod('paypal');
   
	    // Set item list
	    $all_item = [];
        $subtotal = 0;
	    foreach ($details_data as $value) {
	    	$item = 'name'.$value->id;
			$item = new Item();
			$item->setName($value->product->product_name)
			  ->setCurrency("$currency->code")
			  ->setQuantity($value->quantity)
			  ->setPrice($value->total_selling_price);
			$all_item[] = $item;
            $subtotal += $value->total_selling_price;
	    }
		$itemList = new ItemList();
		$itemList->setItems($all_item);

        $details = new Details();
        $details->setSubtotal((float) $subtotal - $order_data->coupon_discount)
                // ->setTax(5)
                ->setShipping((float) $order_data->shipping_amount);
                
		// Set payment amount
        $total = ($order_data->total_amount + $order_data->shipping_amount ) - $order_data->coupon_discount;
		$amount = new Amount();
		$amount->setCurrency($currency->code)
		  ->setTotal((float) $total)
          ->setDetails($details);

		$transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($itemList)
            ->setDescription('Your transaction description');
 
        // dd($transaction);
        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl(URL::route('paypal.status',$order_id)) /** Specify return URL **/
            ->setCancelUrl(URL::route('paypal.status',$order_id));

		// Create the full payment object
		  $payment = new Payment();
		    $payment->setIntent('Sale')
		        ->setPayer($payer)
		        ->setRedirectUrls($redirectUrls)
		        ->setTransactions(array($transaction));

        // dd($payment->create($this->_api_context)); exit; 

        try {

            $payment->create($this->_api_context);

        } catch (\PayPal\Exception\PPConnectionException $ex) {
            
            if (\Config::get('app.debug')) {

                return redirect()->route('order.completed',[

                    'flug'   => 1,
                    'status' => 'error',
                    'message' => 'Connection timeout Payment Failed   for  #'.$order_id.'.It\'s currently on cash on delivery 
                    if u want to pay    
                    click on My Order button',
                ]);

            } else {

                return redirect()->route('order.completed',[

                    'flug'   => 1,
                    'status' => 'error',
                    'message' => 'Some error occur, sorry for inconvenient Payment Failed   for  #'.$order_id.'.It\'s currently on cash on delivery 
                    if u want to pay    
                    click on My Order button',
                ]);

            }

        }

        foreach ($payment->getLinks() as $link) {

            if ($link->getRel() == 'approval_url') {

                $redirect_url = $link->getHref();
                break;

            }

        }

        /** add payment ID to session **/
        Session::put('paypal_payment_id', $payment->getId());

        if (isset($redirect_url)) {

            /** redirect to paypal **/
            return Redirect::away($redirect_url);

        }


        return redirect()->route('order.completed',[
			
            'flug'   => 1,
            'status' => 'error',
            'message' => 'Payment Failed   for  #'.$order_id.'.It\'s currently on cash on delivery 
            if u want to pay    
            click on My Order button',
        ]);

    }

    public function getPaymentStatus(Request $request,$order_id)
    {


        /** Get the payment ID before session clear **/
        $payment_id = Session::get('paypal_payment_id');

        /** clear the session payment ID **/
        Session::forget('paypal_payment_id');
        if (empty(\Request::get('PayerID')) || empty(\Request::get('token'))) {


            return redirect()->route('order.completed',[
                
                'flug'   => 1,
                'status' => 'error',
                'message' => 'Payment Failed   for  #'.$order_id.'.It\'s currently on cash on delivery 
                if u want to pay    
                click on My Order button',
            ]);

        }

        $payment = Payment::get($payment_id, $this->_api_context);
        $execution = new PaymentExecution();
        $execution->setPayerId(\Request::get('PayerID'));

        /**Execute the payment **/
        $result = $payment->execute($execution, $this->_api_context);

        if ($result->getState() == 'approved') {
            $order = Order::find($order_id);

           $order->payment_status = AllStatic::$paid;
           $order->payment_method = AllStatic::$paypal;
           $order->payment_date =  date('Y-m-d');
           $order->update();


            return redirect()->route('order.completed',[
                
                'flug'   => 1,
                'status' => 'success',
                'message' => 'Payment sucess for  #'.$order_id.'.to see your order 
                click on My Order button',
            ]);

        }

        return redirect()->route('order.completed',[
			
            'flug'   => 1,
            'status' => 'error',
            'message' => 'Payment Failed   for  order #'.$order_id.'.It\'s currently on cash on delivery 
            if u want to pay    
            click on My Order button',
        ]);

    }

}