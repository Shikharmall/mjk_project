@extends('Admin.app')
@section('title',('Sample Report'))

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
function reset()
{
    window.location.href = "{{url('panel/Reports/reset')}}";
}
</script>
<script>
function reset1()
{
    window.location.href = "{{url('panel/Reports/reset-all')}}";
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
                <h6 class="mb-0 text-uppercase" style="padding-bottom:10px;">Sample Report <button type="button" onClick="reset1()" class="btn btn-dark" style="padding: 10px 20px;font-size: 14px;float: right;">Clear All</button></h6>
				<hr/>
				
                <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
					<div class="col">
                        <form class="row g-3" action="{{route('panel.Reports.sample-collected')}}" method="POST" enctype="multipart/form-data">
                        @csrf
						<button type="submit" class="card radius-10 bg-primary bg-gradient" style="text-align: left; width: 100%;">
							<div class="card-body" style="width:100%;">
								<div class="d-flex align-items-center" style="margin: 6px 0px;">
									<div>
										<p class="mb-0 text-white">Sample Collected</p>
										<h4 class="my-1 text-white">
										{{$data}}
										</h4>
									</div>
									<div class="text-white ms-auto font-35" style="text-align:right;"><img src="{{asset('image/collection.png')}}" style="width:60%;" />
									</div>
								</div>
							</div>
						</button>
                        </form>
					</div>
					<div class="col">
                        <form class="row g-3" action="{{route('panel.Reports.sample-submitted')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <button type="submit" class="card radius-10 bg-danger bg-gradient" style="text-align: left; width: 100%;">
							<div class="card-body" style="width:100%;">
								<div class="d-flex align-items-center" style="margin: 6px 0px;">
									<div>
										<p class="mb-0 text-white">Transport to Lab</p>
										<h4 class="my-1 text-white">
											{{$data2}}
										</h4>
									</div>
									<div class="text-white ms-auto font-35" style="text-align:right;"><img src="{{asset('image/submitted.png')}}" style="width:50%;" />
									</div>
								</div>
							</div>
                        </button>
                        </form>
					</div>
					<div class="col">
                        <form class="row g-3" action="{{route('panel.Reports.sample-collection')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <button type="submit" class="card radius-10 bg-warning bg-gradient" style="text-align: left; width: 100%;">
							<div class="card-body" style="width:100%;">
								<div class="d-flex align-items-center" style="margin: 6px 0px;">
									<div>
										<p class="mb-0 text-dark">Report Collected</p>
										<h4 class="text-dark my-1">
											{{$data3}}
										</h4>
									</div>
									<div class="text-dark ms-auto font-35" style="text-align:right;"><img src="{{asset('image/report.png')}}" style="width:60%;" />
									</div>
								</div>
							</div>
                        </button>
                        </form>
					</div>
					<div class="col">
                        <form class="row g-3" action="{{route('panel.Reports.sample-submittion')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <button type="submit" class="card radius-10 bg-success bg-gradient" style="text-align: left; width: 100%;">
							<div class="card-body" style="width:100%;">
								<div class="d-flex align-items-center" style="margin: 6px 0px;">
									<div>
										<p class="mb-0 text-white">Report Submitted</p>
										<h4 class="my-1 text-white">
											{{$data4}}
										</h4>
									</div>
									<div class="text-white ms-auto font-35" style="text-align:right;"><img src="{{asset('image/submit_hospital.png')}}" style="width:60%;" />
									</div>
								</div>
							</div>
						</button>
                        </form>
					</div>
				</div>


            <div class="row g-3" style="margin-top:15px;">
			  <div class="col-md-7" style="background:white; padding:10px;">
                <form class="row g-3" action="{{route('panel.Reports.filter')}}" method="POST" enctype="multipart/form-data">
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
                <button type="button" onClick="reset()" class="btn btn-dark" style="padding: 10px 20px;font-size: 14px;float: right;">Clear Filter</button>
				<button class="btn btn-success" style="float: right;padding: 10px 20px;font-size: 14px; margin-right:5px;" id="btnExport" onclick="fnExcelReport3();">Export</button>
				<input type="button" class="btn btn-primary" onclick="printDiv('invoice')" value="Print PDF" style="float:right; padding: 10px 20px;font-size: 14px; margin-right: 5px;" />
              </div>
              <div class="col-md-3" style="padding:0px 16px;">
			  	<div class="row g-3" style=" cursor: pointer;background: #e0efff; padding: 0px 20px 20px 20px; border-radius: 8px; margin-bottom:30px;">
                    <div class="col-md-12">
						@php 
						$district_id = session()->get('sample_district_id');
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
						$staff_id = session()->get('sample_staff_id');
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
              <div class="col-md-9"  id="invoice" style="background:white; padding:10px;">
              <table id="example" class="table align-middle mb-0" style="width:100%;">
				<thead class="table-light">
					<tr>
                        <th style="font-size:14px;">Sr No.</th>
						<!-- <th style="font-size:14px;">Sample Id</th> -->
                        <th style="font-size:14px;">Staff Name</th>
                        <th style="font-size:14px;">Collected From</th>
                        <th style="font-size:14px;">Collected Date<br> Submitted Date</th>
                        <th style="font-size:14px;">Lab Name</th>
                        <th style="font-size:14px;">District</th>
					</tr>
				</thead>
				<tbody>
				    @foreach($User as $User)
					
						@php 
							$sample = DB::table('add_sample_collected_details')->Where('id',$User->id)->select('staff_id','created','submitted_date','collected_from')->first();

							$persample = DB::table('add_sample_box_detail')->Where('sample_auto_id',$User->id)->Where('lab_id',$User->lab_id)->select('lab_id')->first();

							$staff = DB::table('user')->Where('id',$sample->staff_id)->select('name')->first();

							$hospital = DB::table('add_hospital')->Where('hospital_id',$sample->collected_from)->select('name')->first();

                            $lab = DB::table('add_lab')->Where('lab_id',$persample->lab_id)->select('district_id','name')->first();

                            $district = DB::table('add_district')->Where('district_id',$lab->district_id)->select('name')->first();
                        @endphp 
                    <tr>
                      <td>{{ $loop->iteration}}</td> 
					  <td style="font-size:12px;">{{$staff->name}}</td>
					  <td style="font-size:12px;">{{$hospital->name}}</td>
					  <td style="font-size:12px;">{{\Carbon\Carbon::parse($sample->created)->format('d-m-Y')}}<br>{{\Carbon\Carbon::parse($sample->submitted_date)->format('d-m-Y')}}</td>
					  <td style="font-size:12px;">{{$lab->name}}</td>
					  <td style="font-size:12px;">{{$district->name}}</td>
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


@endsection
@push('script')
<script>
    function checkcat1(radio) {
      selected_value = $("input[name='users']:checked").val();
      window.location.href = "{{url('panel/Reports/staff-sample-report')}}/"+selected_value;
    }
</script>

<script>
    function checkcat(radio) {
      selected_value = $("input[name='district']:checked").val();
      window.location.href = "{{url('panel/Reports/district-sample-report')}}/"+selected_value;
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