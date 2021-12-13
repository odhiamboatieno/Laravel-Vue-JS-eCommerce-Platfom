<?php

namespace App\Http\Controllers\Offers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Slider;
use App\AllStatic;
use App\Http\Resources\Slider\SliderResource;
use Image;

class SliderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.offers.slider.slider');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Slider::find($id);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
         'slider_title' => 'required',
         'slider_banner' => 'required|image64:jpeg,png,gif,jpg,webp,bmp'
        ]);

        try
        {
          
          $slider = new Slider;
          $slider->title = $request->slider_title;
          $slider->back_link_url = $request->back_url;
          $slider->status = $request->status;

          $imageData = $request->get('slider_banner');

            if($imageData)
            {
                $fileName = 'slider_banner'.uniqid().'.'.explode('/', explode(':', substr($imageData, 0, strpos($imageData, ';')))[1])[1];

                Image::make($request->get('slider_banner'))
                ->resizeCanvas(1920, 420, 'center', false, '#E1E1E1')
                ->save('images/slider/'.$fileName);

                $slider->slider_image =  $fileName;
            } 

            $slider->save();

            cache()->forget('home-slider');

            return response()->json(['status'=>'success','message'=>'New Slider Added!']);
        }

        catch(\Exceptoin $e)
        {
            return response()->json(['status'=>'error','message'=> $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function sliderList(Request $request)
    {
        $slider = Slider::orderBy('updated_at','desc');

         if ($request->keyword != '')
         {
             $slider->where('title','LIKE','%'.$request->keyword.'%');
         }
        $slider = $slider->paginate(10);

        return SliderResource::collection($slider);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $slider = Slider::find($id);
        return new SliderResource($slider);
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
        $request->validate([
         'slider_title' => 'required',
         'slider_banner' => 'nullable|image64:jpeg,png,gif,jpg,webp,bmp'
        ]);

        try
        {
          
          $slider = Slider::find($id);
          $slider->title = $request->slider_title;
          $slider->back_link_url = $request->back_url;
          $slider->status = $request->status;

          $imageData = $request->get('slider_banner');
            if($imageData)
            {
                if(file_exists('images/slider/'.$slider->slider_image) && !empty($slider->slider_image))
                {
                    unlink('images/slider/'.$slider->slider_image);
                }

                $fileName = 'slider_banner'.uniqid().'.'.explode('/', explode(':', substr($imageData, 0, strpos($imageData, ';')))[1])[1];
                
                 Image::make($request->get('slider_banner'))
                ->resizeCanvas(1920, 420, 'center', false, '#E1E1E1')
                ->save('images/slider/'.$fileName);

                $slider->slider_image =  $fileName;
            } 

            $slider->update();

            cache()->forget('home-slider');

            return response()->json(['status'=>'success','message'=>'Slider Updated!']);
        }

        catch(\Exceptoin $e)
        {
            return response()->json(['status'=>'error','message'=> $e->getMessage()]);
        }
    }

    public function sliderStatus($id)
    {
        try{

            $slider = Slider::find($id);

            $slider->status = $slider->status == AllStatic::$active ? AllStatic::$inactive : AllStatic::$active;
        
            $message = $slider->status == AllStatic::$active ? 'Slider Activated!' : 'Slider Deactivated!';

            $slider->update();

            cache()->forget('home-slider');

            return response()->json(['status'=>'success','message' => $message]);
        }

        catch(\Exception $e)
        {
            return response()->json(['status'=>'error','message'=>'Something went wrong!']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try
        {
           $slider = Slider::find($id);
           if (file_exists('images/slider/'.$slider->slider_image) && !empty($slider->slider_image)) 
            {
                unlink('images/slider/'.$slider->slider_image);
            }  
            $slider->delete();

            cache()->forget('home-slider');
            
            return response()->json(['status' => 'success','message' => 'Slider Deleted  !']);
        }
        catch(\Exception $e)
        {
         return response()->json(['status' => 'error','message' => 'Something Went Wrong !']);
        }
    }
}
