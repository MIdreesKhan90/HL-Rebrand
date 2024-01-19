<?php

namespace App\Nova;

use Eminiarts\Tabs\Tab;
use Eminiarts\Tabs\Tabs;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Waynestate\Nova\CKEditor4Field\CKEditor;
use Whitecube\NovaFlexibleContent\Flexible;

class Commercial extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Commercial>
     */
    public static $model = \App\Models\Commercial::class;

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
            Tabs::make('Home Page',[
                Tab::make('Banner Section',[
                    CKEditor::make('Banner Heading'),
                    Image::make('Banner Image')->path('images/banners'),
                    Text::make('Rank Section Heading','rank_heading'),
                    Text::make('Rank Section Text Copy','rank_text_copy'),
                    Text::make('Link Label','rank_review_link_label'),
                    Text::make('Link Url','rank_review_link_url'),
                ]),
                Tab::make('Services Section',[
                    Text::make('Heading','services_heading'),
                    Flexible::make('Services')
                        ->addLayout('Text Icon', 'textIcon', [
                            Image::make('Image')->path('images/services'),
                            Text::make('Heading'),
                            CKEditor::make('Text'),
                            Text::make('Type')
                                ->withMeta(['value' => 'textIcon',
                                    'extraAttributes' => ['readonly' => true]]),
                        ])

                        ->button('Add Service'),
                ]),

            ])

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
