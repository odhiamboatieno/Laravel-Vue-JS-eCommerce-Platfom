<?php

namespace App\Http\Controllers\Front;

use App\AllStatic;
use App\Http\Controllers\Controller;
use App\Http\Resources\Offer\CampaignResource;
use App\Http\Resources\Product\BrandResource;
use App\Http\Resources\Product\CategoryResource;
use App\Http\Resources\Product\ProductResource;
use App\Http\Resources\Product\SubCategoryResource;
use App\Http\Resources\Product\SubSubCategoryResource;
use App\Model\Brand;
use App\Model\Campaign;
use App\Model\Category;
use App\Model\Product;
use App\Model\Setting\PageSetting;
use App\Model\SubCategory;
use App\Model\SubCategoryBrand;
use App\Model\Subscribe;
use App\Model\SubSubCategory;
use App\Repository\ProductRepository;
use Illuminate\Http\Request;

class WebController extends Controller
{
    protected $productRepository;
    public function __construct(ProductRepository $productRepo)
    {
        $this->productRepository = $productRepo;
    }

    public function index()
    {
        return view('front.index');

    }

    public function ShowPage($slug)
    {
        $data = PageSetting::where('title', str_replace('-', ' ', $slug))->first();
        return view('front.include.page', ['data' => $data]);
    }

    public function categoryList()
    {

        $category = Category::where('status', '=', AllStatic::$active)->get();

        return CategoryResource::collection($category);

    }

    public function homeOffers()
    {

        $campaign = Campaign::select('id', 'title', 'image')
            ->orderBy('updated_at', 'desc')
            ->where('status', '=', 1)
            ->take(3)
            ->get();

        return CampaignResource::collection($campaign);

    }

    public function productList(Request $request)
    {
        // all product will response from repo with all filtering from different palce
        return $this->productRepository->getFrontProduct($request);
    }

    // front category sub category maintaning

    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:subscribers',
        ]);
        try {
            Subscribe::create($request->all());
            return response()->json(['status' => 'success', 'message' => 'You Subscribed Successfully, Thank You!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMassage()]);
        }

    }

    public function categoryProduct($id, $slug)
    {
        $category = new CategoryResource(Category::with(['sub_category' => function ($query) {
            $query->where('status', '=', AllStatic::$active);
        }])
                ->find($id));

        return view('front.cateogry.category', ['category' => $category]);

    }

    public function subCategory($id, $slug = null)
    {

        $sub_category = SubCategory::with(['category:id,category_name',
            'sub_sub_category' => function ($query) {
                $query->where('status', '=', AllStatic::$active);
            }])
            ->find($id);

        return view('front.sub-category.sub_category',
            [
                'sub_category' => new SubCategoryResource($sub_category),
            ]);

    }

    // sub sub category or level three

    public function subSubCategory($id, $slug = null)
    {

        $sub_sub_category = SubSubCategory::with('sub_category')
            ->find($id);

        $brand_id = SubCategoryBrand::where('sub_sub_category_id', '=', $id)
            ->where('status', '=', AllStatic::$active)
            ->pluck('brand_id');

        $sub_sub_category_brand = Brand::select('id', 'brand_name', 'brand_native_name', 'brand_logo')
            ->orderBy('brand_name', 'asc')
            ->whereIn('id', $brand_id)->where('status', AllStatic::$active)->get();

        return view('front.sub-sub-category.sub_sub_category',
            [
                'sub_sub_category' => new SubSubCategoryResource($sub_sub_category),
                'brands'           => BrandResource::collection($sub_sub_category_brand),
            ]);

    }

    public function productDetails($id, $slug)
    {
        $product = Product::with([
            'category:id,category_name',
            'sub_category:id,sub_category_name',
            'brand:id,brand_name',
            'size',
            'color',
            'multiple_image',
        ])->find($id);

        return view('front.product.single_product', ['product' => new ProductResource($product)]);
    }

}
