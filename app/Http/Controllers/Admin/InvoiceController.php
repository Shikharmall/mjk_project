<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\add_district;
use App\Models\add_sample_invoice;
use App\Models\add_kilometer_invoice;
use App\Models\add_invoice_kilometer_sample;
use App\Models\add_invoice_sample;
use App\Models\add_sample_box_detail;
use App\Models\add_sample_collected_details;
use App\Models\distance_status;
use DB;

class InvoiceController extends Controller
{
    public function invoice()
    {
        return view('Admin.generate-invoice');
    }
    
    // public function generate_sample_invoice($district_id,$staff_id)
    // {
    //     return view('Admin.generate-sample-invoice',compact('district_id','staff_id'));
    // }

    // public function generate_kilometer_invoice($district_id,$staff_id)
    // {
    //     return view('Admin.generate-kilometer-invoice',compact('district_id','staff_id'));
    // }

    public function sample_invoice($district_id,$rate, Request $request)
    {
        if(session()->has('order_filter'))
        {
            $request = json_decode(session('order_filter'));
        }

        $sample = DB::table('add_sample_box_detail')->Where('district_id',$district_id)->Where('status',3)->Where('sample_invoice_status',0)
        ->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
            return $query->whereBetween('date', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);
        })
        ->orderBy('id','DESC')->get();

        $from_date =isset($request->from_date)?$request->from_date:null;
        $to_date =isset($request->to_date)?$request->to_date:null;

        return view('Admin.sample-invoice', compact('sample','district_id','from_date', 'to_date','rate'));
    }

    
    public function filter(Request $request)
    {
        $request->validate([
            'from_date' => 'required_if:to_date,true',
            'to_date' => 'required_if:from_date,true',
        ]);
        session()->put('order_filter', json_encode($request->all()));

        if(add_sample_invoice::where('from_date',$request->from_date)->where('to_date',$request->to_date)->where('district_id',$request->district_id)->exists())
        {
            Toastr::warning('Invoice Already Created.');
            return back();
        }
        else
        {
            $samplecount = DB::table('add_sample_box_detail')->Where('district_id',$request->district_id)->Where('status',3)->Where('sample_invoice_status',0)
            ->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                return $query->whereBetween('date', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);
            })->count();
    
            $amounnt = $samplecount * $request->rate;
    
            $data = $request->all();
            $ins=array(
                'district_id'=>$data['district_id'] ? $data['district_id'] : '0',
                'rate'=>$data['rate'] ? $data['rate'] : '0',
                'from_date'=>$data['from_date'] ? $data['from_date'] : 'N/A',
                'to_date'=>$data['to_date'] ? $data['to_date'] : 'N/A',
                'total_sample'=>$samplecount,
                'amount'=>$amounnt,
                'created'=>date('Y-m-d'),
                'created_time'=>date('H:i:s'),
            );
    
            $add_sample_invoice = add_sample_invoice::create($ins);
            $id = $add_sample_invoice->id;
    
            $sample = DB::table('add_sample_box_detail')->Where('district_id',$request->district_id)->Where('status',3)->Where('sample_invoice_status',0)
            ->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                return $query->whereBetween('date', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);
            })
            ->orderBy('id','DESC')->get();
            foreach($sample as $sample)
            {
                $data21 = new add_invoice_sample();
                $data21->invoice_id = $id;
                $data21->sample_id = $sample->id;
                $data21->created = date('Y-m-d');
                $data21->save();
    
                $ins2=array(
                    'sample_invoice_status'=>1,
                );
                $edit = add_sample_box_detail::where('id',$sample->id)->update($ins2);
            }
            Toastr::success('Success! Invoice Created Successfully.');
        }
        return back();
    }

    public function reset(Request $request)
    {
        session()->forget('order_filter');
        return back();
    }

    public function kilometer_invoice($district_id,$rate, Request $request)
    {
        if(session()->has('korder_filter'))
        {
            $request = json_decode(session('korder_filter'));
        }

        $User = DB::table('add_sample_collected_details')->join('add_sample_box_detail','add_sample_collected_details.id','add_sample_box_detail.sample_auto_id')->Where('add_sample_box_detail.district_id',$district_id)->Where('add_sample_box_detail.status',3)->Where('add_sample_collected_details.kilometer_invoice_status',0)->where('add_sample_collected_details.kilometer','!=','0')
        ->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
            return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);
        })->select('add_sample_collected_details.id')->GroupBy('add_sample_collected_details.id')->orderBy('add_sample_collected_details.id','DESC')->get();

        $from_date =isset($request->from_date)?$request->from_date:null;
        $to_date =isset($request->to_date)?$request->to_date:null;

        return view('Admin.kilometer-invoice', compact('User','district_id','from_date', 'to_date','rate'));
    }

    public function kfilter(Request $request)
    {
        $request->validate([
            'from_date' => 'required_if:to_date,true',
            'to_date' => 'required_if:from_date,true',
        ]);
        session()->put('korder_filter', json_encode($request->all()));

        if(add_kilometer_invoice::where('from_date',$request->from_date)->where('to_date',$request->to_date)->where('district_id',$request->district_id)->exists())
        {
            Toastr::warning('Invoice Already Created.');
            return back();
        }
        else
        {

            $purchaseee3 = 0;
            $samplecount = DB::table('add_sample_collected_details')->join('add_sample_box_detail','add_sample_collected_details.id','add_sample_box_detail.sample_auto_id')->Where('add_sample_box_detail.district_id',$request->district_id)->Where('add_sample_box_detail.status',3)->Where('add_sample_collected_details.kilometer_invoice_status',0)->where('add_sample_collected_details.kilometer','!=','0')
            ->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);
            })->select('add_sample_collected_details.id')->GroupBy('add_sample_collected_details.id')->orderBy('add_sample_collected_details.id','DESC')->get();
            foreach($samplecount as $samplecount)
            {
                $sample6 = DB::table('add_sample_collected_details')->Where('id',$samplecount->id)->select('kilometer','to_kilometer','collect_lab_kilometer','submit_hospital_kilometer')->first();

                $val15 = $sample6->kilometer;
                $val25 = $sample6->to_kilometer;
                $val35 = $sample6->collect_lab_kilometer;
                $val45 = $sample6->submit_hospital_kilometer;

                $val = $val15 + $val25 + $val35 + $val45;
                $purchaseee3 = $purchaseee3 + $val;
            }

            $amounnt = $purchaseee3 * $request->rate;

            $data = $request->all();
            $ins=array(
                'district_id'=>$data['district_id'] ? $data['district_id'] : '0',
                'rate'=>$data['rate'] ? $data['rate'] : '0',
                'from_date'=>$data['from_date'] ? $data['from_date'] : 'N/A',
                'to_date'=>$data['to_date'] ? $data['to_date'] : 'N/A',
                'total_kilometer'=>$purchaseee3,
                'amount'=>$amounnt,
                'created'=>date('Y-m-d'),
                'created_time'=>date('H:i:s'),
            );

            $add_kilometer_invoice = add_kilometer_invoice::create($ins);
            $id = $add_kilometer_invoice->id;

            $sample = DB::table('add_sample_collected_details')->join('add_sample_box_detail','add_sample_collected_details.id','add_sample_box_detail.sample_auto_id')->Where('add_sample_box_detail.district_id',$request->district_id)->Where('add_sample_box_detail.status',3)->Where('add_sample_collected_details.kilometer_invoice_status',0)->where('add_sample_collected_details.kilometer','!=','0')
            ->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);
            })->select('add_sample_collected_details.id')->GroupBy('add_sample_collected_details.id')->orderBy('add_sample_collected_details.id','DESC')->get();
            foreach($sample as $sample)
            {
                $data21 = new add_invoice_kilometer_sample();
                $data21->invoice_id = $id;
                $data21->sample_id = $sample->id;
                $data21->created = date('Y-m-d');
                $data21->save();

                $ins2=array(
                    'kilometer_invoice_status'=>1,
                );
                $edit = add_sample_collected_details::where('id',$sample->id)->update($ins2);
            }

            Toastr::success('Success! Invoice Created Successfully.');
        }
        return back();
    }

    public function kreset(Request $request)
    {
        session()->forget('korder_filter');
        return back();
    }

    
    public function generate_sample_invoice(Request $request)
    {
        // $User = DB::table('add_sample_collected_details')->join('add_sample_box_detail','add_sample_collected_details.sample_id','add_sample_box_detail.sample_id')->select('add_sample_collected_details.sample_id')->Where('add_sample_box_detail.district_id',$district_id)->Where('add_sample_collected_details.staff_id',$staff_id)->GroupBy('add_sample_collected_details.sample_id')->get();

        $district_id = session()->get('sample_district_id');
        $staff_id = session()->get('sample_staff_id');

        $data = DB::Table('add_sample_box_sample')->join('add_sample_box_detail','add_sample_box_sample.sample_selected_id','add_sample_box_detail.id')->count();

        $data2 = DB::Table('add_collected_sample')->join('add_sample_box_detail','add_collected_sample.sample_selected_id','add_sample_box_detail.id')->count();

        $data3 = DB::Table('add_collect_submitted_sample')->join('add_sample_box_detail','add_collect_submitted_sample.sample_selected_id','add_sample_box_detail.id')->count();

        $data4 = DB::Table('add_submitted_hospital_sample')->join('add_sample_box_detail','add_submitted_hospital_sample.sample_selected_id','add_sample_box_detail.id')->count();

        $status = '';
        if(session()->has('sample_collected_status'))
        {
            $status = '0';
        }
        else if(session()->has('sample_submitted_status'))
        {
            $status = '1';
        }
        else if(session()->has('sample_collection_status'))
        {
            $status = '2';
        }
        else if(session()->has('sample_submittion_status'))
        {
            $status = '3';
        }
        
        if($status == '')
        {
            if(session()->has('sample_order_filter'))
            {
                $request = json_decode(session('sample_order_filter'));

                if($district_id != '' && $staff_id != '')
                {
                    if($district_id == '0' && $staff_id == '0')
                    {
                        $User = DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                            return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                            ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if($district_id != '0' && $staff_id == '0')
                    {
                        $User = DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                            return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                            ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if($district_id == '0' && $staff_id != '0')
                    {
                        $User = DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                            return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                            ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else{
                        $User = DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                            return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                            ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    
                }
                else if($district_id != '')
                {
                    if($district_id == '0')
                    {
                        $User = DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                            return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                            ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else
                    {
                        $User = DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                            return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                            ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                }
                else if($staff_id != '')
                {
                    if($staff_id == '0')
                    {
                        $User = DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                            return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                            ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else
                    {
                        $User = DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                            return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                            ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                }
                else
                {
                    $User = DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                        return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                        ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                }
            }
            else
            {
                if($district_id != '' && $staff_id != '')
                {
                    if($district_id == '0' && $staff_id == '0')
                    {
                        $User = DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if($district_id != '0' && $staff_id == '0')
                    {
                        $User = DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if($district_id == '0' && $staff_id != '0')
                    {
                        $User = DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else
                    {
                        $User = DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    
                }
                else if($district_id != '')
                {
                    if($district_id == '0')
                    {
                        $User = DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else
                    {
                        $User = DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                }
                else if($staff_id != '')
                {
                    if($staff_id == '0')
                    {
                        $User = DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else
                    {
                        $User = DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                }
                else
                {
                    $User = DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                }
            }
        }
        else
        {
            if(session()->has('sample_order_filter'))
            {
                $request = json_decode(session('sample_order_filter'));

                if($district_id != '' && $staff_id != '')
                {
                    if($district_id == '0' && $staff_id == '0')
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.status',$status)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                    else if($district_id != '0' && $staff_id == '0')
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                    return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                    ->select('add_sample_collected_details.id')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                    else if($district_id == '0' && $staff_id != '0')
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                    return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                    ->select('add_sample_collected_details.id')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                    else
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                    return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                    ->select('add_sample_collected_details.id')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                    
                }
                else if($district_id != '')
                {
                    if($district_id == '0')
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                    return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                    ->select('add_sample_collected_details.id')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                    else
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                    return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                    ->select('add_sample_collected_details.id')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                }
                else if($staff_id != '')
                {
                    if($staff_id == '0')
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                    return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                    ->select('add_sample_collected_details.id')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                    else
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                    return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                    ->select('add_sample_collected_details.id')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                }
                else
                {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                    return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                    ->select('add_sample_collected_details.id')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                }
            }
            else
            {
                if($district_id != '' && $staff_id != '')
                {
                    if($district_id == '0' && $staff_id == '0')
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->select('add_sample_collected_details.id')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->select('add_sample_collected_details.id')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {   
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->select('add_sample_collected_details.id')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->select('add_sample_collected_details.id')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                    else if($district_id != '0' && $staff_id == '0')
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->select('add_sample_collected_details.id')->Where('add_sample_box_detail.district_id',$district_id)->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->select('add_sample_collected_details.id')->Where('add_sample_box_detail.district_id',$district_id)->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {   
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->select('add_sample_collected_details.id')->Where('add_sample_box_detail.district_id',$district_id)->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->select('add_sample_collected_details.id')->Where('add_sample_box_detail.district_id',$district_id)->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                    else if($district_id == '0' && $staff_id != '0')
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->select('add_sample_collected_details.id')->Where('add_sample_box_detail.staff_id',$staff_id)->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->select('add_sample_collected_details.id')->Where('add_sample_box_detail.staff_id',$staff_id)->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {   
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->select('add_sample_collected_details.id')->Where('add_sample_box_detail.staff_id',$staff_id)->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->select('add_sample_collected_details.id')->Where('add_sample_box_detail.staff_id',$staff_id)->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                    else
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->select('add_sample_collected_details.id')->Where('add_sample_box_detail.district_id',$district_id)->Where('add_sample_box_detail.staff_id',$staff_id)->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->select('add_sample_collected_details.id')->Where('add_sample_box_detail.district_id',$district_id)->Where('add_sample_box_detail.staff_id',$staff_id)->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {   
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->select('add_sample_collected_details.id')->Where('add_sample_box_detail.district_id',$district_id)->Where('add_sample_box_detail.staff_id',$staff_id)->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->select('add_sample_collected_details.id')->Where('add_sample_box_detail.district_id',$district_id)->Where('add_sample_box_detail.staff_id',$staff_id)->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                    
                }
                else if($district_id != '')
                {
                    if($district_id == '0')
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->select('add_sample_collected_details.id')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->select('add_sample_collected_details.id')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {   
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->select('add_sample_collected_details.id')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->select('add_sample_collected_details.id')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                    else
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->select('add_sample_collected_details.id')->Where('add_sample_box_detail.district_id',$district_id)->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->select('add_sample_collected_details.id')->Where('add_sample_box_detail.district_id',$district_id)->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {   
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->select('add_sample_collected_details.id')->Where('add_sample_box_detail.district_id',$district_id)->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->select('add_sample_collected_details.id')->Where('add_sample_box_detail.district_id',$district_id)->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                }
                else if($staff_id != '')
                {
                    if($staff_id == '0')
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->select('add_sample_collected_details.id')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->select('add_sample_collected_details.id')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {   
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->select('add_sample_collected_details.id')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->select('add_sample_collected_details.id')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                    else
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->select('add_sample_collected_details.id')->Where('add_sample_box_detail.staff_id',$staff_id)->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->select('add_sample_collected_details.id')->Where('add_sample_box_detail.staff_id',$staff_id)->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {   
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->select('add_sample_collected_details.id')->Where('add_sample_box_detail.staff_id',$staff_id)->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->select('add_sample_collected_details.id')->Where('add_sample_box_detail.staff_id',$staff_id)->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                }
                else
                {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->select('add_sample_collected_details.id')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->select('add_sample_collected_details.id')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {   
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->select('add_sample_collected_details.id')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->select('add_sample_collected_details.id')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                }
            }
        }

        $from_date =isset($request->from_date)?$request->from_date:null;
        $to_date =isset($request->to_date)?$request->to_date:null;

        return view('Admin.generate-sample-invoice', compact('User','data','data2','data3','data4','from_date','to_date'));
    }

    public function sample_collected(Request $request)
    {
        session()->pull('sample_submitted_status');
        session()->pull('sample_collection_status');
        session()->pull('sample_submittion_status');
        $status = 0;
        session()->put('sample_collected_status',$status);

        $district_id = session()->get('sample_district_id');
        $staff_id = session()->get('sample_staff_id');

        $data = DB::Table('add_sample_box_sample')->join('add_sample_box_detail','add_sample_box_sample.sample_selected_id','add_sample_box_detail.id')->count();

        $data2 = DB::Table('add_collected_sample')->join('add_sample_box_detail','add_collected_sample.sample_selected_id','add_sample_box_detail.id')->count();

        $data3 = DB::Table('add_collect_submitted_sample')->join('add_sample_box_detail','add_collect_submitted_sample.sample_selected_id','add_sample_box_detail.id')->count();

        $data4 = DB::Table('add_submitted_hospital_sample')->join('add_sample_box_detail','add_submitted_hospital_sample.sample_selected_id','add_sample_box_detail.id')->count();

            if(session()->has('sample_order_filter'))
            {
                $request = json_decode(session('sample_order_filter'));

                if($district_id != '' && $staff_id != '')
                {
                    if($district_id == '0' && $staff_id == '0')
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.status',$status)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                    else if($district_id != '0' && $staff_id == '0')
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                    return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                    ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                    else if($district_id == '0' && $staff_id != '0')
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                    return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                    ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                    else
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                    return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                    ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                    
                }
                else if($district_id != '')
                {
                    if($district_id == '0')
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                    return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                    ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                    else
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                    return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                    ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                }
                else if($staff_id != '')
                {
                    if($staff_id == '0')
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                    return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                    ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                    else
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                    return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                    ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                }
                else
                {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                    return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                    ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                }
            }
            else
            {
                if($district_id != '' && $staff_id != '')
                {
                    if($district_id == '0' && $staff_id == '0')
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {   
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                    else if($district_id != '0' && $staff_id == '0')
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {   
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                    else if($district_id == '0' && $staff_id != '0')
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {   
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                    else
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {   
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                    
                }
                else if($district_id != '')
                {
                    if($district_id == '0')
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {   
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                    else
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {   
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                }
                else if($staff_id != '')
                {
                    if($staff_id == '0')
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {   
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                    else
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {   
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                }
                else
                {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {   
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                }
            }

        $from_date =isset($request->from_date)?$request->from_date:null;
        $to_date =isset($request->to_date)?$request->to_date:null;

        return view('Admin.generate-sample-invoice', compact('User','data','data2','data3','data4','from_date','to_date'));
    }

    public function sample_submitted(Request $request)
    {
        session()->pull('sample_collected_status');
        session()->pull('sample_collection_status');
        session()->pull('sample_submittion_status');
        $status = 1;
        session()->put('sample_submitted_status',$status);

        $district_id = session()->get('sample_district_id');
        $staff_id = session()->get('sample_staff_id');

        $data = DB::Table('add_sample_box_sample')->join('add_sample_box_detail','add_sample_box_sample.sample_selected_id','add_sample_box_detail.id')->count();

        $data2 = DB::Table('add_collected_sample')->join('add_sample_box_detail','add_collected_sample.sample_selected_id','add_sample_box_detail.id')->count();

        $data3 = DB::Table('add_collect_submitted_sample')->join('add_sample_box_detail','add_collect_submitted_sample.sample_selected_id','add_sample_box_detail.id')->count();

        $data4 = DB::Table('add_submitted_hospital_sample')->join('add_sample_box_detail','add_submitted_hospital_sample.sample_selected_id','add_sample_box_detail.id')->count();

        if(session()->has('sample_order_filter'))
            {
                $request = json_decode(session('sample_order_filter'));

                if($district_id != '' && $staff_id != '')
                {
                    if($district_id == '0' && $staff_id == '0')
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.status',$status)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                    else if($district_id != '0' && $staff_id == '0')
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                    return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                    ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                    else if($district_id == '0' && $staff_id != '0')
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                    return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                    ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                    else
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                    return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                    ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                    
                }
                else if($district_id != '')
                {
                    if($district_id == '0')
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                    return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                    ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                    else
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                    return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                    ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                }
                else if($staff_id != '')
                {
                    if($staff_id == '0')
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                    return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                    ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                    else
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                    return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                    ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                }
                else
                {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                    return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                    ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                }
            }
            else
            {
                if($district_id != '' && $staff_id != '')
                {
                    if($district_id == '0' && $staff_id == '0')
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {   
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->select('add_sample_collected_details.id')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                    else if($district_id != '0' && $staff_id == '0')
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {   
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                    else if($district_id == '0' && $staff_id != '0')
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {   
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                    else
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {   
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                    
                }
                else if($district_id != '')
                {
                    if($district_id == '0')
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {   
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                    else
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {   
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                }
                else if($staff_id != '')
                {
                    if($staff_id == '0')
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {   
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                    else
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {   
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                }
                else
                {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {   
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                }
            }

        $from_date =isset($request->from_date)?$request->from_date:null;
        $to_date =isset($request->to_date)?$request->to_date:null;

        return view('Admin.generate-sample-invoice', compact('User','data','data2','data3','data4','from_date','to_date'));
    }

    public function sample_collection(Request $request)
    {
        session()->pull('sample_collected_status');
        session()->pull('sample_submitted_status');
        session()->pull('sample_submittion_status');
        $status = 2;
        session()->put('sample_collection_status',$status);

        $district_id = session()->get('sample_district_id');
        $staff_id = session()->get('sample_staff_id');

        $data = DB::Table('add_sample_box_sample')->join('add_sample_box_detail','add_sample_box_sample.sample_selected_id','add_sample_box_detail.id')->count();

        $data2 = DB::Table('add_collected_sample')->join('add_sample_box_detail','add_collected_sample.sample_selected_id','add_sample_box_detail.id')->count();

        $data3 = DB::Table('add_collect_submitted_sample')->join('add_sample_box_detail','add_collect_submitted_sample.sample_selected_id','add_sample_box_detail.id')->count();

        $data4 = DB::Table('add_submitted_hospital_sample')->join('add_sample_box_detail','add_submitted_hospital_sample.sample_selected_id','add_sample_box_detail.id')->count();

        if(session()->has('sample_order_filter'))
        {
            $request = json_decode(session('sample_order_filter'));

            if($district_id != '' && $staff_id != '')
            {
                if($district_id == '0' && $staff_id == '0')
                {
                    if(session()->has('sample_collected_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.status',$status)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                            return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                            ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_submitted_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                            return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                            ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_collection_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                            return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                            ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_submittion_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                            return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                            ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                }
                else if($district_id != '0' && $staff_id == '0')
                {
                    if(session()->has('sample_collected_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                            return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                            ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_submitted_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                            return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                            ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_collection_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                            return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                            ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_submittion_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                }
                else if($district_id == '0' && $staff_id != '0')
                {
                    if(session()->has('sample_collected_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                            return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                            ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_submitted_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                            return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                            ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_collection_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                            return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                            ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_submittion_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                }
                else
                {
                    if(session()->has('sample_collected_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                            return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                            ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_submitted_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                            return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                            ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_collection_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                            return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                            ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_submittion_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                }
                
            }
            else if($district_id != '')
            {
                if($district_id == '0')
                {
                    if(session()->has('sample_collected_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                            return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                            ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_submitted_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                            return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                            ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_collection_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                            return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                            ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_submittion_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                }
                else
                {
                    if(session()->has('sample_collected_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                            return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                            ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_submitted_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                            return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                            ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_collection_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                            return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                            ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_submittion_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                }
            }
            else if($staff_id != '')
            {
                if($staff_id == '0')
                {
                    if(session()->has('sample_collected_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                            return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                            ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_submitted_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                            return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                            ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_collection_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                            return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                            ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_submittion_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                }
                else
                {
                    if(session()->has('sample_collected_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                            return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                            ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_submitted_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                            return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                            ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_collection_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                            return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                            ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_submittion_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                }
            }
            else
            {
                    if(session()->has('sample_collected_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                            return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                            ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_submitted_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                            return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                            ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_collection_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                            return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                            ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_submittion_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
            }
        }
        else
        {
            if($district_id != '' && $staff_id != '')
            {
                if($district_id == '0' && $staff_id == '0')
                {
                    if(session()->has('sample_collected_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_submitted_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_collection_status'))
                    {   
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_submittion_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->select('add_sample_collected_details.id')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                }
                else if($district_id != '0' && $staff_id == '0')
                {
                    if(session()->has('sample_collected_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_submitted_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_collection_status'))
                    {   
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_submittion_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                }
                else if($district_id == '0' && $staff_id != '0')
                {
                    if(session()->has('sample_collected_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_submitted_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_collection_status'))
                    {   
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_submittion_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                }
                else
                {
                    if(session()->has('sample_collected_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_submitted_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_collection_status'))
                    {   
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_submittion_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                }
                
            }
            else if($district_id != '')
            {
                if($district_id == '0')
                {
                    if(session()->has('sample_collected_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_submitted_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_collection_status'))
                    {   
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_submittion_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                }
                else
                {
                    if(session()->has('sample_collected_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_submitted_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_collection_status'))
                    {   
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_submittion_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                }
            }
            else if($staff_id != '')
            {
                if($staff_id == '0')
                {
                    if(session()->has('sample_collected_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_submitted_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_collection_status'))
                    {   
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_submittion_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                }
                else
                {
                    if(session()->has('sample_collected_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_submitted_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_collection_status'))
                    {   
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_submittion_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                }
            }
            else
            {
                    if(session()->has('sample_collected_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_submitted_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_collection_status'))
                    {   
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_submittion_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
            }
        }

        $from_date =isset($request->from_date)?$request->from_date:null;
        $to_date =isset($request->to_date)?$request->to_date:null;

        return view('Admin.generate-sample-invoice', compact('User','data','data2','data3','data4','from_date','to_date'));
    }

    public function sample_submittion(Request $request)
    {
        session()->pull('sample_collected_status');
        session()->pull('sample_submitted_status');
        session()->pull('sample_collection_status');

        $status = 3;
        session()->put('sample_submittion_status',$status);

        $district_id = session()->get('sample_district_id');
        $staff_id = session()->get('sample_staff_id');

        $data = DB::Table('add_sample_box_sample')->join('add_sample_box_detail','add_sample_box_sample.sample_selected_id','add_sample_box_detail.id')->count();

        $data2 = DB::Table('add_collected_sample')->join('add_sample_box_detail','add_collected_sample.sample_selected_id','add_sample_box_detail.id')->count();

        $data3 = DB::Table('add_collect_submitted_sample')->join('add_sample_box_detail','add_collect_submitted_sample.sample_selected_id','add_sample_box_detail.id')->count();

        $data4 = DB::Table('add_submitted_hospital_sample')->join('add_sample_box_detail','add_submitted_hospital_sample.sample_selected_id','add_sample_box_detail.id')->count();

        if(session()->has('sample_order_filter'))
        {
            $request = json_decode(session('sample_order_filter'));

            if($district_id != '' && $staff_id != '')
            {
                if($district_id == '0' && $staff_id == '0')
                {
                    if(session()->has('sample_collected_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.status',$status)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                            return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                            ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_submitted_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                            return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                            ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_collection_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                            return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                            ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_submittion_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                            return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                            ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                }
                else if($district_id != '0' && $staff_id == '0')
                {
                    if(session()->has('sample_collected_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                            return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                            ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_submitted_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                            return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                            ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_collection_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                            return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                            ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_submittion_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                }
                else if($district_id == '0' && $staff_id != '0')
                {
                    if(session()->has('sample_collected_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                            return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                            ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_submitted_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                            return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                            ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_collection_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                            return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                            ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_submittion_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                }
                else
                {
                    if(session()->has('sample_collected_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                            return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                            ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_submitted_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                            return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                            ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_collection_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                            return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                            ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_submittion_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                }
                
            }
            else if($district_id != '')
            {
                if($district_id == '0')
                {
                    if(session()->has('sample_collected_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                            return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                            ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_submitted_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                            return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                            ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_collection_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                            return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                            ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_submittion_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                }
                else
                {
                    if(session()->has('sample_collected_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                            return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                            ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_submitted_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                            return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                            ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_collection_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                            return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                            ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_submittion_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                }
            }
            else if($staff_id != '')
            {
                if($staff_id == '0')
                {
                    if(session()->has('sample_collected_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                            return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                            ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_submitted_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                            return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                            ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_collection_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                            return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                            ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_submittion_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                }
                else
                {
                    if(session()->has('sample_collected_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                            return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                            ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_submitted_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                            return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                            ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_collection_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                            return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                            ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_submittion_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                }
            }
            else
            {
                    if(session()->has('sample_collected_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                            return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                            ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_submitted_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                            return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                            ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_collection_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                            return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                            ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_submittion_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
            }
        }
        else
        {
            if($district_id != '' && $staff_id != '')
            {
                if($district_id == '0' && $staff_id == '0')
                {
                    if(session()->has('sample_collected_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_submitted_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_collection_status'))
                    {   
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_submittion_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->select('add_sample_collected_details.id')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                }
                else if($district_id != '0' && $staff_id == '0')
                {
                    if(session()->has('sample_collected_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_submitted_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_collection_status'))
                    {   
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_submittion_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                }
                else if($district_id == '0' && $staff_id != '0')
                {
                    if(session()->has('sample_collected_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_submitted_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_collection_status'))
                    {   
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_submittion_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                }
                else
                {
                    if(session()->has('sample_collected_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_submitted_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_collection_status'))
                    {   
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_submittion_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                }
                
            }
            else if($district_id != '')
            {
                if($district_id == '0')
                {
                    if(session()->has('sample_collected_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_submitted_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_collection_status'))
                    {   
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_submittion_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                }
                else
                {
                    if(session()->has('sample_collected_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_submitted_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_collection_status'))
                    {   
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_submittion_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                }
            }
            else if($staff_id != '')
            {
                if($staff_id == '0')
                {
                    if(session()->has('sample_collected_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_submitted_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_collection_status'))
                    {   
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_submittion_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                }
                else
                {
                    if(session()->has('sample_collected_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_submitted_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_collection_status'))
                    {   
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_submittion_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                }
            }
            else
            {
                    if(session()->has('sample_collected_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_submitted_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_collection_status'))
                    {   
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if(session()->has('sample_submittion_status'))
                    {
                        $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
            }
        }

        $from_date =isset($request->from_date)?$request->from_date:null;
        $to_date =isset($request->to_date)?$request->to_date:null;

        return view('Admin.generate-sample-invoice', compact('User','data','data2','data3','data4','from_date','to_date'));
    }

    public function sample_filter(Request $request)
    {
        $request->validate([
            'from_date' => 'required_if:to_date,true',
            'to_date' => 'required_if:from_date,true',
        ]);
        session()->put('sample_order_filter', json_encode($request->all()));
        // return back();

        $district_id = session()->get('sample_district_id');
        $staff_id = session()->get('sample_staff_id');

        $data = DB::Table('add_sample_box_sample')->join('add_sample_box_detail','add_sample_box_sample.sample_selected_id','add_sample_box_detail.id')->count();

        $data2 = DB::Table('add_collected_sample')->join('add_sample_box_detail','add_collected_sample.sample_selected_id','add_sample_box_detail.id')->count();

        $data3 = DB::Table('add_collect_submitted_sample')->join('add_sample_box_detail','add_collect_submitted_sample.sample_selected_id','add_sample_box_detail.id')->count();

        $data4 = DB::Table('add_submitted_hospital_sample')->join('add_sample_box_detail','add_submitted_hospital_sample.sample_selected_id','add_sample_box_detail.id')->count();

        $status = '';
        if(session()->has('sample_collected_status'))
        {
            $status = '0';
        }
        else if(session()->has('sample_submitted_status'))
        {
            $status = '1';
        }
        else if(session()->has('sample_collection_status'))
        {
            $status = '2';
        }
        else if(session()->has('sample_submittion_status'))
        {
            $status = '3';
        }
        
        if($status == '')
        {
            if(session()->has('sample_order_filter'))
            {
                $request = json_decode(session('sample_order_filter'));

                if($district_id != '' && $staff_id != '')
                {
                    if($district_id == '0' && $staff_id == '0')
                    {
                        $User = DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                            return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                            ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if($district_id != '0' && $staff_id == '0')
                    {
                        $User = DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                            return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                            ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if($district_id == '0' && $staff_id != '0')
                    {
                        $User = DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                            return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                            ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else{
                        $User = DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                            return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                            ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    
                }
                else if($district_id != '')
                {
                    if($district_id == '0')
                    {
                        $User = DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                            return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                            ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else
                    {
                        $User = DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                            return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                            ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                }
                else if($staff_id != '')
                {
                    if($staff_id == '0')
                    {
                        $User = DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                            return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                            ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else
                    {
                        $User = DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                            return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                            ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                }
                else
                {
                    $User = DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                        return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                        ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                }
            }
            else
            {
                if($district_id != '' && $staff_id != '')
                {
                    if($district_id == '0' && $staff_id == '0')
                    {
                        $User = DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if($district_id != '0' && $staff_id == '0')
                    {
                        $User = DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if($district_id == '0' && $staff_id != '0')
                    {
                        $User = DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else
                    {
                        $User = DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    
                }
                else if($district_id != '')
                {
                    if($district_id == '0')
                    {
                        $User = DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else
                    {
                        $User = DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                }
                else if($staff_id != '')
                {
                    if($staff_id == '0')
                    {
                        $User = DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else
                    {
                        $User = DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                }
                else
                {
                    $User = DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                }
            }
        }
        else
        {
            if(session()->has('sample_order_filter'))
            {
                $request = json_decode(session('sample_order_filter'));

                if($district_id != '' && $staff_id != '')
                {
                    if($district_id == '0' && $staff_id == '0')
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.status',$status)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                    else if($district_id != '0' && $staff_id == '0')
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                    return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                    ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                    else if($district_id == '0' && $staff_id != '0')
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                    return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                    ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                    else
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                    return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                    ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                    
                }
                else if($district_id != '')
                {
                    if($district_id == '0')
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                    return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                    ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                    else
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                    return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                    ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                }
                else if($staff_id != '')
                {
                    if($staff_id == '0')
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                    return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                    ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                    else
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                    return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                    ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                }
                else
                {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                    return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                    ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                }
            }
            else
            {
                if($district_id != '' && $staff_id != '')
                {
                    if($district_id == '0' && $staff_id == '0')
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {   
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                    else if($district_id != '0' && $staff_id == '0')
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {   
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                    else if($district_id == '0' && $staff_id != '0')
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {   
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                    else
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {   
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                    
                }
                else if($district_id != '')
                {
                    if($district_id == '0')
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {   
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                    else
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {   
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                }
                else if($staff_id != '')
                {
                    if($staff_id == '0')
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {   
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                    else
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {   
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                }
                else
                {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {   
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                }
            }
        }

        $from_date =isset($request->from_date)?$request->from_date:null;
        $to_date =isset($request->to_date)?$request->to_date:null;

        return view('Admin.generate-sample-invoice', compact('User','data','data2','data3','data4','from_date','to_date'));
    }

    public function sample_reset(Request $request)
    {
        session()->forget('sample_order_filter');
   
        $district_id = session()->get('sample_district_id');
        $staff_id = session()->get('sample_staff_id');

        $data = DB::Table('add_sample_box_sample')->join('add_sample_box_detail','add_sample_box_sample.sample_selected_id','add_sample_box_detail.id')->count();

        $data2 = DB::Table('add_collected_sample')->join('add_sample_box_detail','add_collected_sample.sample_selected_id','add_sample_box_detail.id')->count();

        $data3 = DB::Table('add_collect_submitted_sample')->join('add_sample_box_detail','add_collect_submitted_sample.sample_selected_id','add_sample_box_detail.id')->count();

        $data4 = DB::Table('add_submitted_hospital_sample')->join('add_sample_box_detail','add_submitted_hospital_sample.sample_selected_id','add_sample_box_detail.id')->count();

        $status = '';
        if(session()->has('sample_collected_status'))
        {
            $status = '0';
        }
        else if(session()->has('sample_submitted_status'))
        {
            $status = '1';
        }
        else if(session()->has('sample_collection_status'))
        {
            $status = '2';
        }
        else if(session()->has('sample_submittion_status'))
        {
            $status = '3';
        }
        
        if($status == '')
        {
            if(session()->has('sample_order_filter'))
            {
                $request = json_decode(session('sample_order_filter'));

                if($district_id != '' && $staff_id != '')
                {
                    if($district_id == '0' && $staff_id == '0')
                    {
                        $User = DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                            return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                            ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if($district_id != '0' && $staff_id == '0')
                    {
                        $User = DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                            return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                            ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if($district_id == '0' && $staff_id != '0')
                    {
                        $User = DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                            return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                            ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else{
                        $User = DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                            return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                            ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    
                }
                else if($district_id != '')
                {
                    if($district_id == '0')
                    {
                        $User = DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                            return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                            ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else
                    {
                        $User = DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                            return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                            ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                }
                else if($staff_id != '')
                {
                    if($staff_id == '0')
                    {
                        $User = DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                            return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                            ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else
                    {
                        $User = DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                            return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                            ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                }
                else
                {
                    $User = DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                        return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                        ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                }
            }
            else
            {
                if($district_id != '' && $staff_id != '')
                {
                    if($district_id == '0' && $staff_id == '0')
                    {
                        $User = DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if($district_id != '0' && $staff_id == '0')
                    {
                        $User = DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if($district_id == '0' && $staff_id != '0')
                    {
                        $User = DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else
                    {
                        $User = DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    
                }
                else if($district_id != '')
                {
                    if($district_id == '0')
                    {
                        $User = DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else
                    {
                        $User = DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                }
                else if($staff_id != '')
                {
                    if($staff_id == '0')
                    {
                        $User = DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else
                    {
                        $User = DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                }
                else
                {
                    $User = DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                }
            }
        }
        else
        {
            if(session()->has('sample_order_filter'))
            {
                $request = json_decode(session('sample_order_filter'));

                if($district_id != '' && $staff_id != '')
                {
                    if($district_id == '0' && $staff_id == '0')
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.status',$status)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                    else if($district_id != '0' && $staff_id == '0')
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                    return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                    ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                    else if($district_id == '0' && $staff_id != '0')
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                    return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                    ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                    else
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                    return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                    ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                    
                }
                else if($district_id != '')
                {
                    if($district_id == '0')
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                    return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                    ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                    else
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                    return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                    ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                }
                else if($staff_id != '')
                {
                    if($staff_id == '0')
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                    return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                    ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                    else
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                    return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                    ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                }
                else
                {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                    return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                    ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                }
            }
            else
            {
                if($district_id != '' && $staff_id != '')
                {
                    if($district_id == '0' && $staff_id == '0')
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {   
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                    else if($district_id != '0' && $staff_id == '0')
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {   
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                    else if($district_id == '0' && $staff_id != '0')
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {   
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                    else
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {   
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                    
                }
                else if($district_id != '')
                {
                    if($district_id == '0')
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {   
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                    else
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {   
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                }
                else if($staff_id != '')
                {
                    if($staff_id == '0')
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {   
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                    else
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {   
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                }
                else
                {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {   
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                }
            }
        }
        
        $from_date =isset($request->from_date)?$request->from_date:null;
        $to_date =isset($request->to_date)?$request->to_date:null;

        return view('Admin.generate-sample-invoice', compact('User','data','data2','data3','data4','from_date','to_date'));

        // return back();
    }

    public function sample_reset_all(Request $request)
    {
        session()->forget('sample_order_filter');
        session()->pull('sample_staff_id');
        session()->pull('sample_district_id');
        session()->pull('sample_collected_status');
        session()->pull('sample_submitted_status');
        session()->pull('sample_collection_status');
        session()->pull('sample_submittion_status');

        return redirect()->route('panel.Reports.sample-report');

        // return back();
    }

    public function get_sample_staff($id)
    {
        session()->put('sample_staff_id',$id);

        $district_id = session()->get('sample_district_id');
        $staff_id = session()->get('sample_staff_id');

        $data = DB::Table('add_sample_box_sample')->join('add_sample_box_detail','add_sample_box_sample.sample_selected_id','add_sample_box_detail.id')->count();

        $data2 = DB::Table('add_collected_sample')->join('add_sample_box_detail','add_collected_sample.sample_selected_id','add_sample_box_detail.id')->count();

        $data3 = DB::Table('add_collect_submitted_sample')->join('add_sample_box_detail','add_collect_submitted_sample.sample_selected_id','add_sample_box_detail.id')->count();

        $data4 = DB::Table('add_submitted_hospital_sample')->join('add_sample_box_detail','add_submitted_hospital_sample.sample_selected_id','add_sample_box_detail.id')->count();

        $status = '';
        if(session()->has('sample_collected_status'))
        {
            $status = '0';
        }
        else if(session()->has('sample_submitted_status'))
        {
            $status = '1';
        }
        else if(session()->has('sample_collection_status'))
        {
            $status = '2';
        }
        else if(session()->has('sample_submittion_status'))
        {
            $status = '3';
        }

        if($status == '')
        {
            if(session()->has('sample_order_filter'))
            {
                $request = json_decode(session('sample_order_filter'));

                if($district_id != '' && $staff_id != '')
                {
                    if($district_id == '0' && $staff_id == '0')
                    {
                        $User = DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                            return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                            ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if($district_id != '0' && $staff_id == '0')
                    {
                        $User = DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                            return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                            ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if($district_id == '0' && $staff_id != '0')
                    {
                        $User = DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                            return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                            ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else{
                        $User = DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                            return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                            ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    
                }
                else if($district_id != '')
                {
                    if($district_id == '0')
                    {
                        $User = DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                            return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                            ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else
                    {
                        $User = DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                            return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                            ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                }
                else if($staff_id != '')
                {
                    if($staff_id == '0')
                    {
                        $User = DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                            return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                            ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else
                    {
                        $User = DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                            return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                            ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                }
                else
                {
                    $User = DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                        return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                        ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                }
            }
            else
            {
                if($district_id != '' && $staff_id != '')
                {
                    if($district_id == '0' && $staff_id == '0')
                    {
                        $User = DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if($district_id != '0' && $staff_id == '0')
                    {
                        $User = DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if($district_id == '0' && $staff_id != '0')
                    {
                        $User = DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else
                    {
                        $User = DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    
                }
                else if($district_id != '')
                {
                    if($district_id == '0')
                    {
                        $User = DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else
                    {
                        $User = DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                }
                else if($staff_id != '')
                {
                    if($staff_id == '0')
                    {
                        $User = DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else
                    {
                        $User = DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                }
                else
                {
                    $User = DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                }
            }
        }
        else
        {
            if(session()->has('sample_order_filter'))
            {
                $request = json_decode(session('sample_order_filter'));

                if($district_id != '' && $staff_id != '')
                {
                    if($district_id == '0' && $staff_id == '0')
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.status',$status)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                    else if($district_id != '0' && $staff_id == '0')
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                    return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                    ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                    else if($district_id == '0' && $staff_id != '0')
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                    return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                    ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                    else
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                    return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                    ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                    
                }
                else if($district_id != '')
                {
                    if($district_id == '0')
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                    return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                    ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                    else
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                    return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                    ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                }
                else if($staff_id != '')
                {
                    if($staff_id == '0')
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                    return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                    ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                    else
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                    return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                    ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                }
                else
                {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                    return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                    ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                }
            }
            else
            {
                if($district_id != '' && $staff_id != '')
                {
                    if($district_id == '0' && $staff_id == '0')
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {   
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                    else if($district_id != '0' && $staff_id == '0')
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {   
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                    else if($district_id == '0' && $staff_id != '0')
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {   
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                    else
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {   
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                    
                }
                else if($district_id != '')
                {
                    if($district_id == '0')
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {   
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                    else
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {   
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                }
                else if($staff_id != '')
                {
                    if($staff_id == '0')
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {   
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                    else
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {   
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                }
                else
                {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {   
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                }
            }
        }
        
        $from_date =isset($request->from_date)?$request->from_date:null;
        $to_date =isset($request->to_date)?$request->to_date:null;

        return view('Admin.generate-sample-invoice',compact('User','data','data2','data3','data4','from_date','to_date'));
    }

    public function get_sample_district($id)
    {
        session()->put('sample_district_id',$id);

        $district_id = session()->get('sample_district_id');
        $staff_id = session()->get('sample_staff_id');

        $data = DB::Table('add_sample_box_sample')->join('add_sample_box_detail','add_sample_box_sample.sample_selected_id','add_sample_box_detail.id')->count();

        $data2 = DB::Table('add_collected_sample')->join('add_sample_box_detail','add_collected_sample.sample_selected_id','add_sample_box_detail.id')->count();

        $data3 = DB::Table('add_collect_submitted_sample')->join('add_sample_box_detail','add_collect_submitted_sample.sample_selected_id','add_sample_box_detail.id')->count();

        $data4 = DB::Table('add_submitted_hospital_sample')->join('add_sample_box_detail','add_submitted_hospital_sample.sample_selected_id','add_sample_box_detail.id')->count();

        $status = '';
        if(session()->has('sample_collected_status'))
        {
            $status = '0';
        }
        else if(session()->has('sample_submitted_status'))
        {
            $status = '1';
        }
        else if(session()->has('sample_collection_status'))
        {
            $status = '2';
        }
        else if(session()->has('sample_submittion_status'))
        {
            $status = '3';
        }
        
        if($status == '')
        {
            if(session()->has('sample_order_filter'))
            {
                $request = json_decode(session('sample_order_filter'));

                if($district_id != '' && $staff_id != '')
                {
                    if($district_id == '0' && $staff_id == '0')
                    {
                        $User = DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                            return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                            ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if($district_id != '0' && $staff_id == '0')
                    {
                        $User = DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                            return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                            ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if($district_id == '0' && $staff_id != '0')
                    {
                        $User = DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                            return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                            ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else{
                        $User = DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                            return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                            ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    
                }
                else if($district_id != '')
                {
                    if($district_id == '0')
                    {
                        $User = DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                            return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                            ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else
                    {
                        $User = DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                            return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                            ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                }
                else if($staff_id != '')
                {
                    if($staff_id == '0')
                    {
                        $User = DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                            return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                            ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else
                    {
                        $User = DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                            return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                            ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                }
                else
                {
                    $User = DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                        return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                        ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                }
            }
            else
            {
                if($district_id != '' && $staff_id != '')
                {
                    if($district_id == '0' && $staff_id == '0')
                    {
                        $User = DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if($district_id != '0' && $staff_id == '0')
                    {
                        $User = DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else if($district_id == '0' && $staff_id != '0')
                    {
                        $User = DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else
                    {
                        $User = DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    
                }
                else if($district_id != '')
                {
                    if($district_id == '0')
                    {
                        $User = DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else
                    {
                        $User = DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                }
                else if($staff_id != '')
                {
                    if($staff_id == '0')
                    {
                        $User = DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                    else
                    {
                        $User = DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                    }
                }
                else
                {
                    $User = DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                }
            }
        }
        else
        {
            if(session()->has('sample_order_filter'))
            {
                $request = json_decode(session('sample_order_filter'));

                if($district_id != '' && $staff_id != '')
                {
                    if($district_id == '0' && $staff_id == '0')
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.status',$status)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                    else if($district_id != '0' && $staff_id == '0')
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                    return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                    ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                    else if($district_id == '0' && $staff_id != '0')
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                    return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                    ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                    else
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                    return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                    ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                    
                }
                else if($district_id != '')
                {
                    if($district_id == '0')
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                    return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                    ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                    else
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                    return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                    ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                }
                else if($staff_id != '')
                {
                    if($staff_id == '0')
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                    return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                    ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                    else
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                    return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                    ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                }
                else
                {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                                    return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                                    ->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                }
            }
            else
            {
                if($district_id != '' && $staff_id != '')
                {
                    if($district_id == '0' && $staff_id == '0')
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {   
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                    else if($district_id != '0' && $staff_id == '0')
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {   
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                    else if($district_id == '0' && $staff_id != '0')
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {   
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                    else
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {   
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                    
                }
                else if($district_id != '')
                {
                    if($district_id == '0')
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {   
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                    else
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {   
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->Where('add_sample_box_detail.district_id',$district_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                }
                else if($staff_id != '')
                {
                    if($staff_id == '0')
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {   
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                    else
                    {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {   
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->Where('add_sample_box_detail.staff_id',$staff_id)->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                    }
                }
                else
                {
                        if(session()->has('sample_collected_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_sample_box_sample','add_sample_box_detail.id','add_sample_box_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submitted_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collected_sample','add_sample_box_detail.id','add_collected_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_collection_status'))
                        {   
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_collect_submitted_sample','add_sample_box_detail.id','add_collect_submitted_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                        else if(session()->has('sample_submittion_status'))
                        {
                            $User =  DB::table('add_sample_box_detail')->join('add_sample_collected_details','add_sample_box_detail.sample_auto_id','add_sample_collected_details.id')->join('add_submitted_hospital_sample','add_sample_box_detail.id','add_submitted_hospital_sample.sample_selected_id')->select('add_sample_collected_details.id','add_sample_box_detail.lab_id')->GroupBy('add_sample_collected_details.id','add_sample_box_detail.lab_id')->orderby('add_sample_collected_details.id','DESC')->get();
                        }
                }
            }
        }

        $from_date =isset($request->from_date)?$request->from_date:null;
        $to_date =isset($request->to_date)?$request->to_date:null;

        return view('Admin.generate-sample-invoice',compact('User','data','data2','data3','data4','from_date','to_date'));
    }

    public function generate_kilometer_invoice(Request $request)
    {
        $purchases1 = DB::table('add_sample_collected_details')->select('sample_id')->get();
		$purchase = 0;

        foreach($purchases1 as $purchases1)
        {
            $sample11 = DB::table('add_sample_collected_details')->Where('sample_id',$purchases1->sample_id)->select('kilometer','to_kilometer','collect_lab_kilometer','submit_hospital_kilometer')->first();
            if($sample11->kilometer == 'N/A')
            {
                $sample11->kilometer = 0;
            }
            if($sample11->to_kilometer == 'N/A')
            {
                $sample11->to_kilometer = 0;
            }
            if($sample11->collect_lab_kilometer == 'N/A')
            {
                $sample11->collect_lab_kilometer = 0;
            }
            if($sample11->submit_hospital_kilometer == 'N/A')
            {
                $sample11->submit_hospital_kilometer = 0;
            }
            $val11 = $sample11->kilometer;
            $val21 = $sample11->to_kilometer;
            $val31 = $sample11->collect_lab_kilometer;
            $val41 = $sample11->submit_hospital_kilometer;
            $val11 = $val11 + $val21 + $val31 + $val41;
            $purchase = $purchase + $val11;
        }
        $total = 0;$purchaseee = 0;
        $district_id = session()->get('district_id');
        $staff_id = session()->get('staff_id');
        if($district_id != '' && $staff_id != '')
        {
            $purr = DB::table('add_sample_collected_details')->join('add_sample_box_detail','add_sample_collected_details.sample_id','add_sample_box_detail.sample_id')->select('add_sample_collected_details.sample_id')->Where('add_sample_box_detail.district_id',$district_id)->Where('add_sample_box_detail.staff_id',$staff_id)->GroupBy('add_sample_collected_details.sample_id')->get();
		
            foreach($purr as $purr)
            {
                $sample = DB::table('add_sample_collected_details')->Where('sample_id',$purr->sample_id)->select('kilometer','to_kilometer','collect_lab_kilometer','submit_hospital_kilometer')->first();

                $val1 = $sample->kilometer;
                $val2 = $sample->to_kilometer;
                $val3 = $sample->collect_lab_kilometer;
                $val4 = $sample->submit_hospital_kilometer;
                $val = $val1 + $val2 + $val3 + $val4;
                $total = $total + $val;
                $purchaseee = $total;
            }
        }
        elseif($district_id != '')
        {
            $purr = DB::table('add_sample_collected_details')->join('add_sample_box_detail','add_sample_collected_details.sample_id','add_sample_box_detail.sample_id')->select('add_sample_collected_details.sample_id')->Where('add_sample_box_detail.district_id',$district_id)->GroupBy('add_sample_collected_details.sample_id')->get();
		
            foreach($purr as $purr)
            {
                $sample = DB::table('add_sample_collected_details')->Where('sample_id',$purr->sample_id)->select('kilometer','to_kilometer','collect_lab_kilometer','submit_hospital_kilometer')->first();

                $val1 = $sample->kilometer;
                $val2 = $sample->to_kilometer;
                $val3 = $sample->collect_lab_kilometer;
                $val4 = $sample->submit_hospital_kilometer;
                $val = $val1 + $val2 + $val3 + $val4;
                $total = $total + $val;
            }
        }
        
        elseif($staff_id != '')
        {
            $purchases12 = DB::table('add_sample_collected_details')->where('staff_id',$staff_id)->sum('kilometer');
										
            $purchases22 = DB::table('add_sample_collected_details')->where('staff_id',$staff_id)->sum('to_kilometer');
                                                
            $purchases32 = DB::table('add_sample_collected_details')->where('staff_id',$staff_id)->sum('collect_lab_kilometer');

            $purchases42 = DB::table('add_sample_collected_details')->where('staff_id',$staff_id)->sum('submit_hospital_kilometer');

            $purchaseee = $purchases12 + $purchases22 + $purchases32 + $purchases42;
        }

        if(session()->has('kilometer_order_filter'))
        {
            $request = json_decode(session('kilometer_order_filter'));

            if($district_id != '' && $staff_id != '')
            {
                if($district_id == '0' && $staff_id == '0')
                {
                    $User = DB::table('add_sample_collected_details')->join('add_sample_box_detail','add_sample_collected_details.id','add_sample_box_detail.sample_auto_id')->where('add_sample_collected_details.kilometer','!=','0')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                        return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                        ->select('add_sample_collected_details.id')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                }
                else if($district_id != '0' && $staff_id == '0')
                {
                    $User = DB::table('add_sample_collected_details')->join('add_sample_box_detail','add_sample_collected_details.id','add_sample_box_detail.sample_auto_id')->where('add_sample_collected_details.kilometer','!=','0')->Where('add_sample_box_detail.district_id',$district_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                        return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                        ->select('add_sample_collected_details.id')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                }
                else if($district_id == '0' && $staff_id != '0')
                {
                    $User = DB::table('add_sample_collected_details')->join('add_sample_box_detail','add_sample_collected_details.id','add_sample_box_detail.sample_auto_id')->where('add_sample_collected_details.kilometer','!=','0')->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                        return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                        ->select('add_sample_collected_details.id')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                }
                else{
                    $User = DB::table('add_sample_collected_details')->join('add_sample_box_detail','add_sample_collected_details.id','add_sample_box_detail.sample_auto_id')->where('add_sample_collected_details.kilometer','!=','0')->Where('add_sample_box_detail.district_id',$district_id)->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                        return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                        ->select('add_sample_collected_details.id')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                }
                
            }
            else if($district_id != '')
            {
                if($district_id == '0')
                {
                    $User = DB::table('add_sample_collected_details')->join('add_sample_box_detail','add_sample_collected_details.id','add_sample_box_detail.sample_auto_id')->where('add_sample_collected_details.kilometer','!=','0')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                        return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                        ->select('add_sample_collected_details.id')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                }
                else{
                    $User = DB::table('add_sample_collected_details')->join('add_sample_box_detail','add_sample_collected_details.id','add_sample_box_detail.sample_auto_id')->where('add_sample_collected_details.kilometer','!=','0')->Where('add_sample_box_detail.district_id',$district_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                        return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                        ->select('add_sample_collected_details.id')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                }
            }
            else if($staff_id != '')
            {
                if($staff_id == '0')
                {
                    $User = DB::table('add_sample_collected_details')->join('add_sample_box_detail','add_sample_collected_details.id','add_sample_box_detail.sample_auto_id')->where('add_sample_collected_details.kilometer','!=','0')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                        return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                        ->select('add_sample_collected_details.id')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                }
                else
                {
                    $User = DB::table('add_sample_collected_details')->join('add_sample_box_detail','add_sample_collected_details.id','add_sample_box_detail.sample_auto_id')->where('add_sample_collected_details.kilometer','!=','0')->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                        return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                        ->select('add_sample_collected_details.id')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                }
            }
            else
            {
                $User = DB::table('add_sample_collected_details')->join('add_sample_box_detail','add_sample_collected_details.id','add_sample_box_detail.sample_auto_id')->where('add_sample_collected_details.kilometer','!=','0')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                    return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                    ->select('add_sample_collected_details.id')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
            }
        }
        else
        {
            if($district_id != '' && $staff_id != '')
            {
                if($district_id == '0' && $staff_id == '0')
                {
                    $User = DB::table('add_sample_collected_details')->join('add_sample_box_detail','add_sample_collected_details.id','add_sample_box_detail.sample_auto_id')->select('add_sample_collected_details.id')->where('add_sample_collected_details.kilometer','!=','0')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                }
                else if($district_id != '0' && $staff_id == '0')
                {
                    $User = DB::table('add_sample_collected_details')->join('add_sample_box_detail','add_sample_collected_details.id','add_sample_box_detail.sample_auto_id')->select('add_sample_collected_details.id')->Where('add_sample_box_detail.district_id',$district_id)->where('add_sample_collected_details.kilometer','!=','0')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                }
                else if($district_id == '0' && $staff_id != '0')
                {
                    $User = DB::table('add_sample_collected_details')->join('add_sample_box_detail','add_sample_collected_details.id','add_sample_box_detail.sample_auto_id')->select('add_sample_collected_details.id')->Where('add_sample_box_detail.staff_id',$staff_id)->where('add_sample_collected_details.kilometer','!=','0')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                }
                else
                {
                    $User = DB::table('add_sample_collected_details')->join('add_sample_box_detail','add_sample_collected_details.id','add_sample_box_detail.sample_auto_id')->select('add_sample_collected_details.id')->Where('add_sample_box_detail.district_id',$district_id)->Where('add_sample_box_detail.staff_id',$staff_id)->where('add_sample_collected_details.kilometer','!=','0')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                }
                
            }
            else if($district_id != '')
            {
                if($district_id == '0')
                {
                    $User = DB::table('add_sample_collected_details')->join('add_sample_box_detail','add_sample_collected_details.id','add_sample_box_detail.sample_auto_id')->select('add_sample_collected_details.id')->where('add_sample_collected_details.kilometer','!=','0')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                }
                else
                {
                    $User = DB::table('add_sample_collected_details')->join('add_sample_box_detail','add_sample_collected_details.id','add_sample_box_detail.sample_auto_id')->select('add_sample_collected_details.id')->where('add_sample_collected_details.kilometer','!=','0')->Where('add_sample_box_detail.district_id',$district_id)->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                }
            }
            else if($staff_id != '')
            {
                if($staff_id == '0')
                {
                    $User = DB::table('add_sample_collected_details')->join('add_sample_box_detail','add_sample_collected_details.id','add_sample_box_detail.sample_auto_id')->select('add_sample_collected_details.id')->where('add_sample_collected_details.kilometer','!=','0')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                }
                else
                {
                    $User = DB::table('add_sample_collected_details')->join('add_sample_box_detail','add_sample_collected_details.id','add_sample_box_detail.sample_auto_id')->select('add_sample_collected_details.id')->Where('add_sample_box_detail.staff_id',$staff_id)->where('add_sample_collected_details.kilometer','!=','0')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                }
            }
            else
            {
                $User = DB::table('add_sample_collected_details')->join('add_sample_box_detail','add_sample_collected_details.id','add_sample_box_detail.sample_auto_id')->select('add_sample_collected_details.id')->where('add_sample_collected_details.kilometer','!=','0')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
            }
        }

        $from_date =isset($request->from_date)?$request->from_date:null;
        $to_date =isset($request->to_date)?$request->to_date:null;
											
        return view('Admin.generate-kilometer-invoice',compact('purchase','total','purchaseee','User','from_date', 'to_date'));
    }

    public function kilometer_filter(Request $request)
    {
        $request->validate([
            'from_date' => 'required_if:to_date,true',
            'to_date' => 'required_if:from_date,true',
        ]);
        session()->put('kilometer_order_filter', json_encode($request->all()));
        return back();
    }

    public function kilometer_reset(Request $request)
    {
        session()->forget('kilometer_order_filter');
        session()->pull('staff_id');
        session()->pull('district_id');

        return redirect()->route('panel.Reports.kilometer-report');
    }

    public function get_kilometer_staff($id,Request $request)
    {
        session()->put('staff_id',$id);

        $purchases1 = DB::table('add_sample_collected_details')->select('sample_id')->get();
		$purchase = 0;

        foreach($purchases1 as $purchases1)
        {
            $sample11 = DB::table('add_sample_collected_details')->Where('sample_id',$purchases1->sample_id)->select('kilometer','to_kilometer','collect_lab_kilometer','submit_hospital_kilometer')->first();

            $val11 = $sample11->kilometer;
            $val21 = $sample11->to_kilometer;
            $val31 = $sample11->collect_lab_kilometer;
            $val41 = $sample11->submit_hospital_kilometer;
            $val11 = $val11 + $val21 + $val31 + $val41;
            $purchase = $purchase + $val11;
        }
        $total = 0;$purchaseee = 0;
        $district_id = session()->get('district_id');
        $staff_id = session()->get('staff_id');
        if($district_id != '' && $staff_id != '')
        {
            $purr = DB::table('add_sample_collected_details')->join('add_sample_box_detail','add_sample_collected_details.sample_id','add_sample_box_detail.sample_id')->select('add_sample_collected_details.sample_id')->Where('add_sample_box_detail.district_id',$district_id)->Where('add_sample_box_detail.staff_id',$staff_id)->GroupBy('add_sample_collected_details.sample_id')->get();
		
            foreach($purr as $purr)
            {
                $sample = DB::table('add_sample_collected_details')->Where('sample_id',$purr->sample_id)->select('kilometer','to_kilometer','collect_lab_kilometer','submit_hospital_kilometer')->first();

                $val1 = $sample->kilometer;
                $val2 = $sample->to_kilometer;
                $val3 = $sample->collect_lab_kilometer;
                $val4 = $sample->submit_hospital_kilometer;
                $val = $val1 + $val2 + $val3 + $val4;
                $total = $total + $val;
                $purchaseee = $total;
            }
        }
        elseif($district_id != '')
        {
            $purr = DB::table('add_sample_collected_details')->join('add_sample_box_detail','add_sample_collected_details.sample_id','add_sample_box_detail.sample_id')->select('add_sample_collected_details.sample_id')->Where('add_sample_box_detail.district_id',$district_id)->GroupBy('add_sample_collected_details.sample_id')->get();
		
            foreach($purr as $purr)
            {
                $sample = DB::table('add_sample_collected_details')->Where('sample_id',$purr->sample_id)->select('kilometer','to_kilometer','collect_lab_kilometer','submit_hospital_kilometer')->first();

                $val1 = $sample->kilometer;
                $val2 = $sample->to_kilometer;
                $val3 = $sample->collect_lab_kilometer;
                $val4 = $sample->submit_hospital_kilometer;
                $val = $val1 + $val2 + $val3 + $val4;
                $total = $total + $val;
            }
        }
        
        elseif($staff_id != '')
        {
            $purchases12 = DB::table('add_sample_collected_details')->where('staff_id',$staff_id)->sum('kilometer');
										
            $purchases22 = DB::table('add_sample_collected_details')->where('staff_id',$staff_id)->sum('to_kilometer');
                                                
            $purchases32 = DB::table('add_sample_collected_details')->where('staff_id',$staff_id)->sum('collect_lab_kilometer');

            $purchases42 = DB::table('add_sample_collected_details')->where('staff_id',$staff_id)->sum('submit_hospital_kilometer');

            $purchaseee = $purchases12 + $purchases22 + $purchases32 + $purchases42;
        }

        if(session()->has('kilometer_order_filter'))
        {
            $request = json_decode(session('kilometer_order_filter'));

            if($district_id != '' && $staff_id != '')
            {
                if($district_id == '0' && $staff_id == '0')
                {
                    $User = DB::table('add_sample_collected_details')->join('add_sample_box_detail','add_sample_collected_details.id','add_sample_box_detail.sample_auto_id')->where('add_sample_collected_details.kilometer','!=','0')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                        return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                        ->select('add_sample_collected_details.id')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                }
                else if($district_id != '0' && $staff_id == '0')
                {
                    $User = DB::table('add_sample_collected_details')->join('add_sample_box_detail','add_sample_collected_details.id','add_sample_box_detail.sample_auto_id')->where('add_sample_collected_details.kilometer','!=','0')->Where('add_sample_box_detail.district_id',$district_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                        return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                        ->select('add_sample_collected_details.id')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                }
                else if($district_id == '0' && $staff_id != '0')
                {
                    $User = DB::table('add_sample_collected_details')->join('add_sample_box_detail','add_sample_collected_details.id','add_sample_box_detail.sample_auto_id')->where('add_sample_collected_details.kilometer','!=','0')->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                        return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                        ->select('add_sample_collected_details.id')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                }
                else{
                    $User = DB::table('add_sample_collected_details')->join('add_sample_box_detail','add_sample_collected_details.id','add_sample_box_detail.sample_auto_id')->where('add_sample_collected_details.kilometer','!=','0')->Where('add_sample_box_detail.district_id',$district_id)->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                        return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                        ->select('add_sample_collected_details.id')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                }
                
            }
            else if($district_id != '')
            {
                if($district_id == '0')
                {
                    $User = DB::table('add_sample_collected_details')->join('add_sample_box_detail','add_sample_collected_details.id','add_sample_box_detail.sample_auto_id')->where('add_sample_collected_details.kilometer','!=','0')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                        return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                        ->select('add_sample_collected_details.id')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                }
                else{
                    $User = DB::table('add_sample_collected_details')->join('add_sample_box_detail','add_sample_collected_details.id','add_sample_box_detail.sample_auto_id')->where('add_sample_collected_details.kilometer','!=','0')->Where('add_sample_box_detail.district_id',$district_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                        return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                        ->select('add_sample_collected_details.id')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                }
            }
            else if($staff_id != '')
            {
                if($staff_id == '0')
                {
                    $User = DB::table('add_sample_collected_details')->join('add_sample_box_detail','add_sample_collected_details.id','add_sample_box_detail.sample_auto_id')->where('add_sample_collected_details.kilometer','!=','0')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                        return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                        ->select('add_sample_collected_details.id')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                }
                else
                {
                    $User = DB::table('add_sample_collected_details')->join('add_sample_box_detail','add_sample_collected_details.id','add_sample_box_detail.sample_auto_id')->where('add_sample_collected_details.kilometer','!=','0')->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                        return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                        ->select('add_sample_collected_details.id')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                }
            }
            else
            {
                $User = DB::table('add_sample_collected_details')->join('add_sample_box_detail','add_sample_collected_details.id','add_sample_box_detail.sample_auto_id')->where('add_sample_collected_details.kilometer','!=','0')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                    return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                    ->select('add_sample_collected_details.id')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
            }
        }
        else
        {
            if($district_id != '' && $staff_id != '')
            {
                if($district_id == '0' && $staff_id == '0')
                {
                    $User = DB::table('add_sample_collected_details')->join('add_sample_box_detail','add_sample_collected_details.id','add_sample_box_detail.sample_auto_id')->select('add_sample_collected_details.id')->where('add_sample_collected_details.kilometer','!=','0')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                }
                else if($district_id != '0' && $staff_id == '0')
                {
                    $User = DB::table('add_sample_collected_details')->join('add_sample_box_detail','add_sample_collected_details.id','add_sample_box_detail.sample_auto_id')->select('add_sample_collected_details.id')->Where('add_sample_box_detail.district_id',$district_id)->where('add_sample_collected_details.kilometer','!=','0')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                }
                else if($district_id == '0' && $staff_id != '0')
                {
                    $User = DB::table('add_sample_collected_details')->join('add_sample_box_detail','add_sample_collected_details.id','add_sample_box_detail.sample_auto_id')->select('add_sample_collected_details.id')->Where('add_sample_box_detail.staff_id',$staff_id)->where('add_sample_collected_details.kilometer','!=','0')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                }
                else
                {
                    $User = DB::table('add_sample_collected_details')->join('add_sample_box_detail','add_sample_collected_details.id','add_sample_box_detail.sample_auto_id')->select('add_sample_collected_details.id')->Where('add_sample_box_detail.district_id',$district_id)->Where('add_sample_box_detail.staff_id',$staff_id)->where('add_sample_collected_details.kilometer','!=','0')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                }
                
            }
            else if($district_id != '')
            {
                if($district_id == '0')
                {
                    $User = DB::table('add_sample_collected_details')->join('add_sample_box_detail','add_sample_collected_details.id','add_sample_box_detail.sample_auto_id')->select('add_sample_collected_details.id')->where('add_sample_collected_details.kilometer','!=','0')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                }
                else
                {
                    $User = DB::table('add_sample_collected_details')->join('add_sample_box_detail','add_sample_collected_details.id','add_sample_box_detail.sample_auto_id')->select('add_sample_collected_details.id')->Where('add_sample_box_detail.district_id',$district_id)->where('add_sample_collected_details.kilometer','!=','0')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                }
            }
            else if($staff_id != '')
            {
                if($staff_id == '0')
                {
                    $User = DB::table('add_sample_collected_details')->join('add_sample_box_detail','add_sample_collected_details.id','add_sample_box_detail.sample_auto_id')->select('add_sample_collected_details.id')->where('add_sample_collected_details.kilometer','!=','0')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                }
                else
                {
                    $User = DB::table('add_sample_collected_details')->join('add_sample_box_detail','add_sample_collected_details.id','add_sample_box_detail.sample_auto_id')->select('add_sample_collected_details.id')->Where('add_sample_box_detail.staff_id',$staff_id)->where('add_sample_collected_details.kilometer','!=','0')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                }
            }
            else
            {
                $User = DB::table('add_sample_collected_details')->join('add_sample_box_detail','add_sample_collected_details.id','add_sample_box_detail.sample_auto_id')->select('add_sample_collected_details.id')->where('add_sample_collected_details.kilometer','!=','0')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
            }
        }

        $from_date =isset($request->from_date)?$request->from_date:null;
        $to_date =isset($request->to_date)?$request->to_date:null;
											
        return view('Admin.generate-kilometer-invoice',compact('purchase','total','purchaseee','User','from_date', 'to_date'));
    }

    public function get_kilometer_district($id,Request $request)
    {
        session()->put('district_id',$id);

        $purchases1 = DB::table('add_sample_collected_details')->select('sample_id')->get();
		$purchase = 0;

        foreach($purchases1 as $purchases1)
        {
            $sample11 = DB::table('add_sample_collected_details')->Where('sample_id',$purchases1->sample_id)->select('kilometer','to_kilometer','collect_lab_kilometer','submit_hospital_kilometer')->first();

            $val11 = $sample11->kilometer;
            $val21 = $sample11->to_kilometer;
            $val31 = $sample11->collect_lab_kilometer;
            $val41 = $sample11->submit_hospital_kilometer;
            $val11 = $val11 + $val21 + $val31 + $val41;
            $purchase = $purchase + $val11;
        }
        $total = 0;$purchaseee = 0;
        $district_id = session()->get('district_id');
        $staff_id = session()->get('staff_id');
        if($district_id != '' && $staff_id != '')
        {
            $purr = DB::table('add_sample_collected_details')->join('add_sample_box_detail','add_sample_collected_details.sample_id','add_sample_box_detail.sample_id')->select('add_sample_collected_details.sample_id')->Where('add_sample_box_detail.district_id',$district_id)->Where('add_sample_box_detail.staff_id',$staff_id)->GroupBy('add_sample_collected_details.sample_id')->get();
		
            foreach($purr as $purr)
            {
                $sample = DB::table('add_sample_collected_details')->Where('sample_id',$purr->sample_id)->select('kilometer','to_kilometer','collect_lab_kilometer','submit_hospital_kilometer')->first();

                $val1 = $sample->kilometer;
                $val2 = $sample->to_kilometer;
                $val3 = $sample->collect_lab_kilometer;
                $val4 = $sample->submit_hospital_kilometer;
                $val = $val1 + $val2 + $val3 + $val4;
                $total = $total + $val;
                $purchaseee = $total;
            }
        }
        elseif($district_id != '')
        {
            $purr = DB::table('add_sample_collected_details')->join('add_sample_box_detail','add_sample_collected_details.sample_id','add_sample_box_detail.sample_id')->select('add_sample_collected_details.sample_id')->Where('add_sample_box_detail.district_id',$district_id)->GroupBy('add_sample_collected_details.sample_id')->get();
		
            foreach($purr as $purr)
            {
                $sample = DB::table('add_sample_collected_details')->Where('sample_id',$purr->sample_id)->select('kilometer','to_kilometer','collect_lab_kilometer','submit_hospital_kilometer')->first();

                $val1 = $sample->kilometer;
                $val2 = $sample->to_kilometer;
                $val3 = $sample->collect_lab_kilometer;
                $val4 = $sample->submit_hospital_kilometer;
                $val = $val1 + $val2 + $val3 + $val4;
                $total = $total + $val;
            }
        }
        
        elseif($staff_id != '')
        {
            $purchases12 = DB::table('add_sample_collected_details')->where('staff_id',$staff_id)->sum('kilometer');
										
            $purchases22 = DB::table('add_sample_collected_details')->where('staff_id',$staff_id)->sum('to_kilometer');
                                                
            $purchases32 = DB::table('add_sample_collected_details')->where('staff_id',$staff_id)->sum('collect_lab_kilometer');

            $purchases42 = DB::table('add_sample_collected_details')->where('staff_id',$staff_id)->sum('submit_hospital_kilometer');

            $purchaseee = $purchases12 + $purchases22 + $purchases32 + $purchases42;
        }

        if(session()->has('kilometer_order_filter'))
        {
            $request = json_decode(session('kilometer_order_filter'));

            if($district_id != '' && $staff_id != '')
            {
                if($district_id == '0' && $staff_id == '0')
                {
                    $User = DB::table('add_sample_collected_details')->join('add_sample_box_detail','add_sample_collected_details.id','add_sample_box_detail.sample_auto_id')->where('add_sample_collected_details.kilometer','!=','0')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                        return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                        ->select('add_sample_collected_details.id')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                }
                else if($district_id != '0' && $staff_id == '0')
                {
                    $User = DB::table('add_sample_collected_details')->join('add_sample_box_detail','add_sample_collected_details.id','add_sample_box_detail.sample_auto_id')->where('add_sample_collected_details.kilometer','!=','0')->Where('add_sample_box_detail.district_id',$district_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                        return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                        ->select('add_sample_collected_details.id')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                }
                else if($district_id == '0' && $staff_id != '0')
                {
                    $User = DB::table('add_sample_collected_details')->join('add_sample_box_detail','add_sample_collected_details.id','add_sample_box_detail.sample_auto_id')->where('add_sample_collected_details.kilometer','!=','0')->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                        return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                        ->select('add_sample_collected_details.id')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                }
                else{
                    $User = DB::table('add_sample_collected_details')->join('add_sample_box_detail','add_sample_collected_details.id','add_sample_box_detail.sample_auto_id')->where('add_sample_collected_details.kilometer','!=','0')->Where('add_sample_box_detail.district_id',$district_id)->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                        return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                        ->select('add_sample_collected_details.id')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                }
                
            }
            else if($district_id != '')
            {
                if($district_id == '0')
                {
                    $User = DB::table('add_sample_collected_details')->join('add_sample_box_detail','add_sample_collected_details.id','add_sample_box_detail.sample_auto_id')->where('add_sample_collected_details.kilometer','!=','0')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                        return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                        ->select('add_sample_collected_details.id')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                }
                else{
                    $User = DB::table('add_sample_collected_details')->join('add_sample_box_detail','add_sample_collected_details.id','add_sample_box_detail.sample_auto_id')->where('add_sample_collected_details.kilometer','!=','0')->Where('add_sample_box_detail.district_id',$district_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                        return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                        ->select('add_sample_collected_details.id')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                }
            }
            else if($staff_id != '')
            {
                if($staff_id == '0')
                {
                    $User = DB::table('add_sample_collected_details')->join('add_sample_box_detail','add_sample_collected_details.id','add_sample_box_detail.sample_auto_id')->where('add_sample_collected_details.kilometer','!=','0')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                        return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                        ->select('add_sample_collected_details.id')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                }
                else
                {
                    $User = DB::table('add_sample_collected_details')->join('add_sample_box_detail','add_sample_collected_details.id','add_sample_box_detail.sample_auto_id')->where('add_sample_collected_details.kilometer','!=','0')->Where('add_sample_box_detail.staff_id',$staff_id)->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                        return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                        ->select('add_sample_collected_details.id')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                }
            }
            else
            {
                $User = DB::table('add_sample_collected_details')->join('add_sample_box_detail','add_sample_collected_details.id','add_sample_box_detail.sample_auto_id')->where('add_sample_collected_details.kilometer','!=','0')->when(isset($request->from_date)&&isset($request->to_date)&&$request->from_date!=null&&$request->to_date!=null, function($query)use($request){
                    return $query->whereBetween('add_sample_collected_details.created', [$request->from_date." 00:00:00",$request->to_date." 23:59:59"]);})
                    ->select('add_sample_collected_details.id')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
            }
        }
        else
        {
            if($district_id != '' && $staff_id != '')
            {
                if($district_id == '0' && $staff_id == '0')
                {
                    $User = DB::table('add_sample_collected_details')->join('add_sample_box_detail','add_sample_collected_details.id','add_sample_box_detail.sample_auto_id')->select('add_sample_collected_details.id')->where('add_sample_collected_details.kilometer','!=','0')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                }
                else if($district_id != '0' && $staff_id == '0')
                {
                    $User = DB::table('add_sample_collected_details')->join('add_sample_box_detail','add_sample_collected_details.id','add_sample_box_detail.sample_auto_id')->select('add_sample_collected_details.id')->where('add_sample_collected_details.kilometer','!=','0')->Where('add_sample_box_detail.district_id',$district_id)->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                }
                else if($district_id == '0' && $staff_id != '0')
                {
                    $User = DB::table('add_sample_collected_details')->join('add_sample_box_detail','add_sample_collected_details.id','add_sample_box_detail.sample_auto_id')->select('add_sample_collected_details.id')->where('add_sample_collected_details.kilometer','!=','0')->Where('add_sample_box_detail.staff_id',$staff_id)->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                }
                else
                {
                    $User = DB::table('add_sample_collected_details')->join('add_sample_box_detail','add_sample_collected_details.id','add_sample_box_detail.sample_auto_id')->select('add_sample_collected_details.id')->where('add_sample_collected_details.kilometer','!=','0')->Where('add_sample_box_detail.district_id',$district_id)->Where('add_sample_box_detail.staff_id',$staff_id)->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                }
                
            }
            else if($district_id != '')
            {
                if($district_id == '0')
                {
                    $User = DB::table('add_sample_collected_details')->join('add_sample_box_detail','add_sample_collected_details.id','add_sample_box_detail.sample_auto_id')->select('add_sample_collected_details.id')->where('add_sample_collected_details.kilometer','!=','0')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                }
                else
                {
                    $User = DB::table('add_sample_collected_details')->join('add_sample_box_detail','add_sample_collected_details.id','add_sample_box_detail.sample_auto_id')->select('add_sample_collected_details.id')->where('add_sample_collected_details.kilometer','!=','0')->Where('add_sample_box_detail.district_id',$district_id)->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                }
            }
            else if($staff_id != '')
            {
                if($staff_id == '0')
                {
                    $User = DB::table('add_sample_collected_details')->join('add_sample_box_detail','add_sample_collected_details.id','add_sample_box_detail.sample_auto_id')->select('add_sample_collected_details.id')->where('add_sample_collected_details.kilometer','!=','0')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                }
                else
                {
                    $User = DB::table('add_sample_collected_details')->join('add_sample_box_detail','add_sample_collected_details.id','add_sample_box_detail.sample_auto_id')->select('add_sample_collected_details.id')->where('add_sample_collected_details.kilometer','!=','0')->Where('add_sample_box_detail.staff_id',$staff_id)->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
                }
            }
            else
            {
                $User = DB::table('add_sample_collected_details')->join('add_sample_box_detail','add_sample_collected_details.id','add_sample_box_detail.sample_auto_id')->select('add_sample_collected_details.id')->where('add_sample_collected_details.kilometer','!=','0')->GroupBy('add_sample_collected_details.id')->orderby('add_sample_collected_details.id','DESC')->get();
            }
        }

        $from_date =isset($request->from_date)?$request->from_date:null;
        $to_date =isset($request->to_date)?$request->to_date:null;
											
        return view('Admin.generate-kilometer-invoice',compact('purchase','total','purchaseee','User','from_date', 'to_date'));
    }

    public function distance_check($id)
    {
        session()->put('distance_status',$id);

        $banner = distance_status::find(1);
        $banner->status = $id;
        $banner->created = date('Y-m-d H:i:s');
        $banner->save();
            
        return redirect()->route('panel.Reports.kilometer-report');
    }

    public function sample_info($id)
    {
        return view('Admin.sample-info',compact('id'));
    }

    public function sample_collection_info($id)
    {
        return view('Admin.sample-collection-info',compact('id'));
    }

    public function sample_submittion_info($id)
    {
        return view('Admin.sample-submittion-info',compact('id'));
    }

    public function sample_collectted_info($id)
    {
        return view('Admin.sample-collectted-info',compact('id'));
    }

    public function sample_submitted_info($id)
    {
        return view('Admin.sample-submitted-info',compact('id'));
    }

    // public function generate(Request $request)
    // {
    //     $data=$request->input();
    //     $data->district = $request->name;
    //     $data->created = date('Y-m-d H:i:s');
    //     $data->save();

    //     return redirect('post')->with('data',$data);
    // }

    
    public function sample_generated_invoice_list()
    {
        $sample = add_sample_invoice::orderBy('id','DESC')->get();

        return view('Admin.sample-generated-invoice-list',compact('sample'));
    }
    
    public function kilometer_generated_invoice_list()
    {
        $sample = add_kilometer_invoice::orderBy('id','DESC')->get();

        return view('Admin.kilometer-generated-invoice-list',compact('sample'));
    }

    public function sample_invoice_detail($id)
    {
        $sample = DB::table('add_invoice_sample')->join('add_sample_box_detail','add_invoice_sample.sample_id','add_sample_box_detail.id')->select('add_sample_box_detail.id','add_sample_box_detail.specimen_id','add_sample_box_detail.test_id','add_sample_box_detail.nikshay_id','add_sample_box_detail.patient','add_sample_box_detail.type_patient','add_sample_box_detail.type_test_for','add_sample_box_detail.date')->where('add_invoice_sample.invoice_id',$id)->orderby('add_sample_box_detail.id','DESC')->get();

        return view('Admin.sample-invoice-detail',compact('sample'));
    }

    public function kilometer_invoice_detail($id)
    {
        $sample = DB::table('add_invoice_kilometer_sample')->join('add_sample_collected_details','add_invoice_kilometer_sample.sample_id','add_sample_collected_details.id')->Where('add_invoice_kilometer_sample.invoice_id',$id)->select('add_sample_collected_details.id','add_sample_collected_details.staff_id','add_sample_collected_details.created','add_sample_collected_details.submitted_date','add_sample_collected_details.collected_from')->orderBy('add_sample_collected_details.id','DESC')->get();

        return view('Admin.kilometer-invoice-detail',compact('sample'));
    }

    public function report_sample($id)
    {
        $sample = add_sample_box_detail::where('sample_auto_id',$id)->orderBy('id','DESC')->get();

        return view('Admin.report-sample',compact('sample'));
    }
}
