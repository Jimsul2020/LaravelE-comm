<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BrandController extends Controller
{
    function index(Request $request)
    {
        $brands = Brand::latest('id');
        if (!empty($request->get('keyword'))) {
            $keyword = $request->get('keyword');
            $brands->where(function ($query) use ($keyword) {
                $query->where('name', 'like', '%' . $keyword . '%')
                    ->orWhere('slug', 'like', '%' . $keyword . '%');
            });
        }
        $brands = $brands->paginate(10);
        return view('admin.brand.index', compact('brands'));
    }

    function create(Request $request)
    {
        return view('admin.brand.create');
    }

    function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'slug' => 'required|string|unique:brands',
            'status' => 'required|integer',
        ]);

        if ($validator->passes()) {
            $data = new Brand();
            $data->name = $request->name;
            $data->slug = $request->slug;
            $data->status = $request->status;
            $data->save();
            return response()->json([
                'status' => true,
                'message' => 'Brand added successfully'
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
        $brand = Brand::find($id);
        if (empty($brand)) {
            return response()->json([
                'status' => false,
                'message' => 'Brand not found'
            ]);
        }
        return view('admin.brand.edit', compact('brand'));
    }

    function update($id, Request $request)
    {
        $brand = Brand::find($id);
        if (empty($brand)) {
            return response()->json([
                'status' => false,
                'Not Found' => true,
                'message' => 'Brand not found'
            ]);
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'slug' => 'required|string|unique:brands,slug,' . $brand->id . ',id',
            'status' => 'required|integer',
        ]);

        if ($validator->passes()) {
            $brand->name = $request->name;
            $brand->slug = $request->slug;
            $brand->status = $request->status;
            $brand->save();
            return response()->json([
                'status' => true,
                'message' => 'Brand updated successfully'
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
        $brand = Brand::find($id);
        if (empty($brand)) {
            return response()->json([
                'status' => false,
                'Not Found' => true,
                'message' => 'Brand not found'
            ]);
        }
        $brand->delete();

        return response()->json([
            'status' => true,
            'message' => 'Brand deleted successfully'
        ], 200);
    }
}
