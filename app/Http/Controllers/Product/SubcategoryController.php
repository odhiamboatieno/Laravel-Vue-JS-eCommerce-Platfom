<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\SubCategory;
use App\Model\Category;
use App\Model\Brand;
use App\Model\SubCategoryBrand;
use App\Model\Product;
use App\Model\Size;
use App\AllStatic;
use App\Http\Resources\Product\SubCategoryResource;
use DB;
use Image;
use Cache;

class SubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $category = Category::
                    select('id','category_name','category_native_name')
                    ->where('status','=',AllStatic::$active)
                    ->get();


        return view('admin.subcategory.subcategory',[
          
          'category' => $category,

        ]);
    }

    public function subCategoryList(Request $request){

     $sub_category = SubCategory::
                     with(['category:id,category_name,category_native_name'])
                     ->orderBy('updated_at','desc');

     if ($request->category != '' && $request->category != null)
     {

        $sub_category->where('category_id','=',$request->category);

        // return $request->category

     }

     if ($request->keyword != '')
      {
       
       $sub_category->where('sub_category_name','LIKE','%'.$request->keyword.'%')
                    ->orWhere('sub_category_native_name','LIKE','%'.$request->keyword.'%');

      }   


      $sub_category = $sub_category->paginate(10);

      return SubCategoryResource::collection($sub_category);                          

    }


    // get sub category by category id 

    public function getSubCategory(Request $request,$id){
         
         $sub_category = SubCategory::select('id','sub_category_name','sub_category_native_name')
                                      ->where('category_id','=',$id)
                                      ->orderBy('sub_category_name','asc');
         // in edit time we required active inactive all sub category  
         if (!$request->has('edit_time')) 
            {
             $sub_category->where('status',AllStatic::$active);
            }
          $sub_category =  $sub_category->get();

        return $sub_category;

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
        ],
        [
         'image.image64' => 'File must be an image of jpeg,png,gif'
        ]);

        try
        {

         DB::beginTransaction();   

         $sub_category = new SubCategory;

         $sub_category->category_id        =  $request->category;   
         $sub_category->sub_category_name =  $request->name;   
         $sub_category->sub_category_native_name =  $request->native_name;   
         $sub_category->status               =  $request->status;   
         
         $imageData = $request->get('image');
         
         if($imageData){
          
          $fileName = uniqid().'.'.explode('/', explode(':', substr($imageData, 0, strpos($imageData, ';')))[1])[1];
          Image::make($request->get('image'))->save('images/sub_category/icon/'.$fileName);

          $sub_category->icon = $fileName;


         }

         $sub_category->save();

         DB::commit();
        //  clear home page category cache 
         Cache::forget('all-category');
         return response()->json(['status' => 'success','message' => 'Sub Category Added Successfully !']);

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
           return $sub_category = new  SubCategoryResource(SubCategory::find($id));
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
        ],
        [
         'image.image64' => 'File must be an image of jpeg,png,gif'
        ]);

        try
        {

         DB::beginTransaction();   

         $sub_category = SubCategory::find($id);

         $sub_category->category_id = $request->category;   
         $sub_category->sub_category_name =  $request->name;   
         $sub_category->sub_category_native_name =  $request->native_name;   
         $sub_category->status               =  $request->status;   
         
         $imageData = $request->get('image');
         
         if($imageData){

            if (file_exists('images/sub_category/icon/'.$sub_category->icon) && !empty($sub_category->icon))
             {
             
              unlink('images/sub_category/icon/'.$sub_category->icon);

                
            }
          
          $fileName = uniqid().'.'.explode('/', explode(':', substr($imageData, 0, strpos($imageData, ';')))[1])[1];
          Image::make($request->get('image'))->save('images/sub_category/icon/'.$fileName);

          $sub_category->icon = $fileName;


         }

         $sub_category->update();

         DB::commit();
        
         //  clear home page category cache 
        Cache::forget('all-category');
         return response()->json(['status' => 'success','message' => 'Sub Category Updated !']);

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

                 $count = Product::where('sub_category_id','=',$id)->count();

                if ($count>0) {
                    return response()->json(['status'=>'error','message'=>'can\'t delete this it have product !']);
                 }
                 else
                 {

                 $sub_category = SubCategory::find($id);


                if(file_exists('images/sub_category/icon/'.$sub_category->icon) && !empty($sub_category->icon))
                 {
                 
                  unlink('images/sub_category/icon/'.$sub_category->icon);

                    
                 }

                 $sub_category->delete();

                //  clear home page category cache
                Cache::forget('all-category');

                 return response()->json(['status'=>'success','message'=>'Delete Successfull !']);


                 }

               }

            catch(\Exception $e)
            {
                // return $e;
                return response()->json(['status'=>'error','message'=>'Something Went Wrong !']);
            }
            

     
    }
}
