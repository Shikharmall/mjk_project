@extends('Admin.app')
@section('title',('District'))

@push('css_or_js')
<style>
.nav-tabs-danger .nav-link
{
  color: white !important;
}
.top-icon.nav-tabs .nav-link i {
  font-size: 40px !important;
}
.nav-tabs-danger .nav-link.active
{
  border-top: 10px solid;
}
::placeholder { /* Chrome, Firefox, Opera, Safari 10.1+ */
  color: black !important;
  opacity: 1; /* Firefox */
}
.sidebar-menu li.active>.sidebar-submenu {
  display: block !important;
}

</style>

<link href="{{asset('assets/')}}/plugins/bootstrap-datatable/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css">
@endpush

@section('content')
 <div class="clearfix"></div>
 <div class="content-wrapper" style="padding-right: 0px;padding-left: 0px;">
  <div class="container-fluid">
    <!--Start Dashboard Content-->
    <div class="col-lg-12" style="padding-left: 0px;padding-right: 0px;">
     <div class="card">
      <div class="card-body"> 
        <div class="row">
          <div class="col-lg-12 mx-auto"> 
           <div class="card gradient-scooter" >
             <div class="card-body" style="padding-bottom: 0px;">
               <div class="row">
                <div class="col-lg-12" >
                  <div class="card">
                    <div class="card-body">
                      <h4 style="text-align: center;">All District</h4>
                      <div class="table-responsive">
                        <table id="default-datatable" class="table table-bordered">
                          <thead>
                             <tr>
                              <th>Sr No.</th>
                              <th>Action</th>
                              <th>District Name</th>
                              <th>Date</th>
                            </tr>
                          </thead>
                           <tbody>
                           @php 
                                $district = DB::table('add_district')->orderBy('district_id','DESC')->get();
                            @endphp 

                            @foreach($district as $district)
                         
                                <tr>
                                 <td>{{ $loop->iteration }}</td> 
                                 <td>
                                  <div class="action">
                                  <a href="{{route('panel.District.edit',[$district->district_id])}}">
                                      <span class="btn " data-toggle="tooltip" data-placement="top" title="Edit" style="padding: 3px 6px;"><i class="fa fa-pencil-square-o fa-2x" aria-hidden="true"></i></span>
                                    </a>
                                    <a onclick="status_change_alert('{{route('panel.District.delete',[$district->district_id])}}','{{('Want to delete this District ?')}}',event)">
                                      <span class="btn " data-toggle="tooltip" data-placement="top" title="Delete" style="padding: 3px 6px;"><i class="fa fa-trash-o fa-2x"></i></span> 
                                    </a>
                                  </td>                                 
                                  <td>{{$district->name}}</td>
                                  <td>{{\Carbon\Carbon::parse($district->created)->format('d-m-Y')}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                          </table>
                        
                        </div>
                      </div>
                    </div>
                  </div>
                </div><!-- End Row-->
              </div>
            </div>
          </div>
        </div>
      </div><!--End Row-->
      <!--End Dashboard Content--> <!-- End container-fluid-->
    </div><!--End content-wrapper-->
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
