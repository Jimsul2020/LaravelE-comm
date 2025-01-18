@if(Session::has('errors'))
<div class="alert alert-danger alert-dismissible fade show">
<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    <h4><i class="icon fa fa-ban"></i> Error!</h4>
    {{Session::get('errors')}}
</div>
@endif

@if(Session::has('message'))
<div class="alert alert-success alert-dismissible fade show">
<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    <h4><i class="icon fa fa-check"></i> Success!</h4>
    {{Session::get('message')}}
</div>
@endif