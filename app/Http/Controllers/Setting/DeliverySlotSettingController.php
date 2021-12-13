<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Model\Setting\DeliverySlotSetting;
use Illuminate\Http\Request;

class DeliverySlotSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.setting.slot.date_time_slot');
    }

    public function getSlot()
    {
        return DeliverySlotSetting::orderBy('id', 'desc')->first();
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
            'date_interval' => 'required|integer',
            'date_end'      => 'required|gt:0|integer',
        ]);

        try {
            $slot                = DeliverySlotSetting::find($request->id);
            $slot->date_interval = $request->date_interval;
            $slot->date_end      = $request->date_end;
            $slot->status        = $request->status;
            $slot->update();
            return response()->json(['status' => 'success', 'message' => 'Delivery SLot Setting Updated']);
        } catch (\Throwable $th) {
            return response()->json(['status' => 'error', 'message' => 'Something Went Wrong!']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Setting\DeliverySlotSetting  $deliverySlotSetting
     * @return \Illuminate\Http\Response
     */
    public function show(DeliverySlotSetting $deliverySlotSetting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Setting\DeliverySlotSetting  $deliverySlotSetting
     * @return \Illuminate\Http\Response
     */
    public function edit(DeliverySlotSetting $deliverySlotSetting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Setting\DeliverySlotSetting  $deliverySlotSetting
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DeliverySlotSetting $deliverySlotSetting)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Setting\DeliverySlotSetting  $deliverySlotSetting
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeliverySlotSetting $deliverySlotSetting)
    {
        //
    }
}
