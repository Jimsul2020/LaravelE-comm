@extends('admin.layout.app')

@section('content')
<section class="content-header">
    <div class="container-fluid my-2">
        @include('admin.message')
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit User</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{route('users.index')}}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="container-fluid">
        <form action="" method="post" name="editForm" id="editForm">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name">Name</label>
                                <input type="text" value="{{$user->name}}" name="name" id="name" class="form-control" placeholder="Name">
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="slug">email</label>
                                <input type="email" value="{{$user->email}}" name="email" id="slug" class="form-control" placeholder="email">
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="image">Phone</label>
                                <input type="text" value="{{$user->phone}}" id="phone" name="phone" value="" class="form-control" placeholder="phone">
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="image">Password</label>
                                <input type="password" id="password" name="password" value="" class="form-control" placeholder="phone">
                                <p></p>
                                <span>To change password you have to enter a value, otherwise leave blank.</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status">Status</label>
                                <select name="status" class="form-control" id="status">
                                    <option {{($user->status == 1) ? 'selected' : ''}} value="1">Active</option>
                                    <option {{($user->status == 0) ? 'selected' : ''}} value="0">Block</option>
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
    $(document).ready(function() {
        $("#editForm").submit(function(event) {
            event.preventDefault();
            var formData = $(this).serialize();
            $("button[type=submit]").prop('disabled', true);
            $.ajax({
                url: '{{route("update.users", $user->id)}}',
                type: 'put',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    $("button[type=submit]").prop('disabled', false);
                    if (response['status'] == true) {
                        window.location.href = "{{route('users.index')}}";
                        $("#name").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback')
                            .html("");
                        $("#email").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback')
                        $("#phone").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback')
                            .html("");
                    } else {
                        var errors = response['errors'];
                        if (errors['name']) {
                            $("#name").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['name']);
                        } else {
                            $("#name").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback')
                                .html("");
                        }
                        if (errors['email']) {
                            $("#email").addClass('is-invalid').siblings('p').addClass('invalid-feedback')
                                .html(errors['email']);
                        } else {
                            $("#email").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
                        }

                        if (errors['phone']) {
                            $("#phone").addClass('is-invalid').siblings('p').addClass('invalid-feedback')
                                .html(errors['phone']);
                        } else {
                            $("#phone").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
                        }

                        
                    }

                },
                error: function(jqXHR, exception) {
                    console.log("something went wrong");
                }
            });
        });
    });

</script>
@endsection