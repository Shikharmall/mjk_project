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
use App\Models\add_specimen;
use App\Models\add_test;

class TodayReportController extends Controller
{
    public function today_collection()
    {
        $hospital = add_sample_box_sample::select('hospital_id')->Where('date',date('Y-m-d'))->GroupBy('hospital_id')->get();
        return view('Admin.today-collection-detail', compact('hospital'));
    }

    public function today_collection_sample($id)
    {
        $sample = add_sample_box_sample::leftjoin('add_hospital','add_sample_box_sample.hospital_id','add_hospital.hospital_id')->leftjoin('add_sample_box_detail','add_sample_box_sample.sample_selected_id','add_sample_box_detail.id')->where('add_sample_box_sample.hospital_id',$id)->where('add_sample_box_sample.date',date('Y-m-d'))->orderBy('add_sample_box_sample.id','DESC')->select('add_sample_box_sample.id','add_sample_box_sample.sample_selected_id','add_sample_box_detail.nikshay_id','add_sample_box_detail.patient','add_sample_box_detail.invoice_photo','add_sample_box_detail.specimen_id','add_sample_box_detail.test_id','add_sample_box_detail.type_patient','add_sample_box_detail.type_test_for','add_sample_box_detail.no_of_sample','add_sample_box_detail.created')->get();;

        return view('Admin.today-collection-sample', compact('sample'));
    }

    public function today_submitted()
    {
        $hospital = add_collected_sample::select('lab_id')->Where('created',date('Y-m-d'))->GroupBy('lab_id')->get();
        return view('Admin.today-submitted-detail', compact('hospital'));
    }

    public function today_submitted_sample($id)
    {
        $sample = add_collected_sample::leftjoin('add_lab','add_collected_sample.lab_id','add_lab.lab_id')->leftjoin('add_sample_box_detail','add_collected_sample.sample_selected_id','add_sample_box_detail.id')->where('add_collected_sample.lab_id',$id)->where('add_collected_sample.created',date('Y-m-d'))->orderBy('add_collected_sample.id','DESC')->select('add_collected_sample.id','add_collected_sample.sample_selected_id','add_sample_box_detail.nikshay_id','add_sample_box_detail.patient','add_sample_box_detail.invoice_photo','add_sample_box_detail.specimen_id','add_sample_box_detail.test_id','add_sample_box_detail.type_patient','add_sample_box_detail.type_test_for','add_sample_box_detail.no_of_sample','add_sample_box_detail.created')->get();

        return view('Admin.today-submitted-sample', compact('sample'));
    }

    public function today_collected_report()
    {
        $hospital = add_collect_submitted_sample::select('lab_id')->Where('created',date('Y-m-d'))->GroupBy('lab_id')->get();
        return view('Admin.today-collected-report', compact('hospital'));
    }

    public function today_collected_sample($id)
    {
        $sample = add_collect_submitted_sample::leftjoin('add_lab','add_collect_submitted_sample.lab_id','add_lab.lab_id')->leftjoin('add_sample_box_detail','add_collect_submitted_sample.sample_selected_id','add_sample_box_detail.id')->where('add_collect_submitted_sample.lab_id',$id)->where('add_collect_submitted_sample.created',date('Y-m-d'))->orderBy('add_collect_submitted_sample.id','DESC')->select('add_collect_submitted_sample.id','add_collect_submitted_sample.sample_selected_id','add_sample_box_detail.nikshay_id','add_sample_box_detail.patient','add_sample_box_detail.invoice_photo','add_sample_box_detail.specimen_id','add_sample_box_detail.test_id','add_sample_box_detail.type_patient','add_sample_box_detail.type_test_for','add_sample_box_detail.no_of_sample','add_sample_box_detail.created')->get();

        return view('Admin.today-collected-sample', compact('sample'));
    }

    public function today_submitted_report()
    {
        $hospital = add_submitted_hospital_sample::select('hospital_id')->Where('created',date('Y-m-d'))->GroupBy('hospital_id')->get();
        return view('Admin.today-submitted-report', compact('hospital'));
    }

    public function today_collected_report_sample($id)
    {
        $sample = add_submitted_hospital_sample::leftjoin('add_hospital','add_submitted_hospital_sample.hospital_id','add_hospital.hospital_id')->leftjoin('add_sample_box_detail','add_submitted_hospital_sample.sample_selected_id','add_sample_box_detail.id')->where('add_submitted_hospital_sample.hospital_id',$id)->where('add_submitted_hospital_sample.created',date('Y-m-d'))->orderBy('add_submitted_hospital_sample.id','DESC')->select('add_submitted_hospital_sample.id','add_submitted_hospital_sample.sample_selected_id','add_sample_box_detail.nikshay_id','add_sample_box_detail.patient','add_sample_box_detail.invoice_photo','add_sample_box_detail.specimen_id','add_sample_box_detail.test_id','add_sample_box_detail.type_patient','add_sample_box_detail.type_test_for','add_sample_box_detail.no_of_sample','add_sample_box_detail.created')->get();

        return view('Admin.today-submitted-report-sample', compact('sample'));
    }
}
