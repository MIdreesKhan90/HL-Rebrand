<?php

namespace App\Nova;

use App\Models\DeliveryBoy;
use App\Nova\Metrics\OrderPrice;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Fields\KeyValue;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Support\Facades\DB;
use Laravel\Nova\Query\Search\SearchableRelation;


class FacilityOrder extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\FacilityOrder>
     */
    public static $model = \App\Models\FacilityOrder::class;

    public static function authorizedToCreate(Request $request)
    {
        return false;
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
    public static function searchableColumns()
    {
        return ['id','order_id', new SearchableRelation('facility', 'center_name')];
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
            Text::make('Order Id')->sortable(),
            Text::make('Customer Name'),
            BelongsTo::make('Facility','facility','App\Nova\FacilityCenter'),
            Text::make('Job Status'),
            Currency::make('Prices')->currency('gbp'),
            Currency::make('Penalty')->currency('gbp'),
            Currency::make('Total Price','totalPrice')->currency('gbp'),
            Text::make('Penalty Reason'),
            Text::make('Bags/Hangers','bagsHangers'),
            Badge::make('Order Status')->map([
                '0' => 'info',
                '1' => 'success',
                '2' => 'warning',
                '3' => 'danger',
            ])->labels([
                '0' => '---',
                '1' => 'Done',
                '2' => 'Penalty',
                '3' => 'Cancelled',
            ]),
            Select::make('Collected By')->options(function (){
                $options = DeliveryBoy::where('status',1)->pluck('delivery_boy_name', 'id');
                $options['0'] = 'None';
                return $options;
            })->default(0)->displayUsingLabels(),
            Text::make('Itemization',function (){
                $order_services = json_decode($this->itemization);

                $table = '<table class="table table-hover">
                            <thead>
                            <tr>
                                <th class="p-3">Service</th>
                                <th class="p-3">Sub-service/Item</th>
                                <th class="p-3">Quantity</th>
                                <th class="p-3">Price</th>
                            </tr>
                            </thead>
                            <tbody>';
//

                foreach($order_services as $key => $value){

                    $service_id = $value->service;

                    $order_services[$key]->service = DB::table('facility_services')->where('id',$service_id)->value('name');
                    $service = $order_services[$key]->service;
                    $category = $order_services[$key]->category;
                    $quantity = $order_services[$key]->quantity;
                    $itemPrice = $order_services[$key]->itemPrice;

                    $table.='<tr>
                    <td class="p-3 text-center">'.ucwords($service).'</td>
                    <td class="p-3 text-center">'.ucwords($category).'</td>
                    <td class="p-3 text-center">'.$quantity.'</td>
                    <td class="p-3 text-center">£'.$itemPrice.'</td>
                </tr>';
                }

                return $table.="</tbody></table>";
            })->asHtml()->onlyOnDetail(),

        ];
    }

    /**
     * Get the field for edit form
     *
     */
    public function fieldsForUpdate(NovaRequest $request)
    {
        return [
            Text::make('Facility Order Id','id')
                ->withMeta(['extraAttributes' => ['readonly' => true]]),
            Text::make('Customer Order Id','order_id')
                ->withMeta(['extraAttributes' => ['readonly' => true]]),
            Text::make('Job Status'),
            Currency::make('Prices')->currency('gbp'),
            Currency::make('Penalty')->currency('gbp'),
            Hidden::make('Total Price','totalPrice')->fillUsing(function(NovaRequest $request, $model) {
                if(! isset($model->totalPrice)){
                    $totalPrice = ($this->prices - $this->penalty);
                    $model->totalPrice = number_format($totalPrice,2);
                }
            }),
            Text::make('Penalty Reason'),
            Select::make('Order Status')->options([
                '0' => '---',
                '1' => 'Done',
                '2' => 'Penalty',
                '3' => 'Cancelled',
            ])->displayUsingLabels(),
            Boolean::make('Edit Access')->default(1),

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
        return [
            OrderPrice::make()
                ->icon('library')
                ->refreshWhenFiltersChange(),
        ];
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
            new Filters\FacilityFilter('user_id'),
            new \Marshmallow\Filters\DateRangeFilter('order_date', 'Order date'),
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
