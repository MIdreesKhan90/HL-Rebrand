<?php

namespace App\Http\Controllers;

use App\Models\Commercial;
use Illuminate\Http\Request;

class CommercialController extends Controller
{
    public function index()
    {
        $commercial_page = Commercial::first();

        return view('pages.commercial',compact('commercial_page'));
    }
}
