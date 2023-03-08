<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\add_test;

class TestController extends Controller
{
    public function test()
    {
        return view('Admin/test-add');
    }

    public function add_test(Request $request)
    {
        $data=$request->input();
        if(!empty($data['id']))
        {
            $banner = add_test::find($data['id']);
            $banner->name = $request->name;
            $banner->save();

            Toastr::success('Success! Test Updated');
            return redirect()->route('panel.Test.list');
        }
        else
        {
            $data = new add_test();
            $data->name = $request->name;
            $data->created = date('Y-m-d H:i:s');
            $data->save();

            Toastr::success('Success! Test Inserted');
            return redirect()->back();
        }
    }

    public function edit($id)
    {
        $test = add_test::where('id',$id)->orderBy('id','DESC')->first();
        return view('Admin/test-add',compact('test'));
    }

    public function delete($id)
    {
        $br = add_test::find($id);
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
            $test33 = add_test::where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->Where('name', 'like', "%{$value}%");
                }
            })->orderBy('id', 'desc');
            $query_param = ['search' => $request['search']];
        }else{
            $test33 = add_test::orderBy('id', 'desc');
        }
        $test33 = $test33->paginate(config('default_pagination'))->appends($query_param);

        return view('Admin.test-add', compact('test33','search'));
    }
}