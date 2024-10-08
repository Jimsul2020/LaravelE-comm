<?php

namespace App\Http\Controllers\admin;

use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use App\Models\TemplateImage;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Drivers\Gd\Driver;

class ProductController extends Controller
{
    function index(Request $request)
    {
        $products = Product::with('product_images')->latest('id');
        if (!empty($request->get('keyword'))) {
            $keyword = $request->get('keyword');
            $products->where(function ($query) use ($keyword) {
                $query->where('title', 'like', '%' . $keyword . '%')
                    ->orWhere('price', 'like', '%' . $keyword . '%')
                    ->orWhere('sku', 'like', '%' . $keyword . '%');
            });
        }
        $products = $products->paginate(10);
        // dd($products);
        return view('admin.product.index', compact('products'));
    }
    function getProducts(Request $request){
        $tempProduct = [];
        if($request->term != ''){
            $products = Product::where('title', 'like', '%'.$request->term. '%')->get();

            if ($products != null){
                foreach ($products as $product) {
                    $tempProduct[] = array('id' => $product->id, 'text' => $product->title);
                }
            }
        }
        return response()->json([
            'tags' => $tempProduct,
            'status' => true
        ]);
    }

    function create(Request $request)
    {
        $data = [];
        $categories = Category::orderBy('name', 'ASC')->get();
        $brands = Brand::orderBy('name', 'ASC')->get();
        $data['categories'] = $categories;
        $data['brands'] = $brands;

        return view('admin.product.create', $data);
    }
    function store(Request $request)
    {
        $rules = [
            'title' => 'required|string',
            'sku' => 'required|string|unique:products',
            'barcode' => 'nullable|string',
            'slug' => 'required|string|unique:products',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'is_featured' => 'required|in:Yes,No',
            'qty' => 'required|numeric',
            'compare_price' => 'nullable|numeric',
            'brand_id' => 'nullable|integer',
            'category_id' => 'required|numeric',
            'sub_category_id' => 'nullable|numeric',
            'track_qty' => 'required|in:Yes,No',
            'status' => 'nullable|integer',
        ];
        if (!empty($request->track_qty) && $request->track_qty == 'Yes') {
            $rules['qty'] = 'required';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->passes()) {
            $product = new Product();
            $product->title = $request->title;
            $product->slug = $request->slug;
            $product->sku = $request->sku;
            $product->qty = $request->qty;
            $product->barcode = $request->barcode;
            $product->price = $request->price;
            $product->is_featured = $request->is_featured;
            $product->category_id = $request->category_id;
            $product->brand_id = $request->brand_id;
            $product->sub_category_id = $request->sub_category_id;
            $product->track_qty = $request->track_qty;
            $product->description = $request->description;
            $product->short_description = $request->short_description;
            $product->shipping_returns = $request->shipping_returns;
            $product->compare_price = $request->compare_price;
            $product->related_products = (!empty($request->related_products) ? implode(',', $request->related_products) : '');
            $product->status = $request->status;
            $product->save();
            //save Gallery here
            if (!empty($request->image_array)) {
                foreach ($request->image_array as $temp_image_id)
                    $tempImageInfo = TemplateImage::find($temp_image_id);
                $extArray = explode('.', $tempImageInfo->name);
                $ext = last($extArray); // like jpg,gif,png etc

                $productImage = new ProductImage();
                $productImage->product_id = $product->id;
                $productImage->image = 'NUll';
                $productImage->save();

                $imageName = time() . '.' . $ext;
                $productImage->image = $imageName;
                $productImage->save();

                //Generate image thumbnail
                $sPath = public_path() . '/temp/' . $tempImageInfo->name;

                //large
                $dPath = public_path() . '/uploads/product/large/' . $imageName;
                // File::copy($sPath, $dPath);
                $manager = new ImageManager(new Driver());
                $img = $manager->read($sPath);
                $img->resize(800, 1000);
                $img->save($dPath);

                //small
                $dPath = public_path() . '/uploads/product/small/' . $imageName;
                $manager = new ImageManager(new Driver());
                $image = $manager->read($sPath);
                $image->resize(300, 300);
                $image->save($dPath);
            }
            // $request->session()->flash('success', 'Product added successfully');
            return response()->json([
                'status' => true,
                'message' => 'Product added successfully'
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
        $product = Product::find($id);
        $relatedProducts = [];

        if($product->related_products != ''){
            $productArray = explode(',', $product->related_products);
            $relatedProducts = Product::whereIn('id', $productArray)->with('product_images')->get();
        }

        $data = [];
        $subCategories = SubCategory::where('category_id', $product->category_id)->get();
        $productImages = ProductImage::where('product_id', $product->id)->get();
        $categories = Category::orderBy('name', 'ASC')->get();
        $brands = Brand::orderBy('name', 'ASC')->get();
        $data['categories'] = $categories;
        $data['subCategories'] = $subCategories;
        $data['productImages'] = $productImages;
        $data['brands'] = $brands;
        $data['product'] = $product;
        $data['relatedProducts'] = $relatedProducts;
        return view('admin.product.edit', $data);
    }

    function update($id, Request $request)
    {
        $product = Product::find($id);
        if (empty($product)) {
            return redirect()->route('view.products')->with('error', 'Product not found');
        }
        $rules = [
            'title' => 'required|string',
            'sku' => 'required|string|unique:products,sku, ' . $product->id . ',id',
            'barcode' => 'nullable|string',
            'slug' => 'required|string|unique:products,slug, ' . $product->id . ',id',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'is_featured' => 'required|in:Yes,No',
            'qty' => 'required|numeric',
            'compare_price' => 'nullable|numeric',
            'brand_id' => 'nullable|integer',
            'category_id' => 'required|numeric',
            'sub_category_id' => 'nullable|numeric',
            'track_qty' => 'required|in:Yes,No',
            'status' => 'nullable|integer',
        ];
        if (!empty($request->track_qty) && $request->track_qty == 'Yes') {
            $rules['qty'] = 'required';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->passes()) {
            $product->title = $request->title;
            $product->slug = $request->slug;
            $product->sku = $request->sku;
            $product->qty = $request->qty;
            $product->barcode = $request->barcode;
            $product->price = $request->price;
            $product->is_featured = $request->is_featured;
            $product->category_id = $request->category_id;
            $product->brand_id = $request->brand_id;
            $product->sub_category_id = $request->sub_category_id;
            $product->track_qty = $request->track_qty;
            $product->description = $request->description;
            $product->short_description = $request->short_description;
            $product->shipping_returns = $request->shipping_returns;
            $product->related_products = (!empty($request->related_products) ? implode(',', $request->related_products) : '');
            $product->compare_price = $request->compare_price;
            $product->status = $request->status;
            $product->save();
            //save Gallery here
            // if (!empty($request->image_array)) {
            //     foreach ($request->image_array as $temp_image_id)
            //         $tempImageInfo = TemplateImage::find($temp_image_id);
            //     $extArray = explode('.', $tempImageInfo->name);
            //     $ext = last($extArray); // like jpg,gif,png etc

            //     $productImage = new ProductImage();
            //     $productImage->product_id = $product->id;
            //     $productImage->image = 'NUll';
            //     $productImage->save();

            //     $imageName = $product->id . '-' . $productImage->id . '-' . time() . '-' . $ext;
            //     $productImage->image = $imageName;
            //     $productImage->save();

            //     //Generate image thumbnail
            //     $sPath = public_path() . '/temp/' . $tempImageInfo->name;

            //     //large
            //     $dPath = public_path() . '/uploads/product/large/' . $tempImageInfo->name;
            //     // File::copy($sPath, $dPath);
            //     $manager = new ImageManager(new Driver());
            //     $img = $manager->read($sPath);
            //     $img->resize(1400, 600);
            //     $img->save($dPath);

            //     //small
            //     $dPath = public_path() . '/uploads/product/small/' . $tempImageInfo->name;
            //     $manager = new ImageManager(new Driver());
            //     $image = $manager->read($sPath);
            //     $image->resize(300, 300);
            //     $image->save($dPath);
            // }
            // $request->session()->flash('success', 'Product added successfully');
            return response()->json([
                'status' => true,
                'message' => 'Product updated successfully'
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
        $product = Product::find($id);
        if (empty($product)) {
            return response()->json([
                'status' => false,
                'notFound' => true
            ]);
        }

        $productImages = ProductImage::where('product_id', $id)->get();
        if ($productImages->isNotEmpty()) {
            foreach ($productImages as $productImage) {
                $largeImagePath = File::delete(public_path('/uploads/product/large/' . $productImage->image));
                $smallImagePath = File::delete(public_path('/uploads/product/small/' . $productImage->image));

                if (File::exists($largeImagePath)) {
                    File::delete($largeImagePath);
                }

                if (File::exists($smallImagePath)) {
                    File::delete($smallImagePath);
                }
            }
            ProductImage::where('product_id', $id)->delete();
        }

        $product->delete();
        return response()->json([
            'status' => true,
            'message' => 'Product deleted successfully'
        ]);
    }
}
