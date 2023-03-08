@extends('Admin.app')
@section('title',('Kilometer Invoice List'))

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
				<h6 class="mb-0 text-uppercase" style="padding-bottom: 10px;">Kilometer Invoice List <button class="btn btn-success" style="float: right;padding: 10px 20px;font-size: 14px; margin-right:5px;" id="btnExport" onclick="fnExcelReport3();">Export</button></h6>
				<hr/>
				<div class="card border-top border-0 border-4 border-danger">
          <div class="card-body p-5">
            <div class="table-responsive">
				<table id="example" class="table align-middle mb-0" style="width:100%;">
				    <thead class="table-light">
						<tr>
                            <th style="font-size:14px;">Sr No.</th>
                            <th style="font-size:14px;">Action</th>
                            <th style="font-size:14px;">From Date</th>
                            <th style="font-size:14px;">To Date</th>
                            <th style="font-size:14px;">District</th>
                            <th style="font-size:14px;">Rate</th>
                            <th style="font-size:14px;">Total Kilometer</th>
                            <th style="font-size:14px;">Final Rate</th>
                            <th style="font-size:14px;">Created</th>
						</tr>
				    </thead>
					<tbody>              
                        @foreach($sample as $sample)

                                @php 
                                $specimen = DB::table('add_district')->Where('district_id',$sample->district_id)->select('name')->first();
                                @endphp
                                <tr>
                                  <td>{{ $loop->iteration }}</td> 
                                  <td>
                                    <div class="action">
                                      <a href="{{route('panel.Invoice.kilometer-invoice-detail',[$sample->id])}}" target="_blank" class="btn btn-info" style="font-size:13px; color:white;" title="View">
                                      View
                                      </a>
                                    </div>
                                  </td>            
                                  <td style="font-size:12px;">{{$sample->from_date}}</td>
                                  <td style="font-size:12px;">{{$sample->to_date}}</td>
                                  <td style="font-size:12px;">{{$specimen->name}}</td>
                                  <td style="font-size:12px;">{{$sample->rate}}</td>
                                  <td style="font-size:12px;">{{$sample->total_kilometer}}</td>
                                  <td style="font-size:12px;">{{$sample->amount}}</td>
                                 
                                  <td style="font-size:12px;">{{\Carbon\Carbon::parse($sample->created)->format('d-m-Y')}} {{$sample->created_time}}</td>
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