<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use App\Models\PostCode;
use App\Models\Zone;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DateTime;
use DateInterval;
use Carbon\CarbonPeriod;

class PostCodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function zipTimeSlots($postcode)
    {
        $zip = PostCode::where('post_code',$postcode)->first();
        $zone = Zone::where('id',$zip->zone_id)->first();

        $date = Holiday::first()->value('holiday_date');
        $holiday_date = Carbon::parse($date)->format('Y-m-d');
        $today = Carbon::now()->format('Y-m-d');

//        Check if today is the holiday date.
        if ($today != $holiday_date){

            $pickUpTimeSlots = $this->getTimeSlots($zone->start_time, $zone->end_time, $zone->hours_interval,json_decode($zone->disabled_pick_up_slots));
            $deliveryTimeSlots = $this->getTimeSlots($zone->start_time, $zone->end_time, $zone->hours_interval,json_decode($zone->disabled_delivery_slots));

        }else{

            $pickUpTimeSlots = [];
            $deliveryTimeSlots = [];

        }

        return response()->json(['pickUpTimeSlots' => $pickUpTimeSlots,'deliveryTimeSlots' => $deliveryTimeSlots]);
    }

    public function getTimeSlots($startTime, $endTime, $interval,$disabledSlots) {
        $start = Carbon::parse($startTime);
        $end = Carbon::parse($endTime);

        $period= new CarbonPeriod($start,$interval.' hours',$end);

        $timeSlots = [];

        foreach ($period as $slot){
            // get the end time of the slot by adding the interval to the start time
            $endSlot = $slot->copy()->addHours($interval);

            // break if the end of the slot is after the overall end time
            if($endSlot->gt($end)) {
                break;
            }

            // create the timeslot range
            $timeSlotRange = $slot->format('H:i') . ' - ' . $endSlot->format('H:i');
            if (!in_array($timeSlotRange,$disabledSlots)){
                array_push($timeSlots, $timeSlotRange);
            }
        }

        return $timeSlots;
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
     * @param  \App\Models\PostCode  $postCode
     * @return \Illuminate\Http\Response
     */
    public function show(PostCode $postCode)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PostCode  $postCode
     * @return \Illuminate\Http\Response
     */
    public function edit(PostCode $postCode)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PostCode  $postCode
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PostCode $postCode)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PostCode  $postCode
     * @return \Illuminate\Http\Response
     */
    public function destroy(PostCode $postCode)
    {
        //
    }
}
