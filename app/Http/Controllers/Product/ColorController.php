<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Color;
use DB;

class ColorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('product.color.color');
    }

    public function getColor()
    {
        $color = Color::select('id','name','color_code')
        ->orderBy('name','ASC')
        ->get();

        return $color;
    }

    public function colorList(Request $request)
    {
       $color = Color::orderBy('name');

       if($request->keyword)
       {
         $color->where('name','like','%'.$request->keyword.'%');
       }

       $color = $color->paginate(10);

       return  $color;
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
                'color_code' => 'required|unique:colors,color_code'
            ]);
            
            try
            {
                $color = new Color;
                $color->name = $request->name;
                $color->color_code = $request->color_code;
                $color->save();

                if($request->from == 'createproduct'){
                    // return "hello";
                    $data = Color::latest()->first();
                    return $data;
                }
        
                return response()->json(['status'=>'success','message'=>'Color Added']);
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
                'color_code' => 'required|unique:colors,color_code,'.$id
            ]);
            
            try
            {
                $color = Color::find($id);
                $color->name = $request->name;
                $color->color_code = $request->color_code;
                $color->update();
        
                return response()->json(['status'=>'success','message'=>'Color Updated']);
            }
            catch(Exception $e)
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

        $color = Color::find($id);
        $color->delete();

        return response()->json(['status'=>'success','message'=>'Color Deleted']);

    }
}
