<?php

namespace App\Http\Controllers;

use App\Models\Price;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class PriceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $services = Price::select('id','title','slug','icon','description','price_heading','price_per_item')->with('tags')->get();
//        dd($services);
        return view('pages.price-services.index',compact('services'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
    public function show(Price $service)
    {
        $services = Price::all();

        return view('pages.price-services.details',compact('service','services'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Price  $price
     * @return \Illuminate\Http\Response
     */
    public function edit(Price $price)
    {
        //
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

    public function showPrice(){
        $services = Price::all();

        return view('pages.price-services.details',compact('services'));
    }

    public function showBooking(Request $request){
        $selectedServices = json_decode(urldecode($request->query('selectedServices')), true);
        $services = json_decode(urldecode($request->query('services')), true);

        return view('pages.price-services.booking', compact('selectedServices', 'services'));
    }

}
