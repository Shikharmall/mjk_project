<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\add_lab;
use App\Models\add_hospital;
use App\Models\add_test;
use App\Models\add_specimen;
use DB;
use Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;


class AuthController extends Controller
{
    public function odometer_status(Request $request)
    {
        $distance_status = DB::table('distance_status')->first();

        return response()->json(['success' => true,'status'=>$distance_status->status], 200);
    }

    public function signin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'device_id' => 'required',
        ]);
        if($validator->fails()){
            return response()->json(['success' => 'false','errors' => $validator->errors()], 400);
        }
        if(User::where('email',$request->email)->exists())
        {
            if(Auth::attempt(['email' => $request->email, 'password' => '1234567890' ,'status'=>'1']))
            {
                // if(Auth::attempt(['mobile' => $request->mobile])){
                auth()->user()->tokens()->delete();
                $authUser = Auth::user();
                User::where('id',auth()->user()->id)->update(['device_id'=>$request->device_id]);
                $token = $authUser->createToken('MyAuthApp')->plainTextToken;
                $list = User::where('id',auth()->user()->id)->first();
                $array = Arr::add($list, 'token', $token);
                $list['is_first'] = '0';
                return response()->json(['success' => 'true','token' => $token,'data' => $list,'message' => 'User logged in'], 200);
            }
            else
            {
                return response()->json(['success' => 'false','message' => 'Account has been suspended.'], 200);
            }
        }
        else
        {
            $data = $request->all();
            // $refer_code = Str::random(6);
            // $countt=User::get()->count();
            //     for($i=0;$i<$countt;$i++)
            //     {
            //         if(User::where('refer_code',$refer_code)->exists())
            //         {
            //             $refer_code = Str::random(6);
            //         }
            //         else
            //         {
            //             break;
            //         }
            //     }

              
            $ins=array(
                'name'=>$data['name'] ? $data['name'] : 'N/A',
                'email'=>$data['email'] ? $data['email'] : 'N/A',
                'mobile'=>$data['mobile'] ? $data['mobile'] : 'N/A',
                'image'=>$data['image'] ? $data['image'] : 'N/A',
                'device_id'=>$data['device_id'] ? $data['device_id'] : 'N/A',
                'created'=>date('Y-m-d H:s:i'),
            );
            $ins['password'] = bcrypt('1234567890');
            $user = User::create($ins);
            $token =  $user->createToken('MyAuthApp')->plainTextToken;
            // $detail = detail::where('detail_id',1)->first();
            // $ins1=array(
            //     'user_id'=>$user->id,
            //     'wallet_amount'=>$detail->welcome_amount,
            //     'wallet_created'=>date('Y-m-d H:i:s'),
            // );
            // $wallamount = user_wallet::create($ins1);
            // $ins_d=array(
            //     'user_id'=>$user->id,
            //     'amount'=>$detail->welcome_amount,
            //     'detail_status'=>'1',
            //     'transid'=>mt_rand(111111,999999),
            //     'transtion_type'=>'Welcome bonus points '.$detail->welcome_amount.' credited to your wallet',
            //     'detail_created'=>date('Y-m-d H:i:s'),
            // );
            // $user_wallet_detail = user_wallet_detail::create($ins_d);
            $list = User::where('id',$user->id)->first();
            $list['is_first'] = '1';
            // $list['welcome_amount'] = $detail->welcome_amount;
            return response()->json(['success' => 'true','token' => $token,'data' => $list,'message' => 'User registered successfully.'], 200);
        }
    }

    public function get_hospital(Request $request)
    {
        $sid = auth()->user()->id;
        $data = add_hospital::join('assign_hospital','add_hospital.hospital_id','assign_hospital.hospital_id')->where('add_hospital.status',1)->where('assign_hospital.staff_id',auth()->user()->id)->select('add_hospital.hospital_id','add_hospital.image','add_hospital.name','add_hospital.mobile','add_hospital.email','add_hospital.gps_address','add_hospital.latitude','add_hospital.longitude','add_hospital.image','add_hospital.status','add_hospital.created')->orderBy('add_hospital.hospital_id', 'desc')->get();

        if($data->isEmpty()){
            return response()->json(['success' => false,'message'=>'no data found'], 200);
        }
        else
        {
            foreach($data as $dt)
            $dt['hospital_id']=$dt->hospital_id;
            $dt['image']=$dt->image;
            return response()->json(['success' => true,'data'=>$data], 200);
        } 
    }

    public function search_hospital(Request $request)
    {

        $data = add_hospital::join('assign_hospital','add_hospital.hospital_id','assign_hospital.hospital_id')->where('add_hospital.status',1)->where('add_hospital.name','like','%'.$request['name'].'%')->where('assign_hospital.staff_id',auth()->user()->id)->select('add_hospital.hospital_id','add_hospital.image','add_hospital.name','add_hospital.mobile','add_hospital.email','add_hospital.gps_address','add_hospital.latitude','add_hospital.longitude','add_hospital.image','add_hospital.status','add_hospital.created')->orderBy('add_hospital.hospital_id', 'desc')->get();

        if($data->isEmpty()){
            return response()->json(['success' => false,'message'=>'no data found'], 200);
        }
        else
        {
            foreach($data as $dt)
            $dt['hospital_id']=$dt->hospital_id;
            $dt['image']=$dt->image;
            return response()->json(['success' => true,'data'=>$data], 200);
        } 
    }

    public function get_lab(Request $request)
    {
        $data = add_lab::join('assign_lab','add_lab.lab_id','assign_lab.lab_id')->join('add_district','add_lab.district_id','add_district.district_id')->where('add_lab.status',1)->where('assign_lab.staff_id',auth()->user()->id)->select('add_lab.lab_id','add_lab.image','add_lab.name','add_lab.district_id','add_lab.gps_address','add_lab.latitude','add_lab.longitude','add_lab.image','add_lab.status','add_lab.created','add_district.name as district_name')->orderBy('add_lab.lab_id', 'desc')->get();

        if($data->isEmpty()){
            return response()->json(['success' => false,'message'=>'no data found'], 200);
        }
        else
        {
            foreach($data as $dt)
            $dt['lab_id']=$dt->lab_id;
            $dt['district_id']=$dt->district_id;
            if($dt->district_id == "")
            {
                $dt['district_id']="N/A";
            }
            $dt['district_name']=$dt->district_name;
            if($dt->district_name == "")
            {
                $dt['district_name']="N/A";
            }
            $dt['image']=$dt->image;
            return response()->json(['success' => true,'data'=>$data], 200);
        } 
    }

    public function get_specimen(Request $request)
    {
        $data = add_specimen::orderBy('id', 'desc')->get();

        if($data->isEmpty()){
            return response()->json(['success' => false,'message'=>'no data found'], 200);
        }
        else
        {
            return response()->json(['success' => true,'data'=>$data], 200);
        } 
    }

    public function get_test(Request $request)
    {
        $data = add_test::orderBy('id', 'desc')->get();

        if($data->isEmpty()){
            return response()->json(['success' => false,'message'=>'no data found'], 200);
        }
        else
        {
            return response()->json(['success' => true,'data'=>$data], 200);
        } 
    }

    public function get_sample(Request $request)
    {
        $i = 1;
        for($i = 1; $i<=20; $i++)
        {
            $data = $i;

            $ins1['sample_id']=$refer_code;
            
        }
        return response()->json(['success' => true,'data'=>$ins1], 200);
    }

    public function check_status(Request $request)
    {
        $data = User::where('id',auth()->user()->id)->first();

        return response()->json(['success' => true,'status'=>$data->status], 200);
    }
    
}
