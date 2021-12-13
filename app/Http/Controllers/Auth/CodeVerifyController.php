<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Model\Setting\InstalltionSetting;
use Exception;
use Illuminate\Http\Request;

class CodeVerifyController extends Controller
{
    //

    public function enterCode()
    {
        // return url('/');
        return view('front.setting.verify');
    }

    public function verifyCode(Request $request)
    {
        $request->validate(
            ['code' => 'required|min:25'],
            [
                'code.required' => 'Purchase Code Is Required',
                'code.min'      => 'Invalid Code',
            ]
        );

        $install = new InstalltionSetting;

        $install->purchase_code = $request->code;
        $install->status        = 1;
        $install->save();

        return response()->json(['status' => 'success', 'message' => 'Verified']);

    }

    public function sendVerification()
    {
        try {
            verifyCustomer();
            return response()->json(['status' => 'success']);
        } catch (Exception $e) {
            return $e;
        }

    }
}
