@extends('Admin.app')
@section('title',('Generate Invoice'))

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
				<div class="card border-top border-0 border-4 border-danger">
					<div class="card-body p-5">
            <h6 class="mb-0 text-uppercase">Generate Invoice</h6>
				    <hr/>
          <!-- <form>
					@csrf -->
						<div class="row g-3" style=" cursor: pointer;background: #e0efff; padding: 0px 20px 20px 20px; border-radius: 8px; margin-bottom:30px;">
              <div class="col-md-12">
                <label style="font-weight:500; margin-bottom:10px; font-size:20px;">District</label> 
                <br>
                @php 
                  $district = DB::table('add_district')->orderBy('district_id','DESC')->get();
                @endphp 

                @foreach($district as $district)
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="district" id="{{$district->district_id}}" value="{{$district->district_id}}" onchange="checkcat1(this)">
                    <label class="form-check-label" for="{{$district->district_id}}">{{$district->name}}</label>
                  </div>
                @endforeach
              </div>
            </div>

            <input type="hidden" id="dname" name="dname" />


            <div class="row g-3" style=" cursor: pointer;background: #e0efff; padding: 0px 20px 20px 20px; border-radius: 8px; margin-bottom:30px;">
              <div class="col-md-12">
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="type" id="type" value="1">
                    <label class="form-check-label" for="flexRadioDefatypeult11">Sample Wise</label>
                </div>
              </div>
              <div class="col-md-12">
                <input type="text" class="form-control" id="sample_rate" name="sample_rate" value="" placeholder="Per Sample Rate">
              </div>
            </div>

            <div class="row g-3" style=" cursor: pointer;background: #e0efff; padding: 0px 20px 20px 20px; border-radius: 8px; margin-bottom:30px;">
              <div class="col-md-12">
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="type" id="type1" value="0">
                    <label class="form-check-label" for="type1">Kilometer Wise</label>
                </div>
              </div>
              <div class="col-md-12">
                <input type="text" class="form-control" id="kilometer_rate" name="kilometer_rate" value="" placeholder="Per Kilo-meter Rate">
              </div>
            </div>

            <div class="row g-3">
                <div class="col-md-12" style="text-align:center;">
										<button type="type" onClick="invoice()" name="submit-cat" class="btn btn-danger px-5">Generate Invoice</button>
								</div>
            </div>

          <!-- </form> -->
					</div>
				</div>
			
			</div>
		</div>


@endsection

@push('script')
<script>
    function checkcat1(radio) {
      var dname = $('#dname').val();
      selected_value = $("input[name='district']:checked").val();
      $('#dname').val(selected_value);
    }
</script>
<script>
function invoice()
         {
          var district = $('#dname').val();
          var sample_rate = $('#sample_rate').val();
          var kilometer_rate = $('#kilometer_rate').val();
          var type = document.querySelector('input[name="type"]:checked').value;
          if(type == "1")
          {
            if(sample_rate == "")
            {
              alert('Please add sample rate. ');
            }
            else
            {
              window.location.href = "{{url('panel/Invoice/sample-invoice')}}/"+district+"/"+sample_rate;
            }
          }
          else
          {
            if(kilometer_rate == "")
            {
              alert('Please add kilometer rate. ');
            }
            else
            {
              window.location.href = "{{url('panel/Invoice/kilometer-invoice')}}/"+district+"/"+kilometer_rate;
            }
          }
        }
</script>
<!-- <script>
function invoice()
         {
          var district = $('#dname').val();
          
          var type = document.querySelector('input[name="type"]:checked').value;

         
          if(type == "1")
          {
            window.location.href = "{{url('panel/Invoice/generate-sample-invoice')}}/"+district+"/0";
          }
          else
          {
            window.location.href = "{{url('panel/Invoice/generate-kilometer-invoice')}}/"+district+"/0";
          }
        }
</script> -->
