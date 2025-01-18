<?php

use App\Models\LGA;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\admin\HomeController;
use App\Http\Controllers\admin\ProductController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\ShippingController;
use App\Http\Controllers\admin\AdminLoginController;
use App\Http\Controllers\admin\SubCategoryController;
use App\Http\Controllers\admin\DiscountCodeController;
use App\Http\Controllers\admin\OrderController;
use App\Http\Controllers\admin\PageController;
use App\Http\Controllers\admin\ProductImageController;
use App\Http\Controllers\admin\SettingController;
use App\Http\Controllers\ProductSubCategoryController;
use App\Http\Controllers\admin\TemplateImagesController;
use App\Http\Controllers\admin\UserController;

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

// Route::get('/test', function () {
//     // return view('welcome');
//     return orderEmail(2);
// });


Route::get('/',[FrontController::class,'index'])->name('front.home');
Route::get('/shop/{categorySlug?}/{subCategorySlug?}',[ShopController::class,'index'])->name('front.shop');
Route::get('/thanks/{orderId}', [CartController::class, 'thankyou'])->name('front.thankyou');
Route::get('/checkout', [CartController::class, 'checkout'])->name('front.checkout');
Route::get('/product/{slug}',[ShopController::class,'product'])->name('front.product');
Route::get('/page/{slug}',[FrontController::class,'page'])->name('front.page');
Route::get('/cart', [CartController::class, 'cart'])->name('front.cart');
Route::post('/add-to-Cart', [CartController::class, 'addToCart'])->name('front.addToCart');
Route::post('/add-to-wishlist', [FrontController::class, 'addToWishList'])->name('front.addToWishList');
Route::post('/update-Cart', [CartController::class, 'updateCart'])->name('front.updateCart');
Route::post('/delete-Cart', [CartController::class, 'deleteItem'])->name('front.deleteItem.cart');
Route::post('/process-checkout', [CartController::class, 'processCheckout'])->name('front.processCheckout');
Route::post('/order-summary', [CartController::class, 'orderSummary'])->name('front.orderSummary');
Route::post('/send-contact-mail', [FrontController::class, 'sendContactEmail'])->name('front.sendContactEmail');

//apply coupon
Route::post('/apply-discount', [CartController::class, 'applyDiscount'])->name('front.applyDiscount');
Route::post('/remove-discount', [CartController::class, 'removeCoupon'])->name('front.removeCoupon');


Route::group( ['prefix' => 'account'],function () {
 Route::group(['middleware' => 'guest'], function () {
  Route::get('/register', [AuthController::class, 'register'])->name('account.register');
  Route::get('/login', [AuthController::class, 'login'])->name('account.login');
  Route::post('/login', [AuthController::class, 'authenticate'])->name('account.authenticate');
  Route::post('/process-register', [AuthController::class, 'processRegistration'])->name('account.processRegistration');
 });
 
 Route::group(['middleware' => 'auth'], function () {
  Route::get('/profile', [AuthController::class, 'profile'])->name('account.profile');
  Route::post('/update-profile', [AuthController::class, 'updateProfile'])->name('account.updateProfile');
  Route::post('/update-customer-address', [AuthController::class, 'updateAddress'])->name('account.updateAddress');
  Route::post('/update-password', [AuthController::class, 'updatePassword'])->name('account.updatePassword');
  Route::get('/change-password', [AuthController::class, 'changePassword'])->name('account.changePassword');
  Route::get('/my-wishlist', [AuthController::class, 'wishlist'])->name('account.wishlist');
  Route::post('/remove-product-from-wishlist', [AuthController::class, 'removeProductFromWishList'])->name('account.removeProductFromWishList');
  Route::get('/my-orders', [AuthController::class, 'myOrders'])->name('account.orders');
  Route::get('/order-details/{orderId}', [AuthController::class, 'orderDetails'])->name('account.orderDetails');
  Route::get('/logout', [AuthController::class, 'logout'])->name('account.logout');
  Route::get('/lga-by-state', [CartController::class, 'lgaByState'])->name('lga.state');

 });
});

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


  //routes for users
  Route::get('/users', [UserController::class, 'index'])->name('users.index');
  Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
  Route::post('/users/store', [UserController::class, 'store'])->name('store.users');
  Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('edit.users');
  Route::put('/users/{user}', [UserController::class, 'update'])->name('update.users');
  Route::delete('/users/{user}', [BrandController::class, 'destroy'])->name('destroy.users');

    //routes for pages
    Route::get('/pages', [PageController::class, 'index'])->name('pages.index');
    Route::get('/pages/create', [PageController::class, 'create'])->name('pages.create');
    Route::post('/pages/store', [PageController::class, 'store'])->name('store.pages');
    Route::get('/pages/{page}/edit', [PageController::class, 'edit'])->name('edit.pages');
    Route::put('/pages/{page}', [PageController::class, 'update'])->name('update.pages');
    Route::delete('/pages/{page}', [PageController::class, 'destroy'])->name('destroy.pages');

  //routes for product
  Route::get('/products', [ProductController::class, 'index'])->name('view.products');
  Route::get('/products/create', [ProductController::class, 'create'])->name('create.product');
  Route::post('/products/store', [ProductController::class, 'store'])->name('store.product');
  Route::get('/products/edit{id}', [ProductController::class, 'edit'])->name('edit.product');
  Route::put('/products/update{id}', [ProductController::class, 'update'])->name('update.product');
  Route::delete('/products/delete{id}', [ProductController::class, 'destroy'])->name('destroy.product');
  Route::get('/products-subcategories', [ProductSubCategoryController::class, 'index'])->name('product.subcategories');
  
  Route::post('/products-image-update', [ProductImageController::class, 'update'])->name('update.product.image');
  Route::delete('/products-image-delete', [ProductImageController::class, 'destroy'])->name('destroy.product.image');
  Route::get('/get-products', [ProductController::class, 'getProducts'])->name('product.getProducts');
 });
 //Routes for Shipping
 Route::get('/shipping/create', [ShippingController::class, 'create'])->name('shipping.create');
 Route::get('/shipping/edit/{id}', [ShippingController::class, 'edit'])->name('shipping.edit');
 Route::put('/update-shipping/{id}', [ShippingController::class, 'update'])->name('update.shipping');
 Route::post('/store-shipping', [ShippingController::class, 'store'])->name('store.shipping');
 Route::delete('/delete-shipping/{id}', [ShippingController::class, 'destroy'])->name('delete.shipping');

 //Routes for coupon
 Route::get('/coupons', [DiscountCodeController::class, 'index'])->name('coupons.index');
 Route::get('/coupon/create', [DiscountCodeController::class, 'create'])->name('coupon.create');
 Route::get('/coupon/edit/{id}', [DiscountCodeController::class, 'edit'])->name('edit.coupon');
 Route::put('/coupon/update/{id}', [DiscountCodeController::class, 'update'])->name('update.coupon');
 Route::delete('/coupon/delete/{id}', [DiscountCodeController::class, 'destroy'])->name('destroy.coupon');
 Route::post('/coupon/store', [DiscountCodeController::class, 'store'])->name('coupon.store');

 //Routes for Orders
 Route::get('orders',[OrderController::class,'index'])->name('orders.index');
 Route::get('orders/{id}',[OrderController::class,'detail'])->name('orders.detail');
 Route::post('order/change-status{id}',[OrderController::class,'changeOrderStatus'])->name('orders.changeOrderStatus');
 
 //sendinvoiceemail
 Route::post('order/send-email{id}',[OrderController::class,'sendInvoiceEmail'])->name('orders.sendInvoiceEmail');

 //admin change password
 Route::get('change/password',[SettingController::class,'showChangePasswordForm'])->name('admin.changePassword');
 Route::post('update/password',[SettingController::class,'adminUpdatePassword'])->name('admin.updatePassword');
});
