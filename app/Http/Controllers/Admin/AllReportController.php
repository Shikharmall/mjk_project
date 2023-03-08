<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\add_sample_box_sample;
use App\Models\add_collected_sample;
use App\Models\add_collect_submitted_sample;
use App\Models\add_submitted_hospital_sample;
use App\Models\add_sample_box_detail;
use App\Models\add_hospital;
use App\Models\add_lab;
use App\Models\User;
use DB;

class AllReportController extends Controller
{
    public function all_collection()
    {
        $hospital = add_sample_box_sample::select('hospital_id')->GroupBy('hospital_id')->get();
        return view('Admin.all-collection-detail', compact('hospital'));
    }

    public function collection_sample($id)
    {
        $sample = add_sample_box_sample::leftjoin('add_hospital','add_sample_box_sample.hospital_id','add_hospital.hospital_id')->leftjoin('add_sample_box_detail','add_sample_box_sample.sample_selected_id','add_sample_box_detail.id')->where('add_sample_box_sample.hospital_id',$id)->orderBy('add_sample_box_sample.id','DESC')->select('add_sample_box_sample.id','add_sample_box_sample.sample_selected_id','add_sample_box_detail.nikshay_id','add_sample_box_detail.patient','add_sample_box_detail.invoice_photo','add_sample_box_detail.specimen_id','add_sample_box_detail.test_id','add_sample_box_detail.type_patient','add_sample_box_detail.type_test_for','add_sample_box_detail.no_of_sample','add_sample_box_detail.created')->get();;

        return view('Admin.collection-sample', compact('sample'));
    }

    public function all_submitted()
    {
        $hospital = add_collected_sample::select('lab_id')->GroupBy('lab_id')->get();
        return view('Admin.all-submitted-detail', compact('hospital'));
    }

    public function submitted_sample($id)
    {
        $sample = add_collected_sample::leftjoin('add_lab','add_collected_sample.lab_id','add_lab.lab_id')->leftjoin('add_sample_box_detail','add_collected_sample.sample_selected_id','add_sample_box_detail.id')->where('add_collected_sample.lab_id',$id)->orderBy('add_collected_sample.id','DESC')->select('add_collected_sample.id','add_collected_sample.sample_selected_id','add_sample_box_detail.nikshay_id','add_sample_box_detail.patient','add_sample_box_detail.invoice_photo','add_sample_box_detail.specimen_id','add_sample_box_detail.test_id','add_sample_box_detail.type_patient','add_sample_box_detail.type_test_for','add_sample_box_detail.no_of_sample','add_sample_box_detail.created')->get();

        return view('Admin.submitted-sample', compact('sample'));
    }

    public function all_collected_report()
    {
        $hospital = add_collect_submitted_sample::select('lab_id')->GroupBy('lab_id')->get();
        return view('Admin.all-collected-report', compact('hospital'));
    }

    public function collected_sample($id)
    {
        $sample = add_collect_submitted_sample::leftjoin('add_lab','add_collect_submitted_sample.lab_id','add_lab.lab_id')->leftjoin('add_sample_box_detail','add_collect_submitted_sample.sample_selected_id','add_sample_box_detail.id')->where('add_collect_submitted_sample.lab_id',$id)->orderBy('add_collect_submitted_sample.id','DESC')->select('add_collect_submitted_sample.id','add_collect_submitted_sample.sample_selected_id','add_sample_box_detail.nikshay_id','add_sample_box_detail.patient','add_sample_box_detail.invoice_photo','add_sample_box_detail.specimen_id','add_sample_box_detail.test_id','add_sample_box_detail.type_patient','add_sample_box_detail.type_test_for','add_sample_box_detail.no_of_sample','add_sample_box_detail.created')->get();

        return view('Admin.collected-sample', compact('sample'));
    }

    public function all_submitted_report()
    {
        $hospital = add_submitted_hospital_sample::select('hospital_id')->GroupBy('hospital_id')->get();
        return view('Admin.all-submitted-report', compact('hospital'));
    }

    public function submitted_report_sample($id)
    {
        $sample = add_submitted_hospital_sample::leftjoin('add_hospital','add_submitted_hospital_sample.hospital_id','add_hospital.hospital_id')->leftjoin('add_sample_box_detail','add_submitted_hospital_sample.sample_selected_id','add_sample_box_detail.id')->where('add_submitted_hospital_sample.hospital_id',$id)->orderBy('add_submitted_hospital_sample.id','DESC')->select('add_submitted_hospital_sample.id','add_submitted_hospital_sample.sample_selected_id','add_sample_box_detail.nikshay_id','add_sample_box_detail.patient','add_sample_box_detail.invoice_photo','add_sample_box_detail.specimen_id','add_sample_box_detail.test_id','add_sample_box_detail.type_patient','add_sample_box_detail.type_test_for','add_sample_box_detail.no_of_sample','add_sample_box_detail.created')->get();

        return view('Admin.submitted-report-sample', compact('sample'));
    }

    public function sample_report()
    {
        if(session()->has('order_filter'))
        {
            $request = json_decode(session('order_filter'));
            if($request->type != '' && $request->name != '')
            {
                $input = add_hospital::where('name','like','%'.$request->name.'%')->count();
                if($input == 0)
                {
                    Toastr::success('Success! Hospital Not Found');
                    $astrologerDD = DB::table('add_sample_box_detail')->orderBy('id', 'desc');
                    $sample = $astrologe0rDD->get();

                    return view('Admin.sample-report',compact('sample'));
                }
                else
                {
                    $astrologerDD = DB::table('add_sample_box_detail')->join('add_hospital','add_sample_box_detail.collected_from','add_hospital.hospital_id')->select('add_sample_box_detail.id','add_sample_box_detail.nikshay_id','add_sample_box_detail.patient','add_sample_box_detail.status','add_sample_box_detail.created')->where('add_hospital.name','like','%'.$request->name.'%')->orderBy('add_sample_box_detail.id', 'desc');
                }
            }
            else
            {
                Toastr::success('Success! Type or Name not added. ');

                $astrologerDD = DB::table('add_sample_box_detail')->orderBy('id', 'desc');
                    $sample = $astrologe0rDD->get();

                return view('Admin.sample-report',compact('sample'));
            }
        }
        else
        {
            $astrologerDD = DB::table('add_sample_box_detail')->orderBy('id', 'desc');
            $sample = $astrologe0rDD->get();

            return view('Admin.sample-report',compact('sample'));
        }
        
    }

    public function filter(Request $request)
    {
        if($request->type != '' || $request->name != '')
        {
            $request->validate([
                'type' => 'required_if:type,true',
                'name' => 'required_if:name,true',
            ]);
            session()->put('order_filter', json_encode($request->all()));
            return back();
        }
        else
        {
            session()->forget('order_filter');
            return back();
        }
    }

    public function filter_reset(Request $request)
    {
        session()->forget('order_filter');
        return back();
    }

    public function nikshay_search()
    {
        $sample = add_sample_box_detail::orderBy('id','DESC')->get();

        return view('Admin.nikshay-search',compact('sample'));
    }
}
