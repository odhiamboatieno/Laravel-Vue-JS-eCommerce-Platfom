<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Setting\SeoSetting;
use App\Model\Setting\SeoKeyword;
use DB;
use Image;

class SeoSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.setting.seo.seo');
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
                'title'         => 'required',
                'meta_image'    => 'nullable|image64:jpeg,png,gif,jpg,webp,bmp',
                'sitemap_link'  => 'required',
                'author'        => 'required',
                'description'   => 'required'
            ]
         );

        try
        {

         DB::beginTransaction();
         // just updating a row  
         $seo = SeoSetting::find(1);
         $seo->title         =   $request->title;
         $seo->sitemap_link =   $request->sitemap_link;
         $seo->author        =   $request->author;
         $seo->description   =   $request->description;
         $imageData          =   $request->get('meta_image');
        
        if($imageData)
        {

        if (file_exists('images/setting/seo/'.$seo->meta_image) && !empty($seo->meta_image))
        {
            unlink('images/setting/seo/'.$seo->meta_image);
        }   
        
        $fileName = uniqid().'.'.explode('/', explode(':', substr($imageData, 0, strpos($imageData, ';')))[1])[1];
        Image::make($request->get('meta_image'))->save('images/setting/seo/'.$fileName);
        $seo->meta_image =  $fileName;

         }

        if (count($request->seo_keyword) > 0) 
        {

           $keywords         =  implode(', ',array_column($request->seo_keyword, 'keyword'));
           $seo->keyword     =  $keywords;

        }

         $seo->update();

        // seo keyword for easy fetching taking in another table
         if (count($request->seo_keyword) > 0) 
         {

          SeoKeyword::where('seo_setting_id','=',$seo->id)->delete();   

         foreach ($request->seo_keyword as $value) {

               $seo_keyword = new SeoKeyword;
               $seo_keyword->keyword = $value['keyword'];
               $seo_keyword->seo_setting_id   = $seo->id;
               $seo_keyword->save();     
           }

         }

         DB::commit();
        
          // clear cache 
          cache()->forget('seo-info');

         return response()->json(['status'=>'success','message'=>'Seo Setting Updated']);

        }
        catch(\Exception $e)
        {

         // return $e;   

         DB::rollback();   

         return response()->json(['status'=>'error','message' => 'Something Went Wrong!']);

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
        $seo = SeoSetting::with('seo_keyword:id,seo_setting_id,keyword')->find(1);
        return $seo;
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
}
