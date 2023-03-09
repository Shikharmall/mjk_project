<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\add_hospital;
use App\Models\add_district;
use App\CPU\ImageManager;


class HospitalController extends Controller
{
    public function hospital()
    {
        $add_district = add_district::orderBy('name','ASC')->get();
        return view('Admin/hospital-add' , compact('add_district'));
    }

    public function add_hospital(Request $request)
    {
        $data=$request->input();
        
        if(!empty($data['hospital_id']))
        {
            $banner = add_hospital::find($data['hospital_id']);
            if ($request->has('image')) {
                $banner->image = ImageManager::update('modal/', $banner['image'], 'png', $request->file('image'));
            }
            $banner->name = $request->name;
            $banner->district_id = $request->district_id;
            $banner->gps_address = $request->gps_address;
            $banner->latitude = $request->latitude;
            $banner->longitude = $request->longitude;
            $banner->save();

            Toastr::success('Success! Hospital Updated');
             
            return redirect()->route('panel.Hospital.list');
        }
        else
        {
            $data = new add_hospital();
            $data->name = $request->name;
            $data->gps_address = $request->gps_address;
            $data->district_id = $request->district_id;
            $data->latitude = $request->latitude;
            $data->longitude = $request->longitude;
            $data->image = ImageManager::upload('modal/', 'png', $request->file('image'));
            $data->created = date('Y-m-d H:i:s');
            $data->save();

            Toastr::success('Success! Hospital Inserted');
            return redirect()->back();
        }
    }

    public function edit($hospital_id)
    {
        $hospital = add_hospital::where('hospital_id',$hospital_id)->orderBy('hospital_id','DESC')->first();
        return view('Admin/hospital-add',compact('hospital','hospital'));
    }

    public function delete($hospital_id)
    {
        $br = add_hospital::find($hospital_id);
        $br->delete();
        return redirect()->back();
    }

    public function approve($id)
    {
        $data = add_hospital::find($id);
        $data->status=0;
        $data->save();

        Toastr::success('Success! Hospital Dis-approve');
        return redirect()->back();
    }

    public function disapprove($id)
    {
        $data = add_hospital::find($id);
        $data->status=1;
        $data->save();

        Toastr::success('Success! Hospital Approve');
        return redirect()->back();
    }

    public function list(Request $request)
    {
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search'))
        {
            $key = explode(' ', $request['search']);
            $hospital = add_hospital::where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->Where('name', 'like', "%{$value}%");
                }
            })->orderBy('hospital_id', 'desc');
            $query_param = ['search' => $request['search']];
        }else{
            $hospital = add_hospital::orderBy('hospital_id', 'desc');
        }
        $hospital = $hospital->paginate(config('default_pagination'))->appends($query_param);

        return view('Admin.hospital', compact('hospital','search'));
    }
}
