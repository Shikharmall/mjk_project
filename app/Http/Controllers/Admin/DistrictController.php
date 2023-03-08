<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\add_district;

class DistrictController extends Controller
{
    public function district()
    {
        return view('Admin/district-add');
    }

    public function add_district(Request $request)
    {
        $data=$request->input();
        if(!empty($data['district_id']))
        {
            $banner = add_district::find($data['district_id']);
            $banner->name = $request->name;
            $banner->gps_address = $request->gps_address;
            $banner->latitude = $request->latitude;
            $banner->longitude = $request->longitude;
            $banner->save();

            Toastr::success('Success! District Updated');
             
            return redirect()->route('panel.District.list');
        }
        else
        {
            if(add_district::where('name',$request->name)->exists())
            {
                Toastr::warning('District Already Created.');
                return redirect()->back();
            }
            else
            {
                $data = new add_district();
                $data->name = $request->name;
                $data->gps_address = $request->gps_address;
                $data->latitude = $request->latitude;
                $data->longitude = $request->longitude;
                $data->created = date('Y-m-d H:i:s');
                $data->save();

                Toastr::success('Success! District Inserted');
                return redirect()->back();
            }
        }
    }

    public function edit($district_id)
    {
        $district = add_district::where('district_id',$district_id)->orderBy('district_id','DESC')->first();
        return view('Admin/district-add',compact('district','district'));
    }

    public function delete($district_id)
    {
        $br = add_district::find($district_id);
        $br->delete();
        return redirect()->back();
    }

    public function list(Request $request)
    {
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search'))
        {
            $key = explode(' ', $request['search']);
            $district33 = add_district::where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->Where('name', 'like', "%{$value}%");
                }
            })->orderBy('district_id', 'desc');
            $query_param = ['search' => $request['search']];
        }else{
            $district33 = add_district::orderBy('district_id', 'desc');
        }
        $district33 = $district33->paginate(config('default_pagination'))->appends($query_param);

        return view('Admin.district-add', compact('district33','search'));
    }
}
