<?php

namespace App\Http\Controllers;

use App\Models\SubCategory;
use Illuminate\Http\Request;

class ProductSubCategoryController extends Controller
{
    function index(Request $request)
    {
        if (!empty($request->category_id)) {
            $subCategories = SubCategory::where('category_id', $request->category_id)
                ->orderBy('name', 'ASC')->get();

            return response()->json([
                'status' => true,
                'subCategories' => $subCategories,
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'subCategories' => [],
            ], 422);
        }
    }
}
