@extends('front.layout.app')

@section('content')
    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="{{route('front.home')}}">Home</a></li>
                    <li class="breadcrumb-item"><a class="white-text" href="{{route('front.shop')}}">Shop</a></li>
                    <li class="breadcrumb-item">Checkout</li>
                </ol>
            </div>
        </div>
    </section>

    <section class="section-9 pt-4">
        <div class="container">
         <form action="" id="orderForm" name="orderForm" method="post">
          @csrf
            <div class="row">
                <div class="col-md-8">
                    <div class="sub-title">
                        <h2>Shipping Address</h2>
                    </div>
                    <div class="card shadow-lg border-0">
                        <div class="card-body checkout-form">
                            <div class="row">
                                
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <input type="text" name="first_name" id="first_name" class="form-control" placeholder="First Name" value="{{(!empty($customerAddress)) ? $customerAddress->first_name : ''}}">
                                         <p></p>
                                    </div>            
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <input type="text" name="last_name" id="last_name" class="form-control" placeholder="Last Name" value="{{(!empty($customerAddress)) ? $customerAddress->last_name : ''}}">
                                         <p></p>
                                    </div>            
                                </div>
                                
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <input type="text" name="email" id="email" class="form-control" placeholder="Email" value="{{(!empty($customerAddress)) ? $customerAddress->email : ''}}">
                                         <p></p>
                                    </div>            
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <select name="country_id" id="country_id" class="form-control">
                                         <option value="">Select a country</option>
                                            @if ($countries->isNotEmpty())
                                             @foreach($countries as $country)
                                               <option {{(!empty($customerAddress) && $customerAddress->country_id == $country->id) ? 'selected' : ''}} value="{{$country->id}}">{{$country->name}}</option>
                                            <p></p>
                                             @endforeach
                                            @endif
                                        </select>
                                    </div>            
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <select name="state_id" id="state_id" class="form-control">
                                         <option value="">Select a State</option>
                                            @if ($states->isNotEmpty())
                                             @foreach($states as $state)
                                               <option {{(!empty($customerAddress) && $customerAddress->state_id == $state->id) ? 'selected' : ''}} value="{{$state->id}}">{{$state->name}}</option>
                                            <p></p>
                                             @endforeach
                                            @endif
                                        </select>
                                    </div>            
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <select name="lga_id" id="lga_id" class="form-control" data-lga-id="{{ $customerAddress->lga_id ?? '' }}">
                                         <option value="">Select your Lga</option>
                                        <p></p>
                                        </select>
                                    </div>            
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <input type="text" name="city" id="city" class="form-control" placeholder="City" value="{{(!empty($customerAddress)) ? $customerAddress->city : ''}}">
                                        <p></p>
                                    </div>            
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <input type="text" name="zip" id="zip" class="form-control" placeholder="Zip" value="{{(!empty($customerAddress)) ? $customerAddress->zip : ''}}">
                                        <p></p>
                                    </div>            
                                </div>

                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <textarea name="address" id="address" cols="30" rows="3" placeholder="Address" class="form-control">{{(!empty($customerAddress)) ? $customerAddress->address : ''}}</textarea>
                                    <p></p>  
                                    </div>          
                                </div>

                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <input type="text" name="appartment" id="appartment" class="form-control" placeholder="Apartment, suite, unit, etc. (optional)" value="{{(!empty($customerAddress)) ? $customerAddress->apartment : ''}}">
                                    </div>            
                                </div>

                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <input type="text" id="mobile" name="mobile" id="mobile" class="form-control" placeholder="Mobile No." value="{{(!empty($customerAddress)) ? $customerAddress->mobile : ''}}">
                                    <p></p> 
                                    </div>           
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <textarea name="order_notes" id="order_notes" cols="30" rows="2" placeholder="Order Notes (optional)" class="form-control">{{(!empty($customerAddress)) ? $customerAddress->notes : ''}}</textarea>
                                    </div>            
                                </div>

                            </div>
                        </div>
                    </div>    
                </div>
                <div class="col-md-4">
                    <div class="sub-title">
                        <h2>Order Summery</h3>
                    </div>                    
                    <div class="card cart-summery">
                        <div class="card-body">
                         @foreach(Cart::content() as $item)
                            <div class="d-flex justify-content-between pb-2">
                                <div class="h6">{{$item->name}} X {{$item->qty}}</div>
                                <div class="h6">&#8358;{{$item->price * $item->qty}}</div>
                            </div>
                         @endforeach
                            <div class="d-flex justify-content-between summery-end">
                                <div class="h6"><strong>Subtotal:</strong></div>
                                <div class="h6"><strong>&#8358;{{Cart::subtotal()}}</strong></div>
                            </div>
                            
                            <div class="d-flex justify-content-between summery-end">
                                <div class="h6"><strong>Discount:</strong></div>
                                <div class="h6"><strong id="discount_value">&#8358;{{$discount}}</strong></div>
                            </div>
                            <div class="d-flex justify-content-between mt-2">
                                <div class="h6"><strong>Shipping:</strong></div>
                                <div class="h6">&#8358;<strong id="shippingAmount">{{number_format($totalShippingCharge, 2)}}</strong></div>
                            </div>
                            <div class="d-flex justify-content-between mt-2 summery-end">
                                <div class="h5"><strong>Total:</strong></div>
                                <div class="h5">&#8358;<strong id="grandTotal">{{number_format($grandTotal,2)}}</strong></div>
                            </div>                            
                        </div>
                    </div>   
                    <div class="input-group apply-coupan mt-4">
                        <input type="text" placeholder="Coupon Code" class="form-control" name="discoutn_code" id="discount_code">
                        <button class="btn btn-dark" type="button" id="apply_discount">Apply Coupon</button>
                    </div> 
                    <div id="discount-row-wrapper">
                        @if (Session::has('code'))
                    <div class="mt-4" id="discount-row">
                        <strong>{{Session::get('code')->code}}</strong>
                        <a href="" class="btn btn-sm btn-danger" id="remove_discount"><i class="fa fa-times"></i></a>
                    </div>
                    @endif
                    </div>
                    <div class="card payment-form ">                        
                        <h3 class="card-title h5 mb-3">Payment Method</h3>
                        <div class="card-body p-0">
                            {{-- <div class="mb-3">
                                <label for="card_number" class="mb-2">Card Number</label>
                                <input type="text" name="card_number" id="card_number" placeholder="Valid Card Number" class="form-control">
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="expiry_date" class="mb-2">Expiry Date</label>
                                    <input type="text" name="expiry_date" id="expiry_date" placeholder="MM/YYYY" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label for="expiry_date" class="mb-2">CVV Code</label>
                                    <input type="text" name="expiry_date" id="expiry_date" placeholder="123" class="form-control">
                                </div>
                            </div> --}}
                            <div class="pt-4">
                                {{-- <a href="#" onclick="(confirm('Are you sure you want to proceed to payment?'))" class="btn-dark btn btn-block w-100">Pay Now With >Paystack</a> --}}
                                <button type="submit" class="btn-dark btn btn-block w-100">Pay With Paystack</button>
                            </div>
                        </div>                        
                    </div>

                          
                    <!-- CREDIT CARD FORM ENDS HERE -->
                    
                </div>
            </div>
         </form>
        </div>
    </section>
@endsection

@section('customJs')
<script>

$(document).ready(function(){
    function loadLgas(stateId, selectedLgaId = null) {
        $.ajax({
            url: '{{ route("lga.state") }}',
            type: 'get',
            data: { state_id: stateId },
            dataType: 'json',
            success: function(response) {
                $("#lga_id").find("option").not(":first").remove(); // Clear existing options except for the first one
                if(response.status) {
                    $.each(response.lgas, function(key, item) {
                        $("#lga_id").append(`<option value='${item.id}' ${selectedLgaId == item.id ? 'selected' : ''}>${item.name}</option>`);
                    });
                } else {
                    console.log('No LGAs found for the selected state');
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error("Error:", errorThrown);
            }
        });
    }

    // Trigger when state_id changes
    $("#state_id").change(function() {
        var stateId = $(this).val(); 
        loadLgas(stateId);
    });

    // On page load, if customerAddress exists
    @if(!empty($customerAddress) && $customerAddress->state_id)
        loadLgas($("#state_id").val(), $("#lga_id").data("lga-id"));
    @endif
});




   $('#orderForm').submit(function(event){
    event.preventDefault();
  $("button[type='submit']").prop('disabled', true);
    $.ajax({
     url: "{{route('front.processCheckout')}}",
     type: 'post',
     data: $(this).serializeArray(),
     dataType: 'json',
   success: function(response){
  $("button[type='submit']").prop('disabled', false);
    var errors = response.errors;
    if (response.status == false)
    {
       if(errors.first_name){
        $('#first_name').siblings("p").addClass('invalid-feedback').html(errors.first_name);
        $('#first_name').addClass('is-invalid');
       }else{
        $('#first_name').siblings("p").removeClass('invalid-feedback').html('');
        $('#first_name').removeClass('is-invalid');
    }

   if(errors.last_name){
     $('#last_name').siblings("p").addClass('invalid-feedback').html(errors.last_name);
     $('#last_name').addClass('is-invalid');
    }else{
     $('#last_name').siblings("p").removeClass('invalid-feedback').html('');
     $('#last_name').removeClass('is-invalid');
    }
   if(errors.email){
     $('#email').siblings("p").addClass('invalid-feedback').html(errors.email);
     $('#email').addClass('is-invalid');
    }else{
     $('#email').siblings("p").removeClass('invalid-feedback').html('');
     $('#email').removeClass('is-invalid');
    }

    if(errors.country_id){
     $('#country_id').siblings("p").addClass('invalid-feedback').html(errors.country_id);
     $('#country_id').addClass('is-invalid');
    }else{
     $('#country_id').siblings("p").removeClass('invalid-feedback').html('');
     $('#country_id').removeClass('is-invalid');
    }

   if(errors.address){
     $('#address').siblings("p").addClass('invalid-feedback').html(errors.address);
     $('#address').addClass('is-invalid');
    }else{
     $('#address').siblings("p").removeClass('invalid-feedback').html('');
     $('#address').removeClass('is-invalid');
    }

    if(errors.state_id){
     $('#state_id').siblings("p").addClass('invalid-feedback').html(errors.state_id);
     $('#state_id').addClass('is-invalid');
    }else{
     $('#state_id').siblings("p").removeClass('invalid-feedback').html('');
     $('#state_id').removeClass('is-invalid');
    }

   if(errors.city){
     $('#city').siblings("p").addClass('invalid-feedback').html(errors.city);
     $('#city').addClass('is-invalid');
    }else{
     $('#city').siblings("p").removeClass('invalid-feedback').html('');
     $('#city').removeClass('is-invalid');
    }

    if(errors.lga_id){
     $('#lga_id').siblings("p").addClass('invalid-feedback').html(errors.lga_id);
     $('#lga_id').addClass('is-invalid');
    }else{
     $('#lga_id').siblings("p").removeClass('invalid-feedback').html('');
     $('#lga_id').removeClass('is-invalid');
    }

    if(errors.zip){
     $('#zip').siblings("p").addClass('invalid-feedback').html(errors.zip);
     $('#zip').addClass('is-invalid');
    }else{
     $('#zip').siblings("p").removeClass('invalid-feedback').html('');
     $('#zip').removeClass('is-invalid');
    }

     if(errors.mobile){
     $('#mobile').siblings("p").addClass('invalid-feedback').html(errors.mobile);
     $('#mobile').addClass('is-invalid');
    }else{
     $('#mobile').siblings("p").removeClass('invalid-feedback').html('');
     $('#mobile').removeClass('is-invalid');
    }

    } else{

       window.location.href = "{{url('/thanks/')}}/"+ response.orderId;
    }
   },
   error:function(JQXHR, exception){
    console.log("something went wrong");
   }
  });
 });

 $('#state_id').change(function(){
    $.ajax({
        url:'{{route("front.orderSummary")}}',
        type:'post',
        data:{state_id: $(this).val()},
        dataType: 'json',
        success: function(response){
            if (response.status == true){
                $('#shippingAmount').html(response.totalShippingCharge)
                $('#grandTotal').html(response.grandTotal)
            }
        }
    });
 });

$(document).ready(function(){
$('#apply_discount').click(function(){
    $.ajax({
        url:'{{route("front.applyDiscount")}}',
        type:'post',
        data:{code: $('#discount_code').val(), state_id: $('#state_id').val()},
        dataType: 'json',
        success: function(response){
            if (response.status == true){
                $('#discount_value').html(response.discount);
                $('#grandTotal').html(response.grandTotal);
                $('#shippingAmount').html(response.totalShippingCharge);
                $('#discount-row-wrapper').html(response.discountString);
            }
        }
    });
});

});

//remove discount code
$('body').on('click', "#remove_discount", function(e){
    e.preventDefault();
    $.ajax({
        url:'{{route("front.removeCoupon")}}',
        type:'post',
        data:{state_id: $('#state_id').val()},
        dataType: 'json',
        success: function(response){
            if (response.status == true){
                $('#discount_value').html(response.discount);
                $('#grandTotal').html(response.grandTotal);
                $('#shippingAmount').html(response.totalShippingCharge);
                $('#discount-row').html('');
                $('#discount_code').val('');
            }
        }
    });
});

</script>
@endsection
