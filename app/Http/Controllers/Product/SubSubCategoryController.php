<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Model\Brand;
use App\Model\SubCategoryBrand;
use App\Model\Category;
use App\Model\SubCategory;
use App\Model\SubSubCategory;
use App\Model\Product;
use App\Model\Color;
use App\AllStatic;
use App\Http\Resources\Product\SubSubCategoryResource;
use Illuminate\Http\Request;
use DB;
use Image;
use Cache;

class SubSubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $category = Category::
            select('id', 'category_name', 'category_native_name')
            ->where('status', '=', AllStatic::$active)
            ->get();

        $brand = Brand::
            select('id', 'brand_name', 'brand_native_name')
            ->where('status', '=', AllStatic::$active)
            ->orderBy('brand_name', 'asc')
            ->get();

        return view('admin.subsubcategory.sub_sub_category', [

            'category' => $category,
            'brand' => $brand,

        ]);
    }

    public function subSubCategoryList(Request $request){

        $sub_category = SubSubCategory::
                        with(['category:id,category_name,category_native_name',
                        'sub_category:id,sub_category_name,sub_category_native_name',
                        'sub_sub_category_brand.brand:id,brand_name'])
                     ->orderBy('updated_at','desc');
   
        if ($request->category != '' && $request->category != null)
        {
   
           $sub_category->where('category_id','=',$request->category);
   
           // return $request->category
   
        }
        if ($request->sub_category != '' && $request->sub_category != null)
        {
   
           $sub_category->where('sub_category_id','=',$request->sub_category);
   
           // return $request->category
   
        }
   
        if ($request->keyword != '')
         {
          
          $sub_category->where('sub_sub_category_name','LIKE','%'.$request->keyword.'%')
                       ->orWhere('sub_sub_category_native_name','LIKE','%'.$request->keyword.'%');
   
         }   
   
   
         $sub_category = $sub_category->paginate(10);
   
         return SubSubCategoryResource::collection($sub_category);                          
   
       }
   
   
       // get sub category by category id 
   
       public function getSubSubCategory(Request $request,$sub_category_id){
            
            $sub_category = SubSubCategory::select('id','sub_sub_category_name','sub_sub_category_native_name')
                                         ->where('sub_category_id','=',$sub_category_id)
                                         ->orderBy('sub_sub_category_name','asc');
            // in edit time we required active inactive all sub category  
            if (!$request->has('edit_time')) 
               {
                $sub_category->where('status',AllStatic::$active);
               }
               $sub_category =  $sub_category->get();
             return $sub_category;
   
       }    
   
   
       // get all brand under a sub sub_category 
   
       public function getSubSubCategoryBrand(Request $request,$id){
            
            $brand_id = SubCategoryBrand::where('sub_sub_category_id','=',$id)
                                          ->where('status','=',AllStatic::$active)
                                          ->pluck('brand_id');
   
            $sub_category_brand = Brand::select('id','brand_name','brand_native_name')
                                     ->orderBy('brand_name','asc')
                                     ->whereIn('id',$brand_id);
             if(!$request->has('edit_time')) 
             {
               
               $sub_category_brand->where('status',AllStatic::$active);
   
             }                        
                                     
             $sub_category_brand = $sub_category_brand->get();
   
            return $sub_category_brand;                         
                                     
   
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
              
                'name' => 'required|unique:categories,category_name',
                'image' => 'required|image64:jpeg,jpg,png,gif',
                'category' => 'required',
                'sub_category' => 'required',
           ],
           [
            'image.image64' => 'File must be an image of jpg,jpeg,png,gif'
           ]);
   
           try
           {
   
            DB::beginTransaction();   
   
            $sub_sub_category = new SubSubCategory;
   
            $sub_sub_category->category_id        =  $request->category;   
            $sub_sub_category->sub_category_id        =  $request->sub_category;   
            $sub_sub_category->sub_sub_category_name =  $request->name;   
            $sub_sub_category->sub_sub_category_native_name =  $request->native_name;   
            $sub_sub_category->status               =  $request->status;   
            
            $imageData = $request->get('image');
            
            if($imageData){
             
             $fileName = uniqid().'.'.explode('/', explode(':', substr($imageData, 0, strpos($imageData, ';')))[1])[1];
             Image::make($request->get('image'))->save('images/sub_sub_category/icon/'.$fileName);
   
             $sub_sub_category->icon = $fileName;
   
   
            }
   
            $sub_sub_category->save();
   
            if (count($request->selected_brand) > 0) {
               
               foreach ($request->selected_brand as  $value) {
                   
                   $brand = new SubCategoryBrand;
   
                   $brand->sub_sub_category_id = $sub_sub_category->id;
                   $brand->brand_id = $value['id'];
                   $brand->save();
   
               }
   
   
            }
   
            DB::commit();
           
            // clearing cache 

            cache()->forget('all-category');

            return response()->json(['status' => 'success','message' => 'Sub Sub Category Added Successfully !']);
   
           }
   
           catch(\Exception $e)
           {
             DB::rollback();
             return $e;
             
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
            $sub_sub_category = new  SubSubCategoryResource(SubSubCategory::find($id));

            $sub_category =  SubCategory::where('category_id','=',$sub_sub_category->category_id)->get();
   
            $brand_id = SubCategoryBrand::where('sub_sub_category_id','=',$id)
                                          ->where('status','=',AllStatic::$active)
                                          ->pluck('brand_id');
   
            $selected_brand = Brand::select('id','brand_name','brand_native_name')
                                     ->orderBy('brand_name','asc')
                                     ->whereIn('id',$brand_id)
                                     ->get();
   
   
   
            return json_encode([
                'sub_sub_category'=> $sub_sub_category,
                'sub_category' => $sub_category,
                'selected_brand' => $selected_brand,
                ]);
   
   
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
              
                'name' => 'required|unique:categories,category_name',
                'image' => 'nullable|image64:jpeg,jpg,png,gif',
                'category' => 'required',
                'sub_category' => 'required',
           ],
           [
            'image.image64' => 'File must be an image of jpeg,png,gif'
           ]);
   
           try
           {
   
            DB::beginTransaction();   
   
            $sub_category = SubSubCategory::find($id);
   
            $sub_category->category_id = $request->category;   
            $sub_category->sub_category_id = $request->sub_category;   
            $sub_category->sub_sub_category_name =  $request->name;   
            $sub_category->sub_sub_category_native_name =  $request->native_name;   
            $sub_category->status               =  $request->status;   
            
            $imageData = $request->get('image');
            
            if($imageData){
   
               if (file_exists('images/sub_sub_category/icon/'.$sub_category->icon) && !empty($sub_category->icon))
                {
                
                 unlink('images/sub_sub_category/icon/'.$sub_category->icon);
   
                   
               }
             
             $fileName = uniqid().'.'.explode('/', explode(':', substr($imageData, 0, strpos($imageData, ';')))[1])[1];
             Image::make($request->get('image'))->save('images/sub_sub_category/icon/'.$fileName);
   
             $sub_category->icon = $fileName;
   
   
            }
   
            $sub_category->update();
   
            // delete sub category brand 
   
            SubCategoryBrand::where('sub_sub_category_id','=',$sub_category->id)->delete();
   
            if (count($request->selected_brand) > 0) {
               
               foreach ($request->selected_brand as  $value) {
                   
                   $brand = new SubCategoryBrand;
   
                   $brand->sub_sub_category_id = $sub_category->id;
                   $brand->brand_id = $value['id'];
                   $brand->save();
   
               }
   
   
            }
   
            DB::commit();

            // clearing cache 

            cache()->forget('all-category');
   
            return response()->json(['status' => 'success','message' => 'Sub Sub Category Updated !']);
   
           }
   
           catch(\Exception $e)
           {
             DB::rollback();
             return $e;
             
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
   
               try{
                   DB::beginTransaction();
   
                    $count = Product::where('sub_sub_category_id','=',$id)->count();
   
                   if ($count>0) {
                       return response()->json(['status'=>'error','message'=>'can\'t delete this it have product !']);
                    }
                    else
                    {
   
                    $sub_category = SubSubCategory::find($id);
   
   
                   if(file_exists('images/sub_sub_category/icon/'.$sub_category->icon) && !empty($sub_category->icon))
                    {
                    
                     unlink('images/sub_sub_category/icon/'.$sub_category->icon);
   
                       
                    }
   
                    SubCategoryBrand::where('sub_sub_category_id','=',$id)->delete();
   
                    $sub_category->delete();
                    DB::commit();

                     // clearing cache 

                      cache()->forget('all-category');
                    return response()->json(['status'=>'success','message'=>'Delete Successfull !']);
   
   
                    }
   
                  }
   
               catch(\Exception $e)
               {
                   // return $e;
                   DB::rollBack();
                   return response()->json(['status'=>'error','message'=>'Something Went Wrong !']);
               }
        
       }
}
