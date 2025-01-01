<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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
        return view('front.account.profile');
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

    public function logout(){
        Auth::logout();
        return redirect()->route('account.login')
        ->with('success', "You have successfully logout!");
    }
}
