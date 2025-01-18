@extends('admin.layout.app')

@section('content')
<section class="content-header">
	<div class="container-fluid my-2">
		@include('admin.message')
		<div class="row mb-2">
			<div class="col-sm-6">
				<h1>Change Password</h1>
			</div>
		</div>
	</div>
	<!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
	<!-- Default box -->
	<div class="container-fluid">
        @include('admin.message')
		<form action="" method="post" name="changePassword" id="changePassword">
			<div class="card">
				<div class="card-body">
					<div class="row">
						<div class="col-md-6">
							<div class="mb-3">
								<label for="old password">Old Password</label>
								<input type="text" name="old_password" id="old_password" class="form-control" placeholder="Old Password">
								<p></p>
							</div>
						</div>
						<div class="col-md-6">
							<div class="mb-3">
								<label for="new password">New Password</label>
								<input type="password" name="new_password" id="new_password" class="form-control" placeholder="New Password">
								<p></p>
							</div>
						</div>
						<div class="col-md-6">
							<div class="mb-3">
								<label for="">Confirm Password</label>
								<input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Confirm Password">
								<p></p>
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
		$("#changePassword").submit(function(event) {
			event.preventDefault();
			var formData = $(this).serialize();
			$("button[type=submit]").prop('disabled', true);
			$.ajax({
				url: "{{route('admin.updatePassword')}}",
				type: 'put',
				data: formData,
				dataType: 'json',
				success: function(response) {
					$("button[type=submit]").prop('disabled', false);
					if (response['status'] == true) {
						window.location.href = "{{route('admin.changePassword')}}";
						$("#old_password").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback')
							.html("");
						$("#new_password").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback')
							.html("");
					} else {
						var errors = response['errors'];
						if (errors['old_password']) {
							$("#old_password").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['old_password']);
						} else {
							$("#old_password").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback')
								.html("");
						}
						if (errors['old_password']) {
							$("#old_password").addClass('is-invalid').siblings('p').addClass('invalid-feedback')
								.html(errors['old_password']);
						} else {
							$("#old_password").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
						}
                        if (errors['confirm_password']) {
							$("#confirm_password").addClass('is-invalid').siblings('p').addClass('invalid-feedback')
								.html(errors['confirm_password']);
						} else {
							$("#confirm_password").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
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