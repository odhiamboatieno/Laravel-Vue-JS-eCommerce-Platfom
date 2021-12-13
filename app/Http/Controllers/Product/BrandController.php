<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Resources\Product\BrandResource;
use Illuminate\Http\Request;
use App\Model\Brand;
use App\Model\Product;
use Image;

class brandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.brand.brand');
    }


    public function brandList(Request $request)
    {
        
        $brand = brand::orderBy('updated_at','desc');

        if ($request->keyword != '') 
        {
            $brand->where('brand_name','LIKE','%'.$request->keyword.'%')
                     ->orWhere('brand_native_name','LIKE','%'.$request->keyword.'%');

        }

        $brand = $brand->paginate(10);


        return BrandResource::collection($brand);
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
        $request->validate([
           
             'name' => 'required|unique:brands,brand_name',
             'image' => 'nullable|image64:jpeg,jpg,png,gif'
        ],
        [
         'image.image64' => 'File must be an image of jpeg,png,gif'
        ]);

        try
        {

         $brand = new Brand;

         $brand->brand_name        =  $request->name;   
         $brand->brand_native_name =  $request->native_name;   
         $brand->status               =  $request->status;   
         
         $imageData = $request->get('image');
         
         if($imageData){
          
          $fileName = uniqid().'.'.explode('/', explode(':', substr($imageData, 0, strpos($imageData, ';')))[1])[1];
          Image::make($request->get('image'))->save('images/brand/logo/'.$fileName);

          $brand->brand_logo = $fileName;


         }

         $brand->save();

         return response()->json(['status' => 'success','message' => 'brand Added Successfully !']);

        }

        catch(\Exception $e)
        {
          
          // return $e;
          
          return response()->json(['status' => 'error','message' => 'Opps Something Went Wrong!']);


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
        return new BrandResource(Brand::find($id));
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
           
             'name' => 'required|unique:brands,brand_name,'.$id.',id',
             'image' => 'nullable|image64:jpeg,jpg,png,gif'
        ],
        [
         'image.image64' => 'File must be an image of jpeg,png,gif'
        ]);

        try
        {

         $brand = Brand::find($id);

         $brand->brand_name        =  $request->name;   
         $brand->brand_native_name =  $request->native_name;   
         $brand->status               =  $request->status;   
         
         $imageData = $request->get('image');
         
         if($imageData){

            if (file_exists('images/brand/logo/'.$brand->brand_logo) && !empty($brand->brand_logo)) {
               
               unlink('images/brand/logo/'.$brand->brand_logo);
            }
          
          $fileName = uniqid().'.'.explode('/', explode(':', substr($imageData, 0, strpos($imageData, ';')))[1])[1];
          Image::make($request->get('image'))->save('images/brand/logo/'.$fileName);

          $brand->brand_logo = $fileName;


         }

         $brand->save();

         return response()->json(['status' => 'success','message' => 'Brand  Successfully Updated!']);

        }

        catch(\Exception $e)
        {
          
          // return $e;
          
          return response()->json(['status' => 'error','message' => 'Opps Something Went Wrong!']);


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
        
        // if it have sub brand then it can't be delete

        $count_subabrand = Product::where('brand_id','=',$id)->count();

        if($count_subabrand > 0)
        {


            return response()->json(['status' => 'error' , 'message' => 'Can\'t delete the brand it have Product']);

        }

        try
        {
            $brand = Brand::find($id);
            
           if (file_exists('images/brand/logo/'.$brand->brand_logo) && !empty($brand->brand_logo)) {
               
               unlink('images/brand/logo/'.$brand->brand_logo);
            }

            $brand->delete();

            return response()->json(['status' => 'success' , 'message' => 'brand Deleted']);
        }

        catch(\Exception $e)
        {
            return response()->json(['status' => 'error' , 'message' => 'Something Went Wrong !']);
        }
        
    }
}
