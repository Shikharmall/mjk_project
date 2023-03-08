@extends('Admin.app')
@section('title',('Add District'))

@push('css_or_js')
<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.1/themes/base/minified/jquery-ui.min.css" type="text/css" /> 
@endpush

@section('content')
<style>
.p-5 {
    padding: 2rem!important;
}

table.dataTable {
    clear: both;
    margin-top: 20px !important;
    margin-bottom: 6px !important;
    max-width: none !important;
    border-collapse: separate !important;
    border-spacing: 0;
}
</style>

<div class="page-wrapper">
			<div class="page-content">
				<div class="row">
					<div class="col-xl-12 mx-auto">
						<div class="card border-top border-0 border-4 border-danger">
							<div class="card-body">
								<div class="card-title d-flex align-items-center">
									<h5 class="mb-0 text-danger">Add District</h5>
								</div>
								<hr>
								<form class="row g-3" action="{{route('panel.District.add-district')}}" method="POST" enctype="multipart/form-data">
								@csrf
                  <input type="hidden" name="district_id" value="@if(!empty($district)){{$district->district_id}}@endif" id="district_id">  
                  <div class="col-md-4">
										<label for="inputCity" class="form-label">District Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="@if(!empty($district)){{$district->name}}@endif" required >
									</div>
									<div class="col-md-6">
                    <label for="inputCity" class="form-label">District GPS Address</label>
                    <input type="text" class="form-control" id="gps_address" name="gps_address" value="@if(!empty($district)){{$district->gps_address}}@endif" required >
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
                            
                            <input type="hidden" class="form-control" id="latitude" name="latitude" value="@if(!empty($district)){{$district->latitude}}@endif" required >

                            <input type="hidden" class="form-control" id="longitude" name="longitude" value="@if(!empty($district)){{$district->longitude}}@endif" required >
								
									<div class="col-2">
										<button type="submit" name="submit-cat" class="btn btn-danger px-5" style="margin-top: 29px;">Submit</button>
									</div>
								</form>
							</div>
						</div>

				<div class="card border-top border-0 border-4 border-danger">
					<div class="card-body p-5">
						<div class="table-responsive">
							<table id="example" class="table align-middle mb-0" style="width:100%;">
								<thead class="table-light">
									<tr>
                    <th style="font-size:14px;">Sr No.</th>
                    <th style="font-size:14px;">Action</th>
                    <th style="font-size:14px;">District Name</th>
                    <th style="font-size:14px;">Date</th>
									</tr>
								</thead>
								<tbody>
                @php 
                  $district33 = DB::table('add_district')->orderBy('district_id','DESC')->get();
                @endphp 

                @foreach($district33 as $district33)
                         
                    <tr>
                      <td>{{ $loop->iteration }}</td> 
                      <td>
                        <div class="action">
                          <a href="{{route('panel.District.edit',[$district33->district_id])}}" class="btn btn-primary" style="padding: 23px 7px 8px;" title="Edit">
                          <i class="fadeIn animated bx bx-edit me-0" style="line-height: 16px;font-size: 16px;"></i>
                          </a>

                          <a onclick="status_change_alert('{{route('panel.District.delete',[$district33->district_id])}}','{{('Want to delete this Lab ?')}}',event)" class="btn btn-danger" style="padding: 23px 7px 8px;" title="Delete">
                          <i class="fadeIn animated bx bx-trash-alt me-0" style="line-height: 16px;font-size: 16px;"></i>
                          </a>
                        </div>
                      </td>            
                      <td style="font-size:12px;">{{$district33->name}}</td>
                      <td style="font-size:12px;">{{\Carbon\Carbon::parse($district33->created)->format('d-m-Y')}}</td>
                    </tr>
                @endforeach
								

								</tfoot>
							</table>
						</div>
					</div>
				</div>
						
					</div>
				</div>
				
			</div>
		</div>

@endsection

@push('script')
<script>
     function status_change_alert(url, message, e) {
            e.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: message,
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: 'default',
                confirmButtonColor: '#FC6A57',
                cancelButtonText: 'No',
                confirmButtonText: 'Yes',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    location.href=url;
                }
            })
        }

</script>
<script>
 function fnExcelReport3()
 {
  var tab_text="<table border='2px'><tr bgcolor='#87AFC6'>";
  var textRange; var j=0;
    tab = document.getElementById('default-datatable'); // id of table

    for(j = 0 ; j < tab.rows.length ; j++) 
    {     
      tab_text=tab_text+tab.rows[j].innerHTML+"</tr>";
        //tab_text=tab_text+"</tr>";
      }

      tab_text=tab_text+"</table>";
    tab_text= tab_text.replace(/<A[^>]*>|<\/A>/g, "");//remove if u want links in your table
    tab_text= tab_text.replace(/<img[^>]*>/gi,""); // remove if u want images in your table
    tab_text= tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params

    var ua = window.navigator.userAgent;
    var msie = ua.indexOf("MSIE "); 

    if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./))      // If Internet Explorer
    {
      txtArea3.document.open("txt/html","replace");
      txtArea3.document.write(tab_text);
      txtArea3.document.close();
      txtArea3.focus(); 
      sa=txtArea3.document.execCommand("SaveAs",true,"Say Thanks to Sumit.xls");
    }  
    else                 //other browser not tested on IE 11
      sa = window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text));  

    return (sa);
  }
</script>