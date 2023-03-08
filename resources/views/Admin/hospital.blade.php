@extends('Admin.app')
@section('title',('All Hospital'))

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
				<h6 class="mb-0 text-uppercase">All Hospital</h6>
				<hr/>
				<div class="card border-top border-0 border-4 border-danger">
					<div class="card-body p-5">
						<div class="table-responsive">
							<table id="example" class="table align-middle mb-0" style="width:100%;">
								<thead class="table-light">
									<tr>
                  <th style="font-size:14px;display:none;">Sr No.</th>
                    <th style="font-size:14px;">Hospital Details</th>
                    <th style="font-size:14px;"></th>
									</tr>
								</thead>
								<tbody>
                @php 
                    $hospital = DB::table('add_hospital')->orderBy('hospital_id', 'desc')->get();
                    @endphp 

                    @foreach($hospital as $hospital)
                    <tr>
                      <td style="display:none;">{{ $loop->iteration }}</td> 
                      <td>
                        <div class="row g-3">
                          <div class="col-md-1" style="text-align:center;">
                            <img src="{{asset('image/'.$hospital->image)}}" height="50" width="100%" >
                          </div>
                          <div class="col-md-11">
                           <label style="font-weight:600; margin-bottom:5px;">{{$hospital->name}}</label> 
                           <br>
                           <label style="font-size: 13px; color: #03a9f4; font-weight: 500;">Date :</label> <label style="font-size: 13px; ">{{\Carbon\Carbon::parse($hospital->created)->format('d-m-Y H:i:s')}}</label><br>
                           <label style="font-size:13px;">{{$hospital->gps_address}}</label>
                          </div>
                        </div>
                      </td>   
                      
                      <td>
                        <div class="action" style="display:flex;">
                          <a href="{{route('panel.Hospital.edit',[$hospital->hospital_id])}}" class="btn btn-primary" style="padding: 23px 7px 8px; margin-right:5px;" title="Edit">
                          <i class="fadeIn animated bx bx-edit me-0" style="line-height: 16px;font-size: 16px;"></i>
                          </a>

                          <a onclick="status_change_alert('{{route('panel.Hospital.delete',[$hospital->hospital_id])}}','{{('Want to delete this Hospital ?')}}',event)" class="btn btn-danger" style="padding: 23px 7px 8px; margin-right:5px;" title="Delete">
                          <i class="fadeIn animated bx bx-trash-alt me-0" style="line-height: 16px;font-size: 16px;"></i>
                          </a>

                          @if($hospital->status=='0')
                            <a href="javascript:" onclick="status_change_alert('{{route('panel.Hospital.disapprove',[$hospital->hospital_id])}}','{{('Want to Approve this Hospital ?')}}',event)" class="btn btn-warning" style="padding:23px 7px 8px 7px;" title="Approve">
                            <i class="fadeIn animated bx bx-lock me-0" style="line-height: 16px;font-size: 16px;"></i>
                            </a>
                          @elseif($hospital->status=='1')
                            <a href="javascript:" onclick="status_change_alert('{{route('panel.Hospital.approve',[$hospital->hospital_id])}}','{{('Want to Dis-Approve this Hospital ?')}}',event)" class="btn btn-info" style="padding: 23px 3px 8px 7px;" title="Dis-Approve">
                            <i class="fadeIn animated bx bx-lock-open" style="line-height: 16px;font-size: 16px;color:white;"></i>
                            </a>
                          @endif
                        </div>
                      </td>       
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
