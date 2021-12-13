<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Category;
use App\Model\Size;
use App\AllStatic;
use App\Model\Product;

class SizeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $category = Category::select('id','category_name','category_native_name')
                    ->orderBy('category_name','desc')
            ->where('status','=',AllStatic::$active)   
            ->get();
        return view('product.size.size',[
            'category' => $category
           ]);
    }

    public function sizeList(Request $request)
    {
       $size = Size::with('category:id,category_name')->orderBy('name');

       if($request->keyword)
       {
         $size->where('name','like','%'.$request->keyword.'%');
       }

       $size = $size->paginate(10);

       return  $size;
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
            'name' => 'required',
            'category_id' => 'required'
        ]);
        
        try
        {
            $size = new Size;
            $size->name = $request->name;
            $size->category_id = $request->category_id;
            $size->save();
    
            return response()->json(['status'=>'success','message'=>'Size Added']);
        }
        catch(\Exception $e)
        {
            return response()->json(['status'=>'error','message'=>$e->getMessage()]);    
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
        //
    }

    public function getSizeByCategory($category_id)
    {
      $size = Size::select('id','name','category_id')
          ->where('category_id','=',$category_id)
          ->orderBy('name','ASC')
          ->get();
      return $size;
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
            'name' => 'required',
            'category_id' => 'required'
        ]);
        
        try
        {
            $size = Size::find($id);
            $size->name = $request->name;
            $size->category_id = $request->category_id;
            $size->update();
    
            return response()->json(['status'=>'success','message'=>'Size Updated']);
        }
        catch(\Exception $e)
        {
            return response()->json(['status'=>'error','message'=>$e->getMessage()]);    
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
        $product = Product::where('size_id','=',$id)->first();
        if($product){
            return response()->json(['status'=>'warning','message'=>'It used in Product']);
        } else {
            $size = Size::find($id);
            $size->delete();
            return response()->json(['status'=>'success','message'=>'Size Deleted']);
        }
        
    }
}
