<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\add_specimen;


class SpecimenController extends Controller
{
    public function specimen()
    {
        return view('Admin/specimen-add');
    }

    public function add_specimen(Request $request)
    {
        $data=$request->input();
        if(!empty($data['id']))
        {
            $banner = add_specimen::find($data['id']);
            $banner->name = $request->name;
            $banner->save();

            Toastr::success('Success! Specimen Updated');
             
            return redirect()->route('panel.Specimen.list');
        }
        else
        {
            $data = new add_specimen();
            $data->name = $request->name;
            $data->created = date('Y-m-d H:i:s');
            $data->save();

            Toastr::success('Success! Specimen Inserted');
            return redirect()->back();
        }
    }

    public function edit($id)
    {
        $specimen = add_specimen::where('id',$id)->orderBy('id','DESC')->first();
        return view('Admin/specimen-add',compact('specimen'));
    }

    public function delete($id)
    {
        $br = add_specimen::find($id);
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
            $specimen33 = add_specimen::where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->Where('name', 'like', "%{$value}%");
                }
            })->orderBy('id', 'desc');
            $query_param = ['search' => $request['search']];
        }else{
            $specimen33 = add_specimen::orderBy('id', 'desc');
        }
        $specimen33 = $specimen33->paginate(config('default_pagination'))->appends($query_param);

        return view('Admin.specimen-add', compact('specimen33','search'));
    }
}
