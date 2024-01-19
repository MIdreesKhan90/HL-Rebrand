<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Email;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class AppSetting extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\AppSetting>
     */
    public static $model = \App\Models\AppSetting::class;

    public static function authorizedToCreate(Request $request)
    {
        return false;
    }

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'application_name';

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
            Text::make('Application Name'),
            Image::make('Logo')->path('public'),
            Text::make('Contact Number'),
            Email::make('Email'),
            Text::make('Country'),
            Text::make('Default Currency'),
            Text::make('Currency Short Code')->onlyOnForms(),
            DateTime::make('Opening Time')->displayUsing(fn ($value) => $value ? $value->format('h:i a') : '')->onlyOnForms(),
            DateTime::make('Closing Time')->displayUsing(fn ($value) => $value ? $value->format('h:i a') : '')->onlyOnForms(),
            Text::make('Max Order Per Hour'),
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
