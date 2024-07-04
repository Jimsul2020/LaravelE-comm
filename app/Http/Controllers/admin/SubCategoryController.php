<?php

namespace App\Http\Controllers\admin;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SubCategory;
use Illuminate\Support\Facades\Validator;

class SubCategoryController extends Controller
{
    function index(Request $request)
    {
        $subcategories = SubCategory::select('sub_categories.*', 'c.name as categoryName')
            ->latest('sub_categories.id')
            ->leftJoin('categories as c', 'c.id', 'sub_categories.category_id');
        if (!empty($request->get('keyword'))) {
            $keyword = $request->get('keyword');
            $subcategories->where(function ($query) use ($keyword) {
                $query->where('sub_categories.name', 'like', '%' . $keyword . '%')
                    ->orWhere('c.name', 'like', '%' . $keyword . '%')
                    ->orWhere('sub_categories.slug', 'like', '%' . $keyword . '%');
            });
        }
        $subcategories = $subcategories->paginate(10);
        return view('admin.subcategory.index', compact('subcategories'));
    }

    function create(Request $request)
    {
        $categories = Category::orderBy('name', 'ASC')->get();
        return view('admin.subcategory.create', compact('categories'));
    }

    function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'slug' => 'required|string|unique:sub_categories',
            'category_id' => 'required|integer',
            'status' => 'required|integer',
        ]);

        if ($validator->passes()) {
            $data = new SubCategory();
            $data->name = $request->name;
            $data->slug = $request->slug;
            $data->category_id = $request->category_id;
            $data->status = $request->status;
            $data->save();
            return response()->json([
                'status' => true,
                'message' => 'Sub category added successfully'
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }
    }

    function edit($id, Request $request)
    {
        $category = Category::orderBy('name', 'ASC')->get();
        $subcategory = SubCategory::find($id);
        if (empty($subcategory)) {
            return response()->json([
                'status' => false,
                'message' => 'Sub Category not found'
            ]);
        }
        return view('admin.subcategory.edit', compact('subcategory', 'category'));
    }

    function update($id, Request $request)
    {
        $subcategory = SubCategory::find($id);
        if (empty($subcategory)) {
            return response()->json([
                'status' => false,
                'Not Found' => true,
                'message' => 'Sub Category not found'
            ]);
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'slug' => 'required|string|unique:sub_categories,slug,' . $subcategory->id . ',id',
            'category_id' => 'required|integer',
            'status' => 'required|integer',
        ]);

        if ($validator->passes()) {
            $subcategory->name = $request->name;
            $subcategory->slug = $request->slug;
            $subcategory->category_id = $request->category_id;
            $subcategory->status = $request->status;
            $subcategory->save();
            return response()->json([
                'status' => true,
                'message' => 'Sub category updated successfully'
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }
    }

    function destroy($id, Request $request)
    {
        $subcategory = SubCategory::find($id);
        if (empty($subcategory)) {
            return response()->json([
                'status' => false,
                'Not Found' => true,
                'message' => 'Sub Category not found'
            ]);
        }
        $subcategory->delete();

        return response()->json([
            'status' => true,
            'message' => 'Sub Category deleted successfully'
        ], 200);
    }
}
