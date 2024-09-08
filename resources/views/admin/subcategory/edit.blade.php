@extends('admin.layout.app')

@section('content')
				<section class="content-header">					
					<div class="container-fluid my-2">
						<div class="row mb-2">
							<div class="col-sm-6">
								<h1>Edit Sub Category</h1>
							</div>
							<div class="col-sm-6 text-right">
								<a href="{{route('view.subCategories')}}" class="btn btn-primary">Back</a>
							</div>
						</div>
					</div>
					<!-- /.container-fluid -->
				</section>
				<!-- Main content -->
				<section class="content">
					<!-- Default box -->
					<div class="container-fluid">
      <form action="" id="subCategoryForm" name="subCategoryForm">
						<div class="card">
							<div class="card-body">								
								<div class="row">
         <div class="col-md-12">
										<div class="mb-3">
											<label for="name">Category</label>
           @if(!empty($category))
											<select name="category_id" id="category_id" class="form-control">
            <option value="">--Select--</option>
            @foreach($category as $cat)
             <option @if($subcategory->category_id == $cat->id) selected @endif value="{{$cat->id}}">{{$cat->name}}</option>
             @endforeach
           </select>
           @endif
           <p></p>
										</div>
									</div>
									<div class="col-md-6">
										<div class="mb-3">
											<label for="name">Name</label>
											<input type="text" name="name" id="name" class="form-control" placeholder="Name" value="{{$subcategory->name}}">	
           <p></p>
										</div>
									</div>
									<div class="col-md-6">
										<div class="mb-3">
											<label for="slug">Slug</label>
											<input type="text" readonly name="slug" id="slug" class="form-control" placeholder="Slug" value="{{$subcategory->slug}}">	
           <p></p>
										</div>
									</div>
									<div class="col-md-6">
										<div class="mb-3">
											<label for="status">Status</label>
											<select name="status" class="form-control" id="status">
            <option {{($subcategory->status == 1)? 'selected': ''}} value="1">Active</option>
            <option {{($subcategory->status == 0)? 'selected': ''}} value="0">Block</option>
           </select>
										</div>
									</div>
									
									<div class="col-md-6">
										<div class="mb-3">
											<label for="showHome">Show Home</label>
											<select name="showHome" class="form-control" id="showHome">
            <option {{($subcategory->showHome == 'Yes')? 'selected': ''}} value="Yes">Yes</option>
            <option {{($subcategory->showHome == 'No')? 'selected': ''}} value="No">No</option>
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
  $("#subCategoryForm").submit(function(event){
   event.preventDefault();
   var formData = $(this).serialize();
			$("button[type=submit]").prop('disabled', true);
			$.ajax({
				url: '{{route("update.subCategory", $subcategory->id)}}',
				type: 'put',
				data: formData,
				dataType: 'json',
				success:function(response){
							$("button[type=submit]").prop('disabled', false);
       if(response['status'] == true){
								window.location.href="{{route('view.subCategories')}}";
         $("#name").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback')
        .html("");
         $("#slug").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback')
        .html("");
         $("#category_id").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback')
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
        if(errors['category_id']){
        $("#category_id").addClass('is-invalid').siblings('p').addClass('invalid-feedback')
        .html(errors['category_id']);
       }else{
        $("#category_id").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
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
</script>
@endsection