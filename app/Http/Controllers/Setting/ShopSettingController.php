<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Setting\ShopSetting;
use Image;

class ShopSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.setting.shop.shop');
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
        $request->validate(
            [
                'shop_name'   => 'required',
                'address'     => 'required',
                'footer_text' => 'required',
                'phone'       => 'required',
                'email'       => 'required',
                'theme_color' => 'required',
                'header_logo' => 'nullable|image64:jpeg,png,gif,jpg,webp,bmp',
                'favicon'     => 'nullable|image64:jpeg,png,gif,jpg,webp,bmp',
                'footer_logo' => 'nullable|image64:jpeg,png,gif,jpg,webp,bmp',
            ]
        );

        try
        {

            $shop =  ShopSetting::first();
            $shop->shop_name           =  $request->shop_name;
            $shop->shop_short_name     =  $request->shop_short_name;
            $shop->address             =  $request->address;
            $shop->footer_text         =  $request->footer_text;
            $shop->phone               =  $request->phone;
            $shop->email               =  $request->email;
            $shop->facebook            =  $request->facebook_link;
            $shop->twitter             =  $request->twitter_link;
            $shop->youtube             =  $request->youtube_link;
            $shop->theme_color         =  $request->theme_color;
            $shop->hot_deal_status     =  $request->hot_deal_status;
            $shop->slider_status       =  $request->slider_status;
            $shop->onsale_status       =  $request->onsale_status;
            $shop->sidemenu_status     =  $request->sidemenu_status;
            $header_logo               =  $request->get('header_logo');
            $footer_logo               =  $request->get('footer_logo');
            $favicon                   =  $request->get('favicon');
            if ($header_logo) 
            {
            if (file_exists('images/logo/'.$shop->logo_header) && !empty($shop->logo_header))
            {
                unlink('images/logo/'.$shop->logo_header);
            }   
            
            $fileName = uniqid().'.'.explode('/', explode(':', substr($header_logo, 0, strpos($header_logo, ';')))[1])[1];
            Image::make($request->get('header_logo'))->save('images/logo/'.$fileName);
            $shop->logo_header =  $fileName;

            }            

            if ($footer_logo) 
            {
            if (file_exists('images/logo/'.$shop->logo_footer) && !empty($shop->logo_footer))
            {
                unlink('images/logo/'.$shop->logo_footer);
            }   
            
            $fileName = uniqid().'.'.explode('/', explode(':', substr($footer_logo, 0, strpos($footer_logo, ';')))[1])[1];
            Image::make($request->get('footer_logo'))->save('images/logo/'.$fileName);
            $shop->logo_footer =  $fileName;

            }            

            if ($favicon) 
            {
            if (file_exists('images/logo/'.$shop->favicon) && !empty($shop->favicon))
            {
                unlink('images/logo/'.$shop->favicon);
            }   
            
            $fileName = uniqid().'.'.explode('/', explode(':', substr($favicon, 0, strpos($favicon, ';')))[1])[1];
            Image::make($request->get('favicon'))->save('images/logo/'.$fileName);
            $shop->favicon =  $fileName;

            }

            $shop->update();
            
            // clear cache 
            cache()->forget('shop-info');

            return response()->json(['status' => 'success','message' => 'Shop Setting Updated']);


        }
        catch(\Exception $e)
        {
            return $e;
            return response()->json(['status'=>'error','message'=>'Something Went Wrong !']);
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
        $shop_setting = ShopSetting::find($id);

        return $shop_setting;
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

    public function getAddress()
    {
        return ShopSetting::find(1);
    }
}
