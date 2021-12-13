<?php

namespace App\Http\Controllers\Offers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Coupon\UserCoupon;
use App\Model\Coupon\Coupon;
use App\Model\Setting\ShippingArea;
use App\Model\Setting\ShopSetting;
use App\Sms\AdnSms;
use App\Model\Currency;
use App\User;
use DB,Mail;

class SendCouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::select('id','coupon_code','valid_date')->get();
        $locations = ShippingArea::select('id','city')->get();
        return view('admin.offers.coupon.send_coupon',['coupon' => $coupons, 'location' => $locations]);
    }

    public function customerCouponList(Request $request)
    {
        $coupon = UserCoupon::with('user')->orderBy('created_at','desc');

         if ($request->keyword != '')
         {
             $coupon->Where('coupon_code','LIKE','%'.$request->keyword.'%');
             $coupon->orWhere('user_id','LIKE','%'.$request->keyword.'%');
         }
        $coupon = $coupon->paginate(10);

        return $coupon;
    }

    public function getCustomer(Request $request)
    {
        $user_by_location = [];
        $user_order = [];

        if(count($request->location) > 0){
          $user_by_location[] = User::with('location')->whereIn('location_id',$request->location)->get();
        }

        if($request->order_by == 'most_order' && $request->order_by != ''){
            // $last_one_month = date('Y-m-d H:i:s',strtotime("-3 month"));
            $user_order[] = User::with(['location:id,city'])->whereHas('Order',function($query){
                $query->where('order_date','>=',date('Y-m-d',strtotime("-1 month")));
            })->withCount('order')->orderBy('order_count','desc')->get();
        }

       return response()->json(['location' => $user_by_location,'user_by_order' => $user_order]);
   
    }

    public function store(Request $request)
    {
        $request->validate([
            'coupon' => 'required',
            'select_id' => 'required'
        ]);
        if(count($request->select_id) > 0){
            foreach($request->select_id as $member){
                $v_date = date('Y-m-d',strtotime($request->valid_date));
                $filter = UserCoupon::where(['user_id' => $member,'coupon_code' => $request->coupon['coupon_code'],'is_used' => 0])->where('valid_date','>=',date('Y-m-d'))->first();
                if(!$filter){
                    $user = new UserCoupon;
                    $user->user_id = $member;
                    $user->coupon_code = strtoupper(str_replace(' ','',$request->coupon['coupon_code']));
                    $user->valid_date  = $v_date;
                    $user->is_used     = 0;
                    $user->is_applied  = 0;
                    $result = $user->save();
                
                    $shop_info = ShopSetting::orderBy('id','desc')->first();
                    $customer = User::find($member);
                    $coupon = Coupon::where('coupon_code','=',$request->coupon['coupon_code'])->first();
                        if(($request->send_to_email == 'true') && $result){
                            if($customer->email){
                                $subject = 'Coupon from '.$shop_info->shop_name;
                                $to = $customer->email;
                                $email = $shop_info->email;
                                $name = $shop_info->shop_name;
                                Mail::send('admin.offers.coupon.coupon_mail',['coupon' => $coupon, 'exp_date' => $v_date], 
                                function ($message) use ($to,$email,$name,$subject){
                                    $message->from($email,$name);
                                    $message->to($to)->subject($subject);
                                });
                            }
                    }
                
                    
                    if(($request->send_to_sms == 'true') && $result){
                            if($customer->phone){
                                $currency = Currency::where('currency_status', 1)->first();
                                $type = $coupon->amount_type == 1 ? '' : '% Up to '.$coupon->max_amount_limit;
                                $message = "Coupon Code:".$request->coupon['coupon_code']."\n\nUse This Code To Get ".$coupon->amount.$type.$currency->symbol." Discount \n\n".url('/');
                                // AdnSms::send($customer->phone,$message,'TEXT');
                            }
                            
                    }
                }
               
            }
            return response()->json(['status' => 'success','message' => 'Coupon Set Successfully !']);
        } 
        else {
            return response()->json(['status' => 'error','message' => 'No member Selected !']);
        }
       
    }

    public function destroy($id)
    {
        UserCoupon::find($id)->delete();
        return response()->json(['status' => 'success','message' => 'Coupon Deleted Successfully!']);
    }
}
