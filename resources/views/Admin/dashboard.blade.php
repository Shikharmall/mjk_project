
@extends('Admin.app')
@section('title',('Dashboard'))
@section('content')

<style>
.widgets-icons {
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #ededed00;
    font-size: 26px;
    border-radius: 10px;
}
</style>

<div class="page-wrapper">
			<div class="page-content">
				<h6 class="mb-0 text-uppercase" style="font-weight: 700;">Manage Collection</h6>
				<hr/>
				<div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
					<div class="col">
						<div class="card radius-10">
							<div class="card-body">
								<a class="d-flex align-items-center" href="{{route('panel.TodayReport.today-collection')}}">
									<div>
										<p class="mb-0 text-secondary" style="font-size: 16px;">Today Collection<br> Details</p>
										<h4 class="my-1">{{$data = DB::Table('add_sample_box_sample')->where('date',date('Y-m-d'))->count();}}</h4>
									</div>
									<div class="widgets-icons text-success ms-auto"><img src="{{asset('image/collection.png')}}" style="width:90%;"></i>
									</div>
								</a>
							</div>
						</div>
					</div>
					<div class="col">
						<div class="card radius-10">
							<div class="card-body">
								<a class="d-flex align-items-center" href="{{route('panel.TodayReport.today-submitted')}}">
									<div>
										<p class="mb-0 text-secondary" style="font-size: 16px;">Today Submitted<br> Details</p>
										<h4 class="my-1">{{$data = DB::Table('add_collected_sample')->where('created',date('Y-m-d'))->count();}}</h4>
									</div>
									<div class="widgets-icons text-success ms-auto"><img src="{{asset('image/submitted.png')}}" style="width:90%;"></i>
									</div>
								</a>
							</div>
						</div>
					</div>
					<div class="col">
						<div class="card radius-10">
							<div class="card-body">
								<a class="d-flex align-items-center" href="{{route('panel.TodayReport.today-collected-report')}}">
									<div>
										<p class="mb-0 text-secondary" style="font-size: 16px;">Today Collected<br> Reports</p>
										<h4 class="my-1">{{$data = DB::Table('add_collect_submitted_sample')->where('created',date('Y-m-d'))->count();}}</h4>
									</div>
									<div class="widgets-icons text-success ms-auto"><img src="{{asset('image/report.png')}}" style="width:90%;"></i>
									</div>
								</a>
							</div>
						</div>
					</div>
					<div class="col">
						<div class="card radius-10">
							<div class="card-body">
								<a class="d-flex align-items-center" href="{{route('panel.TodayReport.today-submitted-report')}}">
									<div>
										<p class="mb-0 text-secondary" style="font-size: 16px;">Today Submitted<br> Reports</p>
										<h4 class="my-1">{{$data = DB::Table('add_submitted_hospital_sample')->where('created',date('Y-m-d'))->count();}}</h4>
									</div>
									<div class="widgets-icons text-success ms-auto"><img src="{{asset('image/submit_hospital.png')}}" style="width:90%;"></i>
									</div>
								</a>
							</div>
						</div>
					</div>
				</div>
				<div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
					<div class="col">
						<div class="card radius-10">
							<div class="card-body">
								<a class="d-flex align-items-center" href="{{route('panel.AllReport.all-collection')}}">
									<div>
										<p class="mb-0 text-secondary" style="font-size: 16px;">All Collection <br>Details</p>
										<h4 class="my-1">{{$data = DB::Table('add_sample_box_sample')->count();}}</h4>
									</div>
									<div class="widgets-icons text-success ms-auto"><img src="{{asset('image/collection.png')}}" style="width:90%;"></i>
									</div>
								</a>
							</div>
						</div>
					</div>
					<div class="col">
						<div class="card radius-10">
							<div class="card-body">
								<a class="d-flex align-items-center" href="{{route('panel.AllReport.all-submitted')}}">
									<div>
										<p class="mb-0 text-secondary" style="font-size: 16px;">All Submitted<br> Details</p>
										<h4 class="my-1">{{$data = DB::Table('add_collected_sample')->count();}}</h4>
									</div>
									<div class="widgets-icons text-success ms-auto"><img src="{{asset('image/submitted.png')}}" style="width:90%;"></i>
									</div>
								</a>
							</div>
						</div>
					</div>
					<div class="col">
						<div class="card radius-10">
							<div class="card-body">
								<a class="d-flex align-items-center" href="{{route('panel.AllReport.all-collected-report')}}">
									<div>
										<p class="mb-0 text-secondary" style="font-size: 16px;">All Collected<br> Reports</p>
										<h4 class="my-1">{{$data = DB::Table('add_collect_submitted_sample')->count();}}</h4>
									</div>
									<div class="widgets-icons text-success ms-auto"><img src="{{asset('image/report.png')}}" style="width:90%;"></i>
									</div>
								</a>
							</div>
						</div>
					</div>
					<div class="col">
						<div class="card radius-10">
							<div class="card-body">
								<a class="d-flex align-items-center" href="{{route('panel.AllReport.all-submitted-report')}}">
									<div>
										<p class="mb-0 text-secondary" style="font-size: 16px;">All Submitted<br> Reports</p>
										<h4 class="my-1">{{$data = DB::Table('add_submitted_hospital_sample')->count();}}</h4>
									</div>
									<div class="widgets-icons text-success ms-auto"><img src="{{asset('image/submit_hospital.png')}}" style="width:90%;"></i>
									</div>
								</a>
							</div>
						</div>
					</div>
				</div>
        	</div>

			<div class="page-content" style="padding-top:0px;">
				<h6 class="mb-0 text-uppercase" style="font-weight: 700;">Manage Data</h6>
				<hr/>
				<div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
					<div class="col">
						<div class="card radius-10">
							<div class="card-body">
								<a class="d-flex align-items-center" href="{{route('panel.User.list')}}">
									<div>
										<p class="mb-0 text-secondary">All Staff</p>
										<h4 class="my-1">{{App\Models\User::count();}}</h4>
										<p class="mb-0 font-13 text-success">list of users</p>
									</div>
									<div class="widgets-icons text-success ms-auto"><img src="{{asset('image/staff.png')}}" style="width:90%;"></i>
									</div>
								</a>
							</div>
						</div>
					</div>
					<div class="col">
						<div class="card radius-10">
							<div class="card-body">
								<a class="d-flex align-items-center" href="{{route('panel.Hospital.list')}}">
									<div>
										<p class="mb-0 text-secondary">Manage Hospital</p>
										<h4 class="my-1">{{App\Models\add_hospital::count();}}</h4>
										<p class="mb-0 font-13 text-success">list of hospitals</p>
									</div>
									<div class="widgets-icons text-success ms-auto"><img src="{{asset('image/hospital.png')}}" style="width:90%;"></i>
									</div>
								</a>
							</div>
						</div>
					</div>
					<div class="col">
						<div class="card radius-10">
							<div class="card-body">
								<a class="d-flex align-items-center" href="{{route('panel.District.list')}}">
									<div>
										<p class="mb-0 text-secondary">Districts</p>
										<h4 class="my-1">{{App\Models\add_district::count();}}</h4>
										<p class="mb-0 font-13 text-success">list of district</p>
									</div>
									<div class="widgets-icons text-success ms-auto"><img src="{{asset('image/city.png')}}" style="width:90%;"></i>
									</div>
								</a>
							</div>
						</div>
					</div>
					<div class="col">
						<div class="card radius-10">
							<div class="card-body">
								<a class="d-flex align-items-center" href="{{route('panel.Lab.list')}}">
									<div>
										<p class="mb-0 text-secondary">Manage Lab</p>
										<h4 class="my-1">{{App\Models\add_lab::count();}}</h4>
										<p class="mb-0 font-13 text-success">list of labs</p>
									</div>
									<div class="widgets-icons text-success ms-auto"><img src="{{asset('image/lab-technician.png')}}" style="width:90%;"></i>
									</div>
								</a>
							</div>
						</div>
					</div>


					<div class="col">
						<div class="card radius-10">
							<div class="card-body">
								<a class="d-flex align-items-center" href="{{route('panel.Invoice.invoice')}}">
									@php
									$add_sample_invoice = DB::table('add_sample_invoice')->count();
									$add_kilometer_invoice = DB::table('add_kilometer_invoice')->count();

									$total_invoice = $add_sample_invoice + $add_kilometer_invoice;
									@endphp
									<div>
										<p class="mb-0 text-secondary">Generate Invoice</p>
										<h4 class="my-1">{{$total_invoice}}</h4>
										<p class="mb-0 font-13 text-success">list of invoice</p>
									</div>
									<div class="widgets-icons text-success ms-auto"><img src="{{asset('image/invoice.png')}}" style="width:90%;"></i>
									</div>
								</a>
							</div>
						</div>
					</div>
					<div class="col">
						<div class="card radius-10">
							<div class="card-body">
								<a class="d-flex align-items-center" href="{{route('panel.Search.nikshay-search')}}">
									<div>
										<p class="mb-0 text-secondary">Sample Info</p>
										<h4 class="my-1">{{App\Models\add_sample_box_detail::count();}}</h4>
										<p class="mb-0 font-13 text-success">list of samples</p>
									</div>
									<div class="widgets-icons text-success ms-auto"><img src="{{asset('image/sample.png')}}" style="width:90%;"></i>
									</div>
								</a>
							</div>
						</div>
					</div>
					<div class="col">
						<div class="card radius-10">
							<div class="card-body">
								<a class="d-flex align-items-center" href="{{route('panel.Reports.kilometer-report')}}">
									@php
									$purchases1 = DB::table('add_sample_collected_details')->select('sample_id')->get();
									$totaldistance = 0;

									foreach($purchases1 as $purchases1)
									{
										$sample11 = DB::table('add_sample_collected_details')->Where('sample_id',$purchases1->sample_id)->select('kilometer','to_kilometer','collect_lab_kilometer','submit_hospital_kilometer')->first();

										$val11 = $sample11->kilometer;
										$val21 = $sample11->to_kilometer;
										$val31 = $sample11->collect_lab_kilometer;
										$val41 = $sample11->submit_hospital_kilometer;
										$val11 = $val11 + $val21 + $val31 + $val41;
										$totaldistance = $totaldistance + $val11;
									}
									@endphp
									<div>
										<p class="mb-0 text-secondary">Distance</p>
										<h4 class="my-1">{{$totaldistance}}</h4>
										<p class="mb-0 font-13 text-success">total distance</p>
									</div>
									<div class="widgets-icons text-success ms-auto"><img src="{{asset('image/distance.png')}}" style="width:90%;"></i>
									</div>
								</a>
							</div>
						</div>
					</div>
					
				</div>

        	</div>
</div>



@endsection