@extends('admin.layout.app')

@section('content')
				<section class="content-header">					
					<div class="container-fluid my-2">
						@include('admin.message')
						<div class="row mb-2">
							<div class="col-sm-6">
								<h1>Edit Category</h1>
							</div>
							<div class="col-sm-6 text-right">
								<a href="{{route('view.categories')}}" class="btn btn-primary">Back</a>
							</div>
						</div>
					</div>
					<!-- /.container-fluid -->
				</section>
				<!-- Main content -->
				<section class="content">
					<!-- Default box -->
				<div class="container-fluid">
     <form action="" method="post" name="categoryForm" id="categoryForm">
						<div class="card">
							<div class="card-body">								
								<div class="row">
									<div class="col-md-6">
										<div class="mb-3">
											<label for="name">Name</label>
											<input type="text" name="name" id="name" class="form-control" placeholder="Name" value="{{$category->name}}">
           <p></p>	
										</div>
									</div>
									<div class="col-md-6">
										<div class="mb-3">
											<label for="slug">Slug</label>
											<input type="text" readonly name="slug" id="slug" class="form-control" placeholder="Slug" value="{{$category->name}}">	
           <p></p>
										</div>
									</div>	
									<div class="col-md-6">
										<div class="mb-3">
											<input type="hidden" id="image_id" name="image_id" value="">
											<label for="image">Image</label>
											<div class="dropzone dz-clickable" id="image">
												<div class="dz-message needsclick">
													<br>Drop files here or click to upload.<br><br>
												</div>
											</div>
										</div>
										@if(!empty($category->image))
										<div>
											<img width="250px" src="{{asset('uploads/category/thumb/'.$category->image)}}" alt="">
										</div>
										@endif
									</div>
									<div class="col-md-6">
										<div class="mb-3">
											<label for="status">Status</label>
											<select name="status" class="form-control" id="status">
            <option {{($category->status == 1)? 'selected': ''}} value="1">Active</option>
            <option {{($category->status == 0)? 'selected': ''}} value="0">Block</option>
           </select>
										</div>
									</div>									
								</div>
							</div>							
						</div>
						<div class="pb-5 pt-3">
							<button type="submit" class="btn btn-primary">Update</button>
						</div>
     </form>
					</div>
					<!-- /.card -->
				</section>
@endsection
@section('customJs')
<script>
 $(document).ready(function(){
  $("#categoryForm").submit(function(event){
   event.preventDefault();
   var formData = $(this).serialize();
			$("button[type=submit]").prop('disabled', true);
			$.ajax({
				url: '{{route("update.category", $category->id)}}',
				type: 'put',
				data: formData,
				dataType: 'json',
				success:function(response){
							$("button[type=submit]").prop('disabled', false);
       if(response['status'] == true){
								window.location.href="{{route('view.categories')}}";
         $("#name").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback')
        .html("");
         $("#slug").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback')
        .html("");
       }else{
        var  errors = response['errors'];
       if(errors['name']){
        $("#name").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['name']);
       }else{
         $("#name").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback')
        .html("");
       }
        if(errors['slug']){
        $("#slug").addClass('is-invalid').siblings('p').addClass('invalid-feedback')
        .html(errors['slug']);
       }else{
        $("#slug").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
       }
       }
       
      },
      error:function(jqXHR, exception){
       console.log("something went wrong");
      }
    });
});
});

$("#name").change(function(){
	element = $(this);
							$("button[type=submit]").prop('disabled', true);
							$.ajax({
								url: '{{route("getslug")}}',
								type: 'get',
								data: {title: element.val()},
								dataType: 'json',
								success:function(response){
							$("button[type=submit]").prop('disabled', false);
							if(response["status"] == true){
								$("#slug").val(response["slug"]);
							}
						}
					});
});

Dropzone.autoDiscover = false;
const dropzone = $("#image").dropzone({
	init: function() {
		this.on('addedfile', function(file){
						if(this.files.length > 1){
							this.removeFile(this.files[0]);
		}
});
},
url: "{{route('temp-images.create')}}",
maxFiles: 1,
paramName: 'image',
addRemoveLinks: true,
acceptedFiles: "images/jpeg,image/png,image/gif",
headers:{
	'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
},
success:function(file, response){
	$("#image_id").val(response.image_id);
	//console.log(response);
}
});
</script>
@endsection