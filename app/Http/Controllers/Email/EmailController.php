<?php

namespace App\Http\Controllers\Email;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Setting\Email;
use App\Model\Setting\ShopSetting;
use App\Model\Subscribe;
use App\User;
use \Mail;

class EmailController extends Controller
{
    public function index()
    {
        return view('admin.email.email');
    }

    public function getEmails()
    {
        $data = [];
        $data['user'] = User::select('id','email')->get();
        $data['subscriber'] = Subscribe::select('id','email')->get();
        return $data;
    }

    public function getUserEmail()
    {
        $data = User::select('id','email')->where('status',1)->get();
        return $data;
    }

    public function getSubscriberEmail()
    {
        $data = Subscribe::select('id','email')->where('status',1)->get();
        return $data;
    }

    public function send(Request $request)
    {
      $request->validate([
        'selected_email.*.email' => 'required|email',
        'subject' => 'required'
      ]);
      try {
        $shop_info = ShopSetting::orderBy('id','desc')->first();
        $subject = $request->subject;
        $text_body = $request->text_body;
        $email = $shop_info->email;
        $name = $shop_info->shop_name;

        $emailStatus = Email::first();
        if ($emailStatus->status == 1) {
          $all_mail = array_merge($request->selected_email,$request->selected_subscriber);

          foreach ($all_mail as $value) {
             $to = $value['email'];
            Mail::send('admin.email.subscriber_email_temp',['subject' => $subject, 'text_body' => $text_body], 
                  function ($message) use ($to,$email,$name,$subject){
                      $message->from($email,$name);
                      $message->to($to)->subject($subject);
             });
    
          }
          $message = ['status' => 'success', 'message' => 'Mail Send Successfully!'];
        } else {
          $message = ['status' => 'error', 'message' => 'Please, Active Your Mail Setting.'];
        }
        return response()->json($message);
        
      } catch (\Exception $e) {
        return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
      }
      
	    
    }

    public function showSettingPage()
    {
        return view('admin.setting.email.emailsetting');
    }

    public function getData()
    {
        return Email::find(1);
    }

    public function update(Request $request)
    {
        $request->validate([
            'driver' => 'required',
            'host' => 'required',
            'port' => 'required',
            'username' => 'required',
            'password' => 'required',
            'encryption' => 'required'
        ]);
       $result = Email::find(1);
       $result->driver = $request->driver;
       $result->host = $request->host;
       $result->port = $request->port;
       $result->username = $request->username;
       $result->password = $request->password;
       $result->encryption = $request->encryption;
       $result->from_address = $request->from_address;
       $result->from_name = $request->from_name;
       $result->status = $request->status;
       $result->update();
       return response()->json(['status' => 'success', 'message' => 'Updated Successfully']);
    }

    public function status()
    {
       $result = Email::first();
       if ($result->status === 1) {
           $result->status = 0;
           $message = 'Email Deactiveted Successfully';
       }else{
            $result->status = 1;
            $message = 'Email Activeted Successfully';
       }
       $result->update();
       return response()->json(['status' => 'success', 'message' => $message]);
    }
}
