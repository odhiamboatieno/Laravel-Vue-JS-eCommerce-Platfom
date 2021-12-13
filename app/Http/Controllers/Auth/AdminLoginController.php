<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Admin;
use Auth,Hash,Session;
use App\Model\Setting\ShopSetting;
use \Mail;

class AdminLoginController extends Controller
{
   public function __construct()
    {
      $this->middleware('guest:admin', ['except' => ['logout']]);
    }
    
    public function showLoginForm()
    {
      return view('admin.auth.login');
    }
    
    public function login(Request $request)
    {
      // Validate the form data
      $this->validate($request, [
        'email'   => 'required|email',
        'password' => 'required'
      ]);
      
      // Attempt to log the user in
      if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password,'status' => 1], $request->remember)) {
        // if successful, then redirect to their intended location
        return redirect()->intended(route('admin.dashboard'));
      } 
      // if unsuccessful, then redirect back to the login with the form data
      return redirect()->back()
      ->with('error', 'Wrong Credentials or you are deactivated !')
      ->withInput($request->only('email', 'remember'));
    }
    
    public function showpage()
    {
        return view('admin.auth.reset');
    }

    public function sendmail(Request $request)
    {
        $request->validate([
          'email' => 'required|email|exists:admins,email'
        ]);

        $token = \Hash::make(substr(str_shuffle('abcdefghijklmnopqrstuvwxyz0123456789'),0,6));
        $result = Admin::where('email',$request->email)->first();
        $result->reset_token = $token;
        $result->update();

        $shop_info = ShopSetting::orderBy('id','desc')->first();
        $subject = 'Confim Email For Reset Admin Password';
        $to = $request->email;
        $email = $shop_info->email;
        $name = $shop_info->shop_name;
        Mail::send('admin.auth.reset_email_temp',['token' => $token], 
                  function ($message) use ($to,$email,$name,$subject){
                      $message->from($email,$name);
                      $message->to($to)->subject($subject);
             });
        \Session::flash('resent','A fresh verification link has been sent to your email address.');
        return redirect()->back();
    }

    public function viewResetPage(Request $request)
    { 
      $result = Admin::where('reset_token',$request->token)->first();
      if($result->count() > 0){
        return view('admin.auth.confirm',['token' => $request->token]);
      } else {
        Session::flash('error','Please, try again!!');
        return redirect()->back();
      }
    }

    public function storeNewPassword(Request $request)
    { 
      $request->validate([
        'password' => 'required|confirmed|min:6'
      ]);
      $admin = Admin::where('reset_token',$request->identity)->first();
      $admin->password = Hash::make($request->password);
      $admin->update();
      Auth::logout();
      Session::flash('success','Password is Changed Successfully!');
      return redirect()->route('admin.login');
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.dashboard');
    }
}
