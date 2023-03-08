<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\admin;
use Carbon\Carbon;

class Logincontroller extends Controller
{
    public function login()
    {
        return view('Admin.Auth.login');
    }

    public function submit(Request $request)
    {
        $request -> validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if(auth('admin')->attempt(['email' => $request->email, 'password' => $request->password], $request->remember)){
            Toastr::info('Login Successfully');
            return redirect()->route('panel.dashboard');
        }
        Toastr::error('Email Id or Password is Incorrect');
        return redirect()->back();
    }

    public function logout(Request $request)
    {
        auth()->guard('admin')->logout();
        $request->session()->invalidate();
        Toastr::info('Success! Logged Out');
        return redirect()->route('panel.auth.login');
    }
}
