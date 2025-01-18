<?php

namespace App\Http\Controllers;

use App\Mail\ResetPasswordEmail;
use App\Models\CustomerAddress;
use App\Models\LGA;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\State;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login (Request $request){
        return view('front.account.login');
    }

    public function register(Request $request){
        return view('front.account.register');
    }

    public function processRegistration(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:5|confirmed'
        ]);

        if($validator->passes()) {
            $user = new User;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->password = Hash::make($request->password);
            $user->save();
            session()->flash('success', "You have registered successfully");
            return response()->json([
                'status' => true,
            ]);

        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function authenticate(Request $request){
        $validator = Validator::make($request->all(),[
            'email' => 'required',
            'password' => 'required'
        ]);

        if ($validator->passes()){
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->get('remember')))
            {
                if (session()->has('url.intended')) {
                    $intendedUrl = session()->get('url.intended');
                    session()->forget('url.intended');
                    return redirect($intendedUrl); 
                } else {
                    return redirect()->route('account.profile');
                }
            }else {
                // session()->flash('error', "Either/Password is incorrect.");
                return redirect()
                    ->route('account.login')
                    ->withInput($request->only('email'))
                    ->with('error', "Either/Password is incorrect.");

            }
        }else{
            return redirect()
            ->route('account.login')
            ->withErrors($validator)
            ->withInput($request->only('email'));
        }
    }

    public function profile(Request $request){
        $userId = Auth::user()->id;
        $states = State::orderBy('name', 'ASC')->get();
        $lgas = LGA::orderBy('lga', 'ASC')->get();
        $user = User::where('id', $userId)->first();
        $address = CustomerAddress::where('user_id', $userId)->first();

        return view('front.account.profile',[
            'user' => $user,
            'states' => $states,
            'lgas' => $lgas,
            'address' => $address
        ]);
    }

    public function updateProfile(Request $request){
        $userId = Auth::user()->id;
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$userId.'id',
            'phone' => 'required'
        ]);

        if ($validator->passes()){
            $user = User::find($userId);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->save();
            session()->flash('success', 'Profile Updated successfully.');

            return response()->json([
                'status' => true,
                'message' => 'Profile Updated successfully.'
            ]);

        }else{
            return response()->json([
                'status' => false,
                'errors' =>$validator->errors()
            ]);
        }

    }

    //update address
    public function updateAddress(Request $request){
        $userId = Auth::user()->id;
        $validator = Validator::make($request->all(),[
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users,email,'.$userId.'id',
            'mobile' => 'required',
            'address' => 'required',
            'city' => 'required',
            'apartment' => 'nullable',
            'zip' => 'required',
            'state_id' => 'required',
        ]);

        if ($validator->passes()){
            CustomerAddress::updateOrCreate(
                ['user_id' => $userId],
                [
                    'user_id' => $userId,
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'mobile' => $request->mobile,
                    'address' => $request->address,
                    'city' => $request->city,
                    'appartment' => $request->apartment,
                    'zip' => $request->zip,
                    'state_id' => $request->state_id,
                ],
                );
            session()->flash('success', 'Address Updated successfully.');

            return response()->json([
                'status' => true,
                'message' => 'Address Updated successfully.'
            ]);

        }else{
            return response()->json([
                'status' => false,
                'errors' =>$validator->errors()
            ]);
        }

    }

    public function myOrders(){
        $user = Auth::user();
        $orders = Order::where('user_id', $user->id)->orderBy('created_at', 'DESC')->get();
        $data['orders'] = $orders;
        return view('front.account.order', $data);
    }

    public function orderDetails($id){
        $user = Auth::user();
        $data = [];
        $order = Order::where('user_id', $user->id)->where('id', $id)->first();

        $orderItems = OrderItem::where('order_id', $id)->get();
        $orderItemsCount = OrderItem::where('order_id', $id)->count();
        $data['order'] = $order;
        $data['orderItems'] = $orderItems;
        $data['orderItemsCount'] = $orderItemsCount;
        return view('front.account.order-details', $data);
    }

    public function wishlist(){
        $wishlists = Wishlist::where('user_id', Auth::user()->id)->with('product')->get();
        $data = [];
        $data['wishlists'] = $wishlists;

        return view('front.account.wishlist', $data);
    }

    public function removeProductFromWishList(Request $request){
        $wishlist = Wishlist::where('user_id', Auth::user()->id)
        ->where('product_id', $request->id)->first();

        $wishlist->delete();
        if($wishlist == null){
            session()->flash('error', 'Product already removed');

            return response()->json(
                [
                    'status' => false,
                ]
                );
        }else{
            $wishlist = Wishlist::where('user_id', Auth::user()->id)
        ->where('product_id', $request->id)->delete();
        session()->flash('success', 'Product removed successfully!');
        return response()->json(
            [
                'status' => true,
            ]
            );
        }

    }

    public function changePassword(){
        return view('front.account.change-password');
    }

    public function updatePassword(Request $request){
        // $userId = Auth::user()->id;
        $validator = Validator::make($request->all(),[
            'old_password' => 'required',
            'new_password' => 'required|min:5|same:confirm_password',
            'confirm_password' => 'required',
        ]);

        $user = User::select('id', 'password')->where('id', Auth::user()->id)->first();
        if ($validator->passes()){
            
            if(!Hash::check($request->old_password, $user->password)){
                session()->flash('error', 'Your old password is incorrect, please try again');
                return response()->json([
                    'status' => false,
                ]);

            }
            User::where('id', $user->id)->update([
                'password' => Hash::make($request->new_password)
            ]);
            session()->flash('success', 'Password Updated successfully.');

            return response()->json([
                'status' => true,
                'message' => 'Password Updated successfully.'
            ]);

        }else{
            return response()->json([
                'status' => false,
                'errors' =>$validator->errors()
            ]);
        }
    }


    public function forgotPasswordShow(){
        return view('front.account.forgot-password');
    }

    public function processForgotPassword(Request $request){
        $validator = Validator::make($request->all(),[
            'email' => 'required|email|exists,users,email',
        ]);

        if ($validator->fails()){
            return redirect()->route('front.forgotPasswordShow')->withInput()->withErrors($validator);

        }
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();
        $token = Str::random(60);
        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => now()
        ]);
        //send mail
        $user = User::where('email', $request->email)->first();
        $mailData = [
            'token' => $token,
            'user' => $user,
            'mail_subject' => 'You have requested to reset password'
        ];

        Mail::to($request->email)->send(new ResetPasswordEmail($mailData));
        return redirect()->route('front.forgotPasswordShow')->with('success', 'Please check your email to reset your password.');
    }

    public function resetPassword($token){

        $tokenExists = DB::table('password_reset-tokens')->where('token', $token)->first();

        if ($tokenExists == null){
            return redirect()->route('front.forgotPasswordShow')->with('error', 'Invalid request');
        }
        return view('front.account.reset-password',[
            'token' => $token
        ]);

    }

    public function processResetPassword(Request $request){
        
            $token = $request->token;
            $tokenObj = DB::table('password_reset-tokens')->where('token', $token)->first();

            if ($tokenObj == null){
                return redirect()->route('front.forgotPasswordShow')->with('error', 'Invalid request');
            }
            $user = User::where('email', $tokenObj->email)->first();
            
            $validator = Validator::make($request->all(),[
                'new_password' => 'required|min:5',
                'confirm_password' => 'required|same:new_password'
            ]);
            
            if ($validator->fails()){
                return redirect()->route('front.resetPassword', $token)->withErrors($validator);

            }

            User::where('id', $user->id)->update([
                'password' => Hash::make($request->new_password)
            ]);
            DB::table('password_reset_tokens')->where('email', $user->email)->delete();
            return redirect()->route('account.login')->with('success', 'You have successfully updated your password');

    

    }
    public function logout(){
        Auth::logout();
        return redirect()->route('account.login')
        ->with('success', "You have successfully logout!");
    }
}
