<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
    public function showChangePasswordForm(){
        return view('admin.change_password');
    }

    public function adminUpdatePassword(Request $request){
        // $userId = Auth::user()->id;
        $validator = Validator::make($request->all(),[
            'old_password' => 'required',
            'new_password' => 'required|min:5|same:confirm_password',
            'confirm_password' => 'required',
        ]);
       $id = Auth::guard('admin_user')->user()->id;

        $admin_user = User::select('id', 'password')->where('id', $id)->first();
        if ($validator->passes()){
            
            if(!Hash::check($request->old_password, $admin_user->password)){
                session()->flash('error', 'Your old password is incorrect, please try again');
                return response()->json([
                    'status' => false,
                ]);

            }
            User::where('id', $id)->update([
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
}
