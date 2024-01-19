<?php

namespace App\Nova\Metrics;

use App\Models\FacilityOrder;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Value;
use Laravel\Nova\Nova;

class OrderPrice extends Value
{
    public $roundingPrecision = 2;
    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        return $this->sum($request, FacilityOrder::class, 'totalPrice')
            ->currency('£')
            ->format('0','0.00');
    }

    /**
     * Get the ranges available for the metric.
     *
     * @return array
     */
    public function ranges()
    {
        return [
            'ALL' => "All Time",
        ];
    }

    /**
     * Determine the amount of time the results of the metric should be cached.
     *
     * @return \DateTimeInterface|\DateInterval|float|int|null
     */
    public function cacheFor()
    {
         return now()->addMinutes(5);
    }
}
