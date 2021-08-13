<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\MessageBag;
use Carbon\Carbon;
use App\User;
use App\Admin;
use App\Station;
use App\Officer;
use App\Account_log;
use Auth;

class RegisterController extends Controller
{
    // ============================================= USER ======================================

    public function userRegister(Request $request) {
        $this->validate($request, [
            'email' => 'required',
            'password' => 'required|min:6',
            ]);
            
        if(User::all()->where('email', $request->input('email'))->first()) {
            return response()->json(['error'=>'Email Already Exists! Please Try Again.']);
        }
        if(Carbon::parse($request->input('birthday'))->age < 18) {
            return response()->json(['errorbday'=>'Age Must be 16+']);
        }
        $reg = new User;
        $reg->first_name = $request->input('first_name');
        $reg->middle_name = $request->input('middle_name');
        $reg->last_name = $request->input('last_name');
        $reg->contact_no = $request->input('contact_no');
        $reg->birthday = $request->input('birthday');
        $reg->gender = $request->input('gender');
        $reg->address = $request->input('address');
        if ($request->hasFile('image')) {
            $ran = rand(000,99999);
            $image = $request->file('image');
            $image_name = date('dmy_H_s_i');
            $ext = $image->getClientOriginalExtension();
            $image_full_name = $image_name.'_'.$ran.'.'.$ext;
            $request->file('image')->move('uploads', $image_full_name);
			$reg->image = 'uploads'.$image_full_name;
        }
        if ($request->hasFile('valid_id_image')) {
            $ran2 = rand(000,99999);
            $image2 = $request->file('valid_id_image');
            $image_name2 = date('dmy_H_s_i');
            $ext2 = $image2->getClientOriginalExtension();
            $image_full_name2 = $image_name2.'_'.$ran2.'.'.$ext2;
            $request->file('valid_id_image')->move('uploads', $image_full_name2);
			$reg->valid_id_image = 'uploads'.$image_full_name2;
        }
        $reg->email = $request->input('email');
        $reg->password = md5($request->password);
        $reg->save();
        return response()->json(['success'=>'Registered Successfully']);
    }

    // =========================================== END USER =========================================

    // ======================================== REGISTER OFFICER ====================================

    function registerofficer(Request $request) {
        if (Officer::all()->where('id_no', $request->input('id_no'))->first() != null || Officer::all()->where('badge_no', $request->input('badge_no'))->first() != null) {
            return response()->json(['error'=>'Officer Already Exists! Please Try Again.']);
        }
        if(Carbon::parse($request->input('birthday'))->age < 18) {
            return response()->json(['errorbday'=>'Age Must be 18+']);
        }
        $add = new Officer;
        $add->first_name = $request->input('first_name');
        $add->middle_name = $request->input('middle_name');
        $add->last_name = $request->input('last_name');
        $add->rank_id = $request->input('rank_id');
        $add->id_no = $request->input('id_no');
        $add->badge_no = $request->input('badge_no');
        $add->email = $request->input('email');
        $add->gender = $request->input('gender');
        $add->address = $request->input('address');
        $add->contact_no = $request->input('contact_no');
        $add->birthday = $request->input('birthday');
        $add->status = "pending";
        $add->is_active = "0";
        if ($request->hasFile('image')) {
            $ran = rand(000,99999);
            $image = $request->file('image');
            $image_name = date('dmy_H_s_i');
            $ext = $image->getClientOriginalExtension();
            $image_full_name = $image_name.'_'.$ran.'.'.$ext;
            $add->image = $request->file('image')->move('uploads', $image_full_name);
        }
        $add->save();
        return response()->json(['success'=>'Officer registered. Please contact Administration to verify your registration.']);
    }

    // ====================================== END REGISTER OFFICER =====================================

    // ============================================ SETUP =======================================

    function setupadmin(Request $request) {
        $add = new Admin;
        $add->username = $request->input('username');
        $add->admin_name = $request->input('admin_name');
        $add->admin_contactno = $request->input('admin_contactno');
        $add->admin_location = $request->input('admin_location');
        $add->location_lat = $request->input('location_lat');
        $add->location_lng = $request->input('location_lng');
        if($request->hasFile('image')) {
            $ran = rand(000,99999);
            $image = $request->file('image');
            $image_name = date('dmy_H_s_i');
            $ext = $image->getClientOriginalExtension();
            $image_full_name = $image_name.'_'.$ran.'.'.$ext;
            $add->image = $request->file('image')->move('uploads', $image_full_name);
        }
        $add->password = Hash::make($request->password);
        $add->save();
        return redirect()->intended('/PNPCDO')->with('successadmin', 'Admin Successfully Created!');
    }

    function setupofficer(Request $request) {
        if(Carbon::parse($request->input('birthday'))->age < 18) {
            return redirect()->back()->with('errorage', 'Must be 18+ of Age')->withInput();
        }
        $add = new Officer;
        $add->first_name = $request->input('first_name');
        $add->middle_name = $request->input('middle_name');
        $add->last_name = $request->input('last_name');
        $add->rank_id = $request->input('rank_id');
        $add->id_no = $request->input('id_no');
        $add->badge_no = $request->input('badge_no');
        $add->email = $request->input('email');
        $add->gender = $request->input('gender');
        $add->address = $request->input('address');
        $add->contact_no = $request->input('contact_no');
        $add->birthday = $request->input('birthday');
        $add->status = "admin";
        if ($request->hasFile('image')) {
            $ran = rand(000,99999);
            $image = $request->file('image');
            $image_name = date('dmy_H_s_i');
            $ext = $image->getClientOriginalExtension();
            $image_full_name = $image_name.'_'.$ran.'.'.$ext;
            $add->image = $request->file('image')->move('uploads', $image_full_name);
        } 
        $add->save();
        return redirect()->intended('/PNPCDO');
    }
    
    // ============================================ SETUP =======================================
}
