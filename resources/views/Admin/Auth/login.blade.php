<!DOCTYPE html>
<html lang="en">


<!-- Mirrored from codervent.com/amdash/demo/vertical/authentication-forgot-password.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 12 May 2022 06:17:20 GMT -->
<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--favicon-->
	<link rel="icon" href="{{asset('image/logo.png')}}" type="image/png" />
	<!-- loader-->
	<link href="{{asset('assets')}}/css/pace.min.css" rel="stylesheet" />
	<script src="{{asset('assets')}}/js/pace.min.js"></script>
	<!-- Bootstrap CSS -->
	<link href="{{asset('assets')}}/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&amp;display=swap" rel="stylesheet">
	<link href="{{asset('assets')}}/css/app.css" rel="stylesheet">
	<link href="{{asset('assets')}}/css/icons.css" rel="stylesheet">
	<title>MJK - Login Page</title>

  <script src="{{asset('assets')}}/js/toastr.js"></script>
    <!--end::Page Scripts-->
    {!! Toastr::message() !!}

@if ($errors->any())
    <script>
        @foreach($errors->all() as $error)
        toastr.error('{{$error}}', Error, {
            CloseButton: true,
            ProgressBar: true
        });
        @endforeach
    </script>
@endif

<style>
.form-control-lg {
    min-height: calc(1.5em + 1rem + 2px);
    padding: 0.5rem 1rem;
    font-size: 14px;
    border-radius: 0.3rem;
}
</style>
</head>

<body class="bg-forgot">
	<!-- wrapper -->
	<div class="wrapper">
		<div class="authentication-forgot d-flex align-items-center justify-content-center">
			<div class="card forgot-box">
				<div class="card-body">
          <form class="" action="{{route('panel.auth.submit')}}" method="post" id="form-id">
          @csrf 
					<div class="p-4 rounded  border">
						<div class="text-center">
							<img src="{{asset('image/logo.png')}}" width="120" alt="" />
						</div>
						<h4 class="font-weight-bold" style="msrgin-top:20px;">Sign In?</h4>
						<p class="text-muted">Enter your registered email ID and password</p>
						<div class="my-4">
							<label class="form-label">Email Id</label>
							<input type="text" class="form-control form-control-lg" id="email" name="email" placeholder="Enter your Email ID">
						</div>
            <div class="my-4">
							<label class="form-label">Password</label>
							<input type="text" class="form-control form-control-lg" id="password" name="password" placeholder="Enter your Password">
						</div>
            <div class="my-4">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="termsCheckbox"
                                name="remember">
                        <label class="custom-control-label text-muted" for="termsCheckbox">
                            {{('Remember me')}}
                        </label>
                    </div>
            </div>

						<div class="d-grid gap-2">
							<button type="submit" class="btn btn-primary btn-lg">Login</button> 
						</div>
					</div>
          </form>

				</div>
			</div>
		</div>
	</div>
</body>
</html>