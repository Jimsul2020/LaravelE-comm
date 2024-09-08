<?php

namespace App\Http\Controllers;

use App\Models\LGA;
use App\Models\Order;
use App\Models\State;
use App\Models\Country;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use App\Models\DiscountCoupon;
use App\Models\ShippingCharge;
use Illuminate\Support\Carbon;
use App\Models\CustomerAddress;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    public function cart(Request $request)
    {
        $cartContent = Cart::content();
        // dd(Cart::content());

        $data['cartContent'] = $cartContent;
        return view('front.cart', $data);
    }

    public function addToCart(Request $request)
    {

        $product = Product::with('product_images')->find($request->id);
        if ($product == null) {
            return response()->json([
                'status' => false,
                'message' => 'product not found'
            ]);
        }

        if (Cart::count() > 0) {
            $cartContent = Cart::content();
            $productAlreadyExist = false;
            foreach ($cartContent as $item) {
                if ($item->id == $product->id) {
                    $productAlreadyExist = true;
                }
            }
            if ($productAlreadyExist == false) {
                Cart::add($product->id, $product->title, 1, $product->price, ['productImage' => (!empty($product->product_images)) ? $product->product_images->first() : '']);

                $status = true;
                $message = '<strong>' . $product->title . '</strong>  added to your cart successfully!';
                session()->flash('success', $message);
            } else {

                $status = false;
                $message = $product . 'already added into cart';
            }
        } else {
            Cart::add($product->id, $product->title, 1, $product->price, ['productImage' => (!empty($product->product_images)) ? $product->product_images->first() : '']);
            $status = true;
            $message = '<strong>' . $product->title . '</strong>  added to your cart successfully!';
            session()->flash('success', $message);
        }
        return response([
            'status' => $status,
            'message' => $message,
        ]);
        // Cart::add('293ad', 'Product 1', 1, 9.99);
    }

    public function updateCart(Request $request)
    {
        $rowId = $request->rowId;
        $qty = $request->qty;

        $itemInfo = Cart::get($rowId);

        $product = Product::find($itemInfo->id);
        //check qty available in stock
        if ($product->track_qty == 'Yes') {

            if ($qty <= $product->qty) {
                Cart::update($rowId, $qty);
                $message = 'Cart updated successfully';
                $status = true;
                session()->flash('success', $message);
            } else {
                $message = 'Reqested qty(' . $qty . ') not available in stock.';
                $status = false;
                session()->flash('error', $message);
            }
        } else {

            Cart::update($rowId, $qty);
            $message = 'Cart updated successfully';
            $status = true;
            session()->flash('success', $message);
        }
        return response()->json([
            'status' => $status,
            'message' => $message,

        ]);
    }

    public function deleteItem(Request $request)
    {
        $itemInfo = Cart::get($request->rowId);

        if ($itemInfo == null) {
            $errorMessage = "Item not found";

            session()->flash('error', $errorMessage);

            return response()->json([
                'status' => false,
                'message' => $errorMessage,
            ]);
        }

        Cart::remove($request->rowId);

        $message = "Item removed from cart successfully";
        session()->flash('success', $message);

        return response()->json([
            'status' => true,
            'message' => $message,
        ]);
    }


    public function checkout(Request $request)
    {
        $discount = 0;
        
        if (Cart::count() == 0) {
            return redirect()->route('front.cart');
        }
        //if user is not logged in redirect user to login
        if (Auth::check() == false) {
            if (!session()->has('url.intended')) {
                session(['url.intended' => url()->current()]);
            }
            return redirect()->route('account.login');
        }
        $customerAddress = CustomerAddress::where('user_id', (Auth::user()->id))->first();
        session()->forget('url.intended');

        $countries = Country::orderBy('name', 'ASC')->get();
        $states = State::orderBy('name', 'ASC')->get();
        $subTotal = (float) Cart::subtotal(2, '.', '');
        //Apply discount
        // if (session()->has('code')) {
        //     $code = session()->get('code');

        //     if ($code->type == 'percent') {
        //         $discount = ($code->discount_amount / 100) * $subTotal;
        //     } else {
        //         $discount = $code->discount_amount;
        //     }
        // }
        if (session()->has('code')) {
            $code = session()->get('code');
            $discount = $code->type == 'percent' ? ($code->discount_amount / 100) * $subTotal : $code->discount_amount;

            //display discountString
            // $discountString = '<div class="mt-4" id="discount-row">
            //             <strong>'. session()->get('code')->code.'</strong>
            //             <a href="" class="btn btn-sm btn-danger" id="remove_discount"><i class="fa fa-times"></i></a>
            //         </div>';
        }
        //calculate shipping charge
        if ($customerAddress != '') {
            $userState = $customerAddress->state_id;
            $shippingCharge = ShippingCharge::where('state_id', $userState)->first();
            $totalQty = 0;
            $totalShippingCharge = 0;
            $grandTotal = 0;
            foreach (Cart::content() as $item) {
                $totalQty += $item->qty;
            }
            $totalShippingCharge = $totalQty * $shippingCharge->amount;
            $grandTotal = ($subTotal - $discount) + $totalShippingCharge;
        } else {
            $totalShippingCharge = 0;
            $grandTotal = ($subTotal-$discount);
        }

        return view('front.checkout', [
            'customerAddress' => $customerAddress,
            'countries' => $countries,
            'states' => $states,
            'discount' => $discount,
            // 'discountString' => $discountString,
            'totalShippingCharge' => $totalShippingCharge,
            'grandTotal' => $grandTotal
        ]);
    }

    function lgaByState(Request $request)
    {
        if (!empty($request->state_id)) {
            $lgas = LGA::where('state_id', $request->state_id)
                ->orderBy('name', 'ASC')
                ->get();

            return response()->json([
                'status' => true,
                'lgas' => $lgas,
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'lgas' => [],
            ], 422);
        }
    }


    public function processCheckout(Request $request)
    {
        // dd($request);
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required',
            'country_id' => 'required',
            'address' => 'required',
            'city' => 'required',
            'state_id' => 'required',
            'lga_id' => 'required',
            'zip' => 'required',
            'mobile' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Please fix the error',
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
        //step2 save user's address
        //$customerAddress = CustomerAddress::find();
        $user = Auth::user();
        CustomerAddress::updateOrCreate(
            ['user_id' => $user->id],
            [
                'user_id' => $user->id,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'country_id' => $request->country_id,
                'state_id' => $request->state_id,
                'lga_id' => $request->lga_id,
                'city' => $request->city,
                'address' => $request->address,
                'zip' => $request->zip,
                'mobile' => $request->mobile,
                'appartment' => $request->appartment,
                'notes' => $request->order_notes
            ]
        );


        //step2 save user's order in oerder's table
        // $customerAddress = CustomerAddress::find();
        $shipping = 0;
        $discount = 0;
        $subTotal = Cart::subtotal(2, '.', '');
        $grandTotal = 0;
        $discountCodeId ='';
        $promoCode ='';

        if (session()->has('code')) {
            $code = session()->get('code');

            if ($code->type == 'percent') {
                $discount = ($code->discount_amount / 100) * $subTotal;
            } else {
                $discount = $code->discount_amount;
            }
            $discountCodeId = $code->id;
            $promoCode = $code->code;
        }

        $shippingCharge = ShippingCharge::where('state_id', $request->state_id)->first();

        $totalQty = 0;
        foreach (Cart::content() as $item) {
            $totalQty += $item->qty;
        }

        if ($shippingCharge != null) {
            $shipping = $totalQty * $shippingCharge->amount;
            $grandTotal = ($subTotal - $discount) + $shipping;
        } else {
            $grandTotal = ($subTotal - $discount);
        }


        $order = new Order;
        $order->subtotal = $subTotal;
        $order->discount = $discount;
        $order->coupon_code_id = $discountCodeId;
        $order->coupon_code = $promoCode;
        $order->shipping = $shipping;
        $order->grand_total = $grandTotal;
        $order->user_id = $user->id;
        $order->first_name = $request->first_name;
        $order->last_name = $request->last_name;
        $order->email = $request->email;
        $order->phone = $request->mobile;
        $order->address = $request->address;
        $order->apartment = $request->appartment;
        $order->country_id = $request->country_id;
        $order->state_id = $request->state_id;
        $order->lga_id = $request->lga_id;
        $order->city = $request->city;
        $order->zip = $request->zip;
        $order->notes = $request->notes;

        $order->save();

        //step 4 save user's order item into order_items table
        foreach (Cart::content() as $item) {
            $orderItem = new OrderItem;
            $orderItem->product_id = $item->id;
            $orderItem->order_id = $order->id;
            $orderItem->name = $item->name;
            $orderItem->qty = $item->qty;
            $orderItem->price = $item->price;
            $orderItem->total = $item->price * $item->qty;

            $orderItem->save();
        }

        session()->flash('success', 'You have successfully placed your order');
        Cart::destroy();
        session()->forget('code');

        return response()->json([
            'message' => 'Order saved successfully.',
            'orderId' => $order->id,
            'status' => true
        ]);
    }

    public function thankYou(Request $request, $id)
    {

        return view('front.thankyou', ['id' => $id]);
    }

    public function orderSummary(Request $request)
    {
        $subTotal = (float) Cart::subtotal(2, '.', '');
        $discount = 0;
        $discountString = '';

        if (session()->has('code')) {
            $code = session()->get('code');

            if ($code->type == 'percent') {
                $discount = ($code->discount_amount / 100) * $subTotal;
            } else {
                $discount = $code->discount_amount;
            }
            //display discountString
            $discountString = '<div class="mt-4" id="discount-row">
                        <strong>' . session()->get('code')->code . '</strong>
                        <a href="" class="btn btn-sm btn-danger" id="remove_discount"><i class="fa fa-times"></i></a>
                    </div>';
        }
        if ($request->state_id > 0) {
            // Cast subtotal to a float to ensure correct calculations
            $shippingCharge = ShippingCharge::where('state_id', $request->state_id)->first();

            $totalQty = 0;
            foreach (Cart::content() as $item) {
                $totalQty += $item->qty;
            }

            if ($shippingCharge != null) {
                $totalShippingCharge = $totalQty * $shippingCharge->amount;
                $grandTotal = ($subTotal-$discount) + $totalShippingCharge;

                return response()->json([
                    'status' => true,
                    'grandTotal' => number_format($grandTotal, 2),
                    'discount' => $discount,
                    'discountString' => $discountString,
                    'totalShippingCharge' => number_format($totalShippingCharge, 2),
                ]);
            } else {
                // If shippingCharge is null, handle accordingly
                $grandTotal = ($subTotal - $discount); // No shipping charge, just return the subtotal
                return response()->json([
                    'status' => true,
                    'grandTotal' => number_format($grandTotal, 2),
                    'discount' => $discount,
                    'discountString' => $discountString,
                    'totalShippingCharge' => number_format(0, 2),
                ]);
            }
        } else {
            // Define grandTotal when state_id <= 0
            $grandTotal = (float) Cart::subtotal(2, '.', '');
            return response()->json([
                'status' => true,
                'grandTotal' => number_format($grandTotal, 2),
                'discount' => $discount,
                'discountString' => $discountString,
                'totalShippingCharge' => number_format(0, 2),
            ]);
        }
    }

    // public function applyDiscount(Request $request)
    // {
    //     // dd($request);
    //     $code = DiscountCoupon::where('code', $request->code)->first();

    //     if ($code == null) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Inavlid coupon code'
    //         ]);
    //     }
    //     $now = Carbon::now();
    //     echo $now;
    //     if ($code->start_at != '') {
    //         $startDate = Carbon::createFromFormat('Y-m-d H:i:s', $code->start_at);
    //         Log::info($startDate);

    //         if ($now->lt($startDate)) {
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => 'Inavlid coupon code'
    //             ]);
    //         }
    //     }

    //     if ($code->expires_at != '') {
    //         $endDate = Carbon::createFromFormat('Y-m-d H:i:s', $code->expires_at);
    //         Log::info($endDate);
    //         if ($now->gt($endDate)) {
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => 'Inavlid coupon code'
    //             ]);
    //         }
    //     }

    //     session()->put('code', $code);
    //     return $this->orderSummary($request);
    // }
    public function applyDiscount(Request $request)
    {
        // Check if the coupon exists
        $code = DiscountCoupon::where('code', $request->code)->first();

        if (!$code) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid coupon code'
            ]);
        }

        $now = Carbon::now('UTC');
        // echo "Current time: " . $now->format('Y-m-d H:i:s') . "\n";
        
        
        
        // Validate start date if set
        if (!empty($code->start_at)) {
            $startDate = Carbon::createFromFormat('Y-m-d H:i:s', $code->start_at, 'UTC');
            // echo "Start date: " . $startDate->format('Y-m-d H:i:s') . "\n";
            if ($now->lt($startDate)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Coupon is not valid yet'
                ]);
            }
        }

        // Validate expiration date if set
        if (!empty($code->expires_at)) {
            $endDate = Carbon::createFromFormat('Y-m-d H:i:s', $code->expires_at, 'UTC');
            // echo "End date: " . $endDate->format('Y-m-d H:i:s') . "\n";
            if ($now->gt($endDate)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Coupon has expired'
                ]);
            }
        }

        // Store the coupon in the session
        session()->put('code', $code);

        return $this->orderSummary($request);
    }

    //remove coupon
    public function removeCoupon(Request $request){
        session()->forget('code');
        return $this->orderSummary($request);

    }

}
