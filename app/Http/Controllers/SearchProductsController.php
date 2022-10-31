<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class SearchProductsController extends Controller
{
    public function index()
    {
        $query_str = request('query');
        $items = Product::matches($query_str)->get(); // update this
        return view('search', compact('items'));
    }
}


            