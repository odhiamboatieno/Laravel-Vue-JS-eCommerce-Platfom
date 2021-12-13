<?php

namespace App\Exports;
use App\Model\Order\OrderDetails;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use DB;

class SalesReportExportView implements FromView
{
   protected $category,$sub_category,$sub_sub_category,$brand,$range;

	public function __construct($category,$sub_category,$sub_sub_category,$brand,$range) {
	        $this->category = $category;
	        $this->sub_category = $sub_category;
            $this->sub_sub_category = $sub_sub_category;
	        $this->brand = $brand;
	        $this->range = $range;
	 }
  
    public function view(): View
    {
        $product = OrderDetails::with(['product:id,product_name',
                  'brand:id,brand_name','category:id,category_name',
                   'sub_category:id,sub_category_name',
                   'sub_sub_category:id,sub_sub_category_name,sub_sub_category_native_name'])
        ->selectRaw('product_id,
            category_id,
            sub_category_id,
            sub_sub_category_id,
            brand_id,
            sum(total_selling_price) as total_sales_amount,
            sum(total_buying_price) as total_buying_amount,
            sum(quantity) as total_sold_qty'
         )
        ->groupBy([
            'product_id',
            'category_id',
            'brand_id',
            'sub_category_id',
            'sub_sub_category_id'
          ])
         ->orderBy('updated_at','desc');

         if ($this->category != 'undefined') 
         {
             $product->where('category_id','=',$this->category);
         }
         if ($this->sub_category != 'undefined') 
         {
             $product->where('sub_category_id','=',$this->sub_category);
         }         
         if ($this->sub_sub_category != 'undefined') 
         {
             $product->where('sub_sub_category_id','=',$this->sub_sub_category);
         }
         if ($this->brand != 'undefined') 
         {
             $product->where('brand_id','=',$this->brand);
         }

         if ($this->range != '') 
         {
            $date = $this->range;
            $data = explode(",", $date);
            $start = date_convert($data[0]);
            $end = date_convert($data[1]);
            $product->whereBetween('created_at', [$start, $end]);
         }
       $product = $product->get();
    	return view('admin.report.excel.salesreport',['product' => $product]);
    }
}
