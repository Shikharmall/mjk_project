@extends('Admin.app')
@section('title',('Add Hospital'))

<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.1/themes/base/minified/jquery-ui.min.css" type="text/css" /> 
@push('script')
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
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
@section('content')


<div class="page-wrapper">
			<div class="page-content">
				<div class="row">
					<div class="col-xl-12 mx-auto">
						<div class="card border-top border-0 border-4 border-danger">
							<div class="card-body">
								<div class="card-title d-flex align-items-center">
									<h5 class="mb-0 text-danger">Add Hospital</h5>
								</div>
								<hr>
								<form class="row g-3" action="{{route('panel.Hospital.add-hospital')}}" method="POST" enctype="multipart/form-data">
								@csrf
                  <input type="hidden" name="hospital_id" value="@if(!empty($hospital)){{$hospital->hospital_id}}@endif" id="hospital_id">  
                  <div class="col-md-2">
										<label for="inputCity" class="form-label">Hospital Name</label>
									</div>
									<div class="col-md-10">
										<input type="text" class="form-control" id="name" name="name" value="@if(!empty($hospital)){{$hospital->name}}@endif" required >
									</div>

									<div class="col-md-2">
										<label for="inputCity" class="form-label">District</label>
									</div>
                  <div class="col-sm-10">
                    <select class="form-select" name="district_id" id="district_id" required>
                    <option value="" selected disabled>{{('Select District')}}</option>
                    @foreach($add_district as $p)
                    <option value="{{$p->district_id}}"
                      @if(!empty($lab))
                      @if($lab->district_id == $p->district_id)
                        selected
                      @endif
                      @endif>{{$p->name}}</option>
                    @endforeach
                    </select>
                  </div>			

                  <div class="col-md-2">
										<label for="inputCity" class="form-label">Address</label>
									</div>
									<div class="col-md-10">
									  <input type="text" class="form-control" id="gps_address" name="gps_address" value="@if(!empty($hospital)){{$hospital->gps_address}}@endif" required >
									</div>

									
                  <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCTKicbGh6chqaLZTVHiFt889Mmwn29pio&libraries=places&country=ind"></script>
                            <script type="text/javascript">
                              google.maps.event.addDomListener(window, 'load', function () {

                                var places = new google.maps.places.Autocomplete(document.getElementById('gps_address'));
                                google.maps.event.addListener(places, 'place_changed', function () {
                                  var place = places.getPlace();
                                  var address = place.formatted_address;
                                  var latitude = place.geometry.location.lat();
                                  var longitude = place.geometry.location.lng();

                                  document.getElementById("latitude").value = latitude;
                                  document.getElementById("longitude").value = longitude;
                                });
                              });
                            </script> 
                            
                            <input type="hidden" class="form-control" id="latitude" name="latitude" value="@if(!empty($hospital)){{$hospital->latitude}}@endif" required >

                            <input type="hidden" class="form-control" id="longitude" name="longitude" value="@if(!empty($hospital)){{$hospital->longitude}}@endif" required >
							
									<div class="col-md-2">
										<label for="inputCity" class="form-label">Image</label>
									</div>
									<div class="col-md-5">
									<input type="file" name="image" id="imgInp" class="form-control" accept=".jpg, .png, .jpeg|image/*" @if(empty($hospital)) required @endif>
									</div>
                  					<div class="col-md-4">
									<img class="img_people" id="blah" src="@if(!empty($hospital)){{asset('image/'.$hospital->image)}}@endif" style="width:100%; height:150px;" >
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