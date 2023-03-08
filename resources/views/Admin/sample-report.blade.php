@extends('Admin.app')
@section('title',('Sample Report'))

@push('css_or_js')
<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.1/themes/base/minified/jquery-ui.min.css" type="text/css" /> 
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
div.dataTables_wrapper div.dataTables_filter input {
    margin-left: 0.5em;
    display: none !important;
    width: auto;
}
</style>
@push('script')
<script>
function reset()
{
  location.href = '{{url('/')}}/panel/AllReport/filter/reset';
}
</script>
<div class="page-wrapper">
			<div class="page-content">
				<h6 class="mb-0 text-uppercase">Sample Report</h6>
				<hr/>
				<div class="card border-top border-0 border-4 border-danger">
          <div class="card-body p-5">
            <form class="card-body sidebar-body sidebar-scrollbar" action="{{route('panel.AllReport.filter')}}" method="POST" id="order_filter_form">
            @csrf
            <div class="row" style="margin-bottom:10px;">
              <div class="col-md-3">
                  <select class="form-select" name="type" id="type">
                    <option value="" selected disabled>{{('Select ')}}</option>
                    <option value="1">Hospital</option>
                    <option value="2">Lab</option>
                    <option value="3">Staff</option>
                    <option value="4">Sample</option>
                  </select>
              </div>
              <div class="col-md-3">
                <input type="text" class="form-control" id="name" name="name" value="" >
              </div>
              <div class="col-md-6">
                <button type="submit" name="submit-cat" class="btn btn-danger px-5" style="margin-right:5px;">Submit</button>
                
                <button type="submit" name="submit-cat1" class="btn btn-dark px-5">Clear</button>
              </div>
            </div>
            </form>
            <div class="table-responsive">
							<table id="example" class="table align-middle mb-0" style="width:100%;">
								<thead class="table-light">
									<tr>
                      <th style="font-size:14px;">Sr No.</th>
                      <th style="font-size:14px;">Action</th>
                      <th style="font-size:14px;">Nikshay ID</th>
                      <th style="font-size:14px;">Patient Name</th>
                      <th style="font-size:14px;">Status</th>
                      <th style="font-size:14px;">Created</th>
									</tr>
								</thead>
								<tbody>
               
                                @foreach($sample as $sample)
                                <tr>
                                  <td>{{ $loop->iteration }}</td> 
                                  <td>
                                    <div class="action">
                                      <a href="{{route('panel.Invoice.sample-submitted-info',[$sample->id])}}" class="btn btn-info" style="font-size:13px; color:white;" title="View">
                                      View
                                      </a>
                                    </div>
                                  </td>            
                                  <td style="font-size:12px;">{{$sample->nikshay_id}}</td>
                                  <td style="font-size:12px;">{{$sample->patient}}</td>
                                  <td style="font-size:12px;">
                                    @if($sample->status=='0')
                                      <label style="font-size: 14px; color: #03a9f4; font-weight: 500;">Collected from Hospital</label>
                                    @elseif($sample->status=='1')
                                      <label style="font-size: 14px; color: #ff9800; font-weight: 500;">Submitted to Lab</label>
                                    @elseif($sample->status=='2')
                                      <label style="font-size: 14px; color: black; font-weight: 500;">Collected from Lab</label>
                                    @elseif($sample->status=='3')
                                      <label style="font-size: 14px; color: red; font-weight: 500;">Submitted to Hospital</label>
                                    @endif
                                  </td>
                                  <td style="font-size:12px;">{{\Carbon\Carbon::parse($sample->created)->format('d-m-Y')}}</td>
                              </tr>
                              @endforeach
								

								</tfoot>
							</table>
						</div>
					</div>
				</div>
			
			</div>
		</div>


@endsection