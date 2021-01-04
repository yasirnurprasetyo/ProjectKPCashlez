<form id="form-login">
	<div class="form-group">
		<label class="text-label-sm" for="username">Username</label>
		<div class="input-group input-group-merge">
			<input name="username" id="username" type="text" required="" class="form-control" placeholder="Username">
		</div>
	</div>
	<div class="form-group">
		<label class="text-label-sm" for="password">Password</label>
		<div class="input-group input-group-merge">
			<input name="password" type="password" id="password" required minlength="8" maxlength="" class="form-control" placeholder="Enter your password">
		</div>
	</div>
	<div class="form-group text-right">
		<h6><a class="" href="">Forgot password?</a></h6><br>
	</div>
</form>
<div class="form-group">
	<button id="btn-login" class="btn btn-rounded btn-lg btn-block btn-custom" type="button">Sign In</a><br>
</div><br><br><br><br>
<div class="text-center">
	Don't have an account? <a class="text-body" style="color: blue;" href="signup-centered-boxed.html"><b>Register here!</b></a>
</div>
<script>
	$(function() {
		$("#btn-login").on("click", function() {
			let validate = $("#form-login").valid();
			if (validate) {
				swal.fire({
					title: "Processing Data..",
					text: "Data sedang berkelana",
					imageUrl: '<?= base_url() ?>' + "assets/images/logos/logo-cashlez.png",
					showConfirmButton: false,
					allowOutsideClick: false
				});
				$.ajax({
					type: "POST",
					url: '<?= base_url() ?>' + 'Login/prosesLogin',
					data: {
						"username": $("#username").val(),
						"password": $("#password").val()
					},
					success: function(data) {
						if (data === "1") {
							$.ajax({
								type: "POST",
								url: '<?= base_url() ?>' + 'Login/salesLogin',
								data: {
									"group_code": $("#username").val(),
									"password": $("#password").val()
								},
								success: function(data) {
									window.location.replace("<?= site_url("welcome") ?>");
								}
							});
						} else {
							Swal.fire("Username atau password tidak ditemukan", data, "error");
						}
					}
				});
			}
		});
		$("#form-login").validate({
			rules: {
				username: {
					required: true
				},
				password: {
					required: true,
					minlength: 8,
					maxlength: 16,
				}
			},
			messages: {
				username: {
					required: "Anda belum memasukan username",
				},
				password: {
					required: "Anda belum memasukkan password",
					minlength: "Password at least 8 characters"
				},
			},
			errorElement: 'span',
			errorPlacement: function(error, element) {
				error.addClass('invalid-feedback');
				element.closest('.form-group').append(error);
			},
			highlight: function(element, errorClass, validClass) {
				$(element).addClass('is-invalid');
			},
			unhighlight: function(element, errorClass, validClass) {
				$(element).removeClass('is-invalid');
			}
		});
	});
</script>
