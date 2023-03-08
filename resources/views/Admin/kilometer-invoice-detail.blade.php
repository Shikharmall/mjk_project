@extends('Admin.app')
@section('title',('Kilometer Invoice Detail'))

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
<div class="page-wrapper">
	<div class="page-content">
		<div class="card border-top border-0 border-4 border-danger">
			<div class="card-body p-5" style="padding-bottom:10px;">
                <h6 class="mb-0 text-uppercase">Kilometer Invoice Detail<button class="btn btn-success" style="float: right;padding: 10px 20px;font-size: 14px; margin-right:5px;" id="btnExport" onclick="fnExcelReport3();">Export</button></h6>
				<hr/>
			
            <div class="row g-3" style="margin-top:15px;">
              <div class="col-md-12"  id="invoice" style="background:white; padding:10px;">
              <table id="example" class="table align-middle mb-0" style="width:100%;">
				<thead class="table-light">
					<tr>
                        <th style="font-size:14px;">Sr No.</th>
                        <th style="font-size:14px;">Total Kilometer</th>
                        <th style="font-size:14px;">Staff Name</th>
                        <th style="font-size:14px;">Collected From</th>
                        <th style="font-size:14px;">Lab Name</th>
                        <th style="font-size:14px;">Collected Date</th>
                        <th style="font-size:14px;">Submitted Date</th>
					</tr>
				</thead>
				<tbody>
				    @foreach($sample as $sample)
					@php 
							$persample = DB::table('add_sample_box_detail')->Where('sample_auto_id',$sample->id)->select('lab_id')->first();

							$staff = DB::table('user')->Where('id',$sample->staff_id)->select('name')->first();

							$hospital = DB::table('add_hospital')->Where('hospital_id',$sample->collected_from)->select('name')->first();

                            $lab = DB::table('add_lab')->Where('lab_id',$persample->lab_id)->select('district_id','name')->first();

                            $purchaseee3 = 0;

                            $sample6 = DB::table('add_sample_collected_details')->Where('id',$sample->id)->select('kilometer','to_kilometer','collect_lab_kilometer','submit_hospital_kilometer')->first();

							$val15 = $sample6->kilometer;
							$val25 = $sample6->to_kilometer;
							$val35 = $sample6->collect_lab_kilometer;
							$val45 = $sample6->submit_hospital_kilometer;

							$val = $val15 + $val25 + $val35 + $val45;
							$purchaseee3 = $purchaseee3 + $val;
                        @endphp 

                    <tr>
                      <td>{{ $loop->iteration}}</td> 
                      <td style="font-size:12px;">{{$purchaseee3}}</td>
					  <td style="font-size:12px;">{{$staff->name}}</td>
					  <td style="font-size:12px;">{{$hospital->name}}</td>
					  <td style="font-size:12px;">{{$lab->name}}</td>
					  <td style="font-size:12px;">{{\Carbon\Carbon::parse($sample->created)->format('d-m-Y')}}</td>
                      <td style="font-size:12px;">{{\Carbon\Carbon::parse($sample->submitted_date)->format('d-m-Y')}}</td>
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