@extends('Admin.app')
@section('title',('Kilometer Reports'))

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
<script>
function kreset()
{
    window.location.href = "{{url('panel/Reports/kreset')}}";
}
</script>
<script type="text/javascript">
  function printDiv(divName) {
     var printContents = document.getElementById(divName).innerHTML;
     var originalContents = document.body.innerHTML;

     document.body.innerHTML = printContents;

     window.print();

     document.body.innerHTML = originalContents;
}
</script>
<div class="page-wrapper">
	<div class="page-content">
		<div class="card border-top border-0 border-4 border-danger">
			<div class="card-body p-5">
                <h6 class="mb-0 text-uppercase">Kilometer Report</h6>
				<hr/>
				
                <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
					<div class="col">
						<div class="card radius-10 bg-primary bg-gradient">
							<div class="card-body">
								<div class="d-flex align-items-center" style="margin:10px 0px;">
									<div>
										<p class="mb-0 text-white">Total KMS</p>
										<h4 class="my-1 text-white">{{$purchase}}</h4>
									</div>
									<div class="text-white ms-auto font-35" style="text-align:right;"><img src="{{asset('image/distance.png')}}" style="width:46%;" />
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col">
					
						<h4 class="my-1" style="color: #2196f3;">Distance 
						@php 
						$distance_status = DB::table('distance_status')->first();
						@endphp
						<div class="form-check form-switch">
									<input class="form-check-input" type="checkbox" id="distancecheck" name="distancecheck" onclick="myFunction()" @if(!empty($distance_status))
                                                @if($distance_status->status == "1")
                                                    checked
                                                        @endif
                                            @endif>
									<label class="form-check-label"  for="flexSwitchCheckChecked" style="padding-top: 5px;">Measurement</label>
								</div>
						<!-- <input type="range" class="form-range" min="0" max="5" id="customRange2" style=" width: 50px;"> -->
						
						</h4>
						<!-- <div class="card radius-10 bg-danger bg-gradient">
							<div class="card-body">
								<div class="d-flex align-items-center">
									<div>
										<p class="mb-0 text-white">Transport to Lab</p>
										<h4 class="my-1 text-white">{{$data = DB::Table('add_collected_sample')->count();}}</h4>
									</div>
									<div class="text-white ms-auto font-35"><i class='bx bx-dollar'></i>
									</div>
								</div>
							</div>
						</div> -->
					</div>
					<div class="col">
						<div class="card radius-10 bg-warning bg-gradient">
							<div class="card-body">
								<div class="d-flex align-items-center">
									<div>
										<p class="mb-0 text-dark">Selected District KMs</p>
										<h4 class="text-dark my-1">{{$total;}}</h4>
									</div>
									<div class="text-white ms-auto font-35" style="text-align:right;"><img src="{{asset('image/distance.png')}}" style="width:60%;" />
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col">
						<div class="card radius-10 bg-success bg-gradient">
							<div class="card-body">
								<div class="d-flex align-items-center">
									<div>
										<p class="mb-0 text-white">Selected Staff KMs</p>
										<h4 class="my-1 text-white">{{$purchaseee;}}</h4>
									</div>
									<div class="text-white ms-auto font-35" style="text-align:right;"><img src="{{asset('image/distance.png')}}" style="width:60%;" />
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>


            <div class="row g-3" style="margin-top:15px;">
			  <div class="col-md-7" style="background:white; padding:10px;">
                <form class="row g-3" action="{{route('panel.Reports.kfilter')}}" method="POST" enctype="multipart/form-data">
                @csrf
                    <div class="col-md-3" style="margin-top:0px;">
						<input type="date" class="form-control" id="from_date" name="from_date" value="{{isset($from_date)?$from_date:''}}" required style="padding: 10px 15px;font-size: 14px;">
					</div>
                    <div class="col-md-3" style="margin-top:0px;">
						<input type="date" class="form-control" id="to_date" name="to_date" value="{{isset($to_date)?$to_date:''}}" required style="padding: 10px 15px;font-size: 14px;">
					</div>		
					<div class="col-4" style="margin-top:0px;">
						<button type="submit" class="btn btn-danger px-5" style="padding: 10px 15px;font-size: 14px;">Submit</button>
                	</div>
            	</form>
              </div>
              <div class="col-md-5" style="background:white; padding:10px;margin-top:0px;">
                <button type="button" onClick="kreset()" class="btn btn-dark" style="padding: 10px 20px;font-size: 14px;float: right;">Clear</button>
				<button class="btn btn-success" style="float: right;padding: 10px 20px;font-size: 14px; margin-right:5px;" id="btnExport" onclick="fnExcelReport3();">Export</button>
				<input type="button" class="btn btn-primary" onclick="printDiv('invoice')" value="Print PDF" style="float:right; padding: 10px 20px;font-size: 14px; margin-right: 5px;" />
              </div>
              <div class="col-md-3" style="padding:0px 16px;">
			  	<div class="row g-3" style=" cursor: pointer;background: #e0efff; padding: 0px 20px 20px 20px; border-radius: 8px; margin-bottom:30px;">
                    <div class="col-md-12">
						@php 
						$district_id = session()->get('district_id');
						@endphp
                        <label style="font-weight:500; margin-bottom:10px; font-size:20px;">District</label> 
                        <br>
						<div class="form-check">
                            <input class="form-check-input" type="radio" name="district" id="district0" value="0" onchange="checkcat(this)"  @if(!empty($district_id)) @if($district_id == "0")  checked @endif @endif>
                            <label class="form-check-label" for="district0">All District</label>
                        </div>
                        @php 
                        $district = DB::table('add_district')->orderBy('district_id','DESC')->get();
                        @endphp 

                        @foreach($district as $district)
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="district" id="{{$district->district_id}}" value="{{$district->district_id}}" onchange="checkcat(this)" @if(!empty($district_id)) @if($district_id == $district->district_id)  checked @endif @endif>
                            <label class="form-check-label" for="{{$district->district_id}}">{{$district->name}}</label>
                        </div>
                        @endforeach
                    </div>
                </div>

				<div class="row g-3" style=" cursor: pointer;background: #e0efff; padding: 0px 20px 20px 20px; border-radius: 8px; margin-bottom:30px;">
                    <div class="col-md-12">
						@php 
						$staff_id = session()->get('staff_id');
						@endphp
                        <label style="font-weight:500; margin-bottom:10px; font-size:20px;">Staff</label> 
                        <br>
						<div class="form-check">
                            <input class="form-check-input" type="radio" name="users" id="staff0" value="0" onchange="checkcat1(this)" @if(!empty($staff_id)) @if($staff_id == "0")  checked @endif @endif>
                            <label class="form-check-label" for="staff0">All Staff</label>
                        </div>
                        @php 
                        $users = DB::table('user')->orderBy('id','DESC')->get();
                        @endphp 

                        @foreach($users as $users)
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="users" id="staff{{$users->id}}" value="{{$users->id}}" onchange="checkcat1(this)" @if(!empty($staff_id)) @if($staff_id == $users->id)  checked @endif @endif>
                            <label class="form-check-label" for="staff{{$users->id}}">{{$users->name}}</label>
                        </div>
                        @endforeach
                    </div>
                </div>
              </div>
              <div class="col-md-9" style="background:white; padding:10px;">
              <table id="example" class="table align-middle mb-0" style="width:100%;">
				<thead class="table-light">
					<tr>
						<th style="font-size:14px;display:none;">Sr No.</th>
						<th style="font-size:14px;">Date</th>
                        <th style="font-size:14px;">Staff Name</th>
                        <th style="font-size:14px;">District</th>
                        <th style="font-size:14px;">Daily Distance<br> Covered</th>
						<th style="font-size:14px;">Actual Distance</th>
                        <th style="font-size:14px;">Collection Points</th>
					</tr>
				</thead>
				<tbody>
				
			        @foreach($User as $User)
						@php 
							$sample = DB::table('add_sample_collected_details')->Where('id',$User->id)->select('staff_id','created','submitted_date','collected_from','id','from_latitude','from_longitude','to_latitude','to_longitude','collect_latitude','collect_longitude','submit_latitude','submit_longitude')->first();

							$persample = DB::table('add_sample_box_detail')->Where('sample_auto_id',$User->id)->select('lab_id')->first();
							

							$staff = DB::table('user')->Where('id',$sample->staff_id)->select('name')->first();

							$hospital = DB::table('add_hospital')->Where('hospital_id',$sample->collected_from)->select('name')->first();

                            $lab = DB::table('add_lab')->Where('lab_id',$persample->lab_id)->select('district_id','name')->first();

                            $district = DB::table('add_district')->Where('district_id',$lab->district_id)->select('name')->first();

							$sample6 = DB::table('add_sample_collected_details')->Where('id',$User->id)->select('kilometer','to_kilometer','collect_lab_kilometer','submit_hospital_kilometer')->first();

							if($sample6->kilometer == 'N/A')
							{
								$sample6->kilometer = 0;
							}
							if($sample6->to_kilometer == 'N/A')
							{
								$sample6->to_kilometer = 0;
							}
							if($sample6->collect_lab_kilometer == 'N/A')
							{
								$sample6->collect_lab_kilometer = 0;
							}
							if($sample6->submit_hospital_kilometer == 'N/A')
							{
								$sample6->submit_hospital_kilometer = 0;
							}

							$val15 = $sample6->kilometer;
							$val25 = $sample6->to_kilometer;
							$val35 = $sample6->collect_lab_kilometer;
							$val45 = $sample6->submit_hospital_kilometer;
							$val = $val15 + $val25 + $val35 + $val45;
							$purchaseee3 = $val;

							$actualdata = DB::table('add_sample_box_detail')->Where('sample_auto_id',$User->id)->select('lab_id')->GroupBy('lab_id')->get();
							$count = 0;

							$total_kilometer = 0;
                        @endphp 
							
						@foreach($actualdata as $actualdata)
							@php 
							$count = $count + 1;
							$actualdata1 = DB::table('add_sample_box_detail')->Where('sample_auto_id',$User->id)->Where('lab_id',$actualdata->lab_id)->select('collected_from','lab_id','from_latitude','from_longitude','to_latitude','to_longitude','collect_latitude','collect_longitude','submit_latitude','submit_longitude')->first();

							$hospitalactual = DB::table('add_hospital')->Where('hospital_id',$actualdata1->collected_from)->select('latitude','longitude')->first();

							$labactual = DB::table('add_lab')->Where('lab_id',$actualdata1->lab_id)->select('latitude','longitude')->first();


							$latitude1process = $hospitalactual->latitude;
							$longitude1process = $hospitalactual->longitude;

							$latitude2process = $labactual->latitude;
							$longitude2process = $labactual->longitude;

							$unit = 'miles';

							$theta = $longitude1process - $longitude2process; 
							$distance = (sin(deg2rad($latitude1process)) * sin(deg2rad($latitude2process))) + (cos(deg2rad($latitude1process)) * cos(deg2rad($latitude2process)) * cos(deg2rad($theta))); 
							$distance = acos($distance); 
							$distance = rad2deg($distance); 
							$distance = $distance * 60 * 1.1515; 

							$distance = $distance * 1.609344; 
							
							$first_kilometer = round($distance,2);

							$latitude1process1 = $labactual->latitude;
							$longitude1process1 = $labactual->longitude;

							$latitude2process1 = $labactual->latitude;
							$longitude2process1 = $labactual->longitude;

							$theta1 = $longitude1process1 - $longitude2process1; 
							$distance1 = (sin(deg2rad($latitude1process1)) * sin(deg2rad($latitude2process1))) + (cos(deg2rad($latitude1process1)) * cos(deg2rad($latitude2process1)) * cos(deg2rad($theta1))); 
							$distance1 = acos($distance1); 
							$distance1 = rad2deg($distance1); 
							$distance1 = $distance1 * 60 * 1.1515; 
							$distance1 = $distance1 * 1.609344; 

							$second_kilometer = round($distance1,2);

							$latitude1process2 = $labactual->latitude;
							$longitude1process2 = $labactual->longitude;

							$latitude2process2 = $hospitalactual->latitude;
							$longitude2process2 = $hospitalactual->longitude;

							$theta2 = $longitude1process2 - $longitude2process2; 
							$distance2 = (sin(deg2rad($latitude1process2)) * sin(deg2rad($latitude2process2))) + (cos(deg2rad($latitude1process2)) * cos(deg2rad($latitude2process2)) * cos(deg2rad($theta2))); 
							$distance2 = acos($distance2); 
							$distance2 = rad2deg($distance2); 
							$distance2 = $distance2 * 60 * 1.1515; 
							$distance2 = $distance2 * 1.609344; 

							$third_kilometer = round($distance2,2);

							$total_kilometer1 = $first_kilometer + $second_kilometer + $third_kilometer;

							$total_kilometer = $total_kilometer1 + $total_kilometer; 
							@endphp 

							
						@endforeach

                    <tr>
					  <td style="display:none;">{{ $loop->iteration }}</td> 
					  <td style="font-size:12px;">
					  <a href="{{route('panel.Reports.report-sample',[$sample->id])}}"> 
					  {{\Carbon\Carbon::parse($sample->created)->format('d-m-Y')}}</a></td>
                      <td style="font-size:12px;">{{$staff->name}}</td>
					  <td style="font-size:12px;">{{$district->name}}</td>
                      <td style="font-size:12px;">{{$purchaseee3}}</td>
					  <td style="font-size:12px;">
					  <!-- {{$first_kilometer}} &nbsp;<br> {{$second_kilometer}} &nbsp; <br> {{$third_kilometer}} &nbsp; <br> -->
					  
					  {{$total_kilometer}}
					</td>
                      <td style="font-size:12px;">{{$hospital->name}}</td>
                    </tr>
                @endforeach
				
				</tbody>
			    </table>
              </div>
            </div>

          
					</div>
				</div>
			
			</div>
		</div>


@endsection
@push('script')
<script>
    function checkcat1(radio) {
      selected_value = $("input[name='users']:checked").val();
	  window.location.href = "{{url('panel/Reports/staff-kilometer-report')}}/"+selected_value;
    }
</script>

<script>
    function checkcat(radio) {
      selected_value = $("input[name='district']:checked").val();
	  window.location.href = "{{url('panel/Reports/district-kilometer-report')}}/"+selected_value;
    }
</script>
<script>
function myFunction() {
  var checkBox = document.getElementById("distancecheck");

  if (checkBox.checked == true){
	alert('Distance Measurement is ON');
	window.location.href = "{{url('panel/Reports/distance-check')}}/1";
  } else {
	alert('Distance Measurement is OFF');
	window.location.href = "{{url('panel/Reports/distance-check')}}/0";
  }
}
</script>
<script>
   function fnExcelReport3()
   {
    var tab_text="<table border='2px'><tr bgcolor='#87AFC6'>";
    var textRange; var j=0;
    tab = document.getElementById('example'); // id of table

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