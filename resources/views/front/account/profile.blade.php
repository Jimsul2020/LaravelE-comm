@extends('front.layout.app')

@section('content')
<section class="section-5 pt-3 pb-3 mb-3 bg-white">
    <div class="container">
        <div class="light-font">
            <ol class="breadcrumb primary-color mb-0">
                <li class="breadcrumb-item"><a class="white-text" href="#">My Account</a></li>
                <li class="breadcrumb-item">Settings</li>
            </ol>
        </div>
    </div>
</section>

<section class=" section-11 ">
    <div class="container  mt-5">
        <div class="row">
            <div class="col-md-12">
                @include('front.account.common.message')
            </div>
            <div class="col-md-3">
                @include('front.account.common.sidebar')
            </div>
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">
                        <h2 class="h5 mb-0 pt-2 pb-2">Personal Information</h2>
                    </div>
                    <div class="card-body p-4">
                        <form action="" name="profileForm" id="profileForm">
                            <div class="row">
                                <div class="mb-3">
                                    <label for="name">Name</label>
                                    <input type="text" value="{{$user->name}}" name="name" id="name" placeholder="Enter Your Name" class="form-control">
                                    <p></p>
                                </div>
                                <div class="mb-3">
                                    <label for="email">Email</label>
                                    <input type="text" value="{{$user->email}}" name="email" id="email" placeholder="Enter Your Email" class="form-control">
                                    <p></p>
                                </div>
                                <div class="mb-3">
                                    <label for="phone">Phone</label>
                                    <input type="text" value="{{$user->phone}}" name="phone" id="phone" placeholder="Enter Your Phone" class="form-control">
                                    <p></p>
                                </div>

                                <div class="d-flex">
                                    <button class="btn btn-dark">Update</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card mt-5">
                    <div class="card-header">
                        <h2 class="h5 mb-0 pt-2 pb-2">Address Information</h2>
                    </div>
                    <div class="card-body p-4">
                        <form action="" name="addressForm" id="addressForm">
                            <div class="row">
                                <div class="mb-3 col-md-6">
                                    <label for="name">Firstname</label>
                                    <input type="text" value="{{(!empty($address)) ? $address->first_name : ''}}" name="first_name" id="first_name" placeholder="Enter Your Firstname" class="form-control">
                                    <p></p>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="name">Lastname</label>
                                    <input type="text" value="{{(!empty($address)) ? $address->last_name : ''}}" name="last_name" id="last_name" placeholder="Enter Your Lastname" class="form-control">
                                    <p></p>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="email">Email</label>
                                    <input type="text" value="{{(!empty($address)) ? $address->email : ''}}" name="email" id="email" placeholder="Enter Your Email" class="form-control">
                                    <p></p>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="phone">Mobile</label>
                                    <input type="text" value="{{(!empty($address)) ? $address->mobile : ''}}" name="mobile" id="mobile" placeholder="Enter Your Phone" class="form-control">
                                    <p></p>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="">State</label>
                                    <select name="state_id" id="state_id" class="form-control">
                                        <option value="">Select a State</option>
                                        @if($states->isNotEmpty())
                                        @foreach($states as $state)
                                        <option {{(!empty($address) && $address->state_id == $state->id) ? 'selected' : ''}} value="{{$state->id}}">{{$state->name}}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                    <p></p>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="">Lga</label>
                                    <select name="state_id" id="state_id" class="form-control">
                                        <option value="">Select a Lga</option>
                                        @if($lgas->isNotEmpty())
                                        @foreach($lgas as $lga)
                                        <option {{(!empty($address) && $address->lga_id == $lga->id) ? 'selected' : ''}} value="{{$lga->id}}">{{$lga->lga}}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                    <p></p>
                                </div>
                                <div class="mb-3">
                                    <label for="">Address</label>
                                    <textarea name="address" id="address" placeholder="Enter Your Address" class="form-control">{{(!empty($address)) ? $address->address : ''}}</textarea>
                                    <p></p>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="">Apartment</label>
                                    <input type="text" value="{{(!empty($address)) ? $address->appartment : ''}}" name="apartment" id="apartment" placeholder="Enter Your Apartment" class="form-control">
                                    <p></p>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="">City</label>
                                    <input type="text" value="{{(!empty($address)) ? $address->city : ''}}" name="city" id="city" placeholder="Enter Your City" class="form-control">
                                    <p></p>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="">Zip</label>
                                    <input type="text" value="{{(!empty($address)) ? $address->zip : ''}}" name="zip" id="zip" placeholder="Enter Your zip code" class="form-control">
                                    <p></p>
                                </div>


                                <div class="d-flex">
                                    <button class="btn btn-dark">Update</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('customJs')
<script>
    $("#profileForm").submit(function(event) {
        event.preventDefault();
        $.ajax({
            url: '{{route("account.updateProfile")}}',
            type: 'post',
            data: $(this).serializeArray(),
            dataType: 'json',
            success: function(response) {
                if (response == true) {
                    $("#name").removeClass('is-invalid').siblings('p').html('').removeClass('invalid-feedback');
                    $("#email").removeClass('is-invalid').siblings('p').html('').removeClass('invalid-feedback');
                    $("#phone").removeClass('is-invalid').siblings('p').html('').removeClass('invalid-feedback');

                    window.location.href = '{{route("account.profile")}}';

                } else {
                    var errors = response.errors;
                    if (errors.name) {
                        $("#name").addClass('is-invalid').siblings('p').html(errors.name).addClass('invalid-feedback');
                    } else {
                        $('#name').removeClass('is-invalid').siblings('p').html(errors.name);
                    }

                    if (errors.email) {
                        $("#email").addClass('is-invalid').siblings('p').html(errors.email).addClass('invalid-feedback');
                    } else {
                        $('#email').removeClass('is-invalid').siblings('p').html(errors.email);
                    }

                    if (errors.phone) {
                        $("#phone").addClass('is-invalid').siblings('p').html(errors.phone).addClass('invalid-feedback');
                    } else {
                        $('#phone').removeClass('is-invalid').siblings('p').html(errors.phone);
                    }

                }
            }
        });

    });

    //address update

    $("#addressForm").submit(function(event) {
        event.preventDefault();
        $.ajax({
            url: '{{route("account.updateAddress")}}',
            type: 'post',
            data: $(this).serializeArray(),
            dataType: 'json',
            success: function(response) {
                if (response == true) {
                    $("#first_name").removeClass('is-invalid').siblings('p').html('').removeClass('invalid-feedback');
                    $("#last_name").removeClass('is-invalid').siblings('p').html('').removeClass('invalid-feedback');
                    $("#email").removeClass('is-invalid').siblings('p').html('').removeClass('invalid-feedback');
                    $("#mobile").removeClass('is-invalid').siblings('p').html('').removeClass('invalid-feedback');
                    $("#city").removeClass('is-invalid').siblings('p').html('').removeClass('invalid-feedback');
                    $("#address").removeClass('is-invalid').siblings('p').html('').removeClass('invalid-feedback');
                    $("#apartment").removeClass('is-invalid').siblings('p').html('').removeClass('invalid-feedback');
                    $("#zip").removeClass('is-invalid').siblings('p').html('').removeClass('invalid-feedback');
                    $("#state_id").removeClass('is-invalid').siblings('p').html('').removeClass('invalid-feedback');

                    window.location.href = '{{route("account.profile")}}';

                } else {
                    var errors = response.errors;
                    if (errors.first_name) {
                        $("#first_name").addClass('is-invalid').siblings('p').html(errors.first_name).addClass('invalid-feedback');
                    } else {
                        $('#first_name').removeClass('is-invalid').siblings('p').html(errors.first_name);
                    }
                    if (errors.last_name) {
                        $("#last_name").addClass('is-invalid').siblings('p').html(errors.last_name).addClass('invalid-feedback');
                    } else {
                        $('#last_name').removeClass('is-invalid').siblings('p').html(errors.last_name);
                    }

                    if (errors.email) {
                        $("#email").addClass('is-invalid').siblings('p').html(errors.email).addClass('invalid-feedback');
                    } else {
                        $('#email').removeClass('is-invalid').siblings('p').html(errors.email);
                    }

                    if (errors.mobile) {
                        $("#mobile").addClass('is-invalid').siblings('p').html(errors.mobile).addClass('invalid-feedback');
                    } else {
                        $('#mobile').removeClass('is-invalid').siblings('p').html(errors.mobile);
                    }

                    if (errors.city) {
                        $("#city").addClass('is-invalid').siblings('p').html(errors.city).addClass('invalid-feedback');
                    } else {
                        $('#city').removeClass('is-invalid').siblings('p').html(errors.city);
                    }

                    if (errors.address) {
                        $("#address").addClass('is-invalid').siblings('p').html(errors.address).addClass('invalid-feedback');
                    } else {
                        $('#address').removeClass('is-invalid').siblings('p').html(errors.address);
                    }

                    if (errors.apartment) {
                        $("#apartment").addClass('is-invalid').siblings('p').html(errors.apartment).addClass('invalid-feedback');
                    } else {
                        $('#apartment').removeClass('is-invalid').siblings('p').html(errors.apartment);
                    }

                    if (errors.zip) {
                        $("#zip").addClass('is-invalid').siblings('p').html(errors.zip).addClass('invalid-feedback');
                    } else {
                        $('#zip').removeClass('is-invalid').siblings('p').html(errors.zip);
                    }

                    if (errors.state_id) {
                        $("#state_id").addClass('is-invalid').siblings('p').html(errors.state_id).addClass('invalid-feedback');
                    } else {
                        $('#state_id').removeClass('is-invalid').siblings('p').html(errors.state_id);
                    }

                }
            }
        });

    });
</script>
@endsection