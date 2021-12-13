<?php

namespace App\Http\Controllers\Offers;

use App\AllStatic;
use App\Http\Controllers\Controller;
use App\Http\Resources\Offer\CampaignResource;
use App\Http\Resources\Product\ProductResource;
use App\Model\Campaign;
use App\Model\Product;
use DB;
use Illuminate\Http\Request;
use Image;

class CampaignController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.offers.campaign.campaign');
    }

    public function productList(Request $request)
    {

        if ($request->keyword != '') {

            $search_keyword = $request->keyword;

            $product = Product::where(function ($query) use ($search_keyword) {
                $query->where('product_name', 'LIKE', '%' . $search_keyword . '%')
                    ->orWhere('product_native_name', 'LIKE', '%' . $search_keyword . '%')
                    ->orWhere('product_keyword', 'LIKE', '%' . $search_keyword . '%');
            })
                ->where('status', AllStatic::$active)
                ->where('discount_status', '=', AllStatic::$inactive)
                ->orderBy('product_name', 'ASC')
                ->get();

            return ProductResource::collection($product);

        } else {

            return response()->json(['status' => 'not-found']);

        }

    }

    public function offerList(Request $request)
    {

        $campaign = Campaign::orderBy('updated_at', 'desc');

        if ($request->keyword != '') {

            $campaign->where('title', 'LIKE', '%' . $request->keyword . '%');
        }
        $campaign = $campaign->paginate(10);

        return CampaignResource::collection($campaign);

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
            'campaign_title' => 'required',
            'banner'         => 'required|image64:jpeg,png,gif,jpg,webp,bmp',
            'meta_image'     => 'required|image64:jpeg,png,gif,jpg,webp,bmp',
            'status'         => 'required',

        ]);

        try
        {

            DB::beginTransaction();

            $camp         = new Campaign;
            $camp->title  = $request->campaign_title;
            $camp->status = $request->status;

            $imageData = $request->get('banner');
            $metaImage = $request->get('meta_image');

            if ($imageData) {

                $fileName = 'banner_image_' . uniqid() . '.' . explode('/', explode(':', substr($imageData, 0, strpos($imageData, ';')))[1])[1];
                Image::make($request->get('banner'))->save('images/campaign/banner/' . $fileName);

                $camp->image = $fileName;

            }

            if ($metaImage) {

                $metafile = 'meta_image' . uniqid() . '.' . explode('/', explode(':', substr($metaImage, 0, strpos($metaImage, ';')))[1])[1];
                Image::make($request->get('meta_image'))->save('images/campaign/meta/' . $metafile);

                $camp->meta_image = $metafile;

            }

            $request->start_date = date('Y-M-d');
            $request->end_date   = date('Y-M-d');

            $camp->save();

            if (count($request->product) > 0) {
                foreach ($request->product as $value) {

                    $product                  = Product::find($value['id']);
                    $product->discount_status = $request->status;
                    $product->discount        = $value['discount'];
                    $product->discount_amount = $value['discount_amount'];
                    $product->discount_type   = $value['discount_type'];
                    $product->campaign_id     = $camp->id;
                    $product->update();
                }

            }

            DB::commit();

            return response()->json(['status' => 'success', 'message' => 'Campaign Added!']);

        } catch (Exceptoin $e) {

            // return $e;
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => 'Something Went Wrong']);

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
        $campaign = Campaign::with('product')->find($id);

        return new CampaignResource($campaign);
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
            'campaign_title' => 'required',
            'banner'         => 'nullable|image64:jpeg,png,gif,jpg,webp,bmp',
            'meta_image'     => 'nullable|image64:jpeg,png,gif,jpg,webp,bmp',
            'status'         => 'required',

        ]);

        try
        {

            DB::beginTransaction();

            $camp         = Campaign::find($id);
            $camp->title  = $request->campaign_title;
            $camp->status = $request->status;

            $imageData = $request->get('banner');
            $metaImage = $request->get('meta_image');

            if ($imageData) {

                if (file_exists('images/campaign/banner/' . $camp->image) && !empty($camp->image)) {
                    unlink('images/campaign/banner/' . $camp->image);
                }

                $fileName = 'banner_image_' . uniqid() . '.' . explode('/', explode(':', substr($imageData, 0, strpos($imageData, ';')))[1])[1];
                Image::make($request->get('banner'))->save('images/campaign/banner/' . $fileName);

                $camp->image = $fileName;

            }

            if ($metaImage) {

                if (file_exists('images/campaign/meta/' . $camp->meta_image) && !empty($camp->meta_image)) {
                    unlink('images/campaign/meta/' . $camp->meta_image);
                }

                $metafile = 'meta_image' . uniqid() . '.' . explode('/', explode(':', substr($metaImage, 0, strpos($metaImage, ';')))[1])[1];
                Image::make($request->get('meta_image'))->save('images/campaign/meta/' . $metafile);

                $camp->meta_image = $metafile;

            }

            $request->start_date = date('Y-M-d');
            $request->end_date   = date('Y-M-d');
            $camp->update();

            Product::where('campaign_id', '=', $id)
                ->update([
                    'discount'        => 0,
                    'discount'        => 0,
                    'discount_amount' => 0,
                    'discount_type'   => 1,
                    'discount_status' => 0,
                    'campaign_id'     => 0,
                ]);

            if (count($request->product) > 0) {
                foreach ($request->product as $value) {

                    $product                  = Product::find($value['id']);
                    $product->discount_status = $request->status;
                    $product->discount        = $value['discount'];
                    $product->discount_amount = $value['discount_amount'];
                    $product->discount_type   = $value['discount_type'];
                    $product->campaign_id     = $camp->id;
                    $product->update();
                }

            }

            DB::commit();

            return response()->json(['status' => 'success', 'message' => 'Campaign Added!']);

        } catch (Exceptoin $e) {
            // return $e;
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => 'Something Went Wrong']);
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
            DB::beginTransaction();
            $camp = Campaign::find($id);
            if (file_exists('images/campaign/banner/' . $camp->image) && !empty($camp->image)) {
                unlink('images/campaign/banner/' . $camp->image);
            }

            if (file_exists('images/campaign/meta/' . $camp->meta_image) && !empty($camp->meta_image)) {
                unlink('images/campaign/meta/' . $camp->meta_image);
            }

            $camp->delete();

            Product::where('campaign_id', '=', $id)
                ->update([
                    'discount'        => 0,
                    'discount'        => 0,
                    'discount_amount' => 0,
                    'discount_type'   => 1,
                    'discount_status' => 0,
                    'campaign_id'     => 0,
                ]);

            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Campaign Deleted  !']);
        } catch (\Exception $e) {

            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => 'Something Went Wrong !']);

        }
    }

    public function offerStatus($id)
    {
        try {

            DB::beginTransaction();

            $camp = Campaign::find($id);

            $camp->status = $camp->status == AllStatic::$active ? AllStatic::$inactive : AllStatic::$active;

            $message = $camp->status == AllStatic::$active ? 'Campaign Activated!' : 'Campaign Deactivated!';

            $camp->update();

            Product::where('campaign_id', '=', $camp->id)->update(['discount_status' => $camp->status]);

            DB::commit();

            return response()->json(['status' => 'success', 'message' => $message]);
        } catch (\Exception $e) {
            // return $e;
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => 'Something went wrong!']);

        }
    }

    public function getOffer($id, $offer_name)
    {
        $campaign = Campaign::find($id);
        // return $campaign;
        return view('front.offers.OfferProduct', ['campaign' => $campaign]);
    }

    public function allOffer()
    {
        return view('front.offers.offers');
    }

}
