<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Model\Setting\PaymentSetting;
use Illuminate\Http\Request;

class PaymentSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.setting.payment.payment');
    }

    public function paymentMethodList()
    {

        $payment_gateway = PaymentSetting::all();

        return $payment_gateway;
    }

    public function frontMethodList()
    {
        // taking all active payment method list without cash on delivery method
        $payment_methods = PaymentSetting::where('status', '=', 1)
            ->where('id', '!=', 1)
            ->get();

            return $payment_methods;

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
        $request->validate([
            'provider' => 'required',
            'client_id' => 'required',
            'client_secret' => 'required',
        ]);

        try
        {
        
            $payment = new PaymentSetting;
            $payment->provider = $request->provider;
            $payment->client_id = $request->client_id;
            $payment->client_secret = $request->client_secret;
            $payment->public_key = $request->public_key;
            $payment->save();
            return response()->json(['status' => 'success', 'message' => 'Payment Credential Created']);

        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Something went wrong !']);
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
        $request->validate([
            'client_id' => 'required',
            'client_secret' => 'required',
            'status' => 'required',
        ]);

        try
        {

            $payment = PaymentSetting::find($id);
            $payment->client_id = $request->client_id;
            $payment->client_secret = $request->client_secret;
            $payment->public_key = $request->public_key;
            $payment->status = $request->status;
            $payment->live_status = $request->live_status;
            $payment->update();
            return response()->json(['status' => 'success', 'message' => 'Payment Credential Updated']);

        } catch (\Exception $e) {
            // return $e;
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
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
        //
    }
}
