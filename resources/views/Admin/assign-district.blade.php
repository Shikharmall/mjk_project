@extends('Admin.app')
@section('title',('Assign District'))

@push('css_or_js')
<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.1/themes/base/minified/jquery-ui.min.css" type="text/css" /> 
@endpush

@section('content')
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
				<div class="row">
					<div class="col-xl-12 mx-auto">
						<div class="card border-top border-0 border-4 border-danger">
							<div class="card-body">
								<div class="card-title d-flex align-items-center">
									<h5 class="mb-0 text-danger">Assign District</h5>
								</div>
								<hr>
								<form class="row g-3" action="{{route('panel.User.assign-district')}}" method="POST" enctype="multipart/form-data">
								@csrf
                                <input type="hidden" name="staff_id" value="{{$id}}" id="staff_id">  
                                <div class="col-md-4">
									<label for="inputCity" class="form-label">District Name</label>
                                    <select class="form-select" name="district_id" id="district_id" required>
                                        <option value="" selected disabled>{{('Select District')}}</option>
                                        @foreach($add_district as $p)
                                        <option value="{{$p->district_id}}">{{$p->name}}</option>
                                        @endforeach
                                    </select>
								</div>
								
								<div class="col-2">
									<button type="submit" name="submit-cat" class="btn btn-danger px-5" style="margin-top: 29px;">Submit</button>
								</div>
								</form>
							</div>
						</div>

				<div class="card border-top border-0 border-4 border-danger">
					<div class="card-body p-5">
						<div class="table-responsive">
							<table id="example" class="table align-middle mb-0" style="width:100%;">
								<thead class="table-light">
									<tr>
                                    <th style="font-size:14px;">Sr No.</th>
                                    <th style="font-size:14px;">Action</th>
                                    <th style="font-size:14px;">District Name</th>
                                    <th style="font-size:14px;">Date</th>
									</tr>
								</thead>
								<tbody>
                                @php 
                                $district331 = DB::table('assign_district')->where('staff_id',$id)->orderBy('id','DESC')->get();
                                @endphp 

                                @foreach($district331 as $district331)

                                @php 
                                $district33 = DB::table('add_district')->where('district_id',$district331->district_id)->first();
                                @endphp 
                                    <tr>
                                    <td>{{ $loop->iteration }}</td> 
                                    <td>
                                        <div class="action">
                                        <a onclick="status_change_alert('{{route('panel.User.delete-district',[$district331->id])}}','{{('Want to delete this District ?')}}',event)" class="btn btn-danger" style="padding: 23px 7px 8px;" title="Delete">
                                        <i class="fadeIn animated bx bx-trash-alt me-0" style="line-height: 16px;font-size: 16px;"></i>
                                        </a>
                                        </div>
                                    </td>            
                                    <td style="font-size:12px;">{{$district33->name}}</td>
                                    <td style="font-size:12px;">{{\Carbon\Carbon::parse($district331->assign_date)->format('d-m-Y')}}</td>
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