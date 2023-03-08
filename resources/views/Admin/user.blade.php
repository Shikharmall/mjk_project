@extends('Admin.app')
@section('title',('Admin All Staff User'))

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
<div class="page-wrapper">
			<div class="page-content">
				<h6 class="mb-0 text-uppercase">All Staff</h6>
				<hr/>
				<div class="card border-top border-0 border-4 border-danger">
					<div class="card-body p-5">
						<div class="table-responsive">
							<table id="example" class="table align-middle mb-0" style="width:100%;">
								<thead class="table-light">
									<tr>
                    <th style="font-size:14px;display:none;" >Sr No.</th>
                    <th style="font-size:14px;">Staff Details</th>
                    <th style="font-size:14px;"></th>
									</tr>
								</thead>
								<tbody>
                @php 
                  $User = DB::table('user')->orderBy('id','DESC')->get();
                @endphp 
                @foreach($User as $User)
                    @php 
                      $add_sample_box_sample = DB::table('add_sample_box_sample')->where('staff_id',$User->id)->count();
                      $add_collected_sample = DB::table('add_collected_sample')->where('staff_id',$User->id)->count();
                      $add_collected_sample = DB::table('add_collected_sample')->where('staff_id',$User->id)->count();
                      $add_submitted_hospital_sample = DB::table('add_submitted_hospital_sample')->where('staff_id',$User->id)->count();
                    @endphp  
                    <tr>
                      <td style="display:none;">{{ $loop->iteration }}</td> 
                      <td style="font-size:12px; line-height:22px;">
                      <h4 style="font-size:18px;">{{$User->name}}</h4>
                      <label style="font-size: 13px; color: #03a9f4; font-weight: 500;">Email ID :</label>{{$User->email}}&nbsp; &nbsp; <label style="font-size: 13px; color: #03a9f4; font-weight: 500;">Date :</label>{{\Carbon\Carbon::parse($User->created)->format('d-m-Y H:i:s')}}<br>
                      <label style="font-size: 13px; font-weight: 500;">Sample Collected :</label>{{$add_sample_box_sample}} &nbsp;&nbsp;&nbsp;<label style="font-size: 13px;  font-weight: 500;">Sample Submitted :</label>{{$add_collected_sample}}<br>
                      <label style="font-size: 13px; font-weight: 500;">Reports Collected :</label>{{$add_collected_sample}} &nbsp;&nbsp;&nbsp;<label style="font-size: 13px; font-weight: 500;">Reports Submitted :</label>{{$add_submitted_hospital_sample}}
                      </td>
                      <td style="text-align:right;">
                        <div class="action">
                          <a href="{{route('panel.User.edit',[$User->id])}}" class="btn btn-primary" style="margin-top: 5px; padding: 14px 7px 13px 7px;" title="Edit">
                          <i class="fadeIn animated bx bx-edit me-0" style="line-height: 30px;font-size: 13px;"></i>
                          </a>
                          <a onclick="status_change_alert('{{route('panel.User.delete',[$User->id])}}','{{('Want to delete this User ?')}}',event)" class="btn btn-danger" style="margin-top: 5px; padding: 14px 7px 13px 7px;" title="Delete">
                          <i class="fadeIn animated bx bx-trash-alt me-0" style="line-height: 30px;font-size: 13px;"></i>
                          </a>
                          @if($User->status=='0')
                            <a href="javascript:" onclick="status_change_alert('{{route('panel.User.disapprove',[$User->id])}}','{{('Want to Approve this User ?')}}',event)" class="btn btn-warning" style="margin-top: 5px; padding: 14px 7px 13px 7px;" title="Approve">
                            <i class="fadeIn animated bx bx-lock me-0" style="line-height: 30px;font-size: 13px;"></i>
                            </a>
                          @elseif($User->status=='1')
                            <a href="javascript:" onclick="status_change_alert('{{route('panel.User.approve',[$User->id])}}','{{('Want to Dis-Approve this User ?')}}',event)" class="btn btn-info" style="margin-top: 5px; padding: 14px 3px 13px 7px;" title="Dis-Approve">
                            <i class="fadeIn animated bx bx-lock-open" style="line-height: 30px;font-size: 13px;color:white;"></i>
                            </a>
                          @endif
                          <a href="{{route('panel.User.staff-activity-report',[$User->id])}}" class="btn btn-primary" title="Staff Activity" style="font-size: 12px;margin-top: 6px; padding: 7px 12px;">
                          Staff Activity
                          </a>
                          <br>
                          <!-- <a data-bs-toggle="modal" data-bs-target="#exampleModal{{$User->id}}" class="btn btn-primary" title="Assign District" style="font-size: 12px;margin-top: 6px;">
                          Assign District
                          </a>
                          <a data-bs-toggle="modal" data-bs-target="#exampleModal1{{$User->id}}"  class="btn btn-primary" title="Assign Hospital" style="font-size: 12px;margin-top: 6px;">
                          Assign Hospital
                          </a>
                          <a data-bs-toggle="modal" data-bs-target="#exampleModal2{{$User->id}}"  class="btn btn-primary" title="Assign Lab" style="font-size: 12px;margin-top: 6px;">
                          Assign Lab
                          </a> -->
                          <a href="{{route('panel.User.district-assign',[$User->id])}}" class="btn btn-primary" title="Assign District" style="font-size: 12px;margin-top: 6px;">
                          Assign District
                          </a>
                          <a href="{{route('panel.User.hospital-assign',[$User->id])}}" class="btn btn-primary" title="Assign Hospital" style="font-size: 12px;margin-top: 6px;">
                          Assign Hospital
                          </a>
                          <a href="{{route('panel.User.lab-assign',[$User->id])}}" class="btn btn-primary" title="Assign Lab" style="font-size: 12px;margin-top: 6px;">
                          Assign Lab
                          </a>
                        </div>
                      </td>
                    </tr>

                    <!-- <div class="modal fade" id="exampleModal{{$User->id}}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title">Assign District</h5>
                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="{{route('panel.User.assign-district')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-body">
                              <div class="row g-3">
                                <input type="hidden" name="staff_id" value="{{$User->id}}" id="staff_id">  
                                <div class="col-sm-12">
                                  <select class="form-select" name="district_id" id="district_id" required>
                                  <option value="" selected disabled>{{('Select District')}}</option>
                                  @foreach($add_district as $p)
                                  <option value="{{$p->district_id}}">{{$p->name}}</option>
                                  @endforeach
                                  </select>
                                </div>
                              </div>
                            </div>
                            <div class="modal-footer" style="display:block;">
                              <button type="submit" name="submit-cat1" class="btn btn-danger px-5">Submit</button>
                              <button type="button" class="btn btn-success" data-bs-dismiss="modal" style="width: 146px;">Done</button>
                            </div>
                            </form>
                          </div>
                        </div>
										</div>

                    <div class="modal fade" id="exampleModal1{{$User->id}}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title">Assign Hospital</h5>
                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="{{route('panel.User.assign-hospital')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-body">
                              <div class="row g-3">
                                <input type="hidden" name="staff_id" value="{{$User->id}}" id="staff_id">  
                                <div class="col-sm-12">
                                  <select class="form-select" name="hospital_id" id="hospital_id" required>
                                  <option value="" selected disabled>{{('Select Hospital')}}</option>
                                  @foreach($add_hospital as $p)
                                  <option value="{{$p->hospital_id}}">{{$p->name}}</option>
                                  @endforeach
                                  </select>
                                </div>
                              </div>
                            </div>
                            <div class="modal-footer" style="display:block;">
                              <button type="submit" name="submit-cat2" class="btn btn-danger px-5">Submit</button>
                              <button type="button" class="btn btn-success" data-bs-dismiss="modal" style="width: 146px;">Done</button>
                            </div>
                            </form>
                          </div>
                        </div>
										</div>

                    <div class="modal fade" id="exampleModal2{{$User->id}}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title">Assign Lab</h5>
                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="{{route('panel.User.assign-lab')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-body">
                              <div class="row g-3">
                                <input type="hidden" name="staff_id" value="{{$User->id}}" id="staff_id">  
                                <div class="col-sm-12">
                                  <select class="form-select" name="lab_id" id="lab_id" required>
                                  <option value="" selected disabled>{{('Select Lab')}}</option>
                                  @foreach($add_lab as $p)
                                  <option value="{{$p->lab_id}}">{{$p->name}}</option>
                                  @endforeach
                                  </select>
                                </div>
                              </div>
                            </div>
                            <div class="modal-footer" style="display:block;">
                              <button type="submit" name="submit-cat3" class="btn btn-danger px-5">Submit</button>
                              <button type="button" class="btn btn-success" data-bs-dismiss="modal" style="width: 146px;">Done</button>
                            </div>
                            </form>
                          </div>
                        </div>
										</div> -->
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