@extends('Admin.app')
@section('title',('Sample Invoice'))

@section('content')
<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.1/themes/base/minified/jquery-ui.min.css" type="text/css" /> 
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
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
<script>
function reset()
{
    window.location.href = "{{url('panel/Invoice/reset')}}";
}
</script>
<script type="text/javascript">
  function printDiv(divName) {
     var printContents = document.getElementById(divName).innerHTML;
     var originalContents = document.body.innerHTML;

     document.body.innerHTML = printContents;

     window.print();

     document.body.innerHTML = originalContents;
}
</script>
<div class="page-wrapper">
	<div class="page-content">
		<div class="card border-top border-0 border-4 border-danger">
			<div class="card-body p-5">
                <h6 class="mb-0 text-uppercase">Sample Invoice <label style="float:right;color:#fd3550!important;"> Per Sample Rate Rs. {{$rate}}</label></h6>
				<hr/>
			
            <div class="row g-3" style="margin-top:15px;">
       
              <div class="col-md-7" style="background:white; padding:10px;">
                <form class="row g-3" action="{{route('panel.Invoice.filter')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="district_id" name="district_id" value="{{$district_id}}" />
                <input type="hidden" id="rate" name="rate" value="{{$rate}}" />
                    <div class="col-md-3" style="margin-top:0px;">
						<input type="date" class="form-control" id="from_date" name="from_date" value="{{isset($from_date)?$from_date:''}}" required style="padding: 10px 15px;font-size: 14px;">
					</div>
                    <div class="col-md-3" style="margin-top:0px;">
						<input type="date" class="form-control" id="to_date" name="to_date" value="{{isset($to_date)?$to_date:''}}" required style="padding: 10px 15px;font-size: 14px;">
					</div>		
					<div class="col-4" style="margin-top:0px;">
						<button type="submit" class="btn btn-danger" style="padding: 10px 20px;font-size: 14px;">Create Invoice</button>
                	</div>
              </form>
              </div>
              <div class="col-md-5" style="background:white; padding:10px;margin-top:0px;">
                <button type="button" onClick="reset()" class="btn btn-dark" style="padding: 10px 20px;font-size: 14px; float:right;">Clear</button>
                <button class="btn btn-success" style="float: right;padding: 10px 20px;font-size: 14px; margin-right:5px;" id="btnExport" onclick="fnExcelReport3();">Export</button>
                <input type="button" class="btn btn-primary" onclick="printDiv('invoice')" value="Print PDF" style="float:right; padding: 10px 20px;font-size: 14px; margin-right: 5px;" />
              </div>
              <div class="col-md-12" id="invoice" style="background:white; padding:10px;">
              <table id="example" class="table align-middle mb-0" style="width:100%;">
				<thead class="table-light">
					<tr>
                        <th style="font-size:14px;">Sr No.</th>
                        <!-- <th style="font-size:14px;">Total Sample</th>
                        <th style="font-size:14px;">Staff Name</th>
                        <th style="font-size:14px;">Collected From</th>
                        <th style="font-size:14px;">Lab Name</th>
                        <th style="font-size:14px;">Collected Date</th>
                        <th style="font-size:14px;">Submitted Date</th> -->
                        <th style="font-size:14px;">Nikshay ID</th>
                        <th style="font-size:14px;">Patient Name</th>
                        <th style="font-size:14px;">Type</th>
                        <th style="font-size:14px;">Test for</th>
                        <th style="font-size:14px;">Specimen</th>
                        <th style="font-size:14px;">Test Type</th>
                        <th style="font-size:14px;">Created</th>
					</tr>
				</thead>
				<tbody>
					@foreach($sample as $sample)
					@php 
	            $specimencount = DB::table('add_specimen')->Where('id',$sample->specimen_id)->select('name')->count();

              $specimen = DB::table('add_specimen')->Where('id',$sample->specimen_id)->select('name')->first();

              $Testcount = DB::table('add_test')->Where('id',$sample->test_id)->select('name')->count();

							$Test = DB::table('add_test')->Where('id',$sample->test_id)->select('name')->first();	
          @endphp 
                    <tr>
                      <td>{{ $loop->iteration}}</td> 
                      <td style="font-size:12px;">{{$sample->nikshay_id}}</td>
                      <td style="font-size:12px;">{{$sample->patient}}</td>
                      <td style="font-size:12px;">{{$sample->type_patient}}</td>
                      <td style="font-size:12px;">{{$sample->type_test_for}}</td>
                      <td style="font-size:12px;">
                                  @if($specimencount > 0)
                                  {{$specimen->name}}
                                  @endif
                      </td>
                      <td style="font-size:12px;">
                                  @if($Testcount > 0)
                                  {{$Test->name}}
                                  @endif
                      </td>
                      <td style="font-size:12px;">{{\Carbon\Carbon::parse($sample->date)->format('d-m-Y')}}</td>
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