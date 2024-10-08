@extends('front.layout.app')

@section('content')
    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="{{route('front.home')}}">Home</a></li>
                    <li class="breadcrumb-item active">Shop</li>
                </ol>
            </div>
        </div>
    </section>

    <section class="section-6 pt-5">
        <div class="container">
            <div class="row">            
                <div class="col-md-3 sidebar">
                    <div class="sub-title">
                        <h2>Categories</h3>
                    </div>
                    
                    <div class="card">
                        <div class="card-body">
                            <div class="accordion accordion-flush" id="accordionExample">
                                @if($categories->isNotEmpty())
                                @foreach($categories as $key => $category)
                                <div class="accordion-item">
                                    @if($category->sub_Category->isNotEmpty())
                                    <h2 class="accordion-header" id="headingOne">
                                        <button class="accordion-button collapsed {{($categorySelected == $category->id) ? 'show' : ''}}" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne-{{$key}}" aria-expanded="false" aria-controls="collapseOne">
                                            {{$category->name}}
                                        </button>
                                    </h2>
                                    @else
                                        <a href="{{route('front.shop',$category->slug)}}" class="nav-item nav-link {{($categorySelected == $category->id) ? 'text-warning' : ''}}">{{$category->name}}</a>
                                    @endif
                                    @if($category->sub_Category->isNotEmpty())
                                    <div id="collapseOne-{{$key}}" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample" style="">
                                        <div class="accordion-body">
                                            <div class="navbar-nav">
                                                @foreach($category->sub_Category as $subCategory)
                                                <a href="{{route('front.shop',[$category->slug, $subCategory->slug])}}" class="nav-item nav-link {{($subCategorySelected == $subCategory->id) ? 'text-primary' : ''}}">{{$subCategory->name}}</a>
                                                @endforeach                                       
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                @endforeach  
                                @endif               
                            </div>
                        </div>
                    </div>

                    <div class="sub-title mt-5">
                        <h2>Brand</h3>
                    </div>
                    
                    <div class="card">
                        <div class="card-body">
                            @if($brands->isNotEmpty())
                            @foreach($brands as $key => $brand)
                            <div class="form-check mb-2">
                                <input {{(in_array($brand->id, $brandsArray)) ? 'checked' : ''}} class="form-check-input brand-label" type="checkbox" name="brand[]" value="{{$brand->id}}" id="brand-{{$brand->id}}">
                                <label class="form-check-label" for="brand-{{$brand->id}}">
                                    {{$brand->name}}
                                </label>
                            </div>  
                            @endforeach 
                            @endif            
                        </div>
                    </div>

                    <div class="sub-title mt-5">
                        <h2>Price</h3>
                    </div>
                    
                    <div class="card">
                        <div class="card-body">
                                <input type="text" class="js-range-slider" name="my_range" value="" />
                            {{-- <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                <label class="form-check-label" for="flexCheckDefault">
                                    &#8358;0-100
                                </label>
                            </div>                --}}
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="row pb-3">
                        <div class="col-12 pb-1">
                            <div class="d-flex align-items-center justify-content-end mb-4">
                                <div class="ml-2">
                                    {{-- <div class="btn-group"> --}}
                                        {{-- <button type="button" class="btn btn-sm btn-light dropdown-toggle" data-bs-toggle="dropdown">Sorting</button>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item" href="#">Latest</a>
                                            <a class="dropdown-item" href="#">Price High</a>
                                            <a class="dropdown-item" href="#">Price Low</a>
                                        </div> --}}
                                        <select name="sort" id="sort" class="form-select">
                                            <option value="latest" {{($sort == 'latest') ? 'selected' : ''}}>Latest</option>
                                            <option value="price_desc" {{($sort == 'price_desc') ? 'selected' : ''}}>Price High</option>
                                            <option value="price_asc" {{($sort == 'price_asc') ? 'selected' : ''}}>Price Low</option>
                                        </select>
                                    {{-- </div>                                     --}}
                                </div>
                            </div>
                        </div>
                         @if($products->isNotEmpty())
                         @foreach($products as $featProd)
                
                            @php
                                $productImage = $featProd->product_images->first();
                            @endphp
                        <div class="col-md-4">
                            <div class="card product-card">
                                <div class="product-image position-relative">
                                    <a href="" class="product-img">
                                        
                                        {{-- <img class="card-img-top" src="images/product-1.jpg" alt=""> --}}

                                        @if(!empty($productImage->image))
                                        <img class="card-img-top" src="{{asset('uploads/product/small/'. $productImage->image)}}" class="img-thumbnail" alt=""/>
                                        @else
                                        <img class="card-img-top" src="{{asset('admin-assets/img/default-150x150.png')}}" class="img-thumbnail" alt=""  />
                                        @endif
                                    </a>

                                    <a class="whishlist" href="222"><i class="far fa-heart"></i></a>                            

                                    <div class="product-action">
                                        <a class="btn btn-dark" href="javascript:void(0)" onclick="addToCart({{$featProd->id}})">
                                            <i class="fa fa-shopping-cart"></i> Add To Cart
                                        </a>                            
                                    </div>
                                </div>                        
                                <div class="card-body text-center mt-3">
                                    <a class="h6 link" href="product.php">{{$featProd->title}}</a>
                                    <div class="price mt-2">
                                        <span class="h5"><strong>&#8358;{{$featProd->price}}</strong></span>
                                    @if($featProd->compare_price > 0)
                                    <span class="h6 text-underline"><del>&#8358;{{$featProd->compare_price }}</del></span>
                                    @endif
                                    </div>
                                </div>                        
                            </div>                                               
                        </div> 
                        @endforeach
                        @endif 


                        <div class="col-md-12 pt-5">
                            {{-- <nav aria-label="Page navigation example">
                                <ul class="pagination justify-content-end">
                                    <li class="page-item disabled">
                                    <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                                    </li>
                                    <li class="page-item"><a class="page-link" href="#">1</a></li>
                                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                                    <li class="page-item">
                                    <a class="page-link" href="#">Next</a>
                                    </li>
                                </ul>
                            </nav> --}}
                            {{$products->links()}}
                            {{-- {{$products->withQueryString->links()}} --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('customJs')
{{-- <script>
       rangeSlider = $(".js-range-slider").ionRangeSlider({
        type: "double",
        min: 0,
        max: 1000,
        from: 0,
        step:10,
        to: 500,
        skin:"round",
        max_postfix: "+",
        prefix: "&#8358;",
        onFinish: function(){
            apply_filters();
        }
    });
    //Saving it's instance to var
 var slider = $(".js-ranger-slider").data("ionRangeSlider");
        $('.brand-label').change(function() {
            apply_filters();
        });    


        function apply_filters() {
            var brands = [];
            $('.brand-label').each(function() {
                if ($(this).is(":checked") == true) {
                    brands.push($(this).val());
                }
            });
            console.log(brands.toString());
            var url = '{{ url()->current() }}?';
            url += '$price_min='+slider.result.from+'&price_max='+slider.result.to;
            window.location.href = url+"&brand="+brands.toString();
        }

</script> --}}

<script>
    $(document).ready(function() {
        // Initialize range slider
        let rangeSlider = $(".js-range-slider").ionRangeSlider({
            type: "double",
            min: 0,
            max: 15000,
            from: {{$priceMin}},
            step: 10,
            to: {{$priceMax}},
            skin: "round",
            max_postfix: "+",
            prefix: "&#8358;",
            onFinish: function(data) {
                apply_filters(data.from, data.to);
            }
        });

        // Save its instance to a variable
        let slider = $(".js-range-slider").data("ionRangeSlider");

        // Get the sort parameter from the URL
        const urlParams = new URLSearchParams(window.location.search);
        const sortParam = urlParams.get('sort');
        if (sortParam) {
            $('#sort').val(sortParam);
        }

        // Sort change event
        $("#sort").change(function(){
            apply_filters(slider.result.from, slider.result.to);
        });

        // Brand checkbox change event
        $('.brand-label').change(function() {
            apply_filters(slider.result.from, slider.result.to);
        });

        function apply_filters(price_min, price_max) {
            let brands = [];
            $('.brand-label:checked').each(function() {
                brands.push($(this).val());
            });

            let url = '{{ url()->current() }}?';
            // Price range filter
            url += 'price_min=' + price_min + '&price_max=' + price_max;
            // Brand filter
            if (brands.length > 0) {
                url += '&brand=' + brands.join(',');
            }
            // Sort filter
            url += '&sort=' + $("#sort").val();

            window.location.href = url;
        }
    });
</script>




@endsection