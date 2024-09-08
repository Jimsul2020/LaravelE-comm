<?php

namespace App\Http\Controllers\admin;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\TemplateImage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    function index(Request $request)
    {
        $categories = Category::latest();
        if (!empty($request->get('keyword'))) {
            $categories = $categories->where('name', 'like', '%' . $request->get('keyword') . '%');
        }
        $categories = $categories->paginate(10);
        return view('admin.category.index', compact('categories'));
    }
    function create()
    {
        return view('admin.category.create');
    }
    function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'slug' => 'required|string|unique:categories',
            // 'status' => 'required|integer',
        ]);

        if ($validator->passes()) {
            $data = new Category();
            $data->name = $request->name;
            $data->slug = $request->slug;
            $data->status = $request->status;
            $data->showHome = $request->showHome;
            $data->save();
            //save image here
            if (!empty($request->image_id)) {
                $tempImage = TemplateImage::find($request->image_id);
                $extArray = explode('.', $tempImage->name);
                $ext = last($extArray);

                $newImageName = $data->id . '.' . $ext;
                $sPath = public_path() . '/temp/' . $tempImage->name;
                $dPath = public_path() . '/uploads/category/' . $newImageName;
                File::copy($sPath, $dPath);

                //Generate image thumbnail
                $dPath = public_path() . '/uploads/category/thumb/' . $newImageName;
                $manager = new ImageManager(new Driver());
                $img = $manager->read($sPath);
                $img->resize(450, 600);
                $img->save($dPath);


                $data->image = $newImageName;
                $data->save();
            }
            return response()->json([
                'status' => true,
                'message' => 'Category added successfully'
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
        $category = Category::find($id);
        if (empty($category)) {
            return redirect()->route('view.categories');
        }
        return view('admin.category.edit', compact('category'));
    }

    function update($id, Request $request)
    {
        $category = Category::find($id);
        if (empty($category)) {
            return response()->json([
                'status' => false,
                'Not Found' => true,
                'message' => 'Category not found'
            ]);
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'slug' => 'required|string|unique:categories,slug,' . $category->id . ',id',
        ]);

        if ($validator->passes()) {
            // $data = new Category();
            $category->name = $request->name;
            $category->slug = $request->slug;
            $category->status = $request->status;
            $category->showHome = $request->showHome;

            $oldImage = $category->image;

            $oldImage = $category->image;
            //save image here
            if (!empty($request->image_id)) {
                $tempImage = TemplateImage::find($request->image_id);
                $extArray = explode('.', $tempImage->name);
                $ext = last($extArray);

                $newImageName = $category->id . '-' . time() . '.' . $ext;
                $sPath = public_path() . '/temp/' . $tempImage->name;
                $dPath = public_path() . '/uploads/category/' . $newImageName;
                File::copy($sPath, $dPath);

                //Generate image thumbnail
                $dPath = public_path() . '/uploads/category/thumb/' . $newImageName;
                $manager = new ImageManager(new Driver());
                $img = $manager->read($sPath);
                $img->resize(450, 600);
                $img->save($dPath);


                $category->image = $newImageName;

                //Delete old image
                File::delete(public_path() . '/uploads/category/thumb/' . $oldImage);
                File::delete(public_path() . '/uploads/category/' . $oldImage);
            }
            $category->save();
            return response()->json([
                'status' => true,
                'message' => 'Category updated successfully'
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
        $category = Category::find($id);
        if (empty($category)) {
            return response()->json([
                'status' => false,
                'Not Found' => true,
                'message' => 'Category not found'
            ]);
    }

        File::delete(public_path() . '/uploads/category/thumb/' . $category->image);
        File::delete(public_path() . '/uploads/category/' . $category->image);
        $category->delete();

        return response()->json([
            'status' => true,
            'message' => 'Category deleted successfully'
        ], 200);
}
}
