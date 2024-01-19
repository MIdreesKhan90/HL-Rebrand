<?php

namespace App\Nova;

use App\Models\DeliveryBoy;
use App\Models\FacilityCenter;
use App\Models\OrderService;
use App\Models\Service;
use App\Models\Label;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\BooleanGroup;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\MultiSelect;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Models\Category;
use Laravel\Nova\Fields\FormData;
use Illuminate\Database\Eloquent\Model;
use Laravel\Nova\Query\Search\SearchableRelation;
use Marshmallow\Filters\DateRangeFilter;


class Order extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Order>
     */
    public static $model = \App\Models\Order::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'order_id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static function searchableColumns()
    {
        return ['id','order_id', new SearchableRelation('address', 'postcode'), new SearchableRelation('customer','customer_name'), new SearchableRelation('customer','phone_number')];
    }
    /**
     * The visual style used for the table. Available options are 'tight' and 'default'.
     *
     * @var string
     */
    public static $tableStyle = 'tight';

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->hideFromIndex(),
            Text::make('Order Id')->sortable(),
            Date::make('Order Date','created_at')
                ->displayUsing(fn ($value) => $value ? $value->format('d M-Y') : '')
                ->exceptOnForms(),
            Select::make('Order From','order_type')->options([
                '1' => 'Website',
                '2' => 'IOS App',
                '3' => 'IOS App',
            ])->default(1)->displayUsingLabels(),
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
     * Get the fields displayed by the resource on create page.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fieldsForCreate(NovaRequest $request)
    {
        $timeSlots = ['07:00 AM - 09:00 AM' => '07:00 AM - 09:00 AM','09:00 AM - 11:00 AM' =>'09:00 AM - 11:00 AM','11:00 AM - 01:00 PM' => '11:00 AM - 01:00 PM','01:00 PM - 03:00 PM' => '01:00 PM - 03:00 PM','03:00 PM - 05:00 PM' => '03:00 PM - 05:00 PM','val' => '05:00 PM - 07:00 PM','07:00 PM - 09:00 PM' => '07:00 PM - 09:00 PM','09:00 PM - 11:00 PM' => '09:00 PM - 11:00 PM'];
        return [
            Text::make('Order Id')->default(function ($request) {
                $lastOrderId = \App\Models\Order::latest()->select('order_id')->take(1)->value('order_id');
                $nextOrderId = intval($lastOrderId) + 1;
                return str_pad($nextOrderId, strlen($lastOrderId), '0', STR_PAD_LEFT);
            })->withMeta(['extraAttributes' => ['readonly' => true]]),
            Select::make('Order From','order_type')->options([
                '1' => 'Website',
                '2' => 'IOS App',
                '3' => 'IOS App',
            ])->displayUsingLabels(),
            Select::make('Customer','customer_id')->searchable()
                ->options(function () {
                return \App\Models\Customer::pluck('customer_name','id');
            }),
            Select::make('Address','address_id')
                ->dependsOn('customer_id', function (Select $field, NovaRequest $request, FormData $formData) {
                    $options = \App\Models\Address::where('customer_id',$formData->customer_id)->pluck('address','id');
                    $field->options($options);
            }),
            Date::make('Pickup Date','pickup_date'),
            Select::make('Pickup Time')->options($timeSlots),
            Date::make('Delivery Date','delivery_date'),
            Select::make('Delivery Time')->options($timeSlots),
            Text::make('Other Requests'),
            Text::make('Collection Instructions'),
            Text::make('Delivery Instructions'),
            MultiSelect::make('Services')
                ->options(
                    Service::all()->pluck('service_name', 'id')->toArray()
                )
                ->onlyOnForms()
                ->fillUsing(function ($request, $model, $attribute) {
                    if ($request->exists($attribute)) {
                        $services = json_decode($request[$attribute], true);
                        $model->save();
                        $model->services()->sync($services);
                    }
                })->help('Please press and hold Ctrl to select multiple services.'),
            Select::make('Sub Service', 'sub_service')
                ->dependsOn('services', function (Select $field, NovaRequest $request, FormData $formData) {
                    if (is_array($formData->services)) {
                        if (in_array(1, json_decode(json_encode($formData->services)))) {
                            $options = Category::where('service_id', 1)->pluck('category_name', 'id');
                            $field->options($options);
                        }
                    } else {
                        if ($formData->services == 1) {
                            $options = Category::where('service_id', 1)->pluck('category_name', 'id');
                            $field->options($options);
                        }
                    }
                })
                ->fillUsing(function ($request, $model, $attribute) {
                    if ($request->exists($attribute)) {
                        $sub_service_id = $request[$attribute];

                        // Store the selected sub_service_id in a hidden input field
                        $request->merge(['_sub_service_id' => $sub_service_id]);
                    }
                })
        ];
    }

    /**
     * Register a callback to be called after the resource is created.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public static function afterCreate(NovaRequest $request, Model $model)
    {
        if ($request->exists('_sub_service_id') && $request->exists('services')) {
            $sub_service_id = $request['_sub_service_id'];

            $orderService = OrderService::where([
                ['order_id', $model->id],
                ['service_id', 1],
            ])->first();

            if ($orderService) {
                $orderService->update(['category_id' => $sub_service_id]);
            }

        }
    }

    /**
     * Get the fields displayed by the resource on edit page.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fieldsForUpdate(NovaRequest $request)
    {
        $timeSlots = ['07:00 AM - 09:00 AM' => '07:00 AM - 09:00 AM','09:00 AM - 11:00 AM' =>'09:00 AM - 11:00 AM','11:00 AM - 01:00 PM' => '11:00 AM - 01:00 PM','01:00 PM - 03:00 PM' => '01:00 PM - 03:00 PM','03:00 PM - 05:00 PM' => '03:00 PM - 05:00 PM','val' => '05:00 PM - 07:00 PM','07:00 PM - 09:00 PM' => '07:00 PM - 09:00 PM','09:00 PM - 11:00 PM' => '09:00 PM - 11:00 PM'];
        return [
            Text::make('Order Id')->default(function ($request) {
                $lastOrderId = \App\Models\Order::latest()->select('order_id')->take(1)->value('order_id');
                return ++$lastOrderId;
            })->withMeta(['extraAttributes' => ['readonly' => true]]),
            Select::make('Status')->options(function () {
                return Label::pluck('label_name','id');
            })->displayUsingLabels(),
            Select::make('Facility Center','facility_id')->options(function () {
                return FacilityCenter::pluck('center_name','id');
            })->displayUsingLabels(),
            Select::make('Delivered By')->options(function () {
                return DeliveryBoy::pluck('delivery_boy_name','id');
            })->displayUsingLabels(),
            Date::make('Pickup Date','pickup_date'),
            Select::make('Pickup Time')->options($timeSlots),
            Date::make('Delivery Date','delivery_date'),
            Select::make('Delivery Time')->options($timeSlots),
            Text::make('Other Requests'),
            Text::make('Collection Instructions'),
            Text::make('Delivery Instructions'),
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
            new DateRangeFilter('created_at','Order Date'),
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
