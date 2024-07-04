<?php

namespace App\Http\Controllers\admin;

use App\Models\ProductImage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ProductImageController extends Controller
{
    function update(Request $request){

        $image = $request->image;
        $ext = $image->getClientOriginalExtension();
        $sPath = $image->getPathName();

        $productImage = new ProductImage();
        $productImage->product_id = $request->product_id;
        $productImage->image = 'NULL';
        $productImage->save();

        $imageName = $request->product_id . '-' . $productImage->id . '-' . time() . '.' . $ext;
        $productImage->image = $imageName;
        $productImage->save();

        //Generate image thumbnail

        //large
        $dPath = public_path() . '/uploads/product/large/' . $imageName;
        // File::copy($sPath, $dPath);
        $manager = new ImageManager(new Driver());
        $img = $manager->read($sPath);
        $img->resize(1400, 600);
        $img->save($dPath);

        //small
        $dPath = public_path() . '/uploads/product/small/' . $imageName;
        $manager = new ImageManager(new Driver());
        $image = $manager->read($sPath);
        $image->resize(300, 300);
        $image->save($dPath);

        return response()->json([
            'status' => true,
            'image_id' => $productImage->id,
            'imagePath' => asset('uploads/product/small/' . $productImage->image),
            'message' => 'Image added successfully'
        ], 200);
    }

    function destroy(Request $request){
        $productImage = ProductImage::find($request->id);
        if(empty($productImage)){
            return response()->json([
                'status' => false,
                'message' => 'Image not found'
            ], 422);
        }

        //Delet images from folder
        File::delete(public_path('upload/product/large/'.$productImage->image));
        File::delete(public_path('upload/product/small/'.$productImage->image));
        $productImage->delete();

        return response()->json([
            'status' => true,
            'message' => 'Image deleted successfully'
        ], 200);
    }
}
