<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Stack;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\URL;
use Laravel\Nova\Http\Requests\NovaRequest;
use Waynestate\Nova\CKEditor4Field\CKEditor;
use Whitecube\NovaFlexibleContent\Flexible;
use Eminiarts\Tabs\Traits\HasTabs;
use Eminiarts\Tabs\Tabs;
use Eminiarts\Tabs\Tab;

class HomePage extends Resource
{
    use HasTabs;
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\HomePage>
     */
    public static $model = \App\Models\HomePage::class;
    public static function label()
    {
        return 'Home Page';
    }

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
                Tab::make('Facilities Section',[
                    Flexible::make('Facilities')
                        ->addLayout('Text with icon', 'textIcon', [
                            Image::make('Icon')->path('images/icons'),
                            Textarea::make('Text'),
                        ])->button('Add Facility')
                ]),
                Tab::make('How We Work Section',[
                    Text::make('Heading','how_we_work_heading'),
                    Flexible::make('Processes')
                        ->addLayout('Text Icon', 'textIcon', [
                            Image::make('Image')->path('images/how-we-work'),
                            Text::make('Heading'),
                            CKEditor::make('Text'),
                            Text::make('Type')
                                ->withMeta(['value' => 'textIcon',
                                    'extraAttributes' => ['readonly' => true]]),
                        ])
                        ->addLayout('Text Icon with button', 'textIconButton', [
                            Image::make('Image')->path('images/how-we-work'),
                            Text::make('Heading'),
                            CKEditor::make('Text'),
                            Text::make('Link Label'),
                            Text::make('Link Url'),
                            Text::make('Type')
                                ->withMeta(['value' => 'textIconButton',
                                'extraAttributes' => ['readonly' => true]]),
                        ])
                        ->button('Add Process'),
                ]),
                Tab::make('Services Section',[
                    Text::make('Heading','services_heading'),
                    Flexible::make('Services')
                        ->addLayout('Text Icon', 'textIcon', [
                            Image::make('Icon')->path('images/icons'),
                            Text::make('Service Title'),
                            Textarea::make('Service Description'),
                            Text::make('Service Cost'),
                        ])->button('Add Service'),
                    Text::make('Link Label','services_link_label'),
                    Text::make('Link Url','services_link_url'),
                    Text::make('Bottom Text','minimum_order_text'),
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
