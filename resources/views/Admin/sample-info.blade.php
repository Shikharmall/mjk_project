@section('title',('Sample Information'))
@section('content')
<!doctype html>
<html lang="en" class="color-sidebar sidebarcolor4 color-header headercolor5">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="http://mjk.workfordemo.in/image/logo.png" type="image/png" />
	<link href="{{asset('assets')}}/plugins/notifications/css/lobibox.min.css" rel="stylesheet"/>
	<link href="{{asset('assets')}}/plugins/vectormap/jquery-jvectormap-2.0.2.css" rel="stylesheet"/>
	<link href="{{asset('assets')}}/plugins/simplebar/css/simplebar.css" rel="stylesheet" />
	<link href="{{asset('assets')}}/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" />
	<link href="{{asset('assets')}}/plugins/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
	<link href="{{asset('assets')}}/plugins/metismenu/css/metisMenu.min.css" rel="stylesheet" />
	<link href="{{asset('assets')}}/css/pace.min.css" rel="stylesheet" />
	<script src="{{asset('assets')}}/js/pace.min.js"></script>
	<link href="{{asset('assets')}}/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&amp;display=swap" rel="stylesheet">
	<link href="{{asset('assets')}}/css/app.css" rel="stylesheet">
	<link href="{{asset('assets')}}/css/icons.css" rel="stylesheet">
	<link rel="stylesheet" href="{{asset('assets')}}/css/dark-theme.css" />
	<link rel="stylesheet" href="{{asset('assets')}}/css/semi-dark.css" />
	<link rel="stylesheet" href="{{asset('assets')}}/css/header-colors.css" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
  <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.1/themes/base/minified/jquery-ui.min.css" type="text/css" />
	<title>MJK | @yield('title')</title>
	<style>
		html.headercolor5 .topbar {
			background: #2c0b36;
		}
		.sidebar-wrapper .metismenu a .parent-icon {
			font-size: 20px;
			line-height: 1;
		}
		.sidebar-wrapper .metismenu ul a {
			padding: 6px 15px 6px 15px;
			font-size: 13px;
			border: 0;
		}
		.sidebar-wrapper .metismenu a {
			position: relative;
			display: flex;
			align-items: center;
			justify-content: left;
			padding: 8px 15px;
			font-size: 14px;
			color: #5f5f5f;
			outline-width: 0;
			text-overflow: ellipsis;
			overflow: hidden;
			letter-spacing: .5px;
			border: 1px solid #ffffff00;
			transition: all .3s ease-out;
		}
	</style>
</head>

<body>
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
.border-end {
    border-right: 2px solid #2d96ff!important;
}
.geeks {
                height:250px;
            }
			.geeks1 {
                height:150px;
				margin-bottom:10px;
            }
            img {
                width:100%;
                height:100%;
                object-fit:cover;
            }
</style>
<div class="comtainer">
	<div class="row">
		<div class="col-md-1">
		</div>
		<div class="col-md-10">
			<div class="page-content">
				<div class="card border-top border-0 border-4 border-danger">
					<div class="card-body p-5">
						<!-- <h6 class="mb-0 text-uppercase">Admin Sample Info</h6>
						<hr/> -->
						@php 
							$Sample = DB::table('add_sample_box_detail')->Where('id',$id)->first();

							$staff = DB::table('user')->Where('id',$Sample->staff_id)->select('name')->first();

							$hospital = DB::table('add_hospital')->Where('hospital_id',$Sample->collected_from)->select('name','latitude','longitude','gps_address')->first();

							$lab = DB::table('add_lab')->Where('lab_id',$Sample->lab_id)->select('district_id','name','latitude','longitude','gps_address')->first();

							$specimen = DB::table('add_specimen')->Where('id',$Sample->specimen_id)->select('name')->first();

							$Test = DB::table('add_test')->Where('id',$Sample->test_id)->select('name')->first();


							$Submitlabcount = DB::table('add_collected_sample')->join('add_collected_report','add_collected_sample.report_id','add_collected_report.report_id')->Where('add_collected_sample.sample_selected_id',$id)->count();


							$Submitlab = DB::table('add_collected_sample')->join('add_collected_report','add_collected_sample.report_id','add_collected_report.report_id')->Where('add_collected_sample.sample_selected_id',$id)->select('add_collected_report.lt_name','add_collected_report.designation','add_collected_report.digital_signature')->first();

							$collectlabcount = DB::table('add_collect_submitted_sample')->join('add_collect_submitted_report','add_collect_submitted_sample.report_id','add_collect_submitted_report.report_id')->Where('add_collect_submitted_sample.sample_selected_id',$id)->count();

							$collectlab = DB::table('add_collect_submitted_sample')->join('add_collect_submitted_report','add_collect_submitted_sample.report_id','add_collect_submitted_report.report_id')->Where('add_collect_submitted_sample.sample_selected_id',$id)->select('add_collect_submitted_report.lt_name','add_collect_submitted_report.designation','add_collect_submitted_report.digital_signature')->first();

							$Submithoscount = DB::table('add_submitted_hospital_sample')->join('add_submitted_hospital_report','add_submitted_hospital_sample.report_id','add_submitted_hospital_report.report_id')->Where('add_submitted_hospital_sample.sample_selected_id',$id)->count();

							$Submithos = DB::table('add_submitted_hospital_sample')->join('add_submitted_hospital_report','add_submitted_hospital_sample.report_id','add_submitted_hospital_report.report_id')->Where('add_submitted_hospital_sample.sample_selected_id',$id)->select('add_submitted_hospital_report.lt_name','add_submitted_hospital_report.designation','add_submitted_hospital_report.digital_signature')->first();
						@endphp 
						<div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
							<div class="col">
								<div class="card radius-10 bg-primary bg-gradient" style="height: 110px;">
									<div class="card-body">
										<div>
											<div>
												<p class="mb-0 text-white" style="font-size: 15px;margin-bottom: 5px !important;"><b>Nikshay Id : </b>{{$Sample->nikshay_id}}</p>
												<p class="mb-0 text-white" style="font-size: 15px;margin-bottom: 5px !important;"><b>Patient Name : </b>{{$Sample->patient}}</p>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col">
								<div class="card radius-10 bg-danger bg-gradient" style="height: 110px;">
									<div class="card-body">
										<div>
											<div>
												<p class="mb-0 text-white" style="font-size: 15px;margin-bottom: 5px !important;"><b>Staff : </b>{{$staff->name}}</p>
												<p class="mb-0 text-white" style="font-size: 15px;margin-bottom: 5px !important;"><b>Patient Type : </b>{{$Sample->type_patient}}</p>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col">
								<div class="card radius-10 bg-warning bg-gradient" style="height: 110px;">
									<div class="card-body">
										<div>
											<div>
												<p class="mb-0 text-white" style="font-size: 15px;margin-bottom: 5px !important;"><b>Test For : </b>{{$Sample->type_test_for}}</p>
												<p class="mb-0 text-white" style="font-size: 15px;margin-bottom: 5px !important;"><b>Specimen : </b>{{$specimen->name}}</p>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col">
								<div class="card radius-10 bg-success bg-gradient" style="height: 110px;">
									<div class="card-body">
										<div>
											<div>
												<p class="mb-0 text-white" style="font-size: 15px;margin-bottom: 5px !important;"><b>Test Type : </b>{{$Test->name}}</p>
												<p class="mb-0 text-white" style="font-size: 15px;margin-bottom: 5px !important;"><b>Lab : </b>{{$lab->name}}</p>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
							<div class="col">
								<div class="card radius-10 bg-primary bg-gradient" style="height: 110px;">
									<div class="card-body">
										<div>
											<div>
												<p class="mb-0 text-white" style="font-size: 15px;margin-bottom: 5px !important;"><b>Origin : </b>{{$hospital->name}}</p>
												<p class="mb-0 text-white" style="font-size: 15px;margin-bottom: 5px !important;"><b>Sample Number : </b>{{$Sample->no_of_sample}}</p>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="row g-3" style="background: #e0efff; padding: 0px 20px 20px 20px; border-radius: 8px; margin-bottom:30px; margin-top:20px;">
							<div class="col-md-12">
								<div class="row">
									<div class="col-auto text-center flex-column d-none d-sm-flex">
										<h5 class="m-2"><span class="badge rounded-pill bg-primary">&nbsp;</span></h5>
										<div class="row h-100">
												<div class="col border-end">&nbsp;</div>
												<div class="col">&nbsp;</div>
										</div>
									</div>
									<div class="col py-2">
										<div class="card-body" style="padding-top:0px;">
											<div class="row">
												<div class="col-md-8">
													<h4 class="card-title text-primary">Sample Collected</h4>
													<p class="card-text" style="margin-bottom:5px;"><label style="font-weight:500;">Collection Point : </label> {{$hospital->name}}</p>
													<p class="card-text" style="margin-bottom:5px;"><label style="font-weight:500;">Collection Point Address : </label> {{$hospital->gps_address}}</p>
													<p class="card-text" style="margin-bottom:5px;"><label style="font-weight:500;">Map Area Name : </label> {{$Sample->map_area_name}}</p>
													<p class="card-text" style="margin-bottom:5px;"><label style="font-weight:500;">Geolocation : </label> {{$hospital->latitude}} , {{$hospital->longitude}} || {{$Sample->from_latitude}} , {{$Sample->from_longitude}}</p>
													<a href="javascript:;" data-bs-toggle="modal" data-bs-target="#exampleModal0{{$id}}">View attachments --></a>
												</div>
												<a target="_blank" href="{{asset('image/'.$Sample->sample_meter_photo)}}" class="col-md-4 geeks">
													<img src="{{asset('image/'.$Sample->sample_meter_photo)}}" style="width:100%;" />
												</a>
												<div class="modal fade" id="exampleModal0{{$id}}" tabindex="-1" aria-hidden="true">
													<div class="modal-dialog" style="max-width: 550px;margin-top: 70px; box-shadow:0 3px 10px rgb(65 60 62);">
													<div class="modal-content">
														<div class="modal-header">
														<h5 class="modal-title">Sample Photo</h5>
														<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
														</div>
														<div class="modal-body" style="text-align:center;">
														<div class="row" style="margin-bottom:30px;">
														<a target="_blank" class="col-md-4 geeks1" href="{{asset('image/'.$Sample->sample_meter_photo)}}">
														<img src="{{asset('image/'.$Sample->sample_meter_photo)}}" style="width:100%;" /><br>
														<label style="font-weight:500;margin-top:8px;">Sample Meter Photo</label>
														</a>
														<a target="_blank" class="col-md-4 geeks1" href="{{asset('image/'.$Sample->sample_box_photo)}}">
														<img src="{{asset('image/'.$Sample->sample_box_photo)}}" style="width:100%;" /><br>
														<label style="font-weight:500;margin-top:8px;">Sample Box Photo</label>
														</a>
														<a target="_blank" class="col-md-4 geeks1" href="{{asset('image/'.$Sample->invoice_photo)}}">
														<img src="{{asset('image/'.$Sample->invoice_photo)}}" style="width:100%;" /><br>
														<label style="font-weight:500;margin-top:8px;">Sample Invoice Photo</label>
														</a>
														</div>
														</div>
													</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								@if($Submitlabcount != 0)
								<div class="row">
									<div class="col-auto text-center flex-column d-none d-sm-flex">
										<h5 class="m-2"><span class="badge rounded-pill bg-primary">&nbsp;</span></h5>
										<div class="row h-100">
												<div class="col border-end">&nbsp;</div>
												<div class="col">&nbsp;</div>
										</div>
									</div>
									<div class="col py-2">
										<div class="card-body" style="padding-top:0px;">
											<div class="row">
												<div class="col-md-8">
													<h4 class="card-title text-primary">Sample Submitted</h4>
													<p class="card-text" style="margin-bottom:5px;"><label style="font-weight:500;">Lab Name : </label> {{$lab->name}}</p>
													<p class="card-text" style="margin-bottom:5px;"><label style="font-weight:500;">Lab Address : </label> {{$lab->gps_address}}</p>
													<p class="card-text" style="margin-bottom:5px;"><label style="font-weight:500;">Map Area Name : </label> {{$Sample->to_map_area_name}}</p>
													<p class="card-text" style="margin-bottom:5px;"><label style="font-weight:500;">Geolocation : </label> {{$lab->latitude}} , {{$lab->longitude}} || {{$Sample->to_latitude}} , {{$Sample->to_longitude}}</p>
													
													<p class="card-text" style="margin-bottom:5px;"><label style="font-weight:500;">Acknowledged By : </label> {{$Submitlab->lt_name}}</p>
													<p class="card-text" style="margin-bottom:5px;"><label style="font-weight:500;">Acknowledged Designation : </label> {{$Submitlab->designation}}</p>
													<a href="javascript:;" data-bs-toggle="modal" data-bs-target="#exampleModal1{{$id}}">View attachments --></a><br>
													<a href="javascript:;" data-bs-toggle="modal" data-bs-target="#exampleModal00{{$id}}">View Digital Signature --></a>
												</div>
												<a target="_blank" href="{{asset('image/'.$Sample->to_sample_meter_photo)}}" class="col-md-4 geeks">
													<img src="{{asset('image/'.$Sample->to_sample_meter_photo)}}" style="width:100%;" />
												</a>

												<div class="modal fade" id="exampleModal1{{$id}}" tabindex="-1" aria-hidden="true">
													<div class="modal-dialog" style="max-width: 550px;margin-top: 70px; box-shadow:0 3px 10px rgb(65 60 62);">
													<div class="modal-content">
														<div class="modal-header">
														<h5 class="modal-title">Sample Photo</h5>
														<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
														</div>
														<div class="modal-body" style="text-align:center;">
														<div class="row"  style="margin-bottom:30px;">
														<a target="_blank" href="{{asset('image/'.$Sample->to_sample_meter_photo)}}" class="col-md-4 geeks1">
														<img src="{{asset('image/'.$Sample->to_sample_meter_photo)}}" style="width:100%;" /><br>
														<label style="font-weight:500;margin-top:8px;">Sample Meter Photo</label>
														</a>
														<a target="_blank" href="{{asset('image/'.$Sample->to_sample_box_photo)}}" class="col-md-4 geeks1">
														<img src="{{asset('image/'.$Sample->to_sample_box_photo)}}" style="width:100%;" /><br>
														<label style="font-weight:500;margin-top:8px;">Sample Box Photo</label>
														</a>
														<a target="_blank" href="{{asset('image/'.$Sample->invoice_photo)}}" class="col-md-4 geeks1">
														<img src="{{asset('image/'.$Sample->invoice_photo)}}" style="width:100%;" /><br>
														<label style="font-weight:500;margin-top:8px;">Sample Invoice Photo</label>
														</a>
														</div>
														</div>
													</div>
													</div>
												</div>
												<div class="modal fade" id="exampleModal00{{$id}}" tabindex="-1" aria-hidden="true">
													<div class="modal-dialog" style="max-width: 400px;margin-top: 70px; box-shadow:0 3px 10px rgb(65 60 62);">
													<div class="modal-content">
														<div class="modal-header">
														<h5 class="modal-title">Digital Signature Image</h5>
														<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
														</div>
														<div class="modal-body" style="text-align:center;">
														<img src="{{asset('image/'.$Submitlab->digital_signature)}}" style="width:100%;" />
														</div>
													</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								@endif
								@if($collectlabcount != 0)
								<div class="row">
									<div class="col-auto text-center flex-column d-none d-sm-flex">
										<h5 class="m-2"><span class="badge rounded-pill bg-primary">&nbsp;</span></h5>
										<div class="row h-100">
												<div class="col border-end">&nbsp;</div>
												<div class="col">&nbsp;</div>
										</div>
									</div>
									<div class="col py-2">
										<div class="card-body" style="padding-top:0px;">
											<div class="row">
												<div class="col-md-8">
													<h4 class="card-title text-primary">Collected Report</h4>
													<p class="card-text" style="margin-bottom:5px;"><label style="font-weight:500;">Lab Name : </label> {{$lab->name}}</p>
													<p class="card-text" style="margin-bottom:5px;"><label style="font-weight:500;">Lab Address : </label> {{$lab->gps_address}}</p>
													<p class="card-text" style="margin-bottom:5px;"><label style="font-weight:500;">Map Area Name : </label> {{$Sample->collect_map_area_name}}</p>
													<p class="card-text" style="margin-bottom:5px;"><label style="font-weight:500;">Geolocation : </label> {{$lab->latitude}} , {{$lab->longitude}} || {{$Sample->collect_latitude}} , {{$Sample->collect_longitude}}</p>
													<p class="card-text" style="margin-bottom:5px;"><label style="font-weight:500;">Acknowledged By : </label> {{$collectlab->lt_name}}</p>
													<p class="card-text" style="margin-bottom:5px;"><label style="font-weight:500;">Acknowledged Designation : </label> {{$collectlab->designation}}</p>
													<a href="javascript:;" data-bs-toggle="modal" data-bs-target="#exampleModal2{{$id}}">View attachments --></a><br>
													<a href="javascript:;" data-bs-toggle="modal" data-bs-target="#exampleModal02{{$id}}">View Digital Signature --></a>
												</div>
												<a target="_blank" class="col-md-4 geeks" href="{{asset('image/'.$Sample->collect_lab_sample_meter_photo)}}">
													<img src="{{asset('image/'.$Sample->collect_lab_sample_meter_photo)}}" style="width:100%;" />
												</a>
												<div class="modal fade" id="exampleModal2{{$id}}" tabindex="-1" aria-hidden="true">
													<div class="modal-dialog" style="max-width: 550px;margin-top: 70px; box-shadow:0 3px 10px rgb(65 60 62);">
													<div class="modal-content">
														<div class="modal-header">
														<h5 class="modal-title">Sample Multiple Images</h5>
														<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
														</div>
														<div class="modal-body" style="text-align:center;">
														@php 
														$Sampleimage = DB::table('add_collected_sample_multiple_images')->Where('sample_id',$id)->get();
														@endphp
														<div class="row" style="margin-bottom:30px;">
															@foreach($Sampleimage as $Sampleimage)
															<a target="_blank" href="{{asset('image/'.$Sampleimage->image)}}" class="col-md-4 geeks1">
																<img src="{{asset('image/'.$Sampleimage->image)}}" style="width:100%;" />
															</a>
															@endforeach
														</div>
														
														</div>
													</div>
													</div>
												</div>
												<div class="modal fade" id="exampleModal02{{$id}}" tabindex="-1" aria-hidden="true">
													<div class="modal-dialog" style="max-width: 400px;margin-top: 70px; box-shadow:0 3px 10px rgb(65 60 62);">
													<div class="modal-content">
														<div class="modal-header">
														<h5 class="modal-title">Digital Signature Image</h5>
														<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
														</div>
														<div class="modal-body" style="text-align:center;">
														<img src="{{asset('image/'.$collectlab->digital_signature)}}" style="width:100%;" />
														</div>
													</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								@endif
								@if($Submithoscount != 0)
								<div class="row">
									<div class="col-auto text-center flex-column d-none d-sm-flex">
										<h5 class="m-2"><span class="badge rounded-pill bg-primary">&nbsp;</span></h5>
										<div class="row h-100">
												<div class="col border-end">&nbsp;</div>
												<div class="col">&nbsp;</div>
										</div>
									</div>
									<div class="col py-2">
										<div class="card-body" style="padding-top:0px;">
											<div class="row">
												<div class="col-md-8">
													<h4 class="card-title text-primary">Submitted Report</h4>
													<p class="card-text" style="margin-bottom:5px;"><label style="font-weight:500;">Collection Point : </label> {{$hospital->name}}</p>
													<p class="card-text" style="margin-bottom:5px;"><label style="font-weight:500;">Collection Point Address : </label> {{$hospital->gps_address}}</p>
													<p class="card-text" style="margin-bottom:5px;"><label style="font-weight:500;">Map Area Name : </label> {{$Sample->submit_map_area_name}}</p>
													<p class="card-text" style="margin-bottom:5px;"><label style="font-weight:500;">Geolocation : </label> {{$hospital->latitude}} , {{$hospital->longitude}} || {{$Sample->submit_latitude}} , {{$Sample->submit_longitude}}</p>
													
													<p class="card-text" style="margin-bottom:5px;"><label style="font-weight:500;">Acknowledged By : </label> {{$Submithos->lt_name}}</p>
													<p class="card-text" style="margin-bottom:5px;"><label style="font-weight:500;">Acknowledged Designation : </label> {{$Submithos->designation}}</p>
													<a href="javascript:;" data-bs-toggle="modal" data-bs-target="#exampleModal03{{$id}}">View Digital Signature --></a>
												</div>
												<a target="_blank" class="col-md-4 geeks" href="{{asset('image/'.$Sample->submit_hospital_sample_meter_photo)}}">
													<img src="{{asset('image/'.$Sample->submit_hospital_sample_meter_photo)}}" style="width:100%;" />
												</a>
												<div class="modal fade" id="exampleModal03{{$id}}" tabindex="-1" aria-hidden="true">
													<div class="modal-dialog" style="max-width: 400px;margin-top: 70px; box-shadow:0 3px 10px rgb(65 60 62);">
													<div class="modal-content">
														<div class="modal-header">
														<h5 class="modal-title">Digital Signature Image</h5>
														<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
														</div>
														<div class="modal-body" style="text-align:center;">
														<img src="{{asset('image/'.$Submithos->digital_signature)}}" style="width:100%;" />
														</div>
													</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								@endif
							</div>
						</div>

						

				
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-1">
		</div>
	</div>
</div>


<script src="{{asset('assets')}}/js/bootstrap.bundle.min.js"></script>
	<!--plugins-->
	<script src="{{asset('assets')}}/js/jquery.min.js"></script>
	<script src="{{asset('assets')}}/plugins/simplebar/js/simplebar.min.js"></script>
	<script src="{{asset('assets')}}/plugins/metismenu/js/metisMenu.min.js"></script>
	<script src="{{asset('assets')}}/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
	<script src="{{asset('assets')}}/plugins/vectormap/jquery-jvectormap-2.0.2.min.js"></script>
    <script src="{{asset('assets')}}/plugins/vectormap/jquery-jvectormap-world-mill-en.js"></script>
	<script src="{{asset('assets')}}/plugins/chartjs/js/Chart.min.js"></script>
	<script src="{{asset('assets')}}/plugins/chartjs/js/Chart.extension.js"></script>
	<script src="{{asset('assets')}}/plugins/sparkline-charts/jquery.sparkline.min.js"></script>
	<!--notification js -->
	<script src="{{asset('assets')}}/plugins/notifications/js/lobibox.min.js"></script>
	<script src="{{asset('assets')}}/plugins/notifications/js/notifications.min.js"></script>
	<script src="{{asset('assets')}}/js/index2.js"></script>

	<script src="{{asset('assets')}}/plugins/datatable/js/jquery.dataTables.min.js"></script>
	<script src="{{asset('assets')}}/plugins/datatable/js/dataTables.bootstrap5.min.js"></script>
	<script>
		$(document).ready(function() {
			$('#example').DataTable();
		  } );
	</script>
	<script>
		$(document).ready(function() {
			var table = $('#example2').DataTable( {
				lengthChange: false,
				buttons: [ 'copy', 'excel', 'pdf', 'print']
			} );
		 
			table.buttons().container()
				.appendTo( '#example2_wrapper .col-md-6:eq(0)' );
		} );
	</script>
	<!--app JS-->
	<script src="{{asset('assets')}}/js/app.js"></script>
	<script src="{{asset('assets')}}/js/sweet_alert.js"></script>
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
</body>
</html>