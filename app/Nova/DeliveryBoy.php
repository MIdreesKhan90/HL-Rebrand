<?php

namespace App\Nova;

use App\Models\Status;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Email;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Validation\Rules\Password as PasswordRule;

class DeliveryBoy extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\DeliveryBoy>
     */
    public static $model = \App\Models\DeliveryBoy::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'delivery_boy_name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'delivery_boy_name',
        'phone_number',
        'email'
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
            Text::make('Delivery Boy Name')->required(),
            Email::make('Email')->required(),
            Text::make('Phone Number')->onlyOnForms()->required(),
            Password::make('Password')->onlyOnForms()
                ->creationRules('required', PasswordRule::defaults())
                ->updateRules('nullable', PasswordRule::defaults()),
            Badge::make('Status')->map([
                '1' => 'success',
                '2' => 'danger',
            ])->labels([
                '1' => 'Active',
                '2' => 'Inactive',
            ]),
            Select::make('Status')->options(function (){
                return $statuses = Status::pluck('status_name', 'id');
            })->default(1)->displayUsingLabels()->onlyOnForms(),
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
