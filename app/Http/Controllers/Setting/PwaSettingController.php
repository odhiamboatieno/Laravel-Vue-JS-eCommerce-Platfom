<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Model\Setting\PwaSetting;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Throwable;

class PwaSettingController extends Controller
{
    public function index()
    {
        return PwaSetting::orderBy('id', 'desc')->first();
    }

    public function store(Request $request)
    {
        $request->validate([
            'image'          => 'nullable|image64:jpeg,png',
            'app_name'       => 'required',
            'app_short_name' => 'required',
        ], [
            'image.image64' => 'Feature Image must to be a type of jpeg,png,gif,jpg,webp,bmp',
        ]);

        try
        {

            if ($request->get('image')) {
                // making diffrent size of icon base on uploaded image
                foreach (getPwaIconName() as $icon) {
                    Image::make($request->get('image'))
                        ->resize($icon['height'], $icon['width'])
                        ->save($icon['icon']);
                }

                foreach (getPwaSplashName() as $splash) {
                    // single splash image
                    Image::make($request->get('image'))
                        ->resizeCanvas($splash['width'], $splash['height'], 'center', false, '#F3F3F3')
                        ->save($splash['icon']);

                }

            }

            if ($request->id != 0) {
                $pwa_setting = PwaSetting::find(1);
            } else {
                $pwa_setting = new PwaSetting;
            }
            $pwa_setting->app_name       = $request->app_name;
            $pwa_setting->app_short_name = $request->app_short_name;
            $pwa_setting->image          = 'icon-512x512.png';

            if ($request->id != 0) {
                $pwa_setting->update();
            } else {
                $pwa_setting->save();
            }

            return response()->json(['status' => 'success', 'message' => 'App Setting Updated']);

        } catch (Throwable $th) {
            return response()->json(['status' => 'error', 'message' => $th->getMessage()]);
        }

    }
}
