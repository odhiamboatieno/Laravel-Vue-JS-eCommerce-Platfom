<?php

namespace App\Exports;
use App\Model\Product;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use DB;

class StockReportExportView implements FromView
{
   protected $keyword,$category,$sub_category,$sub_sub_category,$brand,$bulkstore;

	public function __construct($keyword,$category,$sub_category,$sub_sub_category,$brand,$bulkstore) {
	        $this->keyword = $keyword;
	        $this->category = $category;
            $this->sub_category = $sub_category;
            $this->sub_sub_category = $sub_sub_category;
	        $this->brand = $brand;
            $this->bulkstore = $bulkstore;
	 }
  
    public function view(): View
    {
    	$search_keyword = $this->keyword;
        $product = Product::with([
        'category:id,category_name,category_native_name',
        'sub_category:id,sub_category_name,sub_category_native_name',
        'sub_sub_category:id,sub_sub_category_name,sub_sub_category_native_name',
        'brand:id,brand_name,brand_native_name',
        'multiple_image:id,product_id,image_name'
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

         if ($search_keyword != '') 
         {
            // this three field  or combination doing a and comibination with all other combination in upper  
            $product->where(function ($query) use ($search_keyword) {
            $query->where('product_name','LIKE','%'.$search_keyword.'%')
                  ->orWhere('product_native_name', 'LIKE','%'.$search_keyword.'%')
                  ->orWhere('product_keyword','LIKE','%'.$search_keyword.'%');
            });
         }
        $product = $product->get();
         if ($this->bulkstore == 'yes') 
         {
            return view('admin.report.excel.bulkstorage',['product' => $product]);
         }
    	return view('admin.report.excel.stockreport',['product' => $product]);
    }
}
