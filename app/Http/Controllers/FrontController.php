<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    public function index(Request $request){
        $products = Product::where('is_featured', 'Yes')
        ->where('status', 1)
        ->orderBy('id', 'DESC')
        ->take(4)
        ->get();
        $data['featuredProduct'] = $products;
        $latestproducts = Product::orderBy('id', 'DESC')
        ->where('status', 1)
        ->take(4)->get();
        $data['latestProducts'] = $latestproducts;
        return view('front.home', $data);
    }
}
