<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Rules\MatchOldPassword;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use DataTables;
use App\User;
use App\Admin;
use App\Station;
use App\Officer;
use App\News;
use App\Announcement;
use App\Notification;
use App\Incident;
use App\Report;
use App\Report_log;
use App\Dispatch;
use App\Account_log;
use App\Evidence;

class UserController extends Controller
{
    function dashboard() {
        $data = News::orderBy('created_at', 'desc')->where('is_active', '1')->paginate(5);
        $news = News::all()->where('is_active', '1')->count();
        return view('users.dashboard')->with('data', $data)->with('news', $news);
    }

    function stations() {
        $data = Station::all();
        return view('users.stations')->with('data', $data);
    }

    /* ================================= PROFILE ======================================== */

    function profile() {
        return view('users.profile');
    }

    function updateemail(Request $request, $id) {
        if (User::all()->where('email', $request->input('email'))->first()) {
            return response()->json(['error'=>'Email already Exists!']);
        }
        $update = User::find($id);
        if(Hash::check($request->input('confirmpassword'), $update->password)) {
            $update->email = $request->input('email');
            $update->save();
            return response()->json(['success'=>'Email Saved!']);
        } else {
            return response()->json(['errorpw'=>'Password Invalid! Please Try Again.']);
        }
    }

    function updateprofile(Request $request, $id) {
        $update = User::find($id);
        if(Carbon::parse($request->input('birthday'))->age < 18) {
            return response()->json(['errorbday'=>'Age must be 16+']);
        }
        $update->first_name = $request->input('first_name');
        $update->middle_name = $request->input('middle_name');
        $update->last_name = $request->input('last_name');
        $update->contact_no = $request->input('contact_no');
        $update->birthday = $request->input('birthday');
        $update->gender = $request->input('gender');
        $update->address = $request->input('address');

        if ($request->hasFile('image')) {
            $ran = rand(000,99999);
            $image = $request->file('image');
            $image_name = date('dmy_H_s_i');
            $ext = $image->getClientOriginalExtension();
            $image_full_name = $image_name.'_'.$ran.'.'.$ext;
            $request->file('image')->move('uploads', $image_full_name);
			$update->image = 'uploads/'.$image_full_name;
        }
        if ($request->hasFile('valid_id_image')) {
            $ran2 = rand(000,99999);
            $image2 = $request->file('valid_id_image');
            $image_name2 = date('dmy_H_s_i');
            $ext2 = $image2->getClientOriginalExtension();
            $image_full_name2 = $image_name2.'_'.$ran2.'.'.$ext2;
            $request->file('valid_id_image')->move('uploads', $image_full_name2);
			$update->valid_id_image = 'uploads/'.$image_full_name2;
        }
        $update->save();
        return response()->json(['success'=>'Details Updated Successfully']);
    }

    function updatepassword(Request $request, $id) {
        $this->validate($request, [
            'new_password' => 'required|min:6',
            ]);
        
        $update = User::find($id);
        if(Hash::check($request->input('current_password'), $update->password)) {
            $update->password = Hash::make($request->input('new_password'));
            $update->save();
            return response()->json(['success'=>'Password Successfully Changed!']);
        } else {
            return response()->json(['error'=>'Current Password Invalid! Please Try Again.']);
        }
    }

    /* ================================= END PROFILE ======================================== */


    /* =============================== FILE INCIDENT =========================== */

    function incidenttype() {
        $data = Incident::all()->where('is_active', '1');
        foreach($data as $row) {
            $view[] = '<option value='.$row->id.'>'.$row->type.'</option>';
        }
        return response()->json($view);
    }

    function incidenttypedesc($id) {
        $view = Incident::find($id);
        return response()->json($view);
    }

    function reportincident(Request $request) {
        $lat = $request->input('location_lat');
        $lng =  $request->input('location_lng');
        
        $station = Station::select(DB::raw('*, ( 6367 * acos( cos( radians('.$lat.') ) * cos( radians( location_lat ) ) * cos( radians( location_lng ) - radians('.$lng.') ) + sin( radians('.$lat.') ) * sin( radians( location_lat ) ) ) ) AS distance'))
                ->having('distance', '<', 10)
                ->orderBy('distance')
                ->where('is_active', '1')
                ->first();
        
        if($request->input('incident_date') > Carbon::now()->tomorrow()) {
            return response()->json(['errordate'=>'errordate']);
        } else {
            if($station != null) {
                $add = new Report;
                $add->incident_id = $request->input('incident_id');
                $add->description = $request->input('description');
                $add->incident_date = $request->input('incident_date');
                $add->location = $request->input('location');
                $add->location_lat = $request->input('location_lat');
                $add->location_lng = $request->input('location_lng');
                $add->reporter_id = Auth::guard('user')->user()->id;
                $add->station_id = $station->id;
                $add->save();
                if($request->hasFile('file_evidence')) {
                    foreach($request->file('file_evidence') as $multiple) {
                        $ran = rand(000,99999);
                        $image = $multiple;
                        $image_name = date('dmy_H_s_i');
                        $ext = $image->getClientOriginalExtension();
                        if($ext == 'mp4' || $ext == 'mkv' || $ext == '3gp' || $ext == 'ts' || $ext == 'avi' || $ext == 'mov' || $ext == 'webm') {
                            $image_full_name = $image_name.'_'.$ran.'.'.$ext;
                            $multiple->move('evidence', $image_full_name);
                            $image_file_name = 'evidence/'.$image_full_name;

                            $file = new Evidence;
                            $file->report_id = $add->id;
                            $file->filename = $image_file_name;
                            $file->filetype = "video";
                            $file->save();
                        } else {
                            $image_full_name = $image_name.'_'.$ran.'.'.$ext;
                            $multiple->move('evidence', $image_full_name);
                            $image_file_name = 'evidence/'.$image_full_name;

                            $file = new Evidence;
                            $file->report_id = $add->id;
                            $file->filename = $image_file_name;
                            $file->filetype = "image";
                            $file->save();
                        }
                    }
                }

                $notif = new Notification;
                $notif->type = "Report";
                $notif->notif_type = Report::class;
                $notif->notif_id = $add->id;
                $notif->sendto_type = Station::class;
                $notif->sendto_id = $station->id;
                $notif->save();

                $log = new Report_log;
                $log->activity = "pending";
                $log->report_id = $add->id;
                $log->save();

                return response()->json(['success'=>'Successfully Reported to Nearest Police Station.']);
            } else {
                return response()->json(['error'=>'error']);
            }
        }
    }
    
    /* =============================== END FILE INCIDENT =========================== */

    /* ================================= NOTIFICATIONS =========================== */

    function notifications() {
        $getType = User::class;
        $getId = Auth::guard('user')->user()->id;
        $data = Notification::with('notif', 'sendto')->orderBy('created_at', 'desc')
            ->where('sendto_type', $getType)
            ->where('sendto_id', $getId)
            ->get();
        return view('users.notifications')->with('data', $data);
    }

    function notificationheader() {
        $getType = User::class;
        $getId = Auth::guard('user')->user()->id;
        $data = Notification::orderBy('created_at', 'desc')
                ->where('sendto_type', $getType)
                ->where('sendto_id', $getId)
                ->take(5)
                ->get();
        if(count($data) == 0) {
            $view2 = '<li class="text-center">
                        No Notifications Yet...
                    </li>';
            return response()->json($view2);
        } else {
            foreach($data as $row) {
                if($row->type == "Report Feedback") {
                    $view[] = '<li>
                        <a href="javascript:void(0);" style="pointer-events: none;">
                            <i class="mdi mdi-check"></i>Report Responded
                            <span class="font-size-12 d-inline-block float-right">'.$row->created_at->diffForHumans().' <i class="mdi mdi-clock-outline"></i></span>
                        </a>
                    </li>';
                } else {
                    $view[] = '<li>
                        <a href="javascript:void(0);" data-id='.$row->notif_id.' data-toggle="modal" data-target="#notification-announcement-modal">
                            <i class="mdi mdi-alert-outline"></i>'.$row->type.'
                            <span class="font-size-12 d-inline-block float-right">'.$row->created_at->diffForHumans().' <i class="mdi mdi-clock-outline"></i></span>
                        </a>
                    </li>';
                }
            }
            return response()->json($view);
        }
    }

    function notificationcount() {
        $getType = User::class;
        $getId = Auth::guard('user')->user()->id;
        $count = Notification::all()
            ->where('sendto_type', $getType)
            ->where('sendto_id', $getId)
            ->where('status', 'unread')
            ->count();
        return response()->json($count);
    }

    function notificationread() {
        $getType = User::class;
        $getId = Auth::guard('user')->user()->id;
        $data = Notification::all()
                ->where('sendto_type', $getType)
                ->where('sendto_id', $getId)
                ->where('status', 'unread');
        foreach($data as $row) {
            $update = Notification::find($row->id);
            $update->status = "read";
            $update->save();
        }
    }

    function notificationsidebar() {
        $getType = User::class;
        $getId = Auth::guard('user')->user()->id;
        $data = Notification::with('notif')
                ->where('sendto_type', $getType)
                ->where('sendto_id', $getId)
                ->where('status', '!=', 'deleted')
                ->orderBy('created_at', 'desc')
                ->get();
        if(count($data) == 0) {
            $view2 = '<span class="right-sidebar-2-subtitle">No Notifications Yet</span>';
            return response()->json(['view2'=>$view2]);
        } else {
            foreach($data as $row) {
                if($row->type == "Announcement") {
                    if($row->notif->from_type == Station::class) {
                        $view[] = '<a href="javascript:void(0);" data-id='.$row->notif_id.' data-toggle="modal" data-target="#notification-announcement-modal">
                                        <div class="media align-items-center pb-2 d-flex justify-content-between">
                                            <div class="d-flex rounded-circle align-items-center justify-content-center mr-4 media-icon iconbox-45 bg-info text-white">
                                                <i class="mdi mdi-chat-alert font-size-20"></i>
                                            </div>
                                            <div class="media-body">
                                                <div class="d-flex justify-content-between">
                                                    <div>
                                                        <span class="right-sidebar-2-subtitle" style="font-size: 14px">Announcement from '.$row->notif->from->station_name.'</span>
                                                        <span class="font-size-12 d-inline-block"><i class="mdi mdi-clock-outline"></i> '.$row->created_at->diffForHumans().'</span>
                                                    </div>
                                                    <div class="d-flex align-items-center justify-content-center">
                                                        <div class="dropdown show d-inline-block widget-dropdown">
                                                            <a class="dropdown-toggle icon-burger-mini" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-display="static"></a>
                                                            <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown-notification">
                                                                <a href="javascript:void(0);"><li class="dropdown-item">Remove Notification</li></a>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>';
                    } else {
                        $view[] = '<a href="javascript:void(0);" data-id='.$row->notif_id.' data-toggle="modal" data-target="#notification-announcement-modal">
                                        <div class="media align-items-center pb-2 justify-content-between">
                                            <div class="d-flex rounded-circle align-items-center justify-content-center mr-4 media-icon iconbox-45 bg-info text-white">
                                                <i class="mdi mdi-chat-alert font-size-20"></i>
                                            </div>
                                            <div class="media-body">
                                                <div class="d-flex justify-content-between">
                                                    <div>
                                                        <span class="right-sidebar-2-subtitle" style="font-size: 14px">Announcement from '.$row->notif->from->admin_name.'</span>
                                                        <span class="font-size-12 d-inline-block"><i class="mdi mdi-clock-outline"></i> '.$row->created_at->diffForHumans().'</span>
                                                    </div>
                                                    <div class="d-flex align-items-center justify-content-center">
                                                        <div class="dropdown show d-inline-block widget-dropdown">
                                                            <a class="dropdown-toggle icon-burger-mini" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-display="static"></a>
                                                            <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown-notification">
                                                                <a href="javascript:void(0);"><li class="dropdown-item">Remove Notification</li></a>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>';
                    }
                } else {
                    $view[] = '<a href="javascript:void(0);" style="pointer-events: none;">
                                    <div class="media align-items-center pb-2 justify-content-between">
                                        <div class="d-flex rounded-circle align-items-center justify-content-center mr-4 media-icon iconbox-45 bg-success text-white">
                                            <i class="mdi mdi-check font-size-20"></i>
                                        </div>
                                        <div class="media-body">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <span class="right-sidebar-2-subtitle" style="font-size: 14px">Your Report about '.$row->notif->getIncident->type.' has been Responded by '.$row->notif->getStation->station_name.'</span>
                                                    <span class="font-size-12 d-inline-block"><i class="mdi mdi-clock-outline"></i> '.$row->created_at->diffForHumans().'</span>
                                                </div>
                                                <div class="d-flex align-items-center justify-content-center">
                                                    <div class="dropdown show d-inline-block widget-dropdown">
                                                        <a class="dropdown-toggle icon-burger-mini" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-display="static"></a>
                                                        <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown-notification">
                                                            <a href="javascript:void(0);"><li class="dropdown-item">Remove Notification</li></a>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>';
                }
            }
            return response()->json(['view'=>$view]);
        }
    }

    function notificationviewannouncement($id) {
        $view = Announcement::with('from')->find($id);
        return response()->json($view);
    }

    /* ================================= END NOTIFICATIONS =========================== */
}
