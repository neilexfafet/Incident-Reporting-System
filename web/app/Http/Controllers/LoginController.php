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

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:admin')->except('logout');
        $this->middleware('guest:station')->except('logout');
        $this->middleware('guest:user')->except('logout');
    }

    // ======================================= PNPCDO ================================================
    public function LoginForm() {
        if(count(Admin::all()) == 0 || count(Officer::all()) == 0) {
            return view('setup');
        } else {
            return view('login');
        }
    }

    public function Login(Request $request) {
        $this->validate($request, [
            'username' => 'required',
            'password' => 'required|min:6',
            'id_no' => 'required|exists:officers,id_no',
        ]);

        $officer = Officer::all()->where('id_no', $request->input('id_no'))->first();

        if (Auth::guard('admin')->attempt(['username' => $request->username, 'password' => $request->password, 'is_active'=>'1'], $request->get('remember'))) {
            if($officer->status == "admin") {
                $add = new Account_log;
                $add->activity = "Login Admin";
                $add->account_type = Admin::class;
                $add->account_id = Auth::guard('admin')->user()->id;
                $add->officer_id = $officer->id;
                $add->save();
                return response()->json(['adminurl'=>'/admin/dashboard', 'officername'=>$officer->getRank->abbreviation.' '.$officer->first_name.' '.$officer->last_name]);
            } else {
                Auth::guard('admin')->logout();
                return response()->json(['errorstatus'=>'Officer is not Authorized to access this account.']);
            }
        } 
        if (Auth::guard('station')->attempt(['username' => $request->username, 'password' => $request->password, 'is_active'=>'1'], $request->get('remember'))) {
            if($officer->station_id == Auth::guard('station')->user()->id || $officer->status == "admin") {
                $add = new Account_log;
                $add->activity = "Login Station";
                $add->account_type = Station::class;
                $add->account_id = Auth::guard('station')->user()->id;
                $add->officer_id = $officer->id;
                $add->save();
                return response()->json(['stationurl'=>'/station/dashboard', 'officername'=>$officer->getRank->abbreviation.' '.$officer->first_name.' '.$officer->last_name]);
            } else {
                Auth::guard('station')->logout();
                return response()->json(['erroridno'=>'Officer is not Assigned to this Station']);
            }
        }
        return response()->json(['errorform'=>'Username or Password is Invalid! Please Try Again.']);
    }
    

    // =================================================================================================

    public function userLoginForm() {
        return view('userlogin');
    }

    public function userLogin(Request $request) {
        $this->validate($request, [
            'email' => 'required',
            'password' => 'required|min:6',
            ]);

        if(Auth::guard('user')->attempt(['email'=>$request->email, 'password'=>$request->password], $request->get('remember'))) {
            return response()->json(['url'=>'/user/dashboard']);
        }
        return response()->json(['error'=>'Email or Password is invalid. Please try again.']);
    }

    // ========================================================================================================

    public function logout(Request $request) {
        if(Auth::guard('admin')->user()) {
            $add = new Account_log;
            $add->activity = "Logout Admin";
            $add->account_type = Admin::class;
            $add->account_id = Auth::guard('admin')->user()->id;
            $add->save();
            Auth::guard('admin')->logout();
            return redirect()->intended('PNPCDO');
        }
        if(Auth::guard('station')->user()) {
            $add = new Account_log;
            $add->activity = "Logout Station";
            $add->account_type = Station::class;
            $add->account_id = Auth::guard('station')->user()->id;
            $add->save();
            Auth::guard('station')->logout();
            return redirect()->intended('PNPCDO');
        }
        if(Auth::guard('user')->user()) {
            Auth::guard('user')->logout();
            return redirect()->intended('/');
        }
    }
}
