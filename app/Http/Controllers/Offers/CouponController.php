<?php

namespace App\Http\Controllers\Offers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Coupon\Coupon;
use App\Model\Coupon\UserCoupon;
use App\User;
use Illuminate\Support\Facades\Auth;

class CouponController extends Controller
{
    public function index()
    {
        return view('admin.offers.coupon.coupon');
    }

    public function couponList(Request $request)
    {
        $coupon = Coupon::orderBy('updated_at','desc');

         if ($request->keyword != '')
         {
             $coupon->where('coupon_code','LIKE','%'.$request->keyword.'%');
         }
        $coupon = $coupon->paginate(10);

        return $coupon;
    }

    public function store(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required',  
            'amount_type' => 'required',  
            'amount' => 'required',  
            'limit_amount' => 'nullable', 
            'valid_date' => 'required'
        ]);

        try {
            $date = date('Y-m-d', strtotime($request->valid_date));
            $date1 = str_replace('-', '/', $date);
            $tomorrow = date('Y-m-d',strtotime($date1 . "+1 days"));
            $coupon = new Coupon;
            $coupon->coupon_code = strtoupper(str_replace(' ','',$request->coupon_code));
            $coupon->amount_type = $request->amount_type;
            $coupon->amount = $request->amount;
            $coupon->max_amount_limit = $request->limit_amount;
            $coupon->valid_date = $tomorrow;
            $coupon->status = $request->status;
            $coupon->save();

            return response()->json(['status' => 'success', 'message' => 'Coupon Created Successfully !']);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'message' => 'Something went wrong !']);
        }
    }

    public function update(Request $request,$id)
    {
        $request->validate([
            'coupon_code' => 'required',  
            'amount_type' => 'required',  
            'amount' => 'required',  
            'max_amount_limit' => 'nullable', 
            'valid_date' => 'required'
        ]);

        try {
            $date = date('Y-m-d', strtotime($request->valid_date));
            $date1 = str_replace('-', '/', $date);
            $tomorrow = date('Y-m-d',strtotime($date1 . "+1 days"));
            $coupon = Coupon::find($id);
            $coupon->coupon_code = strtoupper(str_replace(' ','',$request->coupon_code));
            $coupon->amount_type = $request->amount_type;
            $coupon->amount = $request->amount;
            $coupon->max_amount_limit = $request->max_amount_limit;
            $coupon->valid_date = $tomorrow;
            $coupon->status = $request->status;
            $coupon->update();

            return response()->json(['status' => 'success', 'message' => 'Coupon Updated Successfully !']);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'message' => 'Something went wrong !']);
        }
    }

    public function userCoupon()
    { 
        $current_date = date('Y-m-d');

        $user_coupon = UserCoupon::where('valid_date','>=',$current_date)
                             ->where('user_id','=',Auth::user()->id)
                             ->where('is_applied','=',1)
                             ->where('is_used','=',0)
                             ->first();
        
        if($user_coupon)
        {
            $coupon = Coupon::where('coupon_code','=',$user_coupon->coupon_code)
                             ->first();
            return $coupon;                 
        }

        return response()->json(['status'=>'error','message' => "No Coupon"]);

    }

    public function applyCoupon(Request $request)
    {
      $request->validate(['coupon_code' => 'required']);

      try
      {
           $current_date = date('Y-m-d');

           $coupon = UserCoupon::where('coupon_code','=',$request->coupon_code)
                              ->where('valid_date','>=',$current_date)
                              ->where('user_id','=',Auth::user()->id)
                              ->first();
          //   if coupon exist 

          if($coupon)
          {
            
         // if coupon already used 

            if($coupon->used === 1)
            { 

                return response()->json(['status'=>'error','message'=>'Coupon Already Used']);
            }

             $update_coupon = UserCoupon::find($coupon->id);
             $update_coupon->is_applied = 1;
             $update_coupon->update();
             return response()->json(['status'=>'success','message'=>'Coupon Applied']);
          }
          
          else
          {
              return response()->json(['status'=>'error','message'=>'Invalid Code!']);
          }
          
      }
      catch(\Exception $e)
      {
        return $e;
        return response()->json(['status'=>'error','message'=>$e->getMessage()]);

      }
    }

    public function destroy($id)
    {
        $data = Coupon::find($id);
        if($data){
            $data->delete();
            return response()->json(['status' => 'success', 'message' => 'Coupon Deleted!']);
        }else{
            return response()->json(['status' => 'success', 'message' => 'This Coupon Not Found!']);
        }
    }
}
