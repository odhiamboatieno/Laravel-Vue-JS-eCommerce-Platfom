<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\AllStatic;
use App\Model\Brand;
use App\Model\Category;
use App\Model\SubCategory;
use App\Model\Product;
use App\Model\Order\Order;
use App\Model\Campaign;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.dashboard');
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $stock = Product::sum('current_quantity');
        $category = Category::count();
        $subcategory = SubCategory::count();
        $product = Product::count();
        $customer = User::count();
        $order = Order::count();
        $paidorder = Order::where('payment_status', AllStatic::$paid)->count();
        $unpaidorder = Order::where('payment_status', AllStatic::$not_paid)->count();
        $delivered = Order::where('status', AllStatic::$delivered)->count();
        $ondelivery = Order::where('status', AllStatic::$on_delivery)->count();
        $pendingorder = Order::where('status', AllStatic::$pending)->count();
        $onprocess = Order::where('status', AllStatic::$processing)->count();
        $campaign = Campaign::count();
        return response()->json(['stock' => $stock,'category' => $category,'product' => $product,'order' => $order,'paidorder' => $paidorder,'unpaidorder' => $unpaidorder,'delivered' => $delivered,'ondelivery' => $ondelivery,'pendingorder' => $pendingorder, 'onprocess' => $onprocess, 'subcategory' => $subcategory, 'customer' => $customer, 'campaign' => $campaign]);
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
