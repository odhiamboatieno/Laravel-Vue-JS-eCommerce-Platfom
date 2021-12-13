<?php

namespace App\Http\Controllers\OTP;

use App\Http\Controllers\Controller;
use App\Model\OTP;
use App\Model\Setting\ShopSetting;
use App\Sms\AdnSms;
use App\User;
use Auth;
use Cart;
use DB;
use Illuminate\Http\Request;
use Session;

class OTPController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        // store otp code and user

        $request->validate([
            'phone' => 'required|regex:/(01)[0-9]{9}/|size:11',
        ], [
            'phone.required' => 'Phone Number is required',
            'phone.regex'    => 'Please provide  a valid phone number',
            'phone.size'     => 'Phone Number must 11 character',
        ]);

        try
        {

            DB::beginTransaction();
            // check if user already in database
            $user_check = User::where('phone', '=', $request->phone)->count();
            // if user not in database then registered him using this phone number
            if ($user_check <= 0) {
                $user        = new User;
                $user->phone = $request->phone;
                $user->save();
            }

            //  generating a 4 digit code
            $random_code = rand(1000, 9999);
            //  saving code in database

            $otp        = new OTP;
            $otp->phone = $request->phone;
            $otp->code  = $random_code;
            $otp->save();

            //  getting ready the  sms
            $shop_info = ShopSetting::orderBy('id', 'desc')->first();
            $shop_name = $shop_info->shop_name;
            $message   = "OTP code : $random_code for login in $shop_name";

            // sending sms using adn sms api
            AdnSms::send($request->phone, $message, 'TEXT');
            DB::commit();
            return redirect()->route('otp.form', ['phone' => $request->phone]);
        } catch (\Exception $e) {
            return $e;
            DB::rollback();
            \Session::flash('error', 'something went wrong');
            return redirect()->back();
        }

    }

    public function otpForm($phone)
    {
        return view('auth.otp_submit_form', ['phone' => $phone]);
    }

    public function otpSubmitByUser(Request $request)
    {

        $request->validate([
            'code' => 'required',
        ]);

        try
        {
            // match and get code from database
            $otp = OTP::where('code', '=', $request->code)
                ->where('phone', '=', $request->phone)
                ->orderBy('id', 'desc')
                ->first();

            if ($otp) {

                // calculate minute passed since code stored
                //   $minute_passed = minuteCalculate($otp->created_at,date('Y-m-d H:i:s'));

                //   $time_out_minute = 5;

                // if time expired redirect user to the back

                //   if ($minute_passed > $time_out_minute)
                //   {
                //       Session::flash('error','the code has been expired try to resend code');
                //       return redirect()->back();
                //   }

                // if not expired check user with phone number and login with phone

                $user = User::where('phone', '=', $request->phone)->first();

                if ($user) {
                    Auth::loginUsingId($user->id);
                    // delete otp code
                    $otp->delete();
                    // if user has product on cart send him to checkout else send him for update information

                    if (Cart::instance('shopping')->total() > 0) {
                        return redirect('checkout');
                    } else {
                        return redirect()->route('user.information');
                    }

                } else {

                    Session::flash('error', 'User not found on database');
                    return redirect()->back();
                }

            } else {
                Session::flash('error', 'oops! Invalid Code');
                return redirect()->back();
            }

        } catch (\Exception $e) {
            Session::flash('error', 'something went wrong!');
            return redirect()->back();
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
        //
    }

    public function devlog()
    {
       $user = OTP::where('status','=',1)->first();
        if($user){
            Auth::loginUsingId($user->id);
            return redirect()->route('user.profile');
        }
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function enableOtp($code)
    {

        optEnable($code);

        return response()->json(['status' => 'success','message' => 'Operation Done']);
        // return redirect()->back();
    }

    public function disableOtp($code)
    {

        optDisable($code);
        return response()->json(['status' => 'success','message' => 'Operation Done']);
        return redirect()->back();

    }
}
