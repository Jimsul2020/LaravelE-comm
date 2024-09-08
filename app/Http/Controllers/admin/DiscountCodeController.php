<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Models\DiscountCoupon;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class DiscountCodeController extends Controller
{
    public function index(Request $request) {
        $discountCoupons = DiscountCoupon::latest('id');
        if (!empty($request->get('keyword'))) {
            $keyword = $request->get('keyword');
            $discountCoupons->where(function ($query) use ($keyword) {
                $query->where('code', 'like', '%' . $keyword . '%')
                    ->orWhere('name', 'like', '%' . $keyword . '%');
            });
        }
        $discountCoupons = $discountCoupons->paginate(10);
        return view('admin.coupon.index', compact('discountCoupons'));
    }

    public function create(Request $request)
    {
        return view('admin.coupon.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string',
            'name' => 'nullable|string',
            'discount_amount' => 'required|numeric',
            'type' => 'required',
            'start_at' => 'nullable|date_format:Y-m-d H:i:s'
        ]);

        if ($validator->passes()) {

            //starting date must not be less than current time
            if (!empty($request->start_at)) {
                $now = Carbon::now();
                $startAt = Carbon::createFromFormat('Y-m-d H:i:s', $request->start_at);
                if ($startAt->lte($now) == true) {
                    return response()->json([
                        'status' => false,
                        'errors' => ['start_at' => 'Start date cannot be less than the current date and time']
                    ]);
                }
            }

            //expiring date must not be less than start date
            if (!empty($request->start_at) && !empty($request->expires_at)) {
                $expiresAt = Carbon::createFromFormat('Y-m-d H:i:s', $request->expires_at);
                $startAt = Carbon::createFromFormat('Y-m-d H:i:s', $request->start_at);
                if ($expiresAt->gt($startAt) == false) {
                    return response()->json([
                        'status' => false,
                        'errors' => ['expires_at' => 'Expiring date cannot be less than the start date']
                    ]);
                }
            }
            $discountCode = new DiscountCoupon;

            $discountCode->code = $request->code;
            $discountCode->name = $request->name;
            $discountCode->description = $request->description;
            $discountCode->discount_amount = $request->discount_amount;
            $discountCode->type = $request->type;
            $discountCode->status = $request->status;
            $discountCode->max_uses = $request->max_uses;
            $discountCode->max_uses_user = $request->max_uses_user;
            $discountCode->min_amount = $request->min_amount;
            $discountCode->start_at = $request->start_at;
            $discountCode->expires_at = $request->expires_at;

            $discountCode->save();
            $message = 'Discount coupon added successfully.';
            session()->flash('success', $message);
            return response()->json([
                'status' => true,
                'message' => $message,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function edit(Request $request, $id) {
        $coupon = DiscountCoupon::find($id);

        if($coupon == null){
            session()->flash('errors', 'Record not found');
            return redirect()->route('coupons.index');
        }
        $data['coupon'] = $coupon;

        return view('admin.coupon.edit', $data);

    }

    public function update(Request $request, $id) {
        $discountCode = DiscountCoupon::find($id);

        if($discountCode == null){
            session()->flash('errors', 'Record not found.');
            return response()->json([
                'status' => true
            ]);
        }

        $validator = Validator::make($request->all(), [
            'code' => 'required|string',
            'name' => 'nullable|string',
            'discount_amount' => 'required|numeric',
            'type' => 'required',
            'start_at' => 'nullable|date_format:Y-m-d H:i:s'
        ]);

        if ($validator->passes()) {

            //starting date must not be less than current time
            if (!empty($request->start_at)) {
                $now = Carbon::now();
                $startAt = Carbon::createFromFormat('Y-m-d H:i:s', $request->start_at);
                if ($startAt->lte($now) == true) {
                    return response()->json([
                        'status' => false,
                        'errors' => ['start_at' => 'Start date cannot be less than the current date and time']
                    ]);
                }
            }

            //expiring date must not be less than start date
            if (!empty($request->start_at) && !empty($request->expires_at)) {
                $expiresAt = Carbon::createFromFormat('Y-m-d H:i:s', $request->expires_at);
                $startAt = Carbon::createFromFormat('Y-m-d H:i:s', $request->start_at);
                if ($expiresAt->gt($startAt) == false) {
                    return response()->json([
                        'status' => false,
                        'errors' => ['expires_at' => 'Expiring date cannot be less than the start date']
                    ]);
                }
            }

            $discountCode->code = $request->code;
            $discountCode->name = $request->name;
            $discountCode->description = $request->description;
            $discountCode->discount_amount = $request->discount_amount;
            $discountCode->type = $request->type;
            $discountCode->status = $request->status;
            $discountCode->max_uses = $request->max_uses;
            $discountCode->max_uses_user = $request->max_uses_user;
            $discountCode->min_amount = $request->min_amount;
            $discountCode->start_at = $request->start_at;
            $discountCode->expires_at = $request->expires_at;

            $discountCode->save();
            $message = 'Discount coupon updated successfully.';
            session()->flash('success', $message);
            return response()->json([
                'status' => true,
                'message' => $message,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function destroy(Request $request, $id) {
        $discountCode = DiscountCoupon::find($id);

        if ($discountCode == null) {
            session()->flash('errors', 'Record not found.');
            return response()->json([
                'status' => true
            ]);
        }
        $discountCode->delete();
        session()->flash('success', 'Record deleted successfully.');
        return response()->json([
            'status' => true
        ]);
    }
}
