@extends('admin.layout.app')

@section('content')
				<section class="content-header">					
					<div class="container-fluid my-2">
						{{-- @include('admin.message') --}}
      @if (Session::has('success'))
      <div class="alert alert-success">
       {{Session::get('success')}}
      </div>
      @endif
						<div class="row mb-2">
							<div class="col-sm-6">
								<h1>Create Shipping</h1>
							</div>
							<div class="col-sm-6 text-right">
								<a href="" class="btn btn-primary">Back</a>
							</div>
						</div>
					</div>
					<!-- /.container-fluid -->
				</section>
				<!-- Main content -->
			<section class="content">
					<!-- Default box -->
				<div class="container-fluid">
     <form action="" method="post" name="shippingForm" id="shippingForm">
						<div class="card">
							<div class="card-body">								
								<div class="row">
									<div class="col-md-6">
										<div class="mb-3">
											<label for="status">Select a State</label>
											<select name="state_id" class="form-control" id="state_id">
            <option value="">Select state</option>
            @if ($states->isNotEmpty())
            @foreach($states as $state)
            <option value="{{$state->id}}">{{$state->name}}</option>
            @endforeach
            @endif
           </select>
            <p></p>
										</div>
									</div>	
									<div class="col-md-6">
										<div class="mb-3">
											<label for="name">Amount</label>
											<input type="text" name="amount" id="amount" class="form-control" placeholder="Amount">
           <p></p>	
										</div>
									</div>								
								</div>
						<div class="text-right">
							<button type="submit" class="btn btn-primary">Create</button>
						</div>
							</div>							
						</div>
      
      <div class="card">
       <div class="card-body ">
        <div class="row">
         <div class="col-md-6">
          <table class="table table-striped">
           <thead>
            <tr>
            <th>ID</th>
            <th>State</th>
            <th>Amount</th>
            <th>Action</th>
           </tr>
           </thead>
           <tbody>
           @if ($shippingCharges->isNotEmpty())
           @foreach($shippingCharges as $shippingCharge)
            <tr>
             <td>{{$shippingCharge->id}}</td>
             <td>{{$shippingCharge->name}}</td>
             <td>&#8358;{{$shippingCharge->amount}}</td>
             <td class="d-flex items-center justify-between">
              <a href="{{route('shipping.edit', $shippingCharge->id)}}" class="btn btn-primary mr-2">Edit</a>
              <a href="javascript:void(0)" onclick="deleteShippingCharge({{$shippingCharge->id}})" class="btn btn-danger ml-1">Delete</a>
             </td>
            </tr>
           @endforeach
           @endif
           </tbody>
          </table>
         </div>
        </div>
       </div>
      </div>
     </form>
					</div>
					<!-- /.card -->
				</section>
@endsection
@section('customJs')
<script>
 $(document).ready(function(){
  $("#shippingForm").submit(function(event){
   event.preventDefault();
   var formData = $(this).serialize();
			$("button[type=submit]").prop('disabled', true);
			$.ajax({
				url: '{{route('store.shipping')}}',
				type: 'post',
				data: formData,
				dataType: 'json',
				success:function(response){
							$("button[type=submit]").prop('disabled', false);
       if(response['status'] == true){
								window.location.href="{{route('shipping.create')}}";
         $("#state_id").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback')
        .html("");
         $("#amount").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback')
        .html("");
       }else{
        var  errors = response['errors'];
       if(errors['state_id']){
        $("#state_id").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['state_id']);
       }else{
         $("#state_id").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback')
        .html("");
       }
        if(errors['amount']){
        $("#amount").addClass('is-invalid').siblings('p').addClass('invalid-feedback')
        .html(errors['amount']);
       }else{
        $("#amount").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
       }
       }
       
      },
      error:function(jqXHR, exception){
       console.log("something went wrong");
      }
    });
});
});


function deleteShippingCharge(id){
		var url = '{{route("delete.shipping", "ID")}}';
		var newUrl = url.replace("ID", id);
if(confirm("Are you sure you want to delete this record?")){
	$.ajax({
				url: newUrl,
				type: 'delete',
				data: {},
				dataType: 'json',
				headers:{
	'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
},
				success:function(response){
       if(response['status']){
								window.location.href="{{route('shipping.create')}}";
	}
}
});
}
}

</script>
@endsection