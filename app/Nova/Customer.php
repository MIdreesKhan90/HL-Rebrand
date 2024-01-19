<?php

namespace App\Nova;

use App\Models\Status;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Fields\Email;
use Laravel\Nova\Fields\Boolean;

class Customer extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Customer>
     */
    public static $model = \App\Models\Customer::class;

    public static function authorizedToCreate(Request $request)
    {
        return false;
    }
    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'customer_name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'customer_name',
        'email',
        'phone_number'
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
            ID::make('ID')->sortable(),
            Text::make('First Name','uFName')->onlyOnForms(),
            Text::make('Last Name','uLName')->onlyOnForms(),
            Hidden::make('Customer Name')->fillUsing(function(NovaRequest $request, $model) {
                if(! isset($model->customer_name)){
                    $model->customer_name = $this->uFName.' '.$this->uLName;
                }
            }),
            Text::make('Customer Name', function () {
                return $this->customer_name;
            }),
            Text::make('Phone Number'),
            Email::make('Email'),
            Password::make('Password')->onlyOnForms()->creationRules('required', 'string', 'min:6')
                ->updateRules('nullable', 'string', 'min:6'),
            Badge::make('Status')->map([
                '1' => 'success',
                '2' => 'danger',
            ])->labels([
                '1' => 'Active',
                '2' => 'Inactive',
            ]),
            Select::make('Status')->options(function (){
                return $statuses = Status::pluck('status_name', 'id');
            })->displayUsingLabels()->onlyOnForms(),
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
