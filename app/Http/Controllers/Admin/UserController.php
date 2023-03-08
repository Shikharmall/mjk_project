<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use App\CPU\ImageManager;
use App\Models\assign_district;
use App\Models\assign_lab;
use App\Models\assign_hospital;
use App\Models\add_district;
use App\Models\add_lab;
use App\Models\add_hospital;
use DB;

class UserController extends Controller
{
    public function user()
    {
        return view('Admin/user-add');
    }

    public function add_user(Request $request)
    {
        $data=$request->input();
        if(!empty($data['id']))
        {
            $banner = User::find($data['id']);
        
            if ($request->has('image')) {
                $banner->image = ImageManager::update('modal/', $banner['image'], 'png', $request->file('image'));
             }
            $banner->name = $request->name;
            $banner->email = $request->email;
            $banner->mobile = $request->mobile;
            $banner->save();
           
            Toastr::success('Success! User Updated');
             
            return redirect()->route('panel.User.list');
        }
        else
        {
            $data = new User();
            
            $data->name = $request->name;
            $data->email = $request->email;
            $data->mobile = $request->mobile;
            $data->created = date('Y-m-d H:i:s');
            $data->image = ImageManager::upload('modal/', 'png', $request->file('image'));
            $data->save();

            Toastr::success('Success! User Inserted');
            return redirect()->back();

        }
    }

    public function edit($id)
    {
        $user = User::where('id',$id)->orderBy('id','DESC')->first();
        return view('Admin/user-add',compact('user'));
    }


    public function list(Request $request)
    {
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search'))
        {
            $key = explode(' ', $request['search']);
            $User = User::where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->Where('name', 'like', "%{$value}%");
                }
            })->orderBy('id', 'desc');
            $query_param = ['search' => $request['search']];
        }else{
            $User = User::orderBy('id', 'desc');
        }
        $User = $User->paginate(config('default_pagination'))->appends($query_param);
        $add_district = add_district::orderBy('name','ASC')->get();
        $add_lab = add_lab::orderBy('name','ASC')->get();
        $add_hospital = add_hospital::orderBy('name','ASC')->get();
        return view('Admin.user',compact('add_district','add_lab','add_hospital'));
    }

    public function delete($id)
    {
        $br = User::find($id);
        $br->delete();
        return redirect()->back();
    }

    public function approve($id)
    {
        $data = User::find($id);
        $data->status=0;
        $data->save();

        Toastr::success('Success! User Dis-approve');
        return redirect()->back();
    }

    public function disapprove($id)
    {
        $data = User::find($id);
        $data->status=1;
        $data->save();

        Toastr::success('Success! User Dis-approve');
        return redirect()->back();
    }

    public function district_assign($id)
    {
        $add_district = add_district::orderBy('district_id','DESC')->get();
        return view('Admin/assign-district',compact('id','add_district'));
    }

    public function lab_assign($id)
    {
        $add_lab = add_lab::orderBy('lab_id','DESC')->get();
        return view('Admin/assign-lab',compact('id','add_lab'));
    }

    public function hospital_assign($id)
    {
        $add_hospital = add_hospital::orderBy('hospital_id','DESC')->get();
        return view('Admin/assign-hospital',compact('id','add_hospital'));
    }

    public function assign_district(Request $request)
    {
        $data=$request->input();
        if(assign_district::where('staff_id',$request->staff_id)->where('district_id',$request->district_id)->where('assign_date',date('Y-m-d'))->exists())
        {
            Toastr::warning('Already assigned. ');
            return back();
        }
        else
        {
            $data = new assign_district();
            $data->staff_id = $request->staff_id;
            $data->district_id = $request->district_id;
            $data->assign_date = date('Y-m-d');
            $data->created = date('Y-m-d H:i:s');
            $data->save();

            Toastr::success('Success! District Assign Successfully. ');
            return redirect()->back();
        }
    }

    public function assign_lab(Request $request)
    {
        $data=$request->input();
        if(assign_lab::where('staff_id',$request->staff_id)->where('lab_id',$request->lab_id)->where('assign_date',date('Y-m-d'))->exists())
        {
            Toastr::warning('Already assigned. ');
            return back();
        }
        else
        {
            $data = new assign_lab();
            $data->staff_id = $request->staff_id;
            $data->lab_id = $request->lab_id;
            $data->assign_date = date('Y-m-d');
            $data->created = date('Y-m-d H:i:s');
            $data->save();

            Toastr::success('Success! Lab Assign Successfully. ');
            return redirect()->back();
        }
    }

    public function assign_hospital(Request $request)
    {
        $data=$request->input();
        if(assign_hospital::where('staff_id',$request->staff_id)->where('hospital_id',$request->hospital_id)->where('assign_date',date('Y-m-d'))->exists())
        {
            Toastr::warning('Already assigned. ');
            return back();
        }
        else
        {
            $data = new assign_hospital();
            $data->staff_id = $request->staff_id;
            $data->hospital_id = $request->hospital_id;
            $data->assign_date = date('Y-m-d');
            $data->created = date('Y-m-d H:i:s');
            $data->save();

            Toastr::success('Success! Hospital Assign Successfully. ');
            return redirect()->back();
        }
    }

    public function delete_district($id)
    {
        $br = assign_district::find($id);
        $br->delete();
        return redirect()->back();
    }

    public function delete_lab($id)
    {
        $br = assign_lab::find($id);
        $br->delete();
        return redirect()->back();
    }

    public function delete_hospital($id)
    {
        $br = assign_hospital::find($id);
        $br->delete();
        return redirect()->back();
    }

    public function staff_activity_report($id)
    {
        $date = "";
        if(session()->has('user_filter'))
        {
            $request = json_decode(session('user_filter'));
            $date = $request->date;
        }
        else
        {
            $date = date('Y-m-d');
        }
     
        $staff_activuty = DB::table('add_staff_activity')->where('staff_id',$id)->where('created', $date)->orderBy('id','DESC')->get();

        $User = DB::table('add_staff_activity')->Where('staff_id',$id)->where('created', $date)->orderBy('id','ASC')->get();
        $latitude = '';
        $longitude = '';
        $User12 = DB::table('add_sample_box_detail')->where('staff_id',$id)->where('date', $date)->orderBy('id','DESC')->count();
        if($User12 != 0)
        {
            $User11 = DB::table('add_sample_box_detail')->where('staff_id',$id)->where('date', $date)->orderBy('id','DESC')->first();
            $district = DB::table('add_district')->where('district_id',$User11->district_id)->first();

            $latitude = $district->latitude;
            $longitude = $district->longitude;
        }
        else
        {
            $latitude = '0.00';
            $longitude = '0.00';   
        }
        

        return view('Admin.staff-activity-report', compact('User','id','date','staff_activuty','latitude','longitude'));
    }

    public function map($id)
    {
        return view('Admin/map',compact('id'));
    }

    public function filter(Request $request)
    {
        $request->validate([
            'date' => 'required_if:date,true',
        ]);
        session()->put('user_filter', json_encode($request->all()));
        return back();
    }

    public function reset($id, Request $request)
    {
        session()->forget('user_filter');
        $date = date('Y-m-d');
     
        $staff_activuty = DB::table('add_staff_activity')->where('staff_id',$id)->where('created', $date)->orderBy('id','DESC')->get();

       
        $latitude = '';
        $longitude = '';
        $User12 = DB::table('add_sample_box_detail')->where('staff_id',$id)->where('date', $date)->orderBy('id','DESC')->count();
        if($User12 != 0)
        {
            $User11 = DB::table('add_sample_box_detail')->where('staff_id',$id)->where('date', $date)->orderBy('id','DESC')->first();
            $district = DB::table('add_district')->where('district_id',$User11->district_id)->first();

            $latitude = $district->latitude;
            $longitude = $district->longitude;
        }
        else
        {
            $latitude = '0.00';
            $longitude = '0.00';   
        }
        return view('Admin.staff-activity-report', compact('id','date','staff_activuty','latitude','longitude'));
    }
}
