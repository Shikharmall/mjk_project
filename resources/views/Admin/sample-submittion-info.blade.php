@extends('Admin.app')
@section('title',('Sample Information'))

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
.border-end {
    border-right: 2px solid #2d96ff!important;
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
<div class="page-wrapper">
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
													<p class="card-text" style="margin-bottom:5px;"><label style="font-weight:500;">Sample Collected : </label> {{$Sample->no_of_sample}}</p>
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
														<a class="col-md-4 geeks1" target="_blank" href="{{asset('image/'.$Sample->sample_meter_photo)}}">
														<img src="{{asset('image/'.$Sample->sample_meter_photo)}}" style="width:100%;" /><br>
														<label style="font-weight:500;margin-top:8px;">Sample Meter Photo</label>
														</a>
														<a target="_blank" href="{{asset('image/'.$Sample->sample_box_photo)}}" class="col-md-4 geeks1">
														<img src="{{asset('image/'.$Sample->sample_box_photo)}}" style="width:100%;" /><br>
														<label style="font-weight:500;margin-top:8px;">Sample Box Photo</label>
														</A>
														<a target="_blank" href="{{asset('image/'.$Sample->invoice_photo)}}" class="col-md-4 geeks1">
														<img src="{{asset('image/'.$Sample->invoice_photo)}}" style="width:100%;" /><br>
														<label style="font-weight:500;margin-top:8px;">Sample Invoice Photo</label>
														</A>
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
												</A>

												<div class="modal fade" id="exampleModal1{{$id}}" tabindex="-1" aria-hidden="true">
													<div class="modal-dialog" style="max-width: 550px;margin-top: 70px; box-shadow:0 3px 10px rgb(65 60 62);">
													<div class="modal-content">
														<div class="modal-header">
														<h5 class="modal-title">Sample Photo</h5>
														<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
														</div>
														<div class="modal-body" style="text-align:center;">
														<div class="row" style="margin-bottom:30px;">
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
							</div>
						</div>

						

				
					</div>
				</div>
			</div>
</div>


@endsection
