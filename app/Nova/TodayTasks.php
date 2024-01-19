<?php

namespace App\Nova;

use App\Models\Category;
use App\Models\Label;
use App\Nova\Filters\TodayTasksFilter;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class TodayTasks extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\TodayTasks>
     */
    public static $model = \App\Models\TodayTasks::class;

    public static function authorizedToCreate(Request $request)
    {
        return false;
    }

    public function authorizedToUpdate(Request $request): bool
    {
        return "nova-api/{resource}" != $request->route()->uri();
    }

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'customer.customer_name';

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

    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query->orderBy('order_id','DESC');
    }

    public function fields(NovaRequest $request)
    {
        return [
            Number::make('Order Id')->sortable(),
            Date::make('Order Date','created_at')
                ->displayUsing(fn ($value) => $value ? $value->format('d M-Y') : '')
                ->exceptOnForms(),
            Select::make('Order From','order_type')->options([
                '1' => 'Website',
                '2' => 'IOS App',
                '3' => 'IOS App',
            ])->default(1)->displayUsingLabels()->onlyOnDetail(),
            Text::make('Voucher Code','promo_code'),
            BelongsTo::make('Customer'),
            Text::make('Email', 'customer.email')->exceptOnForms(),
            Text::make('Phone', 'customer.phone_number')->exceptOnForms(),
            BelongsTo::make('Address')->onlyOnDetail(),
            Text::make('Postcode', 'address.postcode')->onlyOnDetail(),
            Date::make('Pickup Date','pickup_date'),
            Date::make('Delivery Date','delivery_date'),
            Select::make('Status')->options(function () {
                return Label::pluck('label_name','id');
            })->displayUsingLabels(),
            BelongsToMany::make('Services')->fields(function ($request, $relatedModel) {
                return [
                    Text::make('Category',function ($relatedModel){
                        return Category::where('id',$relatedModel->category_id)->value('category_name');
                    }),
                ];
            }),
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
        return [new TodayTasksFilter()];
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
