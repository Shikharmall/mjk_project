@extends('Admin.app')
@section('title',('Add Lab'))

@push('css_or_js')
<script type="text/javascript" src="http://js.nicedit.com/nicEdit-latest.js"></script> 
<script type="text/javascript">
            bkLib.onDomLoaded(function() { nicEditors.allTextAreas() });
</script>
<link href="{{asset('assets/')}}//css/animate.css"  rel="stylesheet" type="text/css"/>
<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.1/themes/base/minified/jquery-ui.min.css" type="text/css" /> 
@endpush

@section('content')


<div class="page-wrapper">
			<div class="page-content">
				<div class="row">
					<div class="col-xl-12 mx-auto">
						<div class="card border-top border-0 border-4 border-danger">
							<div class="card-body">
								<div class="card-title d-flex align-items-center">
									<h5 class="mb-0 text-danger">Add Lab</h5>
								</div>
								<hr>
								<form class="row g-3" action="{{route('panel.Lab.add-lab')}}" method="POST" enctype="multipart/form-data">
                @csrf
                  <input type="hidden" name="lab_id" value="@if(!empty($lab)){{$lab->lab_id}}@endif" id="lab_id">  
                  <div class="col-md-2">
										<label for="inputCity" class="form-label">Lab Name</label>
									</div>
									<div class="col-md-10">
										<input type="text" class="form-control" id="name" name="name" value="@if(!empty($lab)){{$lab->name}}@endif" required >
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
									  <input type="text" class="form-control" id="gps_address" name="gps_address" value="@if(!empty($lab)){{$lab->gps_address}}@endif" required >
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
                            
                  <input type="hidden" class="form-control" id="latitude" name="latitude" value="@if(!empty($lab)){{$lab->latitude}}@endif" required >

                  <input type="hidden" class="form-control" id="longitude" name="longitude" value="@if(!empty($lab)){{$lab->longitude}}@endif" required >
							
									<div class="col-md-2">
										<label for="inputCity" class="form-label">Image</label>
									</div>
									<div class="col-md-5">
									<input type="file" name="image" id="imgInp" class="form-control" accept=".jpg, .png, .jpeg|image/*" @if(empty($lab)) required @endif>
									</div>
                  <div class="col-md-4">
									<img class="img_people" id="blah" src="@if(!empty($lab)){{asset('image/'.$lab->image)}}@endif" style="width:100%; height:150px;" >
									</div>
								
									<div class="col-12">
										<button type="submit" name="submit-cat" class="btn btn-danger px-5">Submit</button>
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