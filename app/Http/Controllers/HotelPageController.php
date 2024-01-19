<?php

namespace App\Http\Controllers;

use App\Models\HotelPage;
use Illuminate\Http\Request;

class HotelPageController extends Controller
{
    public function index()
    {
        $hotel_page = HotelPage::first();

        return view('pages.hotels',compact('hotel_page'));
    }
}
