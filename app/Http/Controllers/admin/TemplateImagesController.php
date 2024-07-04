<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Models\TemplateImage;
use App\Http\Controllers\Controller;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class TemplateImagesController extends Controller
{
    function store(Request $request)
    {
        $image = $request->image;
        if (!empty($image)) {
            $ext = $image->getClientOriginalExtension();
            $newName = time() . '.' . $ext;

            $tempImage = new TemplateImage();
            $tempImage->name = $newName;
            $tempImage->save();

            $image->move(public_path() . '/temp', $newName);

            //Generate thumbnail
            $sourcePath = public_path() . '/temp/' . $newName;
            $destPath = public_path() . '/temp/thumb/' . $newName;
            $manager = new ImageManager(new Driver());
            $image = $manager->read($sourcePath);
            $image->resize(300, 275);
            $image->save($destPath);

            return response()->json([
                'status' => true,
                'image_id' => $tempImage->id,
                'imagePath' => asset('temp/thumb/' . $newName),
                'message' => 'Image uploaded successfully!',
            ]);
        }
    }
}
