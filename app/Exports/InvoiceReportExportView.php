<?php

namespace App\Exports;
use App\Model\Order\Order;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use DB;

class InvoiceReportExportView implements FromView
{
    protected $city,$range;

	public function __construct($city,$range) {
	        $this->city = $city;
	        $this->range = $range;
	 }
  
    public function view(): View
    {
    	$order = Order::orderBy('id');

         if ($this->range != '') 
         {
            $date = $this->range;
            $data = explode(",", $date);
            $start = date("Y-m-d",strtotime(date_convert($data[0])));
            $end = date("Y-m-d",strtotime(date_convert($data[1])));
            $order->whereBetween('order_date', [$start, $end]);
         }
        if($this->city != 'undefined') 
        {
            $order->where('shipping_area_id','=',$this->city);
        }
        $order = $order->get();
    	return view('admin.report.excel.invoicereport',['product' => $order]);
    }
}
