<?php

namespace App\Nova;

use App\Http\Controllers\PostCodeController;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\MultiSelect;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Carbon\Carbon;

class Zone extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Zone>
     */
    public static $model = \App\Models\Zone::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'zone_name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),
            BelongsTo::make('Zone Location','location',ZoneLocation::class),
            Text::make('Zone Name'),
            Text::make('Start time')->withMeta(['type' => 'time'])->displayUsing(function($value) {
                return $value ? Carbon::parse($value)->format('H:i') : '—';
            }),
            Text::make('End time')->withMeta(['type' => 'time'])->displayUsing(function($value) {
                return $value ? Carbon::parse($value)->format('H:i') : '—';
            }),
            Number::make('Pickup Delivery Difference')->help('Insert value in day. i.e 1 for 24hrs Difference'),
            Number::make('Hours Interval'),
            MultiSelect::make('Disabled Pick Up Slots')->displayUsingLabels()->hideFromIndex()->hideWhenCreating(),
            MultiSelect::make('Disabled Delivery Slots')->displayUsingLabels()->hideFromIndex()->hideWhenCreating(),
        ];
    }

    public function fieldsForUpdate(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),
            BelongsTo::make('Zone Location','location',ZoneLocation::class),
            Text::make('Zone Name'),
            Text::make('Start time')->withMeta(['type' => 'time'])->displayUsing(function($value) {
                return $value ? Carbon::parse($value)->format('H:i') : '—';
            }),
            Text::make('End time')->withMeta(['type' => 'time'])->displayUsing(function($value) {
                return $value ? Carbon::parse($value)->format('H:i') : '—';
            }),
            Number::make('Pickup Delivery Difference')->help('Insert value in day. i.e 1 for 24hrs Difference'),
            Number::make('Hours Interval'),
            MultiSelect::make('Disabled Pick Up Slots')->options(function () use ($request){
                $model = $request->findModelOrFail();
                $options = $this->getTimeSlots($model->start_time,$model->end_time,$model->hours_interval);
                return (!empty($options)) ? $options :null;
            })->showOnUpdating()->displayUsingLabels(),
            MultiSelect::make('Disabled Delivery Slots')->options(function () use ($request){
                $model = $request->findModelOrFail();
                $options = $this->getTimeSlots($model->start_time,$model->end_time,$model->hours_interval);
                return (!empty($options)) ? $options :null;
            })->showOnUpdating()->displayUsingLabels(),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [
            new Filters\LocationFilter,
        ];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [];
    }

    public function getTimeSlots($startTime, $endTime, $interval) {
        $start = Carbon::parse($startTime);
        $end = Carbon::parse($endTime);

        $period = new CarbonPeriod($start, $interval.' hours', $end);

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

            // Make sure keys are the same as values
            $timeSlots[$timeSlotRange] = $timeSlotRange;
        }

        return $timeSlots;
    }
}
