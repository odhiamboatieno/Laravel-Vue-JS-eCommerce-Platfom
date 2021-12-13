<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Model\Order\Order;
use App\Model\Order\OrderDetails;
use PDF;

class CustomerController extends Controller
{
    public function index()
    {
    	return view('admin.customer.customers');
    }

    public function getCustomer(Request $request)
    {

    	$customer = User::orderBy('updated_at','desc');
        if ($request->range != '') 
         {
            $date = $request->range;
            $data = explode(",", $date);
            $start = date_convert($data[0]);
            $end = date_convert($data[1]);
            $customer->whereBetween('created_at', [$start, $end]);
         }

        if ($request->keyword != '') 
        {
            $customer->where('name','LIKE','%'.$request->keyword.'%')
                     ->orWhere('email','LIKE','%'.$request->keyword.'%')
                     ->orWhere('phone','LIKE','%'.$request->keyword.'%');
        }
       

      $customer =  $customer->paginate(10);

      return $customer;
     }

     public function customerListPrintPdf(Request $request)
    {
        $fromTo = null;
        $customer = User::orderBy('updated_at','desc');
        if ($request->range != '') 
         {
            $date = $request->range;
            $data = explode(",", $date);
            $start = date_convert($data[0]);
            $end = date_convert($data[1]);
            $customer->whereBetween('created_at', [$start, $end]);
            $fromTo .= 'From '.date('M j, Y',strtotime($start)).' to '.date('M j, Y',strtotime($end));
         }

        if ($request->keyword != '') 
        {
            $customer->where('name','LIKE','%'.$request->keyword.'%')
                     ->orWhere('email','LIKE','%'.$request->keyword.'%')
                     ->orWhere('phone','LIKE','%'.$request->keyword.'%');
        }
       
        $customer =  $customer->get();

        if($request->req == 'print')
        {
            return view('admin.customer.print.customer_print',[
                'customers' => $customer,
                'time_range' => $fromTo
            ]);
        }else{
            // return view('admin.customer.pdf.customer_pdf',[
            $pdf = PDF::loadView('admin.customer.pdf.customer_pdf',[
                'customers' => $customer,
                'time_range' => $fromTo
            ]);
            $pdf->setPaper('A4', 'landscape');
            return $pdf->download("customer-list.pdf");
        }
      
     }

     public function customerListExcel(Request $request)
     {
        return \Excel::download(new \App\Exports\CustomerListExport($request->keyword,$request->range),'customer_list.xlsx');
     }

    public function destroy($id)
    {
    	return User::find('id')->delete();
    }

    public function customerOrder($id)
    {
    	return Order::with(['user','provider:id,provider'])->where('user_id',$id)->get();
    }

    public function customerOrderDetails($id)
    {
    	return OrderDetails::where('order_id',$id)->get();
    }
}
