@extends('admin.layout.app')

@section('content')
<section class="content-header">
    <div class="container-fluid my-2">
        @include('admin.message')
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create Users</h1>
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
        <form action="" method="post" name="userForm" id="userForm">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name">Name</label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Name">
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="slug">email</label>
                                <input type="email" name="email" id="slug" class="form-control" placeholder="email">
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="image">Phone</label>
                                <input type="text" id="phone" name="phone" value="" class="form-control" placeholder="phone">

                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="image">Password</label>
                                <input type="password" id="password" name="password" value="" class="form-control" placeholder="phone">

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
    $(document).ready(function() {
        $("#userform").submit(function(event) {
            event.preventDefault();
            var formData = $(this).serialize();
            $("button[type=submit]").prop('disabled', true);
            $.ajax({
                url: '{{route("store.users")}}',
                type: 'post',
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

                        if (errors['password']) {
                            $("#password").addClass('is-invalid').siblings('p').addClass('invalid-feedback')
                                .html(errors['password']);
                        } else {
                            $("#password").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
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