<?php

namespace App\Http\Controllers\Product;

use App\AllStatic;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\Product\ProductResource;
use App\Model\Brand;
use App\Model\Category;
use App\Model\Order\OrderDetails;
use App\Model\Product;
use App\Model\ProductColor;
use App\Model\ProductImage;
use App\Model\ProductKeyword;
use App\Model\ProductSize;
use App\Model\Size;
use App\Model\SubCategory;
use App\Model\SubCategoryBrand;
use App\Model\SubSubCategory;
use Auth;
use DB;
use Illuminate\Http\Request;
use Image;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function getAllCategory($edit_time = '')
    {
        $category = Category::select('id', 'category_name', 'category_native_name')
            ->orderBy('category_name', 'desc');
        // during edit time will pass a value and will get all category active in_active
        if ($edit_time = '') {
            $category->where('status', '=', AllStatic::$active);
        }
        return $category = $category->get();
    }

    public function getSubCategoryByCategory($id)
    {
        $sub_category = SubCategory::select('id', 'sub_category_name', 'sub_category_native_name')
            ->where('category_id', '=', $id)
            ->orderBy('sub_category_name', 'ASC')
            ->get();
        return $sub_category;
    }

    public function getSizeByCategory($id)
    {
        $sizes = Size::select('id', 'name', 'category_id')
            ->where('category_id', '=', $id)
            ->orderBy('name', 'ASC')
            ->get();
        return $sizes;
    }

    public function getSubSubCategoryBySubCategory($id)
    {
        $sub_sub_category = SubSubCategory::select('id', 'sub_sub_category_name', 'sub_sub_category_native_name')
            ->where('sub_category_id', '=', $id)
            ->orderBy('sub_sub_category_name', 'ASC')
            ->get();
        return $sub_sub_category;
    }

    public function brandBySubcategory($id)
    {

        $brand_id = SubCategoryBrand::where('sub_sub_category_id', '=', $id)
            ->where('status', '=', AllStatic::$active)
            ->pluck('brand_id');

        $sub_category_brand = Brand::select('id', 'brand_name', 'brand_native_name')
            ->orderBy('brand_name', 'asc')
            ->whereIn('id', $brand_id)
            ->get();

        return $sub_category_brand;
    }

    public function index()
    {

        $category = $this->getAllCategory();
        return view('admin.product.product', [
            'category' => $category,
        ]);
    }

    public function productList(Request $request)
    {
        // return $request['category'];

        $search_keyword = $request->keyword;
        $product        = Product::with([
            'category:id,category_name,category_native_name',
            'sub_category:id,sub_category_name,sub_category_native_name',
            'sub_sub_category:id,sub_sub_category_name,sub_sub_category_native_name',
            'brand:id,brand_name,brand_native_name',
            'multiple_image:id,product_id,image_name',
        ])
            ->orderBy('updated_at', 'desc');

        if ($request->category != 'undefined') {
            $product->where('category_id', '=', $request->category);
        }
        if ($request->sub_category != 'undefined') {
            $product->where('sub_category_id', '=', $request->sub_category);
        }

        if ($request->sub_sub_category != 'undefined') {
            $product->where('sub_sub_category_id', '=', $request->sub_sub_category);
        }

        if ($request->brand != 'undefined') {
            $product->where('brand_id', '=', $request->brand);
        }

        if ($request->range != '') {
            $date  = $request->range;
            $data  = explode(",", $date);
            $start = date_convert($data[0]);
            $end   = date_convert($data[1]);
            $product->whereBetween('updated_at', [$start, $end]);
        }

        if ($search_keyword != '') {
            // this three field  or combination doing a and comibination with all other combination in upper
            $product->where(function ($query) use ($search_keyword) {
                $query->where('product_name', 'LIKE', '%' . $search_keyword . '%')
                    ->orWhere('product_native_name', 'LIKE', '%' . $search_keyword . '%')
                    ->orWhere('product_keyword', 'LIKE', '%' . $search_keyword . '%');
            });
        }
        $product = $product->paginate(12);

        return ProductResource::collection($product);

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
    public function store(ProductRequest $request)
    {
        try
        {

            DB::beginTransaction();

            // product adding code will added here

            $product = new Product;

            $product->product_name        = $request->product_name;
            $product->product_native_name = $request->product_native_name;
            $product->category_id         = $request->category;
            $product->sub_category_id     = $request->sub_category;
            $product->sub_sub_category_id = $request->sub_sub_category;
            $product->brand_id            = $request->brand;
            $product->quantity_unit       = $request->quantity_unit;
            $product->stock_quantity      = $request->quantity;
            $product->current_quantity    = $request->quantity;
            $product->buying_price        = $request->buying_price;
            $product->trialable           = $request->trialable;
            $product->selling_price       = $request->selling_price;
            $product->product_description = $request->description;
            $product->created_by          = Auth::guard('admin')->user()->id;

            // if product have tag then make it string and save it to product keyword
            if ($request->product_tag) {

                $keywords                 = implode(', ', $request->product_tag);
                $product->product_keyword = $keywords;

            }

            $imageData = $request->get('image');

            if ($imageData) {

                $fileName = uniqid() . '.' . explode('/', explode(':', substr($imageData, 0, strpos($imageData, ';')))[1])[1];

                Image::make($request->get('image'))
                // ->resizeCanvas(600, 600, 'center', false, '#ffffff')
                    ->save('images/product/feature/' . $fileName);

                // this line for adding watermark
                // ->insert('images/logo/5eb0f9ea62736.png', 'bottom-right', 5, 5)

                $product->product_image = $fileName;

            }

            $product->save();

            // prduct tag
            if ($request->product_tag) {

                foreach ($request->product_tag as $value) {
                    $product_keyword               = new ProductKeyword;
                    $product_keyword->keyword_name = $value;
                    $product_keyword->product_id   = $product->id;
                    $product_keyword->save();
                }

            }

            if ($request->product_size) {

                foreach ($request->product_size as $value) {
                    $size             = new ProductSize;
                    $size->size_id    = $value;
                    $size->product_id = $product->id;
                    $size->status     = 1;
                    $size->save();
                }

            }
            if ($request->product_color) {

                foreach ($request->product_color as $value) {
                    $color             = new ProductColor;
                    $color->color_id   = $value;
                    $color->product_id = $product->id;
                    $color->status     = 1;
                    $color->save();
                }

            }

            if ($request->file('attachments')) {

                foreach ($request->file('attachments') as $key => $file) {
                    $name = time() . '_' . rand(1000, 4000) . '.' . $file->getClientOriginalExtension();

                    Image::make($file)
                    // ->resizeCanvas(600, 600, 'center', false, '#ffffff')
                        ->save('images/product/image/' . $name);

                    $product_image             = new ProductImage;
                    $product_image->image_name = $name;
                    $product_image->product_id = $product->id;
                    $product_image->save();
                }

            }

            DB::commit();

            return response()->json(['status' => 'success', 'message' => 'Product Added !']);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);

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
        $product = new ProductResource(Product::with('category:id,category_name,category_native_name',
            'sub_category:id,sub_category_name,sub_category_native_name',
            'sub_sub_category:id,sub_sub_category_name,sub_sub_category_native_name',
            'brand:id,brand_name,brand_native_name',
            'color', 'size',
            'multiple_image:id,product_id,image_name',
            'productKeyword:id,product_id,keyword_name')->find($id));

        // return $product;

        return response()->json([
            'product'            => $product,
            'categories'         => $this->getAllCategory('yes'),
            'sub_categories'     => $this->getSubCategoryByCategory($product->category->id),
            'sizes'              => $this->getSizeByCategory($product->category->id),
            'sub_sub_categories' => $this->getSubSubCategoryBySubCategory($product->sub_category_id),
            'brands'             => $this->brandBySubcategory($product->sub_sub_category_id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, $id)
    {

        try
        {

            DB::beginTransaction();

            // product adding code will added here

            $product = Product::find($id);

            $product->product_name        = $request->product_name;
            $product->product_native_name = $request->product_native_name;
            $product->category_id         = $request->category;
            $product->sub_category_id     = $request->sub_category;
            $product->sub_sub_category_id = $request->sub_sub_category;

            if ($request->brand_id != 0) {
                $product->brand_id = $request->brand;
            }

            if ($request->quantity >= $product->current_quantity) {

                $increment = $request->quantity - $product->current_quantity;

                $product->stock_quantity = $product->stock_quantity + $increment;

            } else {

                $decrement = $product->current_quantity - $request->quantity;

                $product->stock_quantity = $product->stock_quantity - $decrement;

            }

            $product->quantity_unit       = $request->quantity_unit;
            $product->current_quantity    = $request->quantity;
            $product->buying_price        = $request->buying_price;
            $product->selling_price       = $request->selling_price;
            $product->product_description = $request->description;
            $product->trialable           = $request->trialable;
            $product->updated_by          = Auth::guard('admin')->user()->id;

            // if product have tag then make it string and save it to product keyword
            if ($request->product_tag) {

                $keywords                 = implode(', ', $request->product_tag);
                $product->product_keyword = $keywords;

            }

            ProductSize::where('product_id', '=', $id)->delete();
            if ($request->product_size) {
                foreach ($request->product_size as $value) {
                    $size             = new ProductSize;
                    $size->size_id    = $value;
                    $size->product_id = $product->id;
                    $size->status     = 1;
                    $size->save();
                }

            }
            ProductColor::where('product_id', '=', $id)->delete();
            if ($request->product_color) {
                foreach ($request->product_color as $value) {
                    $color             = new ProductColor;
                    $color->color_id   = $value;
                    $color->product_id = $product->id;
                    $color->status     = 1;
                    $color->save();
                }

            }

            $imageData = $request->get('image');

            if ($imageData) {

            }
            if ($request->product_color) {
                ProductColor::where('product_id', '=', $id)->delete();
                foreach ($request->product_color as $value) {
                    $color             = new ProductColor;
                    $color->color_id   = $value;
                    $color->product_id = $product->id;
                    $color->status     = 1;
                    $color->save();
                }

            }

            $imageData = $request->get('image');

            if ($imageData) {

                if (file_exists('image/product/feature/' . $product->product_image) && !empty($product->product_image)) {
                    unlink('image/product/feature/' . $product->product_image);
                }

                $fileName = uniqid() . '.' . explode('/', explode(':', substr($imageData, 0, strpos($imageData, ';')))[1])[1];
                Image::make($request->get('image'))
                // ->resizeCanvas(600, 600, 'center', false, '#ffffff')
                    ->save('images/product/feature/' . $fileName);

                $product->product_image = $fileName;

            }

            $product->update();

            // prduct tag
            if ($request->product_tag) {

                // delete old tag

                ProductKeyword::where('product_id', '=', $id)->delete();

                foreach ($request->product_tag as $value) {

                    $product_keyword               = new ProductKeyword;
                    $product_keyword->keyword_name = $value;
                    $product_keyword->product_id   = $product->id;
                    $product_keyword->save();
                }

            }

            if ($request->file('attachments')) {

                foreach ($request->file('attachments') as $key => $file) {
                    $name = time() . '_' . rand(1000, 4000) . '.' . $file->getClientOriginalExtension();
                    // $file->move('images/product/image/', $name);
                    Image::make($file)
                    // ->resizeCanvas(600, 600, 'center', false, '#ffffff')
                        ->save('images/product/image/' . $name);

                    $product_image             = new ProductImage;
                    $product_image->image_name = $name;
                    $product_image->product_id = $product->id;
                    $product_image->save();
                }

            }

            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Product updated !']);
        } catch (\Exception $e) {
            return $e;
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => 'oops! something went wrong']);
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
        try {

            // check order details

            $count = OrderDetails::where('product_id', '=', $id)->count();
            if ($count > 0) {

                return response()->json(['status' => 'error', 'message' => 'This Product Has  Sale']);
            }

            DB::beginTransaction();

            ProductKeyword::where('product_id', '=', $id)->delete();

            $product_image = ProductImage::where('product_id', '=', $id)->get();

            if (count($product_image) > 0) {

                foreach ($product_image as $value) {

                    $p_image = ProductImage::find($value->id);

                    if (file_exists('images/product/image/' . $p_image->image_name) && !empty($p_image->image_name)) {
                        unlink('images/product/image/' . $p_image->image_name);
                    }
                    $p_image->delete();

                }
            }

            $product = Product::find($id);

            if (file_exists('images/product/feature/' . $product->product_image) && !empty($product->product_image)) {
                unlink('images/product/feature/' . $product->product_image);
            }

            $product->delete();

            DB::commit();

            return response()->json(['status' => 'success', 'message' => 'Product Deleted']);

        } catch (\Exception $e) {
            // return $e;
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => 'Something Went Wrong !']);

        }
    }

    public function deleteImage($id)
    {

        try
        {

            $p_image = ProductImage::find($id);

            if (file_exists('images/product/image/' . $p_image->image_name) && !empty($p_image->image_name)) {
                unlink('images/product/image/' . $p_image->image_name);
            }

            $p_image->delete();

            return response()->json(['status' => 'success', 'message' => 'image Deleted !']);

        } catch (\Exception $e) {

            return response()->json(['status' => 'error', 'message' => 'something went wrong !']);

        }

    }

    public function deactiveProduct($id)
    {

        try {

            $product = Product::find($id);

            $product->status = $product->status == AllStatic::$active ? AllStatic::$inactive : AllStatic::$active;

            $message = $product->status == AllStatic::$active ? 'Product Deactiveted !' : 'Product Activeted';

            $product->update();

            return response()->json(['status' => 'success', 'message' => $message]);
        } catch (\Exception $e) {
            return $e;
            return response()->json(['status' => 'error', 'message' => 'somethin went wrong !']);

        }
    }

    public function hotDealStatus($id)
    {

        try {

            $product = Product::find($id);

            $product->hot_deal = $product->hot_deal == AllStatic::$active ? AllStatic::$inactive : AllStatic::$active;

            $message = $product->hot_deal == AllStatic::$active ? 'Product Added to Hot Deal !' : 'Product Remove From Hot Deal';

            $product->update();

            return response()->json(['status' => 'success', 'message' => $message]);
        } catch (\Exception $e) {
            return $e;
            return response()->json(['status' => 'error', 'message' => 'somethin went wrong !']);

        }
    }

    public function getForDiscount($id)
    {
        return new ProductResource(Product::find($id));
    }

    public function setDiscount(Request $request)
    {
        $request->validate([
            'discount' => 'required|numeric|gt:0',
        ]);

        $status = $request->discount_status ? 1 : 0;
        Product::where('id', '=', $request->id)
            ->update([
                'discount'        => (float) $request->discount,
                'discount_amount' => (float) $request->discount_amount,
                'discount_type'   => $request->discount_type,
                'discount_status' => $status,
            ]);
        return response()->json(['status' => 'success', 'message' => 'Discount Added Successfull!']);
    }
}
