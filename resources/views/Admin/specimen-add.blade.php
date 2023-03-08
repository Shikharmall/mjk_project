@extends('Admin.app')
@section('title',('Add Specimen'))

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
									<h5 class="mb-0 text-danger">Add Specimen</h5>
								</div>
								<hr>
								<form class="row g-3" action="{{route('panel.Specimen.add-specimen')}}" method="POST" enctype="multipart/form-data">
								@csrf
                                <input type="hidden" name="id" value="@if(!empty($specimen)){{$specimen->id}}@endif" id="id">  
                                    <div class="col-md-2">
										<label for="inputCity" class="form-label">Specimen Name</label>
								    </div>
									<div class="col-md-8">
										<input type="text" class="form-control" id="name" name="name" value="@if(!empty($specimen)){{$specimen->name}}@endif" required >
									</div>
								
									<div class="col-2">
										<button type="submit" name="submit-cat" class="btn btn-danger px-5">Submit</button>
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
                    <th style="font-size:14px;">Specimen Name</th>
                    <th style="font-size:14px;">Date</th>
									</tr>
								</thead>
								<tbody>
                @php 
                  $specimen33 = DB::table('add_specimen')->orderBy('id','DESC')->get();
                @endphp 

                @foreach($specimen33 as $specimen33)
                         
                    <tr>
                      <td>{{ $loop->iteration }}</td> 
                      <td>
                        <div class="action">
                          <a href="{{route('panel.Specimen.edit',[$specimen33->id])}}" class="btn btn-primary" style="padding: 23px 7px 8px;" title="Edit">
                          <i class="fadeIn animated bx bx-edit me-0" style="line-height: 16px;font-size: 16px;"></i>
                          </a>

                          <a href="{{route('panel.Specimen.delete',[$specimen33->id])}}" class="btn btn-danger" style="padding: 23px 7px 8px;" title="Delete">
                          <i class="fadeIn animated bx bx-trash-alt me-0" style="line-height: 16px;font-size: 16px;"></i>
                          </a>
                        </div>
                      </td>            
                      <td style="font-size:12px;">{{$specimen33->name}}</td>
                      <td style="font-size:12px;">{{\Carbon\Carbon::parse($specimen33->created)->format('d-m-Y')}}</td>
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