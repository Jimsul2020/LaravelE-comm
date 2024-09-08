@extends('admin.layout.app')

@section('content')
				<section class="content-header">					
					<div class="container-fluid my-2">
						@include('admin.message')
						<div class="row mb-2">
							<div class="col-sm-6">
								<h1>Create Coupon</h1>
							</div>
							<div class="col-sm-6 text-right">
								<a href="{{route('coupons.index')}}" class="btn btn-primary">Back</a>
							</div>
						</div>
					</div>
					<!-- /.container-fluid -->
				</section>
				<!-- Main content -->
			<section class="content">
					<!-- Default box -->
				<div class="container-fluid">
     <form action="" method="post" name="discountForm" id="discountForm">
						<div class="card">
							<div class="card-body">								
								<div class="row">
									<div class="col-md-6">
										<div class="mb-3">
											<label for="name">Coupon code</label>
											<input type="text" name="code" id="code" class="form-control" placeholder="Coupon code">
           <p></p>	
										</div>
									</div>
									<div class="col-md-6">
										<div class="mb-3">
											<label for="name">Coupon Name</label>
											<input type="text" name="name" id="name" class="form-control" placeholder="Coupon code name">
           <p></p>	
										</div>
									</div>
									<div class="col-md-6">
										<div class="mb-3">
											<label for="">max_uses</label>
           <input type="number" name="max_uses" id="max_uses" class="form-control" placeholder="max uses">
           <p></p>	
										</div>
									</div>
									<div class="col-md-6">
										<div class="mb-3">
											<label for="">max_uses_user</label>
           <input type="number" name="max_uses_user" id="max_uses_user" class="form-control" placeholder="max uses users">
           <p></p>	
										</div>
									</div>
									<div class="col-md-6">
										<div class="mb-3">
											<label for="">Discount Amount</label>
           <input type="number" name="discount_amount" id="discount_amount" class="form-control" placeholder="Discount Amount">
           <p></p>	
										</div>
									</div>
									<div class="col-md-6">
										<div class="mb-3">
											<label for="">Mininmum Amount</label>
           <input type="number" name="min_amount" id="min_amount" class="form-control" placeholder="Minimum Amount">
           <p></p>	
										</div>
									</div>
									<div class="col-md-6">
										<div class="mb-3">
											<label for="status">Status</label>
											<select name="status" class="form-control" id="status">
            <option value="1">Active</option>
            <option value="0">Block</option>
           </select>
										</div>
									</div>	
									<div class="col-md-6">
										<div class="mb-3">
											<label for="type">Types</label>
											<select name="type" class="form-control" id="type">
            <option value="percent">Percent</option>
            <option value="fixed">Fixed</option>
           </select>
										</div>
									</div>			
									<div class="col-md-6">
										<div class="mb-3">
											<label for="">Start At</label>
           <input autocomplete="off" type="text" name="start_at" id="start_at" class="form-control" placeholder="Start At">
           <p></p>	
										</div>
									</div>		
									<div class="col-md-6">
										<div class="mb-3">
											<label for="">Expires At</label>
           <input autocomplete="off" type="text" name="expires_at" id="expires_at" class="form-control" placeholder="Expire At">
           <p></p>	
										</div>
									</div>	
									<div class="col-md-6">
										<div class="mb-3">
											<label for="">Description</label>
											<textarea type="text" name="description" id="description" cols="30" rows="3" class="form-control" placeholder="Description">	
           </textarea>
           <p></p>
										</div>
									</div>						
								</div>
							</div>							
						</div>
						<div class="pb-5 pt-3">
							<button type="submit" class="btn btn-primary">Create</button>
						</div>
     </form>
					</div>
					<!-- /.card -->
				</section>
@endsection
@section('customJs')
<script>
$(document).ready(function(){
  $('#start_at').datetimepicker({
   // options here
  format:'Y-m-d H:i:s',
  });

    $('#expires_at').datetimepicker({
   // options here
  format:'Y-m-d H:i:s',
  });
});

 $(document).ready(function(){
  $("#discountForm").submit(function(event){
   event.preventDefault();
   var formData = $(this).serialize();
			// $("button[type=submit]").prop('disabled', true);
			$.ajax({
				url: '{{route('coupon.store')}}',
				type: 'post',
				data: formData,
				dataType: 'json',
				success:function(response){
							// $("button[type=submit]").prop('disabled', false);
       if(response['status'] == true){
								window.location.href="{{route('coupons.index')}}";
         $("#code").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback')
        .html("");
         $("#discount_amount").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback')
        .html("");
        
         $("#start_at").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback')
        .html("");
         $("#expires_at").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback')
        .html("");
       }else{
        var  errors = response['errors'];
        if(errors['code']){
        $("#code").addClass('is-invalid').siblings('p').addClass('invalid-feedback')
        .html(errors['code']);
       }else{
        $("#code").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
       }
       
        if(errors['discount_amount']){
        $("#discount_amount").addClass('is-invalid').siblings('p').addClass('invalid-feedback')
        .html(errors['discount_amount']);
       }else{
        $("#discount_amount").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
       }
       if(errors['start_at']){
        $("#start_at").addClass('is-invalid').siblings('p').addClass('invalid-feedback')
        .html(errors['start_at']);
       }else{
        $("#start_at").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
       }
       if(errors['expires_at']){
        $("#expires_at").addClass('is-invalid').siblings('p').addClass('invalid-feedback')
        .html(errors['expires_at']);
       }else{
        $("#expires_at").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
       }
       }
       
      },
      error:function(jqXHR, exception){
       console.log("something went wrong");
      }
    });
});
});


</script>
@endsection