@extends('Admin.app')
@section('title',('Today Collection Detail'))

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
				<h6 class="mb-0 text-uppercase">Today Collection Detail</h6>
				<hr/>
				<div class="card border-top border-0 border-4 border-danger">
					<div class="card-body p-5">
						<div class="table-responsive">
							<table id="example" class="table align-middle mb-0" style="width:100%;">
								<thead class="table-light" style="display:none;">
									<tr>
                    <th style="font-size:14px;">Hospital Details</th>
                    <!-- <th style="font-size:14px;"></th> -->
									</tr>
								</thead>
								<tbody>
          
                    @foreach($hospital as $hospital)
                    
                    @php 
                    $data = DB::table('add_hospital')->select('name','hospital_id','image','gps_address')->where('hospital_id',$hospital['hospital_id'])->first();
                    @endphp 
                        
                    <tr>
                      <td style="padding: 28px 11px 0px;">
                        <a class="row g-3" href="{{route('panel.TodayReport.today-collection-sample',[$data->hospital_id])}}" style=" cursor: pointer;background: #e0efff; padding: 0px 10px 20px 10px; border-radius: 8px;">
                          <div class="col-md-1" style="text-align:center;">
                            <img src="{{asset('image/'.$data->image)}}" height="50" width="100%" >
                          </div>
                          <div class="col-md-10">
                           <label style="font-weight:600; margin-bottom:5px; color:#212529;">{{$data->name}}</label> 
                           <br>
                           <label style="font-size:13px; color:#212529;">{{$data->gps_address}}</label>
                          </div>
                        </a>
                      </td>   
                      <!-- <div class="modal fade" id="exampleModal{{$data->hospital_id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title" id="exampleModalLabel">Hospital Sample</h5>
                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                            @php 
                            $category = DB::table('add_sample_box_sample')->leftjoin('add_hospital','add_sample_box_sample.hospital_id','add_hospital.hospital_id')->leftjoin('add_sample_box_detail','add_sample_box_sample.sample_selected_id','add_sample_box_detail.id')->where('add_sample_box_sample.hospital_id',$data->hospital_id)->where('add_sample_box_sample.date',date('Y-m-d'))->orderBy('add_sample_box_sample.id','DESC')->select('add_sample_box_sample.sample_selected_id','add_sample_box_detail.nikshay_id','add_sample_box_detail.patient','add_sample_box_detail.invoice_photo','add_sample_box_detail.sample_meter_photo','add_sample_box_detail.sample_box_photo')->get();
                            @endphp
                            @foreach($category as $val3)
                            <label class="btn btn-danger" style="color:white; margin-bottom:10px; width:100%;text-align: left; cursor:initial;">{{$val3->nikshay_id}} - {{$val3->patient}} <a href="{{route('panel.Invoice.sample-collection-info',[$val3->sample_selected_id])}}"  target="_blank" style="padding: 5px 10px; font-size: 12px; color: #fb3f60; background: white; float: right; border-radius: 3px; font-weight: 500;">View Info</a>
                                          </label><br>
                            @endforeach
                            </div>
                            <div class="modal-footer" style="text-align:center; display:block;">
                              <button type="button" class="btn btn-success" data-bs-dismiss="modal" style="width: 146px;">Done</button>
                            </div>
                          </div>
                        </div>
										  </div> -->
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
