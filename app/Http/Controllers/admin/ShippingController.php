<?php

namespace App\Http\Controllers\admin;

use App\Models\State;
use App\Models\Country;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ShippingCharge;
use Illuminate\Support\Facades\Validator;

class ShippingController extends Controller
{
    public function create(){
        // $countries = Country::get();
        $states = State::get();
        $shippingCharges = ShippingCharge::
        select('shipping_charges.*', 'states.name')
        ->leftJoin('states', 'states.id', 'shipping_charges.state_id')->get();
        $data['states'] = $states;
        $data['shippingCharges'] = $shippingCharges;

        return view('admin.shipping.create', $data);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'state_id' => 'required|unique:shipping_charges',
            'amount' => 'required|numeric',
        ]);

        if ($validator->passes()){
            $shipping = new ShippingCharge;

            $shipping->state_id = $request->state_id;
            $shipping->amount = $request->amount;
            $shipping->save();

            session()->flash('success', 'Shipping added successfully.');
            return response()->json([
                'status' => true,
                // 'message' => 'Shipping added successfully.'
            ]);

        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }

    public function edit(Request $request, $id){
        $states = State::get();
        $shippingCharge = ShippingCharge::find($id);

        $data['states']= $states;
        $data['shippingCharge']= $shippingCharge;
        return view('admin.shipping.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $shipping = ShippingCharge::find($id);
        $validator = Validator::make($request->all(), [
            'state_id' => 'required|unique:shipping_charges,state_id, ' . $shipping->id . ',id',
            'amount' => 'required|numeric',
        ]);

        if ($shipping == null) {
            session()->flash('errors', 'shipping charge not found.');
            return response()->json([
                'status' => false,
                'errors' => "shipping charge not found.",
            ]);
        }

        if ($validator->passes()) {
            $shipping->state_id = $request->state_id;
            $shipping->amount = $request->amount;
            $shipping->save();

            session()->flash('success', 'Shipping updated successfully.');
            return response()->json([
                'status' => true,
                // 'message' => 'Shipping added successfully.'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }

    public function destroy($id){
        $shipping = ShippingCharge::find($id);
        if ($shipping == null){
            session()->flash('errors', 'shipping charge not found.');
            return response()->json([
                'status' => false,
                'errors' => "shipping charge not found.",
            ]);
        }
        else{
            $shipping->delete();
            session()->flash('success', 'Shipping charge deleted successfully.');
            return response()->json([
                'status' => true,
                'message' => "Shipping charge deleted successfully.",
            ]);
        }
    }
}
