<?php

namespace App\Nova;

use App\Nova\Filters\FacilityFilter;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\FormData;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Query\Search\SearchableRelation;

class FacilityPriceManagement extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\FacilityPriceManagement>
     */
    public static $model = \App\Models\FacilityPriceManagement::class;

    public static $with = ['Center','Service','Category','Item'];

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
        return [
            'id',
            new SearchableRelation('center','center_name'),
            new SearchableRelation('service','name'),
            new SearchableRelation('item','item_name'),
        ];
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
            BelongsTo::make('Facility Name','center','App\Nova\FacilityCenter'),
            BelongsTo::make('Service Name','service','App\Nova\FacilityService'),
            BelongsTo::make('Category','category','App\Nova\FacilityCategory')->nullable(),
            BelongsTo::make('Item'),

        ];
    }

    /**
     * Get the field for create form
     *
    */
    public function fieldsForCreate(NovaRequest $request)
    {
        return [
            BelongsTo::make('Facility Name','center','App\Nova\FacilityCenter'),
            BelongsTo::make('Service Name','service','App\Nova\FacilityService'),
            Select::make('Category','sub_service_name')
            ->dependsOn('service',function (Select $field, NovaRequest $request, FormData $formData) {
                    $options = \App\Models\FacilityCategory::where('fc_service_id',$formData->service)->pluck('name','id');
                    return $field->options($options);
                })->nullable(),
            BelongsTo::make('Item'),
            Currency::make('Price')->currency('gbp')->required(),
        ];
    }


    /**
     * Get the field for edit form
     *
     */
    public function fieldsForUpdate(NovaRequest $request)
    {
        return [
            BelongsTo::make('Facility Name','center','App\Nova\FacilityCenter'),
            BelongsTo::make('Service Name','service','App\Nova\FacilityService'),
            Select::make('Category','sub_service_name')
                ->dependsOn('service',function (Select $field, NovaRequest $request, FormData $formData) {
                    $options = \App\Models\FacilityCategory::where('fc_service_id',$formData->service)->pluck('name','id');
                    return $field->options($options);
                })->nullable(),
            BelongsTo::make('Item'),
            Currency::make('Price')->currency('gbp')->required(),
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
            new FacilityFilter('center_name'),
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
}
