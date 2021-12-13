<?php

namespace App\Exports;
use App\Model\Order\Order;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use DB;

class PaymentExportView implements FromView
{
	protected $methods,$singledate,$range;

	public function __construct($methods,$singledate,$range) {
	        $this->methods = $methods;
	        $this->singledate = $singledate;
	        $this->range = $range;
	 }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
    	$order = Order::with('provider:id,provider')->select(DB::raw('payment_method,payment_date,SUM(total_amount) as amount'))
         ->where('payment_method','!=',0)
         ->where('payment_status','=',1);
         if($this->methods != 'undefined') 
         {
             $order->where('payment_method','=',$this->methods);
         }

         if($this->singledate != '') 
         {
            $date = explode(' ', date_convert($this->singledate))[0];
            $order->where('payment_date',$date);
         }

         if($this->range != '') 
         {
            $date = $this->range;
            $data = explode(",", $date);
            $start = date_convert($data[0]);
            $end = date_convert($data[1]);
            $order->whereBetween('payment_date', [$start, $end]);
         }
         $order = $order->groupBy('payment_method')->groupBy('payment_date')->get();
        return view('admin.report.excel.paymentreport',['payments' => $order]);
    }

}
