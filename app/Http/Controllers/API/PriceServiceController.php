<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\FareManagement;
use App\Models\Item;
use App\Models\ItemType;
use App\Models\Price;
use Illuminate\Http\Request;

class PriceServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $services = Price::with('tags')->select(['id', 'title','slug','icon','description','products','service_overview',
            'service_options',
            'service_suitable',
            'service_not_include',
            'service_collection',
            'service_delivery',
            'service_question',
            'service_answer',
            ])->get()->map(function ($query) {
            $query->icon = asset('storage/'.$query->icon);
            $query->tagsString = $query->tags->pluck('title')->implode(' + ');

            $service_details = array('overview' => $query->service_overview,
                                    'options' => $query->service_options,
                                    'suitable' => $query->service_suitable,
                                    'not_include' => $query->service_not_include,
                                    'collection' => $query->service_collection,
                                    'delivery' => $query->service_delivery);
            $query->serviceDetails = $service_details;

            $serviceQA = array('que' => $query->service_question, 'answer' => $query->service_answer);
            $query->serviceQueAns = $serviceQA;

//            // Convert the products JSON string to an array
            $productsArray = json_decode($query->products, true);
//
//            // Fetch product details for each product ID
            $productTypes = collect($productsArray)->map(function ($product) {
                $productId = $product['attributes']['item'];
                $fareManagement = FareManagement::where('item_id',$productId)->first();
                $type = $fareManagement->type;
                if ($type) {
                    return [
                        'id' => $type->id,
                        'name' => $type->name,
                        'slug' => $type->slug
                    ];
                } else {
                    // If the product is not found, return null
                    return null;
                }
            });

            // Filter out any null values from the product details
            $filteredProductTypes = $productTypes->unique('id');

            // Re-index the collection to remove numeric keys
            $reIndexedProductTypes = $filteredProductTypes->values();

            // Add the 'subCategories' attribute to the query
            $query->subCategories = $reIndexedProductTypes;

            $query->childCategories = Category::where('service_id',$query->id)->select('id','category_name')->get()->toArray();


            $query->products = collect($productsArray)->map(function ($product) {
                $productId = $product['attributes']['item'];
                $fareManagement = FareManagement::where('item_id',$productId)->first();
                $item = $fareManagement->item;
                $type = $fareManagement->type;
                $childCat = $fareManagement->category;
                if ($item) {
                    return [
                        'id' => $item->id,
                        'product_name' => $item->item_name,
                        'product_description' => $item->description,
                        'product_price' => number_format($fareManagement->price,2),
                        'product_cat' => $type->slug,
                        'child_cat_id' => ($childCat)?$childCat->id:null,
                        'quantity' => 0,
                    ];
                } else {
                    // If the product is not found, return null
                    return null;
                }
            });

            $query->priceSymbol = '£';

//            unset($query->products);
            unset($query->tags);
            unset($query->service_overview);
            unset($query->service_options);
            unset($query->service_suitable);
            unset($query->service_not_include);
            unset($query->service_collection);
            unset($query->service_delivery);
            unset($query->service_question);
            unset($query->service_answer);

            return $query;
        });


        return response()->json(['services' => $services],200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Price  $price
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $priceDetails = Price::where('id', $id)->value('price_details');

        return response()->json(['prices' => json_decode($priceDetails)]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Price  $price
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Price $price)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Price  $price
     * @return \Illuminate\Http\Response
     */
    public function destroy(Price $price)
    {
        //
    }
}
