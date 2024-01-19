<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\BlockedPostCode;
use App\Models\Holiday;
use App\Models\PostCode;
use App\Models\Zone;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class PostCodeController extends Controller
{
    private $client;

    const DAYS_TO_CHECK = 14;

    public function __construct(Client $client)
    {
        $this->client =  new Client([
            'verify' => false
        ]);
    }

    public function checkService($postcode)
    {
        $baseURL = 'https://api.getaddress.io/find/';
        $apiKey = config('services.get_address.key');
        $url = "{$baseURL}{$postcode}?expand=true&api-key={$apiKey}";

        try {
            $response = $this->client->get($url);
            $data = json_decode($response->getBody()->getContents(), true);

            $addresses = $data['addresses'];
            $postcode = $data['postcode'];
            $city = $data['addresses'][0]['town_or_city'];
            $country = $data['addresses'][0]['country'];
            $district = explode(' ', $postcode)[0];

            $postcodeExist = PostCode::where('post_code', $district)->first();

            if ($postcodeExist && !BlockedPostCode::where('pin_code', $district)->exists()) {
                $location = $postcodeExist->zone->zone_location;
                return response()->json(['postcode' => $postcode, 'city' => $city, 'country' => $country, 'order_location' => $location, 'addresses' => $addresses, 'message' => 'We serve in your area.']);
            }

            return response()->json(['message' => "We don't serve in your area."]);

        } catch (\Exception $e) {
            return response()->json(['message' => 'We serve in your area but insert valid postcode.']);
        }
    }

    public function zipTimeSlots($postcode)
    {
        $district = explode(' ', $postcode);
        $postcodeExist = PostCode::where('post_code', $district)->first();
        $zone = Zone::where('id', $postcodeExist->zone_id)->first();

        $date = Holiday::first()->value('holiday_date');
        $holiday_date = Carbon::parse($date)->format('Y-m-d');
        $today = Carbon::now()->format('Y-m-d');

        //        Check if today is the holiday date.
        if ($today != $holiday_date) {

            $diff = $zone->pickup_delivery_difference; // replace with the dynamic value

            // Create a new empty collection for pick up dates
            $pickUpDates = new Collection();

            // Create a new empty collection for delivery dates
            $deliveryDates = new Collection();

            // Loop 10 times
            for ($i = 0; $i < 14; $i++) {
                // Generate pickup and delivery dates
                $pickupDateKey = '';
                $pickupDateValue = Carbon::now()->addDays($i)->toDateString();
                $deliveryDateKey = '';
                $deliveryDateValue = Carbon::now()->addDays($i + $diff)->toDateString();

                // Add a new date to the pickup collection
                if ($i === 0) {
                    $pickupDateKey = 'Today';
                } elseif ($i === 1) {
                    $pickupDateKey = 'Tomorrow';
                } else {
                    $pickupDateKey = Carbon::now()->addDays($i)->format('D, j M');
                }

                // Add a new date to the delivery collection
                if ($diff == 1 && $i === 0) {
                    $deliveryDateKey = 'Tomorrow';
                } else {
                    $deliveryDateKey = Carbon::now()->addDays($i + $diff)->format('D, j M');
                }

                // Put dates into collections
                $pickUpDates->put($pickupDateKey, $pickupDateValue);
                $deliveryDates->put($deliveryDateKey, $deliveryDateValue);
            }

            $pickUpTimeSlots = $this->getTimeSlots($zone->start_time, $zone->end_time, $zone->hours_interval, json_decode($zone->disabled_pick_up_slots));
            $deliveryTimeSlots = $this->getTimeSlots($zone->start_time, $zone->end_time, $zone->hours_interval, json_decode($zone->disabled_delivery_slots));

        } else {
            $pickUpDates = [];
            $deliveryDates = [];
            $pickUpTimeSlots = [];
            $deliveryTimeSlots = [];

        }

        return response()->json(['pickUpDates' => $pickUpDates, 'pickUpTimeSlots' => $pickUpTimeSlots, 'deliveryDates' => $deliveryDates, 'deliveryTimeSlots' => $deliveryTimeSlots]);
    }

    public function getUpdatedTimeSlotsAndDeliveryDates(Request $request, $postcode)
    {
        $selectedPickupDate = $request->input('pickup_date');
        $selectedDeliveryDate = $request->input('delivery_date');
        $selectedTimeSlot = $request->input('pickup_time');

        $defaultDeliveryDate = true;

        $district = explode(' ', $postcode);

        $postcodeExist = PostCode::where('post_code', $district)->first();
        $zone = Zone::where('id', $postcodeExist->zone_id)->first();

        // Compute the difference based on your requirements
        $diff = $zone->pickup_delivery_difference;
        $pickupDate = Carbon::parse($selectedPickupDate);
        if ($selectedDeliveryDate) {

            $deliveryDate = Carbon::parse($selectedDeliveryDate);

            // Calculate the difference between pickup and delivery dates in days
            $dateDifference = $pickupDate->diffInDays($deliveryDate);

            if ($diff < $dateDifference) {
                $defaultDeliveryDate = false;
            }
        }

        // Get the time slots for the selected pickup date and time slot
        $pickUpTimeSlots = $this->getUpdatedTimeSlots($zone->start_time, $zone->end_time, $zone->hours_interval, json_decode($zone->disabled_pick_up_slots), $selectedPickupDate, null, true);
        $deliveryTimeSlots = $this->getUpdatedTimeSlots($zone->start_time, $zone->end_time, $zone->hours_interval, json_decode($zone->disabled_delivery_slots), $selectedPickupDate, $selectedTimeSlot, $defaultDeliveryDate);

        // Initialize empty array for delivery dates
        $deliveryDates = new Collection();

        // Variable to store the selected delivery date key
        $selectedDeliveryDateKey = null;

        // Generate delivery dates based on the selected pickup date and $diff
        for ($i = 0; $i <= 14; $i++) {

            $deliveryDateValue = Carbon::parse($selectedPickupDate)->addDays($i + $diff)->toDateString();

            if ($i === 0 && $diff == 1 && $pickupDate->isToday()) {
                $deliveryDateKey = 'Tomorrow';
            } else {
                $deliveryDateKey = Carbon::parse($selectedPickupDate)->addDays($i+$diff)->format('D, j M');
            }

            // Check if the current date matches the selected delivery date
            if ($selectedDeliveryDate && $deliveryDateValue == $selectedDeliveryDate) {
                $selectedDeliveryDateKey = $deliveryDateKey;
            }

            $deliveryDates->put($deliveryDateKey, $deliveryDateValue);
        }

        // return the data in JSON format
        return response()->json([
            'pickUpTimeSlots' => $pickUpTimeSlots,
            'deliveryDates' => $deliveryDates,
            'deliveryTimeSlots' => $deliveryTimeSlots,
            'selectedDeliveryDateKey' => $selectedDeliveryDateKey  // Return the selected delivery date key
        ]);
    }

    public function getTimeSlots($startTime, $endTime, $interval, $disabledSlots)
    {
        $start = Carbon::parse($startTime);
        $end = Carbon::parse($endTime);
        $now = Carbon::now();

        $period = new CarbonPeriod($start, $interval . ' hours', $end);

        $timeSlots = [];

        foreach ($period as $slot) {
            // get the end time of the slot by adding the interval to the start time
            $endSlot = $slot->copy()->addHours($interval);

            // break if the end of the slot is after the overall end time
            if ($endSlot->gt($end)) {
                break;
            }

            // create the timeslot range
            $timeSlotRange = $slot->format('H:i') . ' - ' . $endSlot->format('H:i');

            // Skip the timeslot if the current time is greater than or equal to the start of the timeslot
            if ($now->greaterThanOrEqualTo($slot) || in_array($timeSlotRange, $disabledSlots)) {
                continue;
            }

            array_push($timeSlots, $timeSlotRange);
        }

        return $timeSlots;
    }

    public function getUpdatedTimeSlots($startTime, $endTime, $interval, $disabledSlots, $selectedPickupDate, $selectedTimeSlot = null, $defaultDeliveryDate)
    {
        $start = Carbon::parse($startTime);
        $end = Carbon::parse($endTime);

        $period = new CarbonPeriod($start, $interval . ' hours', $end);

        $timeSlots = [];

        foreach ($period as $slot) {
            // get the end time of the slot by adding the interval to the start time
            $endSlot = $slot->copy()->addHours($interval);

            // break if the end of the slot is after the overall end time
            if ($endSlot->gt($end)) {
                break;
            }

            // create the timeslot range
            $timeSlotRange = $slot->format('H:i') . ' - ' . $endSlot->format('H:i');

            // If $defaultDeliveryDate is false, return all time slots without any checks
            if (!$defaultDeliveryDate) {
                $timeSlots[] = $timeSlotRange;
                continue;
            }

            // Skip the timeslot if the selected time slot is earlier than the current slot or if it's a disabled slot
            if (($selectedTimeSlot && $slot->lt(Carbon::parse(explode(' - ', $selectedTimeSlot)[0]))) || in_array($timeSlotRange, $disabledSlots)) {
                continue;
            }

            // If the selected time slot is not provided or is null, then check for today's date and the current time
            $todayDate = Carbon::now()->toDateString();
            $now = Carbon::now();
            if (!$selectedTimeSlot && $todayDate == $selectedPickupDate && $now->greaterThanOrEqualTo($slot)) {
                continue;
            }

            $timeSlots[] = $timeSlotRange;
        }

        return $timeSlots;
    }

}
