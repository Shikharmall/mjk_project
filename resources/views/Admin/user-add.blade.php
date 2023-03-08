@extends('Admin.app')
@section('title',('Update Staff'))

@section('content')
<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.1/themes/base/minified/jquery-ui.min.css" type="text/css" /> 
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>

<div class="page-wrapper">
			<div class="page-content">
				<div class="row">
					<div class="col-xl-12 mx-auto">
						<div class="card border-top border-0 border-4 border-danger">
							<div class="card-body">
								<div class="card-title d-flex align-items-center">
									<h5 class="mb-0 text-danger">Update Staff</h5>
								</div>
								<hr>
                <form class="row g-3" action="{{route('panel.User.add-user')}}" method="POST" enctype="multipart/form-data">
								@csrf
                  <input type="hidden" name="id" value="@if(!empty($user)){{$user->id}}@endif" id="id">    
                  <div class="col-md-2">
										<label for="inputCity" class="form-label">User Name</label>
									</div>
									<div class="col-md-10">
										<input type="text" Readonly class="form-control" id="name" name="name" value="@if(!empty($user)){{$user->name}}@endif" required >
									</div>
                  <div class="col-md-2">
										<label for="inputCity" class="form-label">Email ID</label>
									</div>
									<div class="col-md-10">
									  <input type="text" Readonly class="form-control" id="email" name="email" value="@if(!empty($user)){{$user->email}}@endif" required >
									</div>
                  <div class="col-md-2">
										<label for="inputCity" class="form-label">Mobile No.</label>
									</div>
									<div class="col-md-10">
									  <input type="text" class="form-control" id="mobile" name="mobile" value="@if(!empty($user)){{$user->mobile}}@endif" required >
									</div>

                  <div class="col-md-2">
										<label for="inputCity" class="form-label">Image</label>
									</div>
									<div class="col-md-5">
									<input type="file" name="image" id="imgInp" class="form-control" accept=".jpg, .png, .jpeg|image/*" @if(empty($user)) required @endif>
									</div>
                  <div class="col-md-4">
									<img class="img_people" id="blah" src="@if(!empty($user)){{asset('image/'.$user->image)}}@endif" style="width:100%; height:150px;" >
									</div>
								
									<div class="col-12">
										<button type="submit"  name="submit-cat" class="btn btn-danger px-5">Submit</button>
									</div>
								</form>
							</div>
						</div>
						
					</div>
				</div>
				
			</div>
		</div>

@endsection

@push('script')
<script>
 function readURL(input) {

  if (input.files && input.files[0]) {
    var reader = new FileReader();

    reader.onload = function (e) {
      $('#blah').attr('src', e.target.result);
    }
    reader.readAsDataURL(input.files[0]);
  }
}

$("#imgInp").change(function(){
  readURL(this);
});
</script>