<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Model\Setting\DeliveryTimeSLot;
use Illuminate\Http\Request;

class TimeSlotSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $time_slot = DeliveryTimeSLot::orderBy('expired_at', 'asc')->get();

        return $time_slot;
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
            'slot_name'  => 'required',
            'expired_at' => 'required|date_format:H:i',
        ], [
            'expired_at:date_format' => 'Time Format Must Be hh:ii as like 07:20 ',
        ]);

        try {
            $time_slot             = new DeliveryTimeSLot();
            $time_slot->slot_name  = $request->slot_name;
            $time_slot->expired_at = $request->expired_at;
            $time_slot->status     = $request->status;
            $time_slot->save();
            return response()->json(['status' => 'success', 'message' => 'New Time Slot Added']);
        } catch (\Throwable $th) {

            return response()->json(['status' => 'error', 'message' => 'Something Went Wrong']);
        }
    }

    public function getSlotByDate($date)
    {
        $time_slot = DeliveryTimeSLot::orderBy('expired_at', 'asc')
            ->where('status', '=', 1);

        if ($date == date('Y-m-d')) {
            $current_time = date("H:i:s");
            $time_slot->where('expired_at', '>', $current_time);
        }
        $time_slot = $time_slot->get();

        return $time_slot;

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
        return DeliveryTimeSLot::finOrFail($id);
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
            'slot_name'  => 'required',
            'expired_at' => 'required|date_format:H:i:s',
        ]);

        try {
            $time_slot             = DeliveryTimeSLot::find($id);
            $time_slot->slot_name  = $request->slot_name;
            $time_slot->expired_at = $request->expired_at;
            $time_slot->status     = $request->status;
            $time_slot->update();
            return response()->json(['status' => 'success', 'message' => 'Time Slot Updated']);
        } catch (\Throwable $th) {

            return response()->json(['status' => 'error', 'message' => $th->getMessage()]);
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
            $time_slot = DeliveryTimeSLot::find($id);
            $time_slot->delete();
            return response()->json(['status' => 'success', 'message' => 'Time Slot Deleted']);
        } catch (\Throwable $th) {

            return response()->json(['status' => 'error', 'message' => 'Something Went Wrong']);
        }
    }
}
