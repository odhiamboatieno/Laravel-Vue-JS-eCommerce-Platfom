<?php

namespace App\Exports;

// use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\User;

class CustomerListExport implements FromView
{
	protected $keyword,$range;

	public function __construct($keyword,$range) {
	        $this->keyword = $keyword;
	        $this->range = $range;
	 }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        $customer = User::orderBy('updated_at','desc');
        if ($this->range != '') 
         {
            $date = $this->range;
            $data = explode(",", $date);
            $start = date_convert($data[0]);
            $end = date_convert($data[1]);
            $customer->whereBetween('created_at', [$start, $end]);
         }

        if ($this->keyword != '') 
        {
            $customer->where('name','LIKE','%'.$this->keyword.'%')
                     ->orWhere('email','LIKE','%'.$this->keyword.'%')
                     ->orWhere('phone','LIKE','%'.$this->keyword.'%');
        }
      
      	$customer =  $customer->get();
       return view('admin.customer.excel.customer_excel',['customers' => $customer]);
    }
}
