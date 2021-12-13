<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Currency;

class CurrencyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.setting.currency.currency');
    }

    public function currencyList(Request $request)
    {
        $currency = Currency::orderBy('currency_status','desc');

        if ($request->keyword) {
            
            $currency->where('country','LIKE','%'.$request->keyword.'%')
                     ->orwhere('currency','LIKE','%'.$request->keyword.'%')
                     ->orwhere('code','LIKE','%'.$request->keyword.'%')
                     ->orwhere('symbol','LIKE','%'.$request->keyword.'%');
        }

        $currency = $currency->paginate(15);
      
        return $currency;
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
            
            'country'  => 'Required',
            'currency' => 'Required',
            'code'     => 'Required',
            'symbol'   => 'Required',
        ]);

        try
        {

         $currency = new Currency;
         $currency->country   =  $request->country;
         $currency->currency  =  $request->currency; 
         $currency->code      =  $request->code; 
         $currency->symbol    =  $request->symbol;
         $currency->save(); 
         
        //  clear front currency cache 
        cache()->forget('currency');

         return response()->json(['status'=>'success','message' => 'Currency Added']);

        }
        catch(\Exception $e)
        {

            return response()->json(['status' => 'error' , 'message' => 'Oops Something Went Wrong !']);

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
        return Currency::find($id);
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
            
            'country'  => 'Required',
            'currency' => 'Required',
            'code'     => 'Required',
            'symbol'   => 'Required',
        ]);

        try
        {

         $currency = Currency::find($id);
         $currency->country   =  $request->country;
         $currency->currency  =  $request->currency; 
         $currency->code      =  $request->code; 
         $currency->symbol    =  $request->symbol;
         $currency->update(); 
        
         //  clear front currency cache 
        cache()->forget('currency');

         return response()->json(['status'=>'success','message' => 'Currency Updated']);
         
        }
        catch(\Exception $e)
        {

            return response()->json(['status' => 'error' , 'message' => 'Oops Something Went Wrong !']);

        }
    }


    public function makeCurrentCurrency($id)
    {

        try{

            $active   = 1;
            $inactive = 0;

            Currency::where('currency_status','=',$active)->update(['currency_status'=>$inactive]);

            $currency = Currency::find($id);
            $currency->currency_status = $active;
            $currency->update();
            //  clear front currency cache 
            cache()->forget('currency');

            return response()->json(['status'=>'success','message' => 'Current Currency Changed!']);

        }
        catch(\Exception $e)
        {
     
            return response()->json(['status'=>'error','message' => 'something went wrong!']);
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
        $currency = Currency::find($id);

        // check is it the active currency then it's not possible to delete

        if ($currency->currency_status == 1) 
        {

            return response()->json(['status' => 'error','message' => 'oops! cannot delete current currency']);
            
        }
        else
        {
          
          $currency->delete();

            //  clear front currency cache 
            cache()->forget('currency');

          return response()->json(['status' => 'succes','message'=>'Data Successfully deleted !']);

        }

        
    }
}
