<?php

namespace App\Nova;

use Eminiarts\Tabs\Tab;
use Eminiarts\Tabs\Tabs;
use Eminiarts\Tabs\Traits\HasTabs;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\MultiSelect;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Slug;
use Laravel\Nova\Fields\Tag;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;
use Waynestate\Nova\CKEditor4Field\CKEditor;
use Whitecube\NovaFlexibleContent\Flexible;

class PriceService extends Resource
{
    use HasTabs;
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Price>
     */
    public static $model = \App\Models\Price::class;

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

            new Panel('Basic Details',[
                ID::make()->sortable(),
                Text::make('Title'),
                Slug::make('Slug')
                    ->from('Title')
                    ->withMeta(['extraAttributes' => ['readonly' => true]]),
                Image::make('Icon')->path('images/icons'),
                Textarea::make('Description')->alwaysShow()->hideFromIndex(),
                Text::make('Price Heading'),
                CKEditor::make('Price Per Item')->options([
                    'height' => 100,
                    'toolbar' => [
                        ['Bold','Italic','Strike','-','Subscript','Superscript'],
                        ['NumberedList','BulletedList'],
                    ]
                ])->alwaysShow()->hideFromIndex(),
                Tag::make('Tags')->showCreateRelationButton(),
            ]),
            Tabs::make('Price & Services',[
                Tab::make('Products',[
                    Flexible::make('Products','products')
                        ->addLayout('Product', 'product', [
                            Select::make('Item')->options(function (){
                                return \App\Models\FareManagement::with('item')->get()->pluck('item.item_name','item.id');
                            })->searchable()->displayUsingLabels(),
                        ])->button('Add Products'),
                ]),
                Tab::make('Service Details',[
                    CKEditor::make('Service Overview','service_overview')->options([
                        'height' => 100,
                        'toolbar' => [
                            ['Bold','Italic','Strike','-','Subscript','Superscript'],
                            ['NumberedList','BulletedList'],
                        ]
                        ])->alwaysShow(),
                    CKEditor::make('Service Options','service_options')->options([
                        'height' => 100,
                        'toolbar' => [
                            ['Bold','Italic','Strike','-','Subscript','Superscript'],
                            ['NumberedList','BulletedList'],
                        ]
                    ])->alwaysShow(),
                    CKEditor::make('Suitable For','service_suitable')->options([
                        'height' => 100,
                        'toolbar' => [
                            ['Bold','Italic','Strike','-','Subscript','Superscript'],
                            ['NumberedList','BulletedList'],
                        ]
                    ])->alwaysShow(),
                    CKEditor::make("Don't Include",'service_not_include')->options([
                        'height' => 100,
                        'toolbar' => [
                            ['Bold','Italic','Strike','-','Subscript','Superscript'],
                            ['NumberedList','BulletedList'],
                        ]
                    ])->alwaysShow(),
                    CKEditor::make('Prepare for Collection','service_collection')->options([
                        'height' => 100,
                        'toolbar' => [
                            ['Bold','Italic','Strike','-','Subscript','Superscript'],
                            ['NumberedList','BulletedList'],
                        ]
                    ])->alwaysShow(),
                    CKEditor::make('Laundry Delivery','service_delivery')->options([
                        'height' => 100,
                        'toolbar' => [
                            ['Bold','Italic','Strike','-','Subscript','Superscript'],
                            ['NumberedList','BulletedList'],
                        ]
                    ])->alwaysShow(),
                ]),
                Tab::make('Service Q/A',[
                    Text::make('Service Question'),
                    Text::make('Service Answer'),
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
