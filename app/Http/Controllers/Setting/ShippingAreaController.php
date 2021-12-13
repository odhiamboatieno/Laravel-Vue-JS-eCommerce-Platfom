<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Setting\ShippingArea;

class ShippingAreaController extends Controller
{
    public function index()
    {
        return view('admin.setting.shipping.shipping_area');
    }

    public function getAreaData(Request $request)
    {
    	$result = ShippingArea::orderBy('created_at','desc');
    	if($request->status != ''){
    		$result->where('status','=',$request->status);
    	}
    	if($request->keyword != ''){
    		$result->where('city','LIKE','%'.$request->keyword.'%');
    	}

    	$result = $result->paginate(5);
    	return $result;
    }

    public function store(Request $request)
    {
    	$request->validate([
    		'name' => 'required|max:100' ]);
    	$find = ShippingArea::find($request->id);
    	$status = $request->status == true ? 1 : 0;
    	if($find){
    		$msg = 'City Name '.$find->city.' replaced by '.$request->name;
			$find->city = $request->name;
			$find->status = $status;
			$find->update();
    		return response()->json(['status' => 'success', 'message' => $msg]);
    	}else{

	    	try {
	    		$result = ShippingArea::create([
	    			'city' => $request->name,
	    			'status' => $status
	    		]);
	    		return response()->json(['status' => 'success', 'message' => 'New City '.$request->name.' Added!']);

	    	} catch (Exception $e) {

	    		return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
	    	}
    	}
    }

    public function changeStatus($id)
    {
    	$result = ShippingArea::find($id);
    	if($result->status == 0){
    		$result->status = 1;
    		$msg = $result->city.' City is Activeted!';
    	}else{
    		$result->status = 0;
    		$msg = $result->city.' City is Deactiveted!';
    	}
    	$result->update();
        return response()->json(['status' => 'success','message' => $msg]);
    }

    public function destroy($id)
    {
    	$result = ShippingArea::find($id)->delete();
    	if($result) {
    		return response()->json(['status' => 'success','message' => 'Area Deleted Successfully!']);
    	}else{
    		return response()->json(['status' => 'error','message' => 'Something is wrong!']);
    	}
    }
}
