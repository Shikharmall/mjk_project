@extends('Admin.app')
@section('title',('Staff Activity Report'))

@push('css_or_js')

@endpush

@section('content')
<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.1/themes/base/minified/jquery-ui.min.css" type="text/css" /> 
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
@php 
    $loc = '';
@endphp 
@foreach($staff_activuty as $staff_activuty)
    @php
        $name = str_replace("'", "", $staff_activuty->address);

        $loc .= '{"DisplayText": "'.$name.'", "ADDRESS": "'.$name.'" , "LatitudeLongitude": "'.$staff_activuty->latitude.','.$staff_activuty->longitude.'", "MarkerId": "Customer"},';
    @endphp
@endforeach
@php
$location = substr_replace($loc, "", -1);
@endphp

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCTKicbGh6chqaLZTVHiFt889Mmwn29pio&sensor=true" type="text/javascript"></script>

<script type="text/javascript">
        var map;
        var geocoder;
        var marker;
        var people = new Array();
        var latlng;
        var infowindow;

        $(document).ready(function() {
            ViewCustInGoogleMap();
        });

        function ViewCustInGoogleMap() {

            var mapOptions = {
                zoom: 6,
        center: new google.maps.LatLng({{$latitude}}, {{$longitude}}),
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };
            map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);

            // Get data from database. It should be like below format or you can alter it.

            var data = '[<?php echo $location; ?>]';
            people = JSON.parse(data); 

            for (var i = 0; i < people.length; i++) {
                setMarker(people[i]);
            }

        }

        function setMarker(people) {
            geocoder = new google.maps.Geocoder();
            infowindow = new google.maps.InfoWindow();
            if ((people["LatitudeLongitude"] == null) || (people["LatitudeLongitude"] == 'null') || (people["LatitudeLongitude"] == '')) {
                geocoder.geocode({ 'address': people["Address"] }, function(results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        latlng = new google.maps.LatLng(results[0].geometry.location.lat(), results[0].geometry.location.lng());
                        marker = new google.maps.Marker({
                            position: latlng,
                            map: map,
                            draggable: false,
                            html: people["DisplayText"],
                            icon: "images/marker/" + people["MarkerId"] + ".png"
                        });
                        //marker.setPosition(latlng);
                        //map.setCenter(latlng);
                        google.maps.event.addListener(marker, 'click', function(event) {
                            infowindow.setContent(this.html);
                            infowindow.setPosition(event.latLng);
                            infowindow.open(map, this);
                        });
                    }
                    else {
                        alert(people["DisplayText"] + " -- " + people["Address"] + ". This address couldn't be found");
                    }
                });
            }
            else {
                var latlngStr = people["LatitudeLongitude"].split(",");
                var lat = parseFloat(latlngStr[0]);
                var lng = parseFloat(latlngStr[1]);
                latlng = new google.maps.LatLng(lat, lng);
                marker = new google.maps.Marker({
                    position: latlng,
                    map: map,
                    draggable: false,               // cant drag it
                    html: people["DisplayText"]    // Content display on marker click
                    //icon: "images/marker.png"       // Give ur own image
                });
                //marker.setPosition(latlng);
                //map.setCenter(latlng);
                google.maps.event.addListener(marker, 'click', function(event) {
                    infowindow.setContent(this.html);
                    infowindow.setPosition(event.latLng);
                    infowindow.open(map, this);
                });
            }
        }
</script>
<script>
function reset()
{
    var id = $('#id').val();
    window.location.href = "{{url('panel/User/reset')}}/" + id;
}
</script>
<div class="page-wrapper">		
<div class="page-content">
                <div class="row g-3">
                    <input type="hidden" id="id" name="id" value="{{$id}}" />
                    <div class="col-md-7" style="margin-top:0px;">
                        <h6 class="mb-0 text-uppercase" style="margin-top:15px;">Staff Activity Report</h6>
					</div>		
                    <div class="col-md-4" style="margin-top:0px; ">
                        <form action="{{route('panel.User.filter')}}" method="POST" enctype="multipart/form-data" style="display:flex; margin-bottom:0px;">
                        @csrf
						<input type="date" class="form-control" id="date" name="date" value="{{isset($date)?$date:''}}" required style="padding: 10px 15px;font-size: 14px; float:right; margin-right:5px;">
                        <button type="submit" class="btn btn-danger" style="padding: 10px 20px;font-size: 14px; float:right;">Submit</button>
                        </form>
					</div>
                    <div class="col-md-1" style="margin-top:0px;">
                    <button type="button" onClick="reset()" class="btn btn-dark" style="padding: 10px 20px;font-size: 14px;float: right;">Clear</button>
                    </div>
                </div>
              

				
				<hr/>
                <div class="col-md-12" style="margin-bottom:10px;">
                            <div id="map-canvas" style="width: 100%; display: inline-block; height: 400px; border-radius: 6px;">
                            </div>
                </div>
				<div class="card border-top border-0 border-4 border-danger">
					<div class="card-body p-5">
                        
                        <div class="table-responsive">
							<table id="example" class="table align-middle mb-0" style="width:100%;">
								<thead class="table-light">
									<tr>
                                    <th style="font-size:14px;">Sr No.</th>
                                    <th style="font-size:14px;">Address</th>
                                    <th style="font-size:14px;">Activity Detail</th>
                                    <th style="font-size:14px;">Total Kilometer</th>
                                    <th style="font-size:14px;">Actual Kilometer</th>
									</tr>
								</thead>
								<tbody>
                @php 
                $date = date('Y-m-d');
                $User = DB::table('add_staff_activity')->Where('staff_id',$id)->where('created', $date)->orderBy('id','ASC')->get();
                $kilometer = 0;
                $count =0;
                $id1= '0';
                @endphp
                @foreach($User as $User)
                      @php 
                        $count = $count + 1;
                        if($count == 1)
                        {
                            $kilometer = '0';
                            $id= 0;
                            
                        }
                        else
                        {
                            $id = $id1;

                            $User11 = DB::table('add_staff_activity')->Where('id',$id)->first();

                            if($User11->status == '1')
                            {
                                $hospital1 = DB::table('add_hospital')->select('name','latitude','longitude')->where('hospital_id',$User11->status_id)->first();

                                $latitude1 = $hospital1->latitude;
                                $longitude1 = $hospital1->longitude;  
                            }
                            else if($User11->status == '2')
                            {
                                $lab1 = DB::table('add_lab')->select('name','latitude','longitude')->where('lab_id',$User11->status_id)->first();

                                $latitude1 = $lab1->latitude;
                                $longitude1 = $lab1->longitude;  
                            }
                            else if($User11->status == '3')
                            {
                                $lab1 = DB::table('add_lab')->select('name','latitude','longitude')->where('lab_id',$User11->status_id)->first();

                                $latitude1 = $lab1->latitude;
                                $longitude1 = $lab1->longitude;  
                            }
                            else if($User11->status == '4')
                            {
                                $hospital1 = DB::table('add_hospital')->select('name','latitude','longitude')->where('hospital_id',$User11->status_id)->first();

                                $latitude1 = $hospital1->latitude;
                                $longitude1 = $hospital1->longitude;   
                            }
                        }
                      @endphp  
                      @if($User->status == '1')
                        @php 
                        $hospital = DB::table('add_hospital')->select('name','latitude','longitude')->where('hospital_id',$User->status_id)->first();
                        if($count == 1)
                        {
                            $kilometer = '0';
                        }
                        else
                        {
                            $latitude2 = $hospital->latitude;
                            $longitude2 = $hospital->longitude;
                        }
                        @endphp  
                      @elseif($User->status == '2')
                        @php 
                        $lab = DB::table('add_lab')->select('name','latitude','longitude')->where('lab_id',$User->status_id)->first();
                        if($count == 1)
                        {
                            $kilometer = '0';
                        }
                        else
                        {
                            $latitude2 = $lab->latitude;
                            $longitude2 = $lab->longitude;
                        }
                        @endphp  
                      @elseif($User->status == '3')
                        @php 
                        $lab = DB::table('add_lab')->select('name','latitude','longitude')->where('lab_id',$User->status_id)->first();
                        if($count == 1)
                        {
                            $kilometer = '0';
                        }
                        else
                        {
                            $latitude2 = $lab->latitude;
                            $longitude2 = $lab->longitude;
                        }
                        @endphp  
                      @elseif($User->status == '4')
                        @php 
                        $hospital = DB::table('add_hospital')->select('name','latitude','longitude')->where('hospital_id',$User->status_id)->first();
                        if($count == 1)
                        {
                            $kilometer = '0';
                        }
                        else
                        {
                            $latitude2 = $hospital->latitude;
                            $longitude2 = $hospital->longitude;
                        }
                        @endphp  
                      @endif

                      @php 
                      if($count  == 1)
                      {

                      }
                      else
                      {
                        $unit = 'miles';

                        $theta = $longitude1 - $longitude2; 
                        $distance = (sin(deg2rad($latitude1)) * sin(deg2rad($latitude2))) + (cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * cos(deg2rad($theta))); 
                        $distance = acos($distance); 
                        $distance = rad2deg($distance); 
                        $distance = $distance * 60 * 1.1515; 
                        $distance = $distance * 1.609344; 
                        $kilometer = round($distance,2);
                      }
                      @endphp
                    <tr>
                      <td>{{ $loop->iteration }}</td> 
                      <td style="font-size:12px; line-height:22px;">{{$User->address}}</td>
                      <td style="font-size:12px; line-height:22px;">
                        @if($User->status == '1')
                            Collected from hospital {{$hospital->name}}
                        @elseif($User->status == '2')
                            Submitted to lab {{$lab->name}}
                        @elseif($User->status == '3')
                            Collected from lab {{$lab->name}}
                        @elseif($User->status == '4')
                            Submitted to hospital {{$hospital->name}}
                        @endif
                      </td>
                      <td style="font-size:12px; line-height:22px;">{{$User->kilometer}}</td>
                      <td style="font-size:12px; line-height:22px;">{{$kilometer}}</td>
                    </tr>
                    @php $id1 = $User->id; @endphp
                @endforeach
								</tfoot>
							</table>
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