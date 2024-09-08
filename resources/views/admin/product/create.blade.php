@extends('admin.layout.app')

@section('content')
				<section class="content-header">					
					<div class="container-fluid my-2">
						@include('admin.message')
						<div class="row mb-2">
							<div class="col-sm-6">
								<h1>Create Product</h1>
							</div>
							<div class="col-sm-6 text-right">
								<a href="{{route('view.products')}}" class="btn btn-primary">Back</a>
							</div>
						</div>
					</div>
					<!-- /.container-fluid -->
				</section>
				<!-- Main content -->
			<section class="content">
					<!-- Default box -->
				<div class="container-fluid">
     <form action="" method="post" name="productForm" id="productForm">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="card mb-3">
                                    <div class="card-body">								
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="title">Title</label>
                                                    <input type="text" name="title" id="title" class="form-control" placeholder="Title" />
                                                    <p class="error"></p>	
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="slug">Slug</label>
                                                    <input type="text" name="slug" id="slug" class="form-control" placeholder="Slug" />
                                                    <p class="error"></p>	
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="description">Short Description</label>
                                                    <textarea name="short_description" id="short_description" cols="30" rows="10" class="summernote" placeholder="Short Description"></textarea>
                                                </div>
                                            </div>  
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="description">Description</label>
                                                    <textarea name="description" id="description" cols="30" rows="10" class="summernote" placeholder="Description"></textarea>
                                                </div>
                                            </div> 
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="description">Shipping Returns</label>
                                                    <textarea name="shipping_returns" id="shipping_returns" cols="30" rows="10" class="summernote" placeholder=""></textarea>
                                                </div>
                                            </div>                                            
                                        </div>
                                    </div>	                                                                      
                                </div>
                                <div class="card mb-3">
                                    <div class="card-body">
											{{-- <input type="hidden" id="image_id" name="image_id" value=""> --}}
                                        <h2 class="h4 mb-3">Media</h2>								
                                        <div id="image" name="image" class="dropzone dz-clickable">
                                            <div class="dz-message needsclick">    
                                                <br>Drop files here or click to upload.<br><br>                                            
                                            </div>
                                        </div>
                                    </div>     
                                </div>  
                                    <div class="row" id="product-gallery">

                                    </div>
                                <div class="card mb-3">
                                    <div class="card-body">
                                    <h2 class="h4 mb-3">Pricing</h2>								
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="price">Price</label>
                                                    <input type="text" name="price" id="price" class="form-control" placeholder="Price" />
                                                    <p class="error"></p>	
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="compare_price">Compare at Price</label>
                                                    <input type="text" name="compare_price" id="compare_price" class="form-control" placeholder="Compare Price" />
                                                    <p class="error"></p>
                                                    <p class="text-muted mt-3">
                                                        To show a reduced price, move the product’s original price into Compare at price. Enter a lower value into Price.
                                                    </p>	
                                                </div>
                                            </div>                                            
                                        </div>
                                    </div>	                                                                      
                                </div>
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <h2 class="h4 mb-3">Inventory</h2>								
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="sku">SKU (Stock Keeping Unit)</label>
                                                    <input type="text" name="sku" id="sku" class="form-control" placeholder="sku" />
                                                    <p class="error"></p>	
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="barcode">Barcode</label>
                                                    <input type="text" name="barcode" id="barcode" class="form-control" placeholder="Barcode" />
                                                    <p class="error"></p>	
                                                </div>
                                            </div>   
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="hidden" name="track_qty" value="No">
                                                        <input class="custom-control-input" type="checkbox" id="track_qty" name="track_qty" value="Yes" checked />
                                                        <label for="track_qty" class="custom-control-label">Track Quantity</label>
                                                        <p class="error"></p>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <input type="number" min="0" name="qty" id="qty" class="form-control" placeholder="Qty" />
                                                    <p class="error"></p>	
                                                </div>
                                            </div>                                         
                                        </div>
                                    </div>	                                                                      
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card mb-3">
                                    <div class="card-body">	
                                        <h2 class="h4 mb-3">Product status</h2>
                                        <div class="mb-3">
                                            <select name="status" id="status" class="form-control">
                                                <option value="1">Active</option>
                                                <option value="0">Block</option>
                                            </select>
                                            <p class="error"></p>
                                        </div>
                                    </div>
                                </div> 
                                <div class="card">
                                    <div class="card-body">	
                                        <h2 class="h4  mb-3">Product category</h2>
                                        <div class="mb-3">
                                            <label for="category">Category</label>
                                            <select name="category_id" id="category_id" class="form-control">
                                                <option value="">Select Category</option>
                                                @if($categories->isNotEmpty())
                                                @foreach($categories as $cat)
                                                <option value="{{$cat->id}}">{{$cat->name}}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                            <p class="error"></p>
                                        </div>
                                        <div class="mb-3">
                                            <label for="sub_category">Sub category</label>
                                            <select name="sub_category_id" id="sub_category_id" class="form-control">
                                                <option value="">Select Sub Category</option>
                                            </select>
                                            <p class="error"></p>
                                        </div>
                                    </div>
                                </div> 
                                <div class="card mb-3">
                                    <div class="card-body">	
                                        <h2 class="h4 mb-3">Product brand</h2>
                                        <div class="mb-3">
                                            <select name="brand_id" id="brand_id" class="form-control">
                                                <option value="">Select brand</option>
                                                @if($brands->isNotEmpty())
                                                @foreach($brands as $brand)
                                                <option value="{{$brand->id}}">{{$brand->name}}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                            <p class="error"></p>
                                        </div>
                                    </div>
                                </div> 
                                <div class="card mb-3">
                                    <div class="card-body">	
                                        <h2 class="h4 mb-3">Featured product</h2>
                                        <div class="mb-3">
                                            <select name="is_featured" id="is_featured" class="form-control">
                                                <option value="No">No</option>
                                                <option value="Yes">Yes</option>
                                            </select>
                                            <p class="error"></p>
                                        </div>
                                    </div>
                                </div>  
                                <div class="card mb-3">
                                    <div class="card-body">	
                                        <h2 class="h4 mb-3">Related Product</h2>
                                        <div class="mb-3">
                                            <select multiple name="related_products[]" class="related-products w-100 form-select" id="related_products">
                                            </select>
                                            <p class="error"></p>
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
    //related product
     $('.related-products').select2({
        ajax:{
            url: '{{route("product.getProducts")}}',
            dataType: 'json',
            tags: true,
            multiple:true,
            minimumInputlength: 3,
            processResults: function (data){
                return{
                    results:data.tags
                };
            }
        }
    });
  $("#productForm").submit(function(event){
   event.preventDefault();
   var formData = $(this).serializeArray();
			$("button[type=submit]").prop('disabled', true);
			$.ajax({
				url: '{{route('store.product')}}',
				type: 'post',
				data: formData,
				dataType: 'json',
				success:function(response){
							$("button[type=submit]").prop('disabled', false);
       if(response['status'] == true){
		window.location.href="{{route('view.products')}}";
        //  $("#title").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback')
        // .html("");
        //  $("#slug").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback')
        // .html("");
       }else{
        var  errors = response['errors'];
        $(".error").removeClass('invalid-feedback').html('');
        //remove invalid-feedback class
        $("input[type='text'], select, input[type='number']").removeClass('is-invalid');
        $.each(errors, function(key, value){
            $(`#${key}`).addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(value);
        });

       }
       
      },
      error:function(jqXHR, exception){
       console.log("something went wrong");
      }
    });
});
});

$("#title").change(function(){
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
url: "{{route('temp-images.create')}}",
maxFiles: 10,
paramName: 'image',
addRemoveLinks: true,
acceptedFiles: "images/jpeg,image/png,image/gif",
headers:{
	'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
},
success:function(file, response){
	// $("#image_id").val(response.image_id);
	//console.log(response);

    var html = `<div class="col-md-3" id="image-row-${response.image_id}">
                <div class="card">
                    <input type="hidden" name="image_array[]" value="${response.image_id}" />
                    <img src="${response.imagePath}" class="card-img-top" alt="">
                    <div class="card-body">
                        <a href="javascript:void(0)" onclick="deleteImage(${response.image_id})" class="btn btn-danger">Delete</a>
                    </div>
                </div>
                </div>`;
                $("#product-gallery").append(html);
},
complete:function(file){
    this.removeFile(file);
}
});

$("#category_id").change(function() {
    var cId = $(this).val(); 
    $.ajax({
        url: '{{ route("product.subcategories") }}',
        type: 'get',
        data: { category_id: cId },
        dataType: 'json',
        success: function(response) {
            // console.log(response);
            $("#sub_category_id").find("option").not(":first").remove();
            $.each(response["subCategories"], function(key,item){
              $("#sub_category_id").append(`<option value = '${item.id}'>${item.name}</option>`)
            })
            // if (response["status"] == true) {
            //     $("#slug").val(response["slug"]);
            // }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error("Error:", errorThrown);
        }
    });
});
function deleteImage(id){
    $("#image-row-"+id).remove();
}
</script>
@endsection