<?php

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\HomeController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\AdminLoginController;
use App\Http\Controllers\admin\ProductController;
use App\Http\Controllers\admin\ProductImageController;
use App\Http\Controllers\admin\SubCategoryController;
use App\Http\Controllers\admin\TemplateImagesController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\ProductSubCategoryController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });


Route::group(['prefix' => 'admin'], function () {
 Route::group(['middleware' => 'admin.guest'], function () {
  Route::get('/login', [AdminLoginController::class, 'index'])->name('admin.login');
  Route::post('/authenticate', [AdminLoginController::class, 'authenticate'])->name('admin.authenticate');
 });

 Route::group(['middleware' => 'admin.auth'], function () {
  Route::get('/dashboard', [HomeController::class, 'index'])->name('admin.dashboard');
  Route::get('/logout', [HomeController::class, 'logout'])->name('admin.logout');

  //routes for category
  Route::get('/categories', [CategoryController::class, 'index'])->name('view.categories');
  Route::get('/categories/create', [CategoryController::class, 'create'])->name('create.category');
  Route::post('/categories/store', [CategoryController::class, 'store'])->name('store.category');
  Route::get('/categories/edit{id}', [CategoryController::class, 'edit'])->name('edit.category');
  Route::put('/categories/update{id}', [CategoryController::class, 'update'])->name('update.category');
  Route::delete('/categories/delete{id}', [CategoryController::class, 'destroy'])->name('destroy.category');
  Route::post('/upload-temp-image', [TemplateImagesController::class, 'store'])->name('temp-images.create');
  //temp-images.create
  Route::get('/getslug', function (Request $request) {
   $slug = '';
   if (!empty($request->title)) {
    $slug = Str::slug($request->title);
   }
   return response()->json([
    'status' => true,
    'slug' => $slug
   ]);
  })->name('getslug');

  //routes for sub-category
  Route::get('/sub-categories', [SubCategoryController::class, 'index'])->name('view.subCategories');
  Route::get('/sub-categories/create', [SubCategoryController::class, 'create'])->name('create.subCategory');
  Route::post('/sub-categories/store', [SubCategoryController::class, 'store'])->name('store.subCategory');
  Route::get('/sub-categories/edit{id}', [SubCategoryController::class, 'edit'])->name('edit.subCategory');
  Route::put('/sub-categories/update{id}', [SubCategoryController::class, 'update'])->name('update.subCategory');
  Route::delete('/sub-categories/delete{id}', [SubCategoryController::class, 'destroy'])->name('destroy.subCategory');


  //routes for brand
  Route::get('/brands', [BrandController::class, 'index'])->name('view.brands');
  Route::get('/brands/create', [BrandController::class, 'create'])->name('create.brand');
  Route::post('/brands/store', [BrandController::class, 'store'])->name('store.brand');
  Route::get('/brands/edit{id}', [BrandController::class, 'edit'])->name('edit.brand');
  Route::put('/brands/update{id}', [BrandController::class, 'update'])->name('update.brand');
  Route::delete('/brands/delete{id}', [BrandController::class, 'destroy'])->name('destroy.brand');


  //routes for product
  Route::get('/products', [ProductController::class, 'index'])->name('view.products');
  Route::get('/products/create', [ProductController::class, 'create'])->name('create.product');
  Route::post('/products/store', [ProductController::class, 'store'])->name('store.product');
  Route::get('/products/edit{id}', [ProductController::class, 'edit'])->name('edit.product');
  Route::put('/products/update{id}', [ProductController::class, 'update'])->name('update.product');
  Route::delete('/products/delete{id}', [ProductController::class, 'destroy'])->name('destroy.product');
  Route::get('/products-subcategories', [ProductSubCategoryController::class, 'index'])->name('product.subcategories');
  
  Route::post('/products-image-update', [ProductImageController::class, 'update'])->name('update.product.image');
 });
 Route::delete('/products-image-delete', [ProductImageController::class, 'destroy'])->name('destroy.product.image');
});
