<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Model\Setting\Trial;
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
        return view('admin.setting.trial.trial');
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
        $trial = Trial::find(1);
        return $trial;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->validate([
            'product_in_cart'  => 'Required',
            'max_trial_item' => 'Required'
        ]);
        try {
            $result = Trial::first();
            $result->product_in_cart = $request->product_in_cart;
            $result->max_trial_item = $request->max_trial_item;
            $result->trial_charge_per_item = $request->trial_charge_per_item;
            $result->status = $request->status;
            $result->update();
            $message = ['status' => 'success', 'message' => 'Trial Updated Successfully'];
        } catch (Exception $e) {
            $message = ['status' => 'error', 'message' => $e->errorInfo[2]];
        }
        
        return $message;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       
    }
}
