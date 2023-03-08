<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\add_sample_collected_details;
use App\Models\add_sample_box_detail;
use App\Models\add_selected_sample;
use App\Models\add_collected_report;
use App\Models\add_collected_sample;
use App\Models\add_sample_box_item;
use App\Models\add_hospital;
use App\Models\add_lab;
use App\Models\add_collected_sample_multiple_images;
use App\Models\add_collect_submitted_report;
use App\Models\add_collect_submitted_sample;
use App\Models\add_submitted_hospital_report;
use App\Models\add_submitted_hospital_sample;
use App\Models\add_sample_box_sample;
use App\Models\User;
use App\Models\add_specimen;
use App\Models\add_test;
use App\Models\add_url;
use App\Models\add_staff_activity;
use Illuminate\Support\Str;
use DB;

class SampleController extends Controller
{
    public function add_sample_box_item(Request $request)
    {
        $data = $request->all();

        $ins=array(
            'staff_id'=>auth()->user()->id,
            'invoice_photo'=>$data['invoice_photo'] ? $data['invoice_photo'] : 'N/A',
            'scan_code'=>$data['scan_code'] ? $data['scan_code'] : 'N/A',
            'nikshay_id'=>$data['nikshay_id'] ? $data['nikshay_id'] : 'N/A',
            'patient'=>$data['patient'] ? $data['patient'] : 'N/A',
            'type_test_for'=>$data['type_test_for'] ? $data['type_test_for'] : 'N/A',
            'type_patient'=>$data['type_patient'] ? $data['type_patient'] : 'N/A',
            'no_of_sample'=>$data['no_of_sample'] ? $data['no_of_sample'] : 'N/A',
            'specimen_id'=>$data['specimen_id'] ? $data['specimen_id'] : 'N/A',
            'test_id'=>$data['test_id'] ? $data['test_id'] : 'N/A',
            'lab_id'=>$data['lab_id'] ? $data['lab_id'] : 'N/A',
            'created'=>date('Y-m-d'),
        );

            $add_sample_box_item = add_sample_box_item::create($ins);
            if ($add_sample_box_item) 
            {
                return response()->json(['success' => 'true','data' => $add_sample_box_item,'message' => 'added successfully'], 200);
            }
            else
            {
                return response()->json(['success' => 'false','data' => $add_sample_box_item,'message' => 'Something went wrong please try again'], 200);
            }
    }

    public function sample_box_item(Request $request)
    {
        $data = DB::Table('add_sample_box_item')->where('staff_id',auth()->user()->id)->orderBy('id','DESC')->get();

        if($data->isEmpty()){
            return response()->json(['success' => false,'message'=>'no data found'], 200);
        }
        else{
            return response()->json(['success' => true,'data'=>$data], 200);
        } 
    }

    public function add_sample(Request $request)
    {
        $data = $request->all();
        $refer_code = Str::random(6);

        $ins=array(
            'staff_id'=>auth()->user()->id,
            'sample_id'=>$refer_code,
            'sample_meter_name'=>$data['sample_meter_name'] ? $data['sample_meter_name'] : 'N/A',
            'sample_meter_photo'=>$data['sample_meter_photo'] ? $data['sample_meter_photo'] : 'N/A',
            'sample_date_time'=>$data['sample_date_time'] ? $data['sample_date_time'] : 'N/A',
            'collected_from'=>$data['collected_from'] ? $data['collected_from'] : 'N/A',
            'map_area_name'=>$data['map_area_name'] ? $data['map_area_name'] : 'N/A',
            'kilometer'=>$data['kilometer'] ? $data['kilometer'] : '0',
            'degree'=>$data['degree'] ? $data['degree'] : 'N/A',
            'sample_box_photo'=>$data['sample_box_photo'] ? $data['sample_box_photo'] : 'N/A',
            'sample_box_name'=>$data['sample_box_name'] ? $data['sample_box_name'] : 'N/A',
            'from_latitude'=>$data['from_latitude'] ? $data['from_latitude'] : 'N/A',
            'from_longitude'=>$data['from_longitude'] ? $data['from_longitude'] : 'N/A',
            'created'=>date('Y-m-d'),
        );

            $add_sample_collected_details = add_sample_collected_details::create($ins);
            $list['sample_id'] = $refer_code;
            $list['id'] = $add_sample_collected_details->id;

            $input11 = add_sample_collected_details::where('sample_id',$refer_code)->first();
            
            $input = add_sample_box_item::where('staff_id',auth()->user()->id)->get();
            foreach($input as $in)
            {
                $ins1['staff_id']=auth()->user()->id;
                $ins1['sample_auto_id']=$add_sample_collected_details->id;
                $ins1['sample_id']=$refer_code;
                $ins1['collected_from']=$in->collected_from;
                $ins1['scan_code']=$in->scan_code;
                $ins1['nikshay_id']=$in->nikshay_id;
                $ins1['invoice_photo']=$in->invoice_photo;
                $ins1['patient']=$in->patient;
                $ins1['type_test_for']=$in->type_test_for;
                $ins1['type_patient']=$in->type_patient;
                $ins1['no_of_sample']=$in->no_of_sample;
                $ins1['specimen_id']=$in->specimen_id;
                $ins1['test_id']=$in->test_id;
                $ins1['lab_id']=$in->lab_id;

                $districttt = add_lab::Where('lab_id',$in->lab_id)->select('district_id')->first();

                $ins1['district_id']=$districttt->district_id;

                $ins1['sample_meter_name']=$request->sample_meter_name;
                $ins1['sample_meter_photo']=$request->sample_meter_photo;
                $ins1['sample_date_time']=$request->sample_date_time;
                if($request->kilometer == '')
                {
                    $request->kilometer = '0';
                }
                $ins1['kilometer'] = $request->kilometer;
                $ins1['degree']=$request->degree;
                $ins1['sample_box_photo']=$request->sample_box_photo;
                $ins1['collected_from']=$request->collected_from;
                $ins1['sample_box_name']=$request->sample_box_name;

                $ins1['map_area_name']=$request->map_area_name;
                $ins1['from_latitude']=$request->from_latitude;
                $ins1['from_longitude']=$request->from_longitude;
                $ins1['created']=date('Y-m-d H:i:s');
                $ins1['date']=date('Y-m-d');
                $add_sample_box_detail = add_sample_box_detail::create($ins1);

                $data21 = new add_sample_box_sample();
                $data21->hospital_id = $request->collected_from;
                $data21->sample_selected_id = $add_sample_box_detail->id;;
                $data21->staff_id = auth()->user()->id;
                $data21->created = date('Y-m-d H:s:i');
                $data21->date = date('Y-m-d');
                $data21->save();
            }
            if ($add_sample_collected_details) 
            {
                $sid = auth()->user()->id;
                add_sample_box_item::where('staff_id',$sid)->delete();

                $data22 = new add_staff_activity();
                    $data22->staff_id = auth()->user()->id;
                    $data22->address = $request->map_area_name;
                    $data22->latitude = $request->from_latitude;
                    $data22->longitude = $request->from_longitude;
                    $data22->kilometer = $request->kilometer;
                    $data22->status = '1';
                    $data22->status_id = $request->collected_from;
                    $data22->created_time = date('H:i:s');
                    $data22->created = date('Y-m-d');
                    $data22->save();

                return response()->json(['success' => 'true','data' => $add_sample_collected_details,'message' => 'insert successfully'], 200);
            }
            else
            {
                return response()->json(['success' => 'false','data' => $add_sample_collected_details,'message' => 'Something went wrong please try again'], 200);
            }
    }

    public function submit_collection_lab(Request $request)
    {
        // $storage = [];
        $input = add_sample_box_detail::where('staff_id',auth()->user()->id)->where('status',0)->select('lab_id')->GroupBy('lab_id')->get();
        foreach ($input as $key) 
        {
            $data = add_lab::select('name','lab_id','image','latitude','longitude','gps_address')->where('lab_id',$key['lab_id'])->first();

            $key['name'] = $data->name;
            $key['image'] =$data->image;
            $key['gps_address'] =$data->gps_address;
            $key['latitude'] =$data->latitude;
            $key['longitude'] =$data->longitude;

            // array_push($storage, $key);
        }
        
        if($input->isEmpty()){
            return response()->json(['success' => false,'message'=>'no data found'], 200);
        }
        else{
            return response()->json(['success' => true,'data'=>$input], 200);
        }
    }

    public function search_collection_lab(Request $request)
    {
        $input = add_lab::where('name','like','%'.$request['name'].'%')->select('lab_id')->orderBy('lab_id', 'desc')->get();
        foreach ($input as $key) 
        {
            $input11 = add_sample_box_detail::where('staff_id',auth()->user()->id)->where('status',0)->where('lab_id',$key['lab_id'])->select('lab_id')->GroupBy('lab_id')->count();

            if($input11 == 0)
            {
                return response()->json(['success' => false,'message'=>'no data found'], 200);
            }
            else
            {
                $data = add_sample_box_detail::where('staff_id',auth()->user()->id)->where('status',0)->where('lab_id',$key['lab_id'])->select('lab_id')->first();

                $data1 = add_lab::select('name','lab_id','image','latitude','longitude','gps_address')->where('lab_id',$data['lab_id'])->first();

                $key['lab_id'] = $key->lab_id;
                $key['name'] = $data1->name;
                $key['image'] =$data1->image;
                $key['gps_address'] =$data1->gps_address;
                $key['latitude'] =$data1->latitude;
                $key['longitude'] =$data1->longitude;
            }
        }
        
        if($input->isEmpty()){
            return response()->json(['success' => false,'message'=>'no data found'], 200);
        }
        else{
            return response()->json(['success' => true,'data'=>$input], 200);
        }
    }

    public function all_collection_detail(Request $request)
    {
        // $input112 = add_sample_collected_details::join('add_hospital','add_sample_collected_details.collected_from','add_hospital.hospital_id')->where('add_sample_collected_details.staff_id',auth()->user()->id)->count();

        $input = add_sample_collected_details::where('staff_id',auth()->user()->id)->select('collected_from')->GroupBy('collected_from')->get();
        foreach ($input as $key) 
        {
            $input11 = add_hospital::select('name','hospital_id','image','latitude','longitude','gps_address')->where('hospital_id',$key['collected_from'])->count();

            if($input11 == 0)
            {

            }
            else
            {
                $data = add_hospital::select('name','hospital_id','image','latitude','longitude','gps_address')->where('hospital_id',$key['collected_from'])->first();

                $key['hospital_id'] = $data->hospital_id;
                $key['name'] = $data->name;
                $key['image'] =$data->image;
                $key['gps_address'] =$data->gps_address;
                $key['latitude'] =$data->latitude;
                $key['longitude'] =$data->longitude;
            }
        }
        
        if($input->isEmpty()){
            return response()->json(['success' => false,'message'=>'no data found'], 200);
        }
        else{
            return response()->json(['success' => true,'data'=>$input], 200);
        }
    }

    public function search_collection_hospital(Request $request)
    {
        $input = add_hospital::where('name','like','%'.$request['name'].'%')->select('hospital_id')->orderBy('hospital_id', 'desc')->get();
        foreach ($input as $key) 
        {
            $input11 = add_sample_collected_details::where('staff_id',auth()->user()->id)->where('collected_from',$key['hospital_id'])->select('collected_from')->count();
            // $key['count'] = $data1;

            if($input11 == 0)
            {
                return response()->json(['success' => false,'message'=>'no data found'], 200);
            }
            else
            {
                $data = add_sample_collected_details::where('staff_id',auth()->user()->id)->where('collected_from',$key['hospital_id'])->select('collected_from')->first();

                $data1 = add_hospital::select('name','hospital_id','image','latitude','longitude','gps_address')->where('hospital_id',$data['collected_from'])->first();

                $key['hospital_id'] = $key->hospital_id;
                $key['name'] = $data1->name;
                $key['image'] =$data1->image;
                $key['gps_address'] =$data1->gps_address;
                $key['latitude'] =$data1->latitude;
                $key['longitude'] =$data1->longitude;
            }
        }
        
        if($input->isEmpty()){
            return response()->json(['success' => false,'message'=>'no data found'], 200);
        }
        else{
            return response()->json(['success' => true,'data'=>$input], 200);
        }
    }

    public function all_collection_count(Request $request)
    {
        $date = date('Y-m-d');
        $data = DB::Table('add_sample_box_detail')->where('add_sample_box_detail.staff_id',auth()->user()->id)->where('add_sample_box_detail.status',0)->count();

        return response()->json(['success' => true,'count'=>$data], 200);
    }

    public function all_hospital_sample_collected(Request $request)
    {
        $date = date('Y-m-d');
        $data = DB::Table('add_sample_box_detail')->leftjoin('add_hospital','add_sample_box_detail.collected_from','add_hospital.hospital_id')->leftjoin('add_specimen','add_sample_box_detail.specimen_id','add_specimen.id')->leftjoin('add_test','add_sample_box_detail.test_id','add_test.id')->leftjoin('add_lab','add_sample_box_detail.lab_id','add_lab.lab_id')->where('add_sample_box_detail.collected_from',$request->id)->where('add_sample_box_detail.staff_id',auth()->user()->id)->where('add_sample_box_detail.status',0)->orderBy('add_sample_box_detail.id','DESC')->select('add_sample_box_detail.id','add_sample_box_detail.nikshay_id','add_sample_box_detail.patient','add_sample_box_detail.invoice_photo','add_sample_box_detail.sample_meter_photo','add_sample_box_detail.sample_box_photo','add_sample_box_detail.type_test_for','add_sample_box_detail.type_patient','add_sample_box_detail.no_of_sample','add_specimen.name as specimen','add_test.name as test_name','add_lab.name as lab_name')->get();

        if($data->isEmpty()){
            return response()->json(['success' => false,'message'=>'no data found'], 200);
        }
        else{
            return response()->json(['success' => true,'data'=>$data], 200);
        } 
    }

    public function lab_collection_point(Request $request)
    {
        $list = add_sample_box_detail::join('add_lab','add_sample_box_detail.lab_id','add_lab.lab_id')->where('add_sample_box_detail.lab_id',$request->id)->where('add_sample_box_detail.staff_id',auth()->user()->id)->where('add_sample_box_detail.status',0)->select('add_sample_box_detail.id','add_sample_box_detail.sample_id','add_sample_box_detail.nikshay_id','add_sample_box_detail.patient','add_sample_box_detail.invoice_photo')->orderBy('add_sample_box_detail.id','DESC')->get();

        if($list->isEmpty())
        {
            return response()->json(['success' => 'false','message' => 'no data found','data'=>$list
            ], 200);
        }
        else
        {
            return response()->json(['success' => 'true','data'=>$list
            ], 200);
        }
    }
    
    public function add_collected_report(Request $request)
    {
        $data = $request->all();
        $refer_code = Str::random(6);
        $ins=array(
            'staff_id'=>auth()->user()->id,
            'report_id'=>$refer_code,
            'lab_id'=>$data['lab_id'] ? $data['lab_id'] : 'N/A',
            'lt_name'=>$data['lt_name'] ? $data['lt_name'] : 'N/A',
            'designation'=>$data['designation'] ? $data['designation'] : 'N/A',
            'digital_signature'=>$data['digital_signature'] ? $data['digital_signature'] : 'N/A',
            'sample_selected_id'=>$data['sample_selected_id'] ? $data['sample_selected_id'] : 'N/A',
            'created'=>date('Y-m-d'),
        );
        $add_collected_report = add_collected_report::create($ins);
        $arrayNames = explode(',', $data['sample_selected_id']);

        $count = 0;
        foreach($arrayNames as $name)
        {
                $str = $name;
                $rem = trim($str, "[]");
                
                $par = str_replace(' ', '', $rem);
                $data2 = new add_collected_sample();
                $data2->collected_id = $add_collected_report->id;
                $data2->lab_id = $add_collected_report->lab_id;
                $data2->report_id = $refer_code;
                $data2->sample_selected_id = $par;
                $data2->staff_id = auth()->user()->id;
                $data2->created = date('Y-m-d');
                $data2->save();
                
                $ins2=array(
                    'to_sample_date_time'=>$data['to_sample_date_time'] ? $data['to_sample_date_time'] : 'N/A',
                    'to_kilometer'=>$data['to_kilometer'] ? $data['to_kilometer'] : '0',
                    'to_degree'=>$data['to_degree'] ? $data['to_degree'] : 'N/A',
                    'to_sample_meter_photo'=>$data['to_sample_meter_photo'] ? $data['to_sample_meter_photo'] : 'N/A',
                    'to_sample_box_photo'=>$data['to_sample_box_photo'] ? $data['to_sample_box_photo'] : 'N/A',
                    'to_map_area_name'=>$data['to_map_area_name'] ? $data['to_map_area_name'] : 'N/A',
                    'to_longitude'=>$data['to_longitude'] ? $data['to_longitude'] : 'N/A',
                    'to_latitude'=>$data['to_latitude'] ? $data['to_latitude'] : 'N/A',
                    'status'=>1,
                );
                $edit = add_sample_box_detail::where('id',$par)->update($ins2);

                $selectdata = add_sample_box_detail::where('id',$par)->select('sample_id')->first();

                $ins2=array(
                    'to_actual_kilometer'=>$data['to_sample_date_time'] ? $data['to_sample_date_time'] : 'N/A',
                );
                $edit = add_sample_box_detail::where('id',$par)->update($ins2);

                $selectdata333 = add_sample_collected_details::where('sample_id',$selectdata['sample_id'])->where('to_kilometer','!=','')->count();
                if($selectdata333 != 0)
                {
                    $selectdata331 = add_sample_collected_details::where('sample_id',$selectdata['sample_id'])->where('to_kilometer','!=','')->first();
                    if($request->to_kilometer == '')
                    {
                        $request->to_kilometer = '0';
                    }
                    $kilometer = $selectdata331->to_kilometer + $request->to_kilometer;
                    if($count == 0)
                    {
                        $ins4=array(
                            'to_sample_date_time'=>$data['to_sample_date_time'] ? $data['to_sample_date_time'] : 'N/A',
                            'to_kilometer'=>$kilometer,
                            'to_degree'=>$data['to_degree'] ? $data['to_degree'] : 'N/A',
                            'to_sample_meter_photo'=>$data['to_sample_meter_photo'] ? $data['to_sample_meter_photo'] : 'N/A',
                            'to_sample_box_photo'=>$data['to_sample_box_photo'] ? $data['to_sample_box_photo'] : 'N/A',
                            'to_map_area_name'=>$data['to_map_area_name'] ? $data['to_map_area_name'] : 'N/A',
                            'to_longitude'=>$data['to_longitude'] ? $data['to_longitude'] : 'N/A',
                            'to_latitude'=>$data['to_latitude'] ? $data['to_latitude'] : 'N/A',
                            'status'=>1,
                        );
                        $edit11 = add_sample_collected_details::where('sample_id',$selectdata['sample_id'])->update($ins4);
                        $count = $count + 1;
                    }
                }
                else
                {
                    $ins4=array(
                        'to_sample_date_time'=>$data['to_sample_date_time'] ? $data['to_sample_date_time'] : 'N/A',
                        'to_kilometer'=>$data['to_kilometer'] ? $data['to_kilometer'] : '0',
                        'to_degree'=>$data['to_degree'] ? $data['to_degree'] : 'N/A',
                        'to_sample_meter_photo'=>$data['to_sample_meter_photo'] ? $data['to_sample_meter_photo'] : 'N/A',
                        'to_sample_box_photo'=>$data['to_sample_box_photo'] ? $data['to_sample_box_photo'] : 'N/A',
                        'to_map_area_name'=>$data['to_map_area_name'] ? $data['to_map_area_name'] : 'N/A',
                        'to_longitude'=>$data['to_longitude'] ? $data['to_longitude'] : 'N/A',
                        'to_latitude'=>$data['to_latitude'] ? $data['to_latitude'] : 'N/A',
                        'status'=>1,
                    );
                    $edit11 = add_sample_collected_details::where('sample_id',$selectdata['sample_id'])->update($ins4);

                    $count = $count + 1;
                }
        } 
        if ($add_collected_report) 
        {      
                $data22 = new add_staff_activity();
                $data22->staff_id = auth()->user()->id;
                $data22->address = $request->to_map_area_name;
                $data22->latitude = $request->to_latitude;
                $data22->longitude = $request->to_longitude;
                $data22->status = '2';
                $data22->status_id = $request->lab_id;
                if($request->to_kilometer == '')
                    {
                        $request->to_kilometer = '0';
                    }
                $data22->kilometer = $request->to_kilometer;
                $data22->created_time = date('H:i:s');
                $data22->created = date('Y-m-d');
                $data22->save();  
            return response()->json(['success' => 'true','data' => $add_collected_report,'message' => 'insert successfully'], 200);
        }
        else
        {
            return response()->json(['success' => 'false','data' => $add_collected_report,'message' => 'Something went wrong please try again'], 200);
        }
    }

    public function collected_report_lab(Request $request)
    {
        // $storage = [];
        
        $input = add_sample_box_detail::where('staff_id',auth()->user()->id)->where('status',1)->select('lab_id')->GroupBy('lab_id')->get();
        foreach ($input as $key) 
        {
            $data = add_lab::select('name','lab_id','image','latitude','longitude','gps_address')->where('lab_id',$key['lab_id'])->first();

            $key['name'] = $data->name;
            $key['image'] =$data->image;
            $key['gps_address'] =$data->gps_address;
            $key['latitude'] =$data->latitude;
            $key['longitude'] =$data->longitude;
        }
        
        if($input->isEmpty()){
            return response()->json(['success' => false,'message'=>'no data found'], 200);
        }
        else{
            return response()->json(['success' => true,'data'=>$input], 200);
        }
    }

    public function all_submitted_count(Request $request)
    {
        $date = date('Y-m-d');
        $data = DB::Table('add_sample_collected_details')->leftjoin('add_sample_box_detail','add_sample_collected_details.sample_id','add_sample_box_detail.sample_id')->where('add_sample_collected_details.staff_id',auth()->user()->id)->where('add_sample_box_detail.status',1)->count();

        return response()->json(['success' => true,'count'=>$data], 200);
    }

    public function search_collected_report_lab(Request $request)
    {
        $input = add_lab::where('name','like','%'.$request['name'].'%')->select('lab_id')->orderBy('lab_id', 'desc')->get();
        foreach ($input as $key) 
        {

            $input11 = add_sample_box_detail::where('staff_id',auth()->user()->id)->where('status',1)->where('lab_id',$key['lab_id'])->select('lab_id')->GroupBy('lab_id')->count();

            if($input11 == 0)
            {
                return response()->json(['success' => false,'message'=>'no data found'], 200);
            }
            else
            {
                $data = add_sample_box_detail::where('staff_id',auth()->user()->id)->where('status',1)->where('lab_id',$key['lab_id'])->select('lab_id')->first();

                $data1 = add_lab::select('name','lab_id','image','latitude','longitude','gps_address')->where('lab_id',$data['lab_id'])->first();

                $key['lab_id'] = $key->lab_id;
                $key['name'] = $data1->name;
                $key['image'] =$data1->image;
                $key['gps_address'] =$data1->gps_address;
                $key['latitude'] =$data1->latitude;
                $key['longitude'] =$data1->longitude;
            }
        }
        
        if($input->isEmpty()){
            return response()->json(['success' => false,'message'=>'no data found'], 200);
        }
        else{
            return response()->json(['success' => true,'data'=>$input], 200);
        }
    }

    public function submitted_report_detail(Request $request)
    {
        
        // $input = add_collected_report::where('staff_id',auth()->user()->id)->where('lab_id',$request->id)->select('lt_name','designation','digital_signature')->first();
        
        // $input['data1'] = add_sample_box_detail::where('staff_id',auth()->user()->id)->where('lab_id',$request->id)->where('status',1)->get();

        // return response()->json(['success' => true,'data'=>$input], 200);

        $input = add_sample_box_detail::where('staff_id',auth()->user()->id)->where('lab_id',$request->id)->where('status',1)->orderBy('id','DESC')->get();
        foreach ($input as $key) 
        {
            $data1 = add_collected_sample::select('report_id')->where('sample_selected_id',$key['id'])->orderBy('id','DESC')->first();

            $data = add_collected_report::select('lt_name','designation','digital_signature')->where('report_id',$data1['report_id'])->first();
            
            $key['lt_name'] = $data->lt_name;
            $key['designation'] = $data->designation;
            $key['digital_signature'] = $data->digital_signature;
        }

        if($input->isEmpty()){
            return response()->json(['success' => false,'message'=>'no data found'], 200);
        }
        else{
            return response()->json(['success' => true,'data'=>$input], 200);
        }
        
    }

    public function submitted_lab_sample(Request $request)
    {
        $input = add_sample_box_detail::where('staff_id',auth()->user()->id)->where('lab_id',$request->id)->where('status',1)->get();
        foreach ($input as $key) 
        {
            $data = add_hospital::select('name')->where('hospital_id',$key['collected_from'])->first();
            $key['hospital_name'] = $data->name;
        }

        if($input->isEmpty()){
            return response()->json(['success' => false,'message'=>'no data found'], 200);
        }
        else{
            return response()->json(['success' => true,'data'=>$input], 200);
        }
    }

    public function add_submitted_sample_images(Request $request)
    {
        $data = $request->all();

        $arrayNames = explode(',', $request['image']);
        foreach($arrayNames as $name)
        {
                $str = $name;
                $rem = trim($str, "[]");
                $par = str_replace(' ', '', $rem);
                $data2 = new add_collected_sample_multiple_images();
                $data2->sample_id = $request->sample_id;
                $data2->image = $par;
                $data2->staff_id = auth()->user()->id;
                $data2->created = date('Y-m-d');
                $data2->save();
        }

        return response()->json(['success' => 'true','message' => 'insert successfully'], 200);


        // $ins=array(
        //     'staff_id'=>auth()->user()->id,
        //     'sample_id'=>$data['sample_id'] ? $data['sample_id'] : 'N/A',
        //     'image'=>$data['image'] ? $data['image'] : 'N/A',
        //     'created'=>date('Y-m-d H:i:s'),
        // );

        //     $add_collected_sample_multiple_images = add_collected_sample_multiple_images::create($ins);
        //     if ($add_collected_sample_multiple_images) 
        //     {
        //         return response()->json(['success' => 'true','data' => $add_collected_sample_multiple_images,'message' => 'added successfully'], 200);
        //     }
        //     else
        //     {
        //         return response()->json(['success' => 'false','message' => 'Something went wrong please try again'], 200);
        //     }
    }

    public function submitted_sample_images(Request $request)
    {
        $data = DB::Table('add_collected_sample_multiple_images')->where('staff_id',auth()->user()->id)->where('sample_id',$request->id)->orderBy('id','DESC')->get();

        if($data->isEmpty()){
            return response()->json(['success' => false,'message'=>'no data found'], 200);
        }
        else{
            return response()->json(['success' => true,'data'=>$data], 200);
        } 
    }

    public function add_submitted_collected_report(Request $request)
    {
        $data = $request->all();
        $refer_code = Str::random(6);
        $ins=array(
            'staff_id'=>auth()->user()->id,
            'report_id'=>$refer_code,
            'lab_id'=>$data['lab_id'] ? $data['lab_id'] : 'N/A',
            'lt_name'=>$data['lt_name'] ? $data['lt_name'] : 'N/A',
            'designation'=>$data['designation'] ? $data['designation'] : 'N/A',
            'digital_signature'=>$data['digital_signature'] ? $data['digital_signature'] : 'N/A',
            'sample_selected_id'=>$data['sample_selected_id'] ? $data['sample_selected_id'] : 'N/A',
            'created'=>date('Y-m-d'),
        );
        $add_collect_submitted_report = add_collect_submitted_report::create($ins);
        $arrayNames = explode(',', $data['sample_selected_id']);
        $count = 0;
        foreach($arrayNames as $name)
        {
                $str = $name;
                $rem = trim($str, "[]");
                $par = str_replace(' ', '', $rem);
                $data2 = new add_collect_submitted_sample();
                $data2->collected_id = $add_collect_submitted_report->id;
                $data2->lab_id = $add_collect_submitted_report->lab_id;
                $data2->report_id = $refer_code;
                $data2->sample_selected_id = $par;
                $data2->staff_id = auth()->user()->id;
                $data2->created = date('Y-m-d');
                $data2->save();
                
                $ins2=array(
                    'collect_lab_sample_meter_photo'=>$data['collect_lab_sample_meter_photo'] ? $data['collect_lab_sample_meter_photo'] : 'N/A',
                    'collect_lab_sample_date_time'=>$data['collect_lab_sample_date_time'] ? $data['collect_lab_sample_date_time'] : 'N/A',
                    'collect_lab_kilometer'=>$data['collect_lab_kilometer'] ? $data['collect_lab_kilometer'] : '0',
                    'collect_map_area_name'=>$data['collect_map_area_name'] ? $data['collect_map_area_name'] : 'N/A',
                    'collect_latitude'=>$data['collect_latitude'] ? $data['collect_latitude'] : 'N/A',
                    'collect_longitude'=>$data['collect_longitude'] ? $data['collect_longitude'] : 'N/A',
                    'status'=>2,
                );
                $edit = add_sample_box_detail::where('id',$par)->update($ins2);

                $selectdata = add_sample_box_detail::where('id',$par)->select('sample_id')->first();

                $selectdata333 = add_sample_collected_details::where('sample_id',$selectdata['sample_id'])->where('collect_lab_kilometer','!=','')->count();
                if($selectdata333 != 0)
                {
                    $selectdata331 = add_sample_collected_details::where('sample_id',$selectdata['sample_id'])->where('collect_lab_kilometer','!=','')->first();
                    if($request->collect_lab_kilometer == '')
                    {
                        $request->collect_lab_kilometer = 0;
                    }
                    $kilometer = $selectdata331->collect_lab_kilometer + $request->collect_lab_kilometer;
                    if($count == 0)
                    {
                        $ins4=array(
                            'collect_lab_sample_meter_photo'=>$data['collect_lab_sample_meter_photo'] ? $data['collect_lab_sample_meter_photo'] : 'N/A',
                            'collect_lab_sample_date_time'=>$data['collect_lab_sample_date_time'] ? $data['collect_lab_sample_date_time'] : 'N/A',
                            'collect_lab_kilometer'=>$kilometer,
                            'collect_map_area_name'=>$data['collect_map_area_name'] ? $data['collect_map_area_name'] : 'N/A',
                            'collect_latitude'=>$data['collect_latitude'] ? $data['collect_latitude'] : 'N/A',
                            'collect_longitude'=>$data['collect_longitude'] ? $data['collect_longitude'] : 'N/A',
                            'status'=>2,
                        );
                        $edit11 = add_sample_collected_details::where('sample_id',$selectdata['sample_id'])->update($ins4);
                        $count = $count + 1;
                    }
                }
                else
                {
                    $ins4=array(
                        'collect_lab_sample_meter_photo'=>$data['collect_lab_sample_meter_photo'] ? $data['collect_lab_sample_meter_photo'] : 'N/A',
                        'collect_lab_sample_date_time'=>$data['collect_lab_sample_date_time'] ? $data['collect_lab_sample_date_time'] : 'N/A',
                        'collect_lab_kilometer'=>$data['collect_lab_kilometer'] ? $data['collect_lab_kilometer'] : '0',
                        'collect_map_area_name'=>$data['collect_map_area_name'] ? $data['collect_map_area_name'] : 'N/A',
                        'collect_latitude'=>$data['collect_latitude'] ? $data['collect_latitude'] : 'N/A',
                        'collect_longitude'=>$data['collect_longitude'] ? $data['collect_longitude'] : 'N/A',
                        'status'=>2,
                    );
                    $edit11 = add_sample_collected_details::where('sample_id',$selectdata['sample_id'])->update($ins4);

                    $count = $count + 1;
                }
        } 
        if ($add_collect_submitted_report) 
        {        
                $data22 = new add_staff_activity();
                $data22->staff_id = auth()->user()->id;
                $data22->address = $request->collect_map_area_name;
                $data22->latitude = $request->collect_latitude;
                $data22->longitude = $request->collect_latitude;
                $data22->status = '3';
                $data22->status_id = $request->lab_id;
                if($request->collect_lab_kilometer == '')
                    {
                        $request->collect_lab_kilometer = 0;
                    }
                $data22->kilometer = $request->collect_lab_kilometer;
                $data22->created_time = date('H:i:s');
                $data22->created = date('Y-m-d');
                $data22->save();  

            return response()->json(['success' => 'true','data' => $add_collect_submitted_report,'message' => 'insert successfully'], 200);
        }
        else
        {
            return response()->json(['success' => 'false','message' => 'Something went wrong please try again'], 200);
        }
    }

    public function all_collected_report_count(Request $request)
    {
        $date = date('Y-m-d');
        $data = DB::Table('add_sample_box_detail')->where('add_sample_box_detail.staff_id',auth()->user()->id)->where('add_sample_box_detail.status',2)->count();

        return response()->json(['success' => true,'count'=>$data], 200);
    }

    public function collect_submitted_report_lab(Request $request)
    {
        $input = add_sample_box_detail::where('staff_id',auth()->user()->id)->where('status',2)->select('lab_id')->GroupBy('lab_id')->get();
        foreach ($input as $key) 
        {
            $data = add_lab::select('name','lab_id','image','latitude','longitude','gps_address')->where('lab_id',$key['lab_id'])->first();

            $key['name'] = $data->name;
            $key['image'] =$data->image;
            $key['gps_address'] =$data->gps_address;
            $key['latitude'] =$data->latitude;
            $key['longitude'] =$data->longitude;
        }
        
        if($input->isEmpty()){
            return response()->json(['success' => false,'message'=>'no data found'], 200);
        }
        else{
            return response()->json(['success' => true,'data'=>$input], 200);
        }
    }

    public function search_collect_submitted_report_lab(Request $request)
    {
        $input = add_lab::where('name','like','%'.$request['name'].'%')->select('lab_id')->orderBy('lab_id', 'desc')->get();
        foreach ($input as $key) 
        {

            $input11 = add_sample_box_detail::where('staff_id',auth()->user()->id)->where('status',2)->where('lab_id',$key['lab_id'])->select('lab_id')->GroupBy('lab_id')->count();

            if($input11 == 0)
            {
                return response()->json(['success' => false,'message'=>'no data found'], 200);
            }
            else
            {
                $data = add_sample_box_detail::where('staff_id',auth()->user()->id)->where('status',2)->where('lab_id',$key['lab_id'])->select('lab_id')->first();

                $data1 = add_lab::select('name','lab_id','image','latitude','longitude','gps_address')->where('lab_id',$data['lab_id'])->first();

                $key['lab_id'] = $key->lab_id;
                $key['name'] = $data1->name;
                $key['image'] =$data1->image;
                $key['gps_address'] =$data1->gps_address;
                $key['latitude'] =$data1->latitude;
                $key['longitude'] =$data1->longitude;
            }
        }
        
        if($input->isEmpty()){
            return response()->json(['success' => false,'message'=>'no data found'], 200);
        }
        else{
            return response()->json(['success' => true,'data'=>$input], 200);
        }
    }

    public function collect_submitted_report_detail(Request $request)
    {
        // $input = add_collect_submitted_report::where('staff_id',auth()->user()->id)->where('lab_id',$request->id)->select('lt_name','designation','digital_signature')->first();
        
        // $input['data1'] = add_sample_box_detail::where('staff_id',auth()->user()->id)->where('lab_id',$request->id)->where('status',2)->get();

        // return response()->json(['success' => true,'data'=>$input], 200);

        $input = add_sample_box_detail::where('staff_id',auth()->user()->id)->where('lab_id',$request->id)->where('status',2)->orderBy('id','DESC')->get();
        foreach ($input as $key) 
        {
            $data1 = add_collect_submitted_sample::select('report_id')->where('sample_selected_id',$key['id'])->orderBy('id','DESC')->first();

            $data = add_collect_submitted_report::select('lt_name','designation','digital_signature')->where('report_id',$data1['report_id'])->first();
            
            $key['lt_name'] = $data->lt_name;
            $key['designation'] = $data->designation;
            $key['digital_signature'] = $data->digital_signature;
        }

        if($input->isEmpty()){
            return response()->json(['success' => false,'message'=>'no data found'], 200);
        }
        else{
            return response()->json(['success' => true,'data'=>$input], 200);
        }
    }

    public function collected_report_hospital(Request $request)
    {
        $input = add_sample_box_detail::where('staff_id',auth()->user()->id)->where('status',2)->select('collected_from')->GroupBy('collected_from')->get();
        foreach ($input as $key) 
        {
            $data = add_hospital::select('name','hospital_id','image','latitude','longitude','gps_address')->where('hospital_id',$key['collected_from'])->first();

            $key['hospital_id'] = $data->hospital_id;
            $key['name'] = $data->name;
            $key['image'] =$data->image;
            $key['gps_address'] =$data->gps_address;
            $key['latitude'] =$data->latitude;
            $key['longitude'] =$data->longitude;
        }

        if($input->isEmpty()){
            return response()->json(['success' => false,'message'=>'no data found'], 200);
        }
        else{
            return response()->json(['success' => true,'data'=>$input], 200);
        }
    }

    public function search_collect_report_hospital(Request $request)
    {
        $input = add_hospital::where('name','like','%'.$request['name'].'%')->select('hospital_id')->orderBy('hospital_id', 'desc')->get();
        foreach ($input as $key) 
        {

            $input11 = add_sample_box_detail::where('staff_id',auth()->user()->id)->where('status',2)->where('collected_from',$key['hospital_id'])->select('collected_from')->GroupBy('collected_from')->count();

            if($input11 == 0)
            {
                return response()->json(['success' => false,'message'=>'no data found'], 200);
            }
            else
            {
                $data = add_sample_box_detail::where('staff_id',auth()->user()->id)->where('status',2)->where('collected_from',$key['hospital_id'])->select('collected_from')->first();

                $data1 = add_hospital::select('name','hospital_id','image','latitude','longitude','gps_address')->where('hospital_id',$data['collected_from'])->first();

                $key['hospital_id'] = $key->hospital_id;
                $key['name'] = $data1->name;
                $key['image'] =$data1->image;
                $key['gps_address'] =$data1->gps_address;
                $key['latitude'] =$data1->latitude;
                $key['longitude'] =$data1->longitude;
            }
        }
        
        if($input->isEmpty()){
            return response()->json(['success' => false,'message'=>'no data found'], 200);
        }
        else{
            return response()->json(['success' => true,'data'=>$input], 200);
        }
    }

    public function submitted_hospital_sample(Request $request)
    {
        $input = add_sample_box_detail::where('staff_id',auth()->user()->id)->where('collected_from',$request->id)->where('status',2)->get();
        foreach ($input as $key) 
        {
            $data = add_hospital::select('name')->where('hospital_id',$key['collected_from'])->first();
            $key['hospital_name'] = $data->name;
        }

        if($input->isEmpty()){
            return response()->json(['success' => false,'message'=>'no data found'], 200);
        }
        else{
            return response()->json(['success' => true,'data'=>$input], 200);
        }
    }

    public function add_submitted_report(Request $request)
    {
        $data = $request->all();
        $refer_code = Str::random(6);
        $ins=array(
            'staff_id'=>auth()->user()->id,
            'report_id'=>$refer_code,
            'hospital_id'=>$data['hospital_id'] ? $data['hospital_id'] : 'N/A',
            'lt_name'=>$data['lt_name'] ? $data['lt_name'] : 'N/A',
            'designation'=>$data['designation'] ? $data['designation'] : 'N/A',
            'digital_signature'=>$data['digital_signature'] ? $data['digital_signature'] : 'N/A',
            'sample_selected_id'=>$data['sample_selected_id'] ? $data['sample_selected_id'] : 'N/A',
            'created'=>date('Y-m-d'),
        );
        $add_submitted_hospital_report = add_submitted_hospital_report::create($ins);
        $arrayNames = explode(',', $data['sample_selected_id']);
        foreach($arrayNames as $name)
        {
                $str = $name;
                $rem = trim($str, "[]");
                $par = str_replace(' ', '', $rem);
                $data2 = new add_submitted_hospital_sample();
                $data2->collected_id = $add_submitted_hospital_report->id;
                $data2->hospital_id = $add_submitted_hospital_report->hospital_id;
                $data2->report_id = $refer_code;
                $data2->sample_selected_id = $par;
                $data2->staff_id = auth()->user()->id;
                $data2->created = date('Y-m-d');
                $data2->save();
                
                $ins2=array(
                    'submit_hospital_sample_meter_photo'=>$data['submit_hospital_sample_meter_photo'] ? $data['submit_hospital_sample_meter_photo'] : 'N/A',
                    'submit_hospital_sample_date_time'=>$data['submit_hospital_sample_date_time'] ? $data['submit_hospital_sample_date_time'] : 'N/A',
                    'submit_hospital_kilometer'=>$data['submit_hospital_kilometer'] ? $data['submit_hospital_kilometer'] : '0',
                    'submit_map_area_name'=>$data['submit_map_area_name'] ? $data['submit_map_area_name'] : 'N/A',
                    'submit_latitude'=>$data['submit_latitude'] ? $data['submit_latitude'] : 'N/A',
                    'submit_longitude'=>$data['submit_longitude'] ? $data['submit_longitude'] : 'N/A',
                    'status'=>3,
                );
                $edit = add_sample_box_detail::where('id',$par)->update($ins2);

                $selectdata = add_sample_box_detail::where('id',$par)->select('sample_id')->first();

                $ins4=array(
                    'submit_hospital_sample_meter_photo'=>$data['submit_hospital_sample_meter_photo'] ? $data['submit_hospital_sample_meter_photo'] : 'N/A',
                    'submit_hospital_sample_date_time'=>$data['submit_hospital_sample_date_time'] ? $data['submit_hospital_sample_date_time'] : 'N/A',
                    'submit_hospital_kilometer'=>$data['submit_hospital_kilometer'] ? $data['submit_hospital_kilometer'] : '0',
                    'submit_map_area_name'=>$data['submit_map_area_name'] ? $data['submit_map_area_name'] : 'N/A',
                    'submit_latitude'=>$data['submit_latitude'] ? $data['submit_latitude'] : 'N/A',
                    'submit_longitude'=>$data['submit_longitude'] ? $data['submit_longitude'] : 'N/A',
                    'status'=>3,
                    'submitted_date'=>date('Y-m-d'),
                    'status'=>3,
                );
                $edit11 = add_sample_collected_details::where('sample_id',$selectdata['sample_id'])->update($ins4);
        } 
        if ($add_submitted_hospital_report) 
        {        
            $data22 = new add_staff_activity();
                $data22->staff_id = auth()->user()->id;
                $data22->address = $request->submit_map_area_name;
                $data22->latitude = $request->submit_latitude;
                $data22->longitude = $request->submit_longitude;
                $data22->status = '4';
                $data22->status_id = $request->hospital_id;
                if($request->collect_lab_kilometer == '')
                {
                    $request->collect_lab_kilometer = '0';
                }
                $data22->kilometer = $request->submit_hospital_kilometer;
                $data22->created_time = date('H:i:s');
                $data22->created = date('Y-m-d');
                $data22->save();  

            return response()->json(['success' => 'true','data' => $add_submitted_hospital_report,'message' => 'insert successfully'], 200);
        }
        else
        {
            return response()->json(['success' => 'false','message' => 'Something went wrong please try again'], 200);
        }
    }

    public function all_submitted_report_count(Request $request)
    {
        $date = date('Y-m-d');
        $data = DB::Table('add_sample_box_detail')->where('add_sample_box_detail.staff_id',auth()->user()->id)->where('add_sample_box_detail.status',3)->count();

        return response()->json(['success' => true,'count'=>$data], 200);
    }

    public function submitted_report_hospital(Request $request)
    {
        $input = add_sample_box_detail::where('staff_id',auth()->user()->id)->where('status',3)->select('collected_from')->GroupBy('collected_from')->get();
        foreach ($input as $key) 
        {
            $data = add_hospital::select('name','hospital_id','image','latitude','longitude','gps_address')->where('hospital_id',$key['collected_from'])->first();

            $key['hospital_id'] = $data->hospital_id;
            $key['name'] = $data->name;
            $key['image'] =$data->image;
            $key['gps_address'] =$data->gps_address;
            $key['latitude'] =$data->latitude;
            $key['longitude'] =$data->longitude;
        }

        if($input->isEmpty()){
            return response()->json(['success' => false,'message'=>'no data found'], 200);
        }
        else{
            return response()->json(['success' => true,'data'=>$input], 200);
        }
    }
    
    public function search_submitted_report_hospital(Request $request)
    {
        $input = add_hospital::where('name','like','%'.$request['name'].'%')->select('hospital_id')->orderBy('hospital_id', 'desc')->get();
        foreach ($input as $key) 
        {
            $input11 = add_sample_box_detail::where('staff_id',auth()->user()->id)->where('status',3)->where('collected_from',$key['hospital_id'])->select('collected_from')->GroupBy('collected_from')->count();

            if($input11 == 0)
            {
                return response()->json(['success' => false,'message'=>'no data found'], 200);
            }
            else
            {
                $data = add_sample_box_detail::where('staff_id',auth()->user()->id)->where('status',3)->where('collected_from',$key['hospital_id'])->select('collected_from')->first();

                $data1 = add_hospital::select('name','hospital_id','image','latitude','longitude','gps_address')->where('hospital_id',$data['collected_from'])->first();

                $key['hospital_id'] = $key->hospital_id;
                $key['name'] = $data1->name;
                $key['image'] =$data1->image;
                $key['gps_address'] =$data1->gps_address;
                $key['latitude'] =$data1->latitude;
                $key['longitude'] =$data1->longitude;
            }
        }
        
        if($input->isEmpty()){
            return response()->json(['success' => false,'message'=>'no data found'], 200);
        }
        else{
            return response()->json(['success' => true,'data'=>$input], 200);
        }
    }

    public function submitted_report_hospital_detail(Request $request)
    {
        // $input = add_submitted_hospital_report::where('staff_id',auth()->user()->id)->where('hospital_id',$request->id)->select('lt_name','designation','digital_signature')->first();
        
        // $input['data1'] = add_sample_box_detail::where('staff_id',auth()->user()->id)->where('collected_from',$request->id)->where('status',3)->get();

        // return response()->json(['success' => true,'data'=>$input], 200);

        $input = add_sample_box_detail::where('staff_id',auth()->user()->id)->where('collected_from',$request->id)->where('status',3)->orderBy('id','DESC')->get();
        foreach ($input as $key) 
        {
            $data1 = add_submitted_hospital_sample::select('report_id')->where('sample_selected_id',$key['id'])->orderBy('id','DESC')->first();

            $data = add_submitted_hospital_report::select('lt_name','designation','digital_signature')->where('report_id',$data1['report_id'])->first();
            
            $key['lt_name'] = $data->lt_name;
            $key['designation'] = $data->designation;
            $key['digital_signature'] = $data->digital_signature;
        }

        if($input->isEmpty()){
            return response()->json(['success' => false,'message'=>'no data found'], 200);
        }
        else{
            return response()->json(['success' => true,'data'=>$input], 200);
        }
    }

    public function scan_qr_info(Request $request)
    {
        $urll = add_url::where('id',1)->select('url')->first();
        // print_r(auth()->user()->id);
        $input = add_sample_box_detail::where('staff_id',auth()->user()->id)->where('scan_code',$request->scan_code)->select('id')->first();

        $input11 = add_sample_box_detail::where('staff_id',auth()->user()->id)->where('scan_code',$request->scan_code)->select('id')->count();
       
        // foreach ($input as $key) 
        // {
        //     $data = add_hospital::select('name','latitude','longitude','gps_address')->where('hospital_id',$key['collected_from'])->first();
        //     $key['hospital_name'] = $data->name;
        //     $key['hospital_latitude'] = $data->latitude;
        //     $key['hospital_longitude'] = $data->longitude;
        //     $key['hospital_gps_address'] = $data->gps_address;

        //     $staff = User::Where('id',$key['staff_id'])->select('name')->first();
        //     $key['staff_name'] = $staff->name;

        //     $lab = add_lab::Where('lab_id',$key['lab_id'])->select('district_id','name','latitude','longitude','gps_address')->first();

        //     $key['lab_name'] = $lab->name;
        //     $key['lab_latitude'] = $lab->latitude;
        //     $key['lab_longitude'] = $lab->longitude;
        //     $key['lab_gps_address'] = $lab->gps_address;

        //     $specimen = add_specimen::Where('id',$key['specimen_id'])->select('name')->first();
        //     $key['specimen_name'] = $specimen->name;

        //     $Test = add_test::Where('id',$key['test_id'])->select('name')->first();
        //     $key['test_name'] = $Test->name;

        //     $Submitlabcount = add_collected_sample::join('add_collected_report','add_collected_sample.report_id','add_collected_report.report_id')->Where('add_collected_sample.sample_selected_id',$key['id'])->count();

        //     if($Submitlabcount != 0)
        //     {
        //         $Submitlab = add_collected_sample::join('add_collected_report','add_collected_sample.report_id','add_collected_report.report_id')->Where('add_collected_sample.sample_selected_id',$key['id'])->select('add_collected_report.lt_name','add_collected_report.designation','add_collected_report.digital_signature')->first();

        //         $key['submit_lab_lt_name'] = $Submitlab->lt_name;
        //         $key['submit_lab_designation'] = $Submitlab->designation;
        //         $key['submit_lab_digital_signature'] = $Submitlab->digital_signature;
        //     }
        //     else{
        //         $key['submit_lab_lt_name'] = 'N/A';
        //         $key['submit_lab_designation'] = 'N/A';
        //         $key['submit_lab_digital_signature'] = 'N/A';
        //     }

		// 	$collectlabcount = add_collect_submitted_sample::join('add_collect_submitted_report','add_collect_submitted_sample.report_id','add_collect_submitted_report.report_id')->Where('add_collect_submitted_sample.sample_selected_id',$key['id'])->count();

        //     if($collectlabcount != 0)
        //     {
        //         $collectlab = add_collect_submitted_sample::join('add_collect_submitted_report','add_collect_submitted_sample.report_id','add_collect_submitted_report.report_id')->Where('add_collect_submitted_sample.sample_selected_id',$key['id'])->select('add_collect_submitted_report.lt_name','add_collect_submitted_report.designation','add_collect_submitted_report.digital_signature')->first();

        //         $key['collect_lab_lt_name'] = $collectlab->lt_name;
        //         $key['collect_lab_designation'] = $collectlab->designation;
        //         $key['collect_lab_digital_signature'] = $collectlab->digital_signature;
        //     }
        //     else{
        //         $key['collect_lab_lt_name'] = 'N/A';
        //         $key['collect_lab_designation'] = 'N/A';
        //         $key['collect_lab_digital_signature'] = 'N/A';
        //     }

		// 	$Submithoscount = add_submitted_hospital_sample::join('add_submitted_hospital_report','add_submitted_hospital_sample.report_id','add_submitted_hospital_report.report_id')->Where('add_submitted_hospital_sample.sample_selected_id',$key['id'])->count();

        //     if($Submithoscount != 0)
        //     {
        //         $Submithos = add_submitted_hospital_sample::join('add_submitted_hospital_report','add_submitted_hospital_sample.report_id','add_submitted_hospital_report.report_id')->Where('add_submitted_hospital_sample.sample_selected_id',$key['id'])->select('add_submitted_hospital_report.lt_name','add_submitted_hospital_report.designation','add_submitted_hospital_report.digital_signature')->first();

        //         $key['submit_hospital_lt_name'] = $Submithos->lt_name;
        //         $key['submit_hospital_designation'] = $Submithos->designation;
        //         $key['submit_hospital_digital_signature'] = $Submithos->digital_signature;
        //     }
        //     else{
        //         $key['submit_hospital_lt_name'] = 'N/A';
        //         $key['submit_hospital_designation'] = 'N/A';
        //         $key['submit_hospital_digital_signature'] = 'N/A';
        //     }
        // }

        if($input11 == 0){
            return response()->json(['success' => false,'message'=>'Invalid QR Code'], 200);
        }
        else{
            $key = $urll->url.'panel/Invoice/sample-info/'.$input->id;
            return response()->json(['success' => true,'data'=>$key], 200);
        }
    }
}
