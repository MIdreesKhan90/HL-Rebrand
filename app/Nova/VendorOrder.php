<?php

namespace App\Nova;

use App\Models\Category;
use App\Models\HotelEmployee;
use App\Models\Label;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\FormData;
use Laravel\Nova\Fields\HasOne;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Query\Search\SearchableRelation;

class VendorOrder extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\VendorOrder>
     */
    public static $model = \App\Models\VendorOrder::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static function searchableColumns()
    {
        return ['id','customer_name','phone_number', new SearchableRelation('vendor','hotel_name')];
    }

    public static function label()
    {
        return 'Hotel Orders';
    }

    public static function authorizedToCreate(Request $request)
    {
        return false;
    }


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
            BelongsTo::make('Vendor Name','vendor',VendorsManagement::class),
            Select::make('Employee', 'employee_id')
                ->dependsOn('vendor', function (Select $field, NovaRequest $request, FormData $formData) {
                    $options = HotelEmployee::where('vendor_id', $formData->vendor)->pluck('employee_name', 'id');
                    $field->options($options);
                }),
            Text::make('Customer Name'),
            Text::make('Customer Room Number'),
            Text::make('Phone Number'),
            Select::make('Status')->options(function () {
                return Label::pluck('label_name','id');
            })->displayUsingLabels(),
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
        return [];
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
}
