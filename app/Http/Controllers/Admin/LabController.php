<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\add_lab;
use App\Models\add_district;
use App\CPU\ImageManager;

class LabController extends Controller
{
    public function lab()
    {
        $add_district = add_district::orderBy('name','ASC')->get();
        return view('Admin/lab-add',compact('add_district'));
    }

    public function add_lab(Request $request)
    {
        $data=$request->input();
        if(!empty($data['lab_id']))
        {
            $banner = add_lab::find($data['lab_id']);
            if ($request->has('image')) {
                $banner->image = ImageManager::update('modal/', $banner['image'], 'png', $request->file('image'));
            }
            $banner->name = $request->name;
            $banner->district_id = $request->district_id;
            $banner->gps_address = $request->gps_address;
            $banner->latitude = $request->latitude;
            $banner->longitude = $request->longitude;
            $banner->save();

            Toastr::success('Success! Lab Updated');
             
            return redirect()->route('panel.Lab.list');
        }
        else
        {
            $data = new add_lab();
            $data->name = $request->name;
            $data->gps_address = $request->gps_address;
            $data->district_id = $request->district_id;
            $data->latitude = $request->latitude;
            $data->longitude = $request->longitude;
            $data->image = ImageManager::upload('modal/', 'png', $request->file('image'));
            $data->created = date('Y-m-d H:i:s');
            $data->save();

            Toastr::success('Success! Lab Inserted');
            return redirect()->back();
        }
    }

    public function edit($lab_id)
    {
        $add_district = add_district::orderBy('name','ASC')->get();
        $lab = add_lab::where('lab_id',$lab_id)->orderBy('lab_id','DESC')->first();
        return view('Admin/lab-add',compact('lab','add_district'));
    }

    public function delete($lab_id)
    {
        $br = add_lab::find($lab_id);
        $br->delete();
        return redirect()->back();
    }

    public function approve($id)
    {
        $data = add_lab::find($id);
        $data->status=0;
        $data->save();

        Toastr::success('Success! Lab Dis-approve');
        return redirect()->back();
    }

    public function disapprove($id)
    {
        $data = add_lab::find($id);
        $data->status=1;
        $data->save();

        Toastr::success('Success! Lab Approve');
        return redirect()->back();
    }

    public function list(Request $request)
    {
        return view('Admin.lab');
    }
}
