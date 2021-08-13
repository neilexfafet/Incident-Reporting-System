<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use File;
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

class AdminController extends Controller
{
    function dashboard() {
        $officers = Officer::all()->where('is_active', '1')->where('status', '!=', 'pending')->count();
        $reports = Report::all()->count();
        $userscount = User::all()->count();
        $stationcount = Station::all()->where('is_active', '1')->count();
        $officer = Officer::with('getStation')->get();
        $users = User::orderBy('created_at', 'desc')->get();
        $newusers = User::all()
            ->where('created_at', '>=', Carbon::now()->subWeeks(1))
            ->where('created_at', '<=', Carbon::now())
            ->count();
        return view('PNPadmin.dashboard')
            ->with('officers', $officers)
            ->with('officer', $officer)
            ->with('userscount', $userscount)
            ->with('stationcount', $stationcount)
            ->with('users', $users)
            ->with('newusers', $newusers)
            ->with('reports', $reports);
    }

    function dashboardreports() {
        $all = Report::all()->count();
        $responded = Report::all()->where('status', 'responded')->count();
        $verified = Report::all()->where('status', 'verified')->count();
        $bogus = Report::all()->where('status', 'bogus')->count();
        $reports = Report_log::with('getReport', 'getDispatch')->orderBy('created_at', 'desc')->get();

        if(count($reports) != 0) {
            foreach($reports as $report) {
                if($report->activity == "pending") {
                    $view[] = '<div class="media py-3 align-items-center justify-content-between">
                                    <div class="d-flex rounded-circle align-items-center justify-content-center mr-3 media-icon iconbox-45 bg-warning text-white">
                                        <i class="mdi mdi-alert-outline font-size-20"></i>
                                    </div>
                                    <div class="media-body pr-3 ">
                                        <p class="text-dark font-weight-bold">'.$report->getReport->getUser->first_name.' Reported an Incident</p>
                                        <p>'.$report->getReport->getUser->first_name.' reported a/an '.$report->getReport->getIncident->type.' and was sent to '.$report->getReport->getStation->station_name.'.</p>
                                        <p style="font-size: 90%"><i class="mdi mdi-clock-outline"></i>&nbsp;'.$report->created_at->format('F j, Y').' at '.$report->created_at->format('h:i A').'</p>
                                    </div>
                                </div>';
                }
                else if($report->activity == "responded") {
                    $view[] = '<div class="media py-3 align-items-center justify-content-between">
                                    <div class="d-flex rounded-circle align-items-center justify-content-center mr-3 media-icon iconbox-45 bg-info text-white">
                                        <i class="mdi mdi-map-search-outline font-size-20"></i>
                                    </div>
                                    <div class="media-body pr-3 ">
                                        <p class="text-dark font-weight-bold">Responded by '.$report->getReport->getStation->station_name.'</p>
                                        <p>The Report that was on '.$report->getReport->location.' was responded by '.$report->getReport->getStation->station_name.' and dispatched officers at '.$report->updated_at->format('h:i A').', '.$report->updated_at->format('F j, Y').'.</p>
                                        <p style="font-size: 90%"><i class="mdi mdi-clock-outline"></i>&nbsp;'.$report->created_at->format('F j, Y').' at '.$report->created_at->format('h:i A').'</p>
                                    </div>
                                </div>';
                }
                else if($report->activity == "verified") {
                    $view[] = '<div class="media py-3 align-items-center justify-content-between">
                                    <div class="d-flex rounded-circle align-items-center justify-content-center mr-3 media-icon iconbox-45 bg-success text-white">
                                        <i class="mdi mdi-check-circle-outline font-size-20"></i>
                                    </div>
                                    <div class="media-body pr-3 ">
                                        <p class="text-dark font-weight-bold">A Report was verified by '.$report->getReport->getStation->station_name.'.</p>
                                        <p>'.$report->getReport->getUser->first_name.' Reported an Incident and it is confirmed by '.$report->getReport->getStation->station_name.' at '.$report->updated_at->format('h:i A').', '.$report->updated_at->format('F j, Y').'.</p>
                                        <p><i class="mdi mdi-key-variant"></i>&nbsp;<a href="javascript:void(0);" data-id='.$report->dispatch_id.' data-toggle="modal" data-target="#view-dispatch">'.$report->getDispatch->dispatch_id.'</a></p>
                                        <p style="font-size: 90%"><i class="mdi mdi-clock-outline"></i>&nbsp;'.$report->created_at->format('F j, Y').' at '.$report->created_at->format('h:i A').'</p>
                                    </div>
                                </div>';
                }
                else if($report->activity == "bogus") {
                    $view[] = '<div class="media py-3 align-items-center justify-content-between">
                                    <div class="d-flex rounded-circle align-items-center justify-content-center mr-3 media-icon iconbox-45 bg-danger text-white">
                                        <i class="mdi mdi-close-outline font-size-20"></i>
                                    </div>
                                    <div class="media-body pr-3 ">
                                        <p class="text-dark font-weight-bold">'.$report->getReport->getUser->first_name.' Reported a Fraud Information</p>
                                        <p>The Report was a Fraud confirmed by '.$report->getReport->getStation->station_name.' at '.$report->updated_at->format('h:i A').', '.$report->updated_at->format('F j, Y').'. Sanctions will be implemented to the Reporter.</p>
                                        <p><i class="mdi mdi-key-variant"></i>&nbsp;<a href="javascript:void(0);" data-id='.$report->dispatch_id.' data-toggle="modal" data-target="#view-dispatch">'.$report->getDispatch->dispatch_id.'</a></p>
                                        <p style="font-size: 90%"><i class="mdi mdi-clock-outline"></i>&nbsp;'.$report->created_at->format('F j, Y').' at '.$report->created_at->format('h:i A').'</p>
                                    </div>
                                </div>';
                }
            }
        } else {
            $view = '<div class="media py-3 align-items-center justify-content-between">
                            <div class="media-body text-center pr-3 ">
                                <p>No Reports Yet</p>
                            </div>
                        </div>';
        }
        return response()->json(['view'=>$view, 'all'=>$all, 'responded'=>$responded, 'verified'=>$verified, 'bogus'=>$bogus]);
    }

    // ========================================= NOTIFICATIONS ===================================

    function notifications() {
        $getType = Admin::class;
        $getId = Auth::guard('admin')->user()->id;
        $data = Notification::with('notif', 'sendto')->orderBy('created_at', 'desc')
            ->where('sendto_type', $getType)
            ->where('sendto_id', $getId)
            ->get();
        return view('PNPadmin.notifications')->with('data', $data);
    }

    function notificationheader() {
        $getType = Admin::class;
        $getId = Auth::guard('admin')->user()->id;
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
                $view[] = '<li>
                                <a href="javascript:void(0);" data-id='.$row->notif_id.' data-toggle="modal" data-target="#notification-announcement-modal">
                                    <i class="mdi mdi-alert-outline"></i>'.$row->type.'
                                    <span class="font-size-12 d-inline-block float-right">'.$row->created_at->diffForHumans().' <i class="mdi mdi-clock-outline"></i></span>
                                </a>
                            </li>';
                
            }
            return response()->json($view);
        }
        
    }

    function notificationcount() {
        $getType = Admin::class;
        $getId = Auth::guard('admin')->user()->id;
        $count = Notification::all()
            ->where('sendto_type', $getType)
            ->where('sendto_id', $getId)
            ->where('status', 'unread')
            ->count();
        return response()->json($count);
    }

    function notificationread() {
        $getType = Admin::class;
        $getId = Auth::guard('admin')->user()->id;
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
        $getType = Admin::class;
        $getId = Auth::guard('admin')->user()->id;
        $data = Notification::with('notif')
                ->where('sendto_type', $getType)
                ->where('sendto_id', $getId)
                ->orderBy('created_at', 'desc')
                ->get();
        if(count($data) == 0) {
            $view2 = '<span class="right-sidebar-2-subtitle">No Notifications</span>';
            return response()->json(['view2'=>$view2]);
        } else {
            foreach($data as $row) {
                if($row->type == "Announcement") {
                    if($row->notif->from_type == Station::class) {
                        $view[] = '<a href="javascript:void(0);" data-id='.$row->notif_id.' data-toggle="modal" data-target="#notification-announcement-modal">
                                        <div class="media align-items-center pb-2 justify-content-between">
                                            <div class="d-flex rounded-circle align-items-center justify-content-center mr-4 media-icon iconbox-45 bg-info text-white">
                                                <i class="mdi mdi-chat-alert font-size-20"></i>
                                            </div>
                                            <div class="media-body">
                                                <span class="right-sidebar-2-subtitle" style="font-size: 14px">Announcement from '.$row->notif->from->station_name.'</span>
                                                <span class="font-size-12 d-inline-block"><i class="mdi mdi-clock-outline"></i> '.$row->created_at->diffForHumans().'</span>
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
                                                <span class="right-sidebar-2-subtitle" style="font-size: 14px">Announcement from '.$row->notif->from->admin_name.'</span>
                                                <span class="font-size-12 d-inline-block"><i class="mdi mdi-clock-outline"></i> '.$row->created_at->diffForHumans().'</span>
                                            </div>
                                        </div>
                                    </a>';
                    }
                }
            }
            return response()->json(['view'=>$view]);
        }
    }

    function notificationviewannouncement($id) {
        $view = Announcement::with('from')->find($id);
        return response()->json($view);
    }

    // ======================================== END NOTIFICATIONS ================================

    // ========================================= PROFILE ===========================================

    function profile() {
        return view('PNPadmin.profile');
    }

    function updateusername(Request $request, $id) {
        if (Admin::all()->where('username', $request->input('username'))->first()) {
            return response()->json(['error'=>'Username already Exists!']);
        }
        if (Station::all()->where('username', $request->input('username'))->first()) {
            return response()->json(['error'=>'Username already Exists!']);
        }

        $update = Admin::find($id);
        if(Hash::check($request->input('confirmpassword'), $update->password)) {
            $update->username = $request->input('username');
            $update->save();
            return response()->json(['success'=>'Username Saved!']);
        } else {
            return response()->json(['errorpw'=>'Password Invalid! Please Try Again.']);
        }
        
    }

    function updateprofile(Request $request, $id) {
        $update = Admin::find($id);
        $update->admin_name = $request->input('admin_name');
        $update->admin_contactno = $request->input('admin_contactno');
        $update->admin_location = $request->input('admin_location');
        $update->location_lat = $request->input('location_lat');
        $update->location_lng = $request->input('location_lng');
        if ($request->hasFile('image')) {
            File::delete($update->image);
            $ran = rand(000,99999);
            $image = $request->file('image');
            $image_name = date('dmy_H_s_i');
            $ext = $image->getClientOriginalExtension();
            $image_full_name = $image_name.'_'.$ran.'.'.$ext;
            $update->image = $request->file('image')->move('uploads', $image_full_name);
        }
        $update->save();
        return response()->json(['success'=>'Details Updated Successfully']);
    }

    function updatepassword(Request $request, $id) {
        $update = Admin::find($id);
        if(Hash::check($request->input('current_password'), $update->password)) {
            $update->password = Hash::make($request->input('new_password'));
            $update->save();
            return response()->json(['success'=>'Password Successfully Changed!']);
        } else {
            return response()->json(['error'=>'Current Password Invalid! Please Try Again.']);
        }
    }

    // ====================================== END PROFILE ============================================


    // ====================================== USER ===============================================

    function users() {
        $data = Admin::all()->where('is_active', '1');
        $officer = Officer::with('getRank')
            ->where('status', 'admin')
            ->whereNull('station_id')
            ->where('is_active', '1')
            ->get();
        return view('PNPadmin.users')->with('data', $data)->with('officer', $officer);
    }

    function addadmin(Request $request) {
        if (Admin::all()->where('username', $request->input('username'))->first()) {
            return redirect()->intended('admin/users')->with('error', 'Username already Exists! Please Try Again.');
        }
        if (Station::all()->where('username', $request->input('username'))->first()) {
            return redirect()->intended('admin/users')->with('error', 'Username already Exists! Please Try Again.');
        }
        $add = new Admin;
        $add->username = $request->input('username');
        $add->admin_name = $request->input('admin_name');
        $add->admin_contactno = $request->input('admin_contactno');
        $add->admin_location = $request->input('admin_location');
        $add->location_lat = $request->input('location_lat');
        $add->location_lng = $request->input('location_lng');
        $add->password = Hash::make($request->password);
        $add->save();
        return redirect()->intended('admin/users')->with('success', 'Admin Successfully Created!');
    }

    function unauthorizeofficer(Request $request, $id) {
        if(Hash::check($request->input('password'), Auth::guard('admin')->user()->password)) {
            $update = Officer::find($id);
            $update->status = "unassigned";
            $update->save();
            return response()->json(['success'=>'Police Officer Unauthorized!']);
        } else {
            return response()->json(['error'=>'Password Invalid.']);
        }
    }

    function removeadmin(Request $request) {
        $remove = Admin::find($request->input('id'));
        $remove->is_active = '0';
        $remove->save();
        return redirect()->intended('admin/users');
    }

    // ===================================== END USER  ============================================


    // =================================== NEWS AND ANNOUNCEMENTS ==================================

    /* NEWS */

    function news() {
        if(request()->ajax()) {
            $data = News::all()->where('is_active', '1');
            return datatables()->of($data)
                ->addColumn('action', function($row) {
                    $btn = '<div class="btn-group">
                                <button type="button" class="mb-1 btn btn-primary btn-sm" data-id='.$row->id.' data-toggle="modal" data-target="#view-news" title="View">
                                    <span class="mdi mdi-eye">&nbsp;View</span>
                                </button>
                                <button type="button" class="mb-1 btn btn-warning btn-sm" data-id='.$row->id.' data-toggle="modal" data-target="#update-news" title="Update">
                                    <span class="mdi mdi-square-edit-outline">&nbsp;Edit</span>
                                </button>
                                <button type="button" class="mb-1 btn btn-danger btn-sm" data-id='.$row->id.' data-toggle="modal" data-target="#remove-news" title="Remove">
                                    <span class="mdi mdi-window-close">&nbsp;Remove</span>
                                </button>
                            </div>';

                    return $btn;
                })
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
        }
        return view('PNPadmin.news');
    }

    function viewnews($id) {
        $view = News::find($id);
        return response()->json($view);
    }

    function addnews(Request $request) {
        $add = new News;
        $add->title = $request->input('title');
        $add->author = $request->input('author');
        $add->content = $request->input('content');
        $add->source_link = $request->input('source_link');
        if ($request->hasFile('image')) {
            $ran = rand(000,99999);
            $image = $request->file('image');
            $image_name = date('dmy_H_s_i');
            $ext = $image->getClientOriginalExtension();
            $image_full_name = $image_name.'_'.$ran.'.'.$ext;
            $add->image = $request->file('image')->move('post', $image_full_name);
        }
        $add->save();
        return response()->json(['success'=>'News Added Succesfully!']);
    }

    function updatenews(Request $request, $id) {
        $update = News::find($id);
        $update->title = $request->input('title');
        $update->author = $request->input('author');
        $update->content = $request->input('content');
        $update->source_link = $request->input('source_link');
        if($request->hasFile('image')) {
            File::delete($update->image);
            $ran = rand(000,99999);
            $image = $request->file('image');
            $image_name = date('dmy_H_s_i');
            $ext = $image->getClientOriginalExtension();
            $image_full_name = $image_name.'_'.$ran.'.'.$ext;
            $update->image = $request->file('image')->move('post', $image_full_name);
        }
        $update->save();
        return response()->json(['success'=>'News updated Succesfully!']);
    }

    function removenews(Request $request, $id) {
        if(Hash::check($request->input('password'), Auth::guard('admin')->user()->password)) {
            $update = News::find($id);
            $update->is_active = "0";
            $update->save();
            return response()->json(['success'=>'Data Removed!']);
        } else {
            return response()->json(['error'=>'Password Invalid']);
        }
    }

    /* END NEWS */

    /* ANNOUNCEMENTS */

    function announcements() {
        if(request()->ajax()) {
            $data = Announcement::with('from')
                ->where('is_active', '1')
                ->get();
            return datatables()->of($data)
                ->addColumn('action', function($row) {
                    if($row->from_id == Auth::guard('admin')->user()->id && $row->from_type == Admin::class) {
                        $btn = '<div class="btn-group">
                            <button type="button" class="mb-1 btn btn-primary btn-sm" data-id='.$row->id.' data-toggle="modal" data-target="#view-announcement" title="View">
                                <span class="mdi mdi-eye">&nbsp;View</span>
                            </button>
                            <button type="button" class="mb-1 btn btn-warning btn-sm" data-id='.$row->id.' data-toggle="modal" data-target="#update-announcement" title="Update">
                                <span class="mdi mdi-square-edit-outline">&nbsp;Edit</span>
                            </button>
                            <button type="button" class="mb-1 btn btn-danger btn-sm" data-id='.$row->id.' data-toggle="modal" data-target="#remove-announcement" title="Remove">
                                <span class="mdi mdi-window-close">&nbsp;Remove</span>
                            </button>
                        </div>';
                    } else {
                        $btn = '<div class="btn-group">
                            <button type="button" class="mb-1 btn btn-primary btn-sm" data-id='.$row->id.' data-toggle="modal" data-target="#view-announcement" title="View">
                                <span class="mdi mdi-eye">&nbsp;View</span>
                            </button>
                        </div>';
                    }
                    return $btn;
                })
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
        }
        $station = Station::all();

        return view('PNPadmin.announcements')->with('station', $station);
    }

    function addannouncement(Request $request) {
        $stations = Station::all()->where('is_active', '1');
        $admins = Admin::all()->where('is_active', '1');
        $users = User::all();

        if($request->hasFile('image')) {
            $add = new Announcement;
                $ran = rand(000,99999);
                $image = $request->file('image');
                $image_name = date('dmy_H_s_i');
                $ext = $image->getClientOriginalExtension();
                $image_full_name = $image_name.'_'.$ran.'.'.$ext;
                $request->file('image')->move('announcements', $image_full_name);
				$add->image = 'announcements/'.$image_full_name;
            $add->subject = "TBD";
            $add->message = "TBD";
            $add->from_id = Auth::guard('admin')->user()->id;
            $add->from_type = Admin::class;
            $add->save();
        } else {
            $add = new Announcement;
            $add->subject = $request->input('subject');
            $add->message = $request->input('message');
            $add->from_id = Auth::guard('admin')->user()->id;
            $add->from_type = Admin::class;
            $add->save();
        }
        foreach($stations as $station) {
            $notif = new Notification;
            $notif->type = "Announcement";
            $notif->notif_type = Announcement::class;
            $notif->notif_id = $add->id;
            $notif->sendto_type = Station::class;
            $notif->sendto_id = $station->id;
            $notif->save();
        }
        foreach($admins as $admin) {
            if($admin->id != Auth::guard('admin')->user()->id) {
                $notif2 = new Notification;
                $notif2->type = "Announcement";
                $notif2->notif_type = Announcement::class;
                $notif2->notif_id = $add->id;
                $notif2->sendto_type = Admin::class;
                $notif2->sendto_id = $admin->id;
                $notif2->save();
            }
        }
        foreach($users as $user) {
            $notif3 = new Notification;
            $notif3->type = "Announcement";
            $notif3->notif_type = Announcement::class;
            $notif3->notif_id = $add->id;
            $notif3->sendto_type = User::class;
            $notif3->sendto_id = $user->id;
            $notif3->save();
        }
        return response()->json(['success'=>'Announcement Created Successfully!']);
    }

    function viewannouncement($id) {
        $view = Announcement::with('from')->find($id);
        return response()->json($view);
    }

    function updateannouncement(Request $request, $id) {
        $update = Announcement::find($id);
        if($request->hasFile('image')) {
            File::delete($update->image);
                $ran = rand(000,99999);
                $image = $request->file('image');
                $image_name = date('dmy_H_s_i');
                $ext = $image->getClientOriginalExtension();
                $image_full_name = $image_name.'_'.$ran.'.'.$ext;
                $update->image = $request->file('image')->move('announcements', $image_full_name);
            $update->save();
        } else {
            $update->subject = $request->input('subject');
            $update->message = $request->input('message');
            $update->save();
        }
        return response()->json(['success'=>'Announcement Updated Successfully!']);
    }

    function removeannouncement(Request $request, $id) {
        if(Hash::check($request->input('password'), Auth::guard('admin')->user()->password)) {
            $update = Announcement::find($id);
            $update->is_active = "0";
            $update->save();
            return response()->json(['success'=>'Data Removed!']);
        } else {
            return response()->json(['error'=>'Password Invalid']);
        }
    }

    /* END ANNOUNCEMENTS */

    // ============================= END NEWS AND ANNOUNCEMENTS ======================================

    
    // ================================ STATION ================================================ 
    
    function station() {
        if(request()->ajax()) {
            $data = Station::all()->where('is_active', '1');
            return datatables()->of($data)
                ->addColumn('officers', function($row) {
                    $count = count(Officer::all()->where('station_id', $row->id));
                    return $count;
                })
                ->addColumn('action', function($row) {
                    $btn = '<div class="btn-group" role="group" aria-label="Basic example">
                                <button type="button" class="mb-1 btn btn-primary btn-sm" data-id='.$row->id.' data-toggle="modal" data-target="#view" title="View">
                                    <span class="mdi mdi-eye">&nbsp;View</span>
                                </button>
                                <div class="dropdown d-inline-block mb-1" data-toggle="tooltip" data-placement="top" title="Update">
                                    <button class="btn btn-warning btn-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-display="static">
                                        <span class="mdi mdi-square-edit-outline">&nbsp;Edit</span>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item" href="javascript:void(0);"
                                            data-id='.$row->id.'
                                            data-toggle="modal" 
                                            data-target="#update">Update Station Details</a>
                                        <a class="dropdown-item" href="javascript:void(0);"
                                            data-id='.$row->id.'
                                            data-toggle="modal" 
                                            data-target="#update-username">Change Username</a>
                                        <a class="dropdown-item" href="javascript:void(0);"
                                            data-id='.$row->id.'
                                            data-toggle="modal" 
                                            data-target="#change-password">Reset Password</a>
                                    </div>
                                </div>
                                <button type="button" class="mb-1 btn btn-danger btn-sm" data-id='.$row->id.' data-toggle="modal" data-target="#remove-station" title="Remove">
                                    <span class="mdi mdi-close">&nbsp;Remove</span>
                                </button>
                            </div>';

                    return $btn;
                })
            ->rawColumns(['officers'])
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
        }
        return view('PNPadmin.stations');
    }

    function viewstation($id) {
        $view = Station::find($id);
        return response()->json($view);
    }

    function viewofficers($id) {
        $data = Officer::with('getRank')->where('station_id', $id)->orderBy('rank_id')->get();
        foreach($data as $key=>$row) {
            if($key == 0) {
                $view[] = '<li class="nav-item">
                                <a href=#officer_'.$row->id.' class="nav-link active" data-toggle="tab" aria-expanded="true">'.$row->getRank->abbreviation.' '.$row->first_name.' '.$row->last_name.'</a>
                            </li>';
            } else {
                $view[] = '<li class="nav-item">
                                <a href=#officer_'.$row->id.' class="nav-link" data-toggle="tab" aria-expanded="false">'.$row->getRank->abbreviation.' '.$row->first_name.' '.$row->last_name.'</a>
                            </li>';
            }
            if($key == 0) {
                if($row->image == "TBD") {
                    $view2[] = '<div class="tab-pane fade active show" id=officer_'.$row->id.' aria-expanded="true">
                                    <div class="card card-default p-4">
                                        <a href="javascript:void(0);" class="media text-secondary" style="pointer-events: none;">
                                            <img src='.url("uploads/user.jpg").' class="mr-3 img-fluid rounded" height="100px" alt="Avatar Image">
                                            <div class="media-body">
                                                <h4 class="mt-0 mb-2 text-dark">'.$row->first_name.' '.$row->last_name.'</h4>
                                                <ul class="list-unstyled">
                                                    <li class="d-flex mb-1"><i class="mdi mdi-seal mr-1"></i><span>'.$row->getRank->name.' ('.$row->getRank->abbreviation.')</span></li>
                                                    <li class="d-flex mb-1"><i class="mdi mdi-gender-male-female mr-1"></i><span>'.$row->gender.'</span></li>
                                                    <li class="d-flex mb-1"><i class="mdi mdi-phone mr-1"></i><span>'.$row->contact_no.'</span></li>
                                                    <li class="d-flex mb-1"><i class="mdi mdi-calendar mr-1"></i><span>'.date("F j, Y", strtotime($row->birthday)).'</span></li>
                                                    <li class="d-flex mb-1"><i class="mdi mdi-calendar-clock mr-1"></i><span>'.Carbon::parse($row->birthday)->age.' Years of age</span></li>
                                                    <li class="d-flex mb-1"><i class="mdi mdi-email-outline mr-1"></i><span>'.$row->email.'</span></li>
                                                </ul>
                                            </div>
                                        </a>
                                    </div>
                                </div>';
                } else {
                    $view2[] = '<div class="tab-pane fade" id=officer_'.$row->id.' aria-expanded="false">
                                    <div class="card card-default p-4">
                                        <a href="javascript:void(0);" class="media text-secondary" style="pointer-events: none;">
                                            <img src='.url($row->image).' class="img-fluid mr-3 " style="max-width: 150px;max-height: 150px;" alt="Avatar Image">
                                            <div class="media-body">
                                                <h4 class="mt-0 mb-2 text-dark">'.$row->first_name.' '.$row->last_name.'</h4>
                                                <ul class="list-unstyled">
                                                    <li class="d-flex mb-1"><i class="mdi mdi-seal mr-1"></i><span>'.$row->getRank->name.' ('.$row->getRank->abbreviation.')</span></li>
                                                    <li class="d-flex mb-1"><i class="mdi mdi-gender-male-female mr-1"></i><span>'.$row->gender.'</span></li>
                                                    <li class="d-flex mb-1"><i class="mdi mdi-phone mr-1"></i><span>'.$row->contact_no.'</span></li>
                                                    <li class="d-flex mb-1"><i class="mdi mdi-calendar mr-1"></i><span>'.date("F j, Y", strtotime($row->birthday)).'</span></li>
                                                    <li class="d-flex mb-1"><i class="mdi mdi-calendar-clock mr-1"></i><span>'.Carbon::parse($row->birthday)->age.' Years of age</span></li>
                                                    <li class="d-flex mb-1"><i class="mdi mdi-email-outline mr-1"></i><span>'.$row->email.'</span></li>
                                                </ul>
                                            </div>
                                        </a>
                                    </div>
                                </div>';
                }
            } else {
                if($row->image == "TBD") {
                    $view2[] = '<div class="tab-pane fade" id=officer_'.$row->id.' aria-expanded="false">
                                    <div class="card card-default p-4">
                                        <a href="javascript:void(0);" class="media text-secondary" style="pointer-events: none;">
                                            <img src='.url("uploads/user.jpg").' class="mr-3 img-fluid rounded" height="100px" alt="Avatar Image">
                                            <div class="media-body">
                                                <h4 class="mt-0 mb-2 text-dark">'.$row->first_name.' '.$row->last_name.'</h4>
                                                <ul class="list-unstyled">
                                                    <li class="d-flex mb-1"><i class="mdi mdi-seal mr-1"></i><span>'.$row->getRank->name.' ('.$row->getRank->abbreviation.')</span></li>
                                                    <li class="d-flex mb-1"><i class="mdi mdi-gender-male-female mr-1"></i><span>'.$row->gender.'</span></li>
                                                    <li class="d-flex mb-1"><i class="mdi mdi-phone mr-1"></i><span>'.$row->contact_no.'</span></li>
                                                    <li class="d-flex mb-1"><i class="mdi mdi-calendar mr-1"></i><span>'.date("F j, Y", strtotime($row->birthday)).'</span></li>
                                                    <li class="d-flex mb-1"><i class="mdi mdi-calendar-clock mr-1"></i><span>'.Carbon::parse($row->birthday)->age.' Years of age</span></li>
                                                    <li class="d-flex mb-1"><i class="mdi mdi-email-outline mr-1"></i><span>'.$row->email.'</span></li>
                                                </ul>
                                            </div>
                                        </a>
                                    </div>
                                </div>';
                } else {
                    $view2[] = '<div class="tab-pane fade" id=officer_'.$row->id.' aria-expanded="false">
                                    <div class="card card-default p-4">
                                        <a href="javascript:void(0);" class="media text-secondary" style="pointer-events: none;">
                                            <img src='.url($row->image).' class="img-fluid mr-3 " style="max-width: 150px;max-height: 150px;" alt="Avatar Image">
                                            <div class="media-body">
                                                <h4 class="mt-0 mb-2 text-dark">'.$row->first_name.' '.$row->last_name.'</h4>
                                                <ul class="list-unstyled">
                                                    <li class="d-flex mb-1"><i class="mdi mdi-seal mr-1"></i><span>'.$row->getRank->name.' ('.$row->getRank->abbreviation.')</span></li>
                                                    <li class="d-flex mb-1"><i class="mdi mdi-gender-male-female mr-1"></i><span>'.$row->gender.'</span></li>
                                                    <li class="d-flex mb-1"><i class="mdi mdi-phone mr-1"></i><span>'.$row->contact_no.'</span></li>
                                                    <li class="d-flex mb-1"><i class="mdi mdi-calendar mr-1"></i><span>'.date("F j, Y", strtotime($row->birthday)).'</span></li>
                                                    <li class="d-flex mb-1"><i class="mdi mdi-calendar-clock mr-1"></i><span>'.Carbon::parse($row->birthday)->age.' Years of age</span></li>
                                                    <li class="d-flex mb-1"><i class="mdi mdi-email-outline mr-1"></i><span>'.$row->email.'</span></li>
                                                </ul>
                                            </div>
                                        </a>
                                    </div>
                                </div>';
                }
            }
        }
        return response()->json(['tab'=>$view, 'tabcontent'=>$view2]);
    }

    function addstation(Request $request) {
        if (Station::all()->where('username', $request->input('username'))->first()) {
            return response()->json(['error'=>'Username Already Exists! Please Try Again.']);
        }
        if (Admin::all()->where('username', $request->input('username'))->first()) {
            return response()->json(['error'=>'Username Already Exists! Please Try Again.']);
        }
        $add = new Station;
        $add->username = $request->input('username');
        $add->station_name = $request->input('station_name');
        $add->station_contactno = $request->input('station_contactno');
        $add->location_name = $request->input('location_name');
        $add->location_lat = $request->input('location_lat');
        $add->location_lng = $request->input('location_lng');
        $add->password = Hash::make($request->password);
        if($request->hasFile('image')) {
            $ran = rand(000,99999);
            $image = $request->file('image');
            $image_name = date('dmy_H_s_i');
            $ext = $image->getClientOriginalExtension();
            $image_full_name = $image_name.'_'.$ran.'.'.$ext;
            $request->file('image')->move('uploads', $image_full_name);
			$add->image = 'uploads/'.$image_full_name;
        }
        $add->save();
        return response()->json(['success'=>'Station Created Succesfully!']);
    }

    function updatestation(Request $request, $id) {
        if (Admin::all()->where('username', $request->input('username'))->first()) {
            return response()->json(['error'=>'Username Already Exists! Please Try Again.']);
        }
        if (Station::all()->where('username', $request->input('username'))->first()) {
            return response()->json(['error'=>'Username Already Exists! Please Try Again.']);
        }
        
        $update = Station::find($id);
        if ($request->has('username')) {
            $update->username = $request->input('username');
        }
        if ($request->has('station_name')) {
            $update->station_name = $request->input('station_name');
        }
        if ($request->has('station_contactno')) {
            $update->station_contactno = $request->input('station_contactno');
        }
        if($request->has('location_name')) {
            $update->location_name = $request->input('location_name');
        }
        if($request->has('location_lat')) {
            $update->location_lat = $request->input('location_lat');
        }
        if($request->has('location_lng')) {
            $update->location_lng = $request->input('location_lng');
        }
        if($request->hasFile('image')) {
            File::delete($update->image);
            $ran = rand(000,99999);
            $image = $request->file('image');
            $image_name = date('dmy_H_s_i');
            $ext = $image->getClientOriginalExtension();
            $image_full_name = $image_name.'_'.$ran.'.'.$ext;
            $request->file('image')->move('uploads', $image_full_name);
			$update->image = 'uploads/'.$image_full_name;
        }
        $update->save();
        return response()->json(['success'=>'Station Successfully Updated!']);
    }

    function resetpasswordconfirm(Request $request) {
        if(Hash::check($request->input('password'), Auth::guard('admin')->user()->password)) {
            return response()->json(['success'=>'Confirmed']);
        } else {
            return response()->json(['error'=>'Password invalid.']);
        }
    }
    function resetpassword(Request $request, $id) {
        $this->validate($request, [
            'password' => 'required|min:6',
        ]);
        $update = Station::find($id);
        $update->password = Hash::make($request->input('password'));
        $update->save();
        return response()->json(['success'=>'Password Reset Successfully']);
    }

    function removestation(Request $request, $id) {
        if(Hash::check($request->input('password'), Auth::guard('admin')->user()->password)) {
            $remove = Station::find($id);
            $remove->is_active = "0";
            $remove->save();

            $officers = Officer::all()->where('station_id', $id);
            foreach($officers as $row) {
                $update = Officer::find($row->id);
                $update->status = "unassigned";
                $update->station_id = null;
                $update->save();
            }
            return response()->json(['success'=>'Data Removed!']);
        } else {
            return response()->json(['error'=>'Password Invalid']);
        }
    }

    // ======================================== END STATION ========================================


    // ===================================== OFFICER  ========================================================

    function officers() {
        if(request()->ajax()) {
            $data = Officer::with('getStation', 'getRank')
                ->where('is_active', '1')
                ->whereNotNull('station_id')
                ->get()->sortBy('last_name');
            return datatables()->of($data)
                ->addColumn('action', function($row){
                    $btn = '<div class="btn-group" role="group" aria-label="Basic example">
                                <button data-id='.$row->id.' data-toggle="modal" data-target="#view-assign" type="button" class="btn btn-primary btn-sm" title="View">
                                    <span class="mdi mdi-eye">&nbsp;View</span>
                                </button>
                                <div class="dropdown" data-toggle="tooltip" data-placement="top" title="Update">
                                    <button class="btn btn-warning btn-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-display="static">
                                        <span class="mdi mdi-square-edit-outline">&nbsp;Edit</span>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item" href="javascript:void(0);"
                                            data-id='.$row->id.'
                                            data-toggle="modal" 
                                            data-target="#update-officer">Update Officer</a>
                                        <a class="dropdown-item" href="javascript:void(0);"
                                            data-id='.$row->id.'
                                            data-toggle="modal" 
                                            data-target="#change-station">Change Station</a>
                                    </div>
                                </div>
                                <button data-id='.$row->id.' data-toggle="modal" data-target="#remove-officer" type="button" class="btn btn-danger btn-sm" title="Remove">
                                    <span class="mdi mdi-window-close">&nbsp;Remove</span>
                                </button>
                            </div>';
                    return $btn;
                })
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
        }

        $station = Station::all()->where('is_active', '1');
        
        return view('PNPadmin.officers')
            ->with('station', $station);
    }

    function unassignofficers() {
        if(request()->ajax()) {
            $data = Officer::with('getStation', 'getRank')
            ->where('is_active', '1')
            ->where('status', 'unassigned')
            ->whereNull('station_id')
            ->get();
            return datatables()->of($data)
                ->addColumn('action', function($row) {
                    $btn = '<div class="btn-group" role="group" aria-label="Basic example">
                                <button data-id='.$row->id.' data-toggle="modal" data-target="#view-assign" class="btn btn-primary btn-sm" title="View">
                                    <span class="mdi mdi-eye">&nbsp;View</span>
                                </button>
                                <div class="dropdown" data-toggle="tooltip" data-placement="top" title="Update">
                                    <button class="btn btn-warning btn-sm" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-display="static">
                                        <span class="mdi mdi-square-edit-outline">&nbsp;Edit</span>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item" href="javascript:void(0);"
                                            data-id='.$row->id.'
                                            data-toggle="modal" 
                                            data-target="#update-officer">Update Officer</a>
                                        <a class="dropdown-item" href="javascript:void(0);"
                                            data-id='.$row->id.'
                                            data-toggle="modal" 
                                            data-target="#change-station">Change Station</a>
                                        <a class="dropdown-item" href="javascript:void(0);"
                                                data-id='.$row->id.'
                                                data-toggle="modal" 
                                                data-target="#transfer-officer">Transfer to Administration</a>
                                    </div>
                                </div>
                                <button data-id='.$row->id.' data-toggle="modal" data-target="#remove-officer" type="button" class="btn btn-danger btn-sm" title="Remove">
                                    <span class="mdi mdi-window-close">&nbsp;Remove</span>
                                </button>
                            </div>';
                    return $btn;
                })
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
        }
    }

    function pendingofficers() {
        if(request()->ajax()) {
            $data = Officer::with('getStation', 'getRank')
            ->where('is_active', '0')
            ->where('status', 'pending')
            ->whereNull('station_id')
            ->get();
            return datatables()->of($data)
                ->addColumn('action', function($row) {
                    $btn = '<div class="btn-group" role="group" aria-label="Basic example">
                                <button data-id='.$row->id.' data-toggle="modal" data-target="#view-pending" class="btn btn-primary btn-sm" title="View">
                                    <span class="mdi mdi-eye">&nbsp;View Information</span>
                                </button>
                            </div>';
                    return $btn;
                })
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
        }
    }

    function unassigncount() {
        $count = Officer::all()
            ->where('is_active', '1')
            ->where('status', 'unassigned')
            ->whereNull('station_id')
            ->count();
            
        return response()->json($count);
    }

    function viewassign($id) {
        $view = Officer::with('getRank')->find($id);
        return response()->json($view);
    }
    
    function addofficer(Request $request) {
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
        if ($request->hasFile('image')) {
            $ran = rand(000,99999);
            $image = $request->file('image');
            $image_name = date('dmy_H_s_i');
            $ext = $image->getClientOriginalExtension();
            $image_full_name = $image_name.'_'.$ran.'.'.$ext;
            $add->image = $request->file('image')->move('uploads', $image_full_name);
        }
        $add->save();
        return response()->json(['success'=>'Officer Successfully Added!']);
    }

    function updateofficer(Request $request, $id) {
        $update = Officer::find($id);
        if(Carbon::parse($request->input('birthday'))->age < 18) {
            return response()->json(['errorbday'=>'Age Must be 18+']);
        }
        $update->first_name = $request->input('first_name');
        $update->middle_name = $request->input('middle_name');
        $update->last_name = $request->input('last_name');
        $update->rank_id = $request->input('rank_id');
        $update->email = $request->input('email');
        $update->birthday = $request->input('birthday');
        $update->gender = $request->input('gender');
        $update->address = $request->input('address');
        $update->contact_no = $request->input('contact_no');
        if ($request->hasFile('image')) {
            File::delete($update->image);
            $ran = rand(000,99999);
            $image = $request->file('image');
            $image_name = date('dmy_H_s_i');
            $ext = $image->getClientOriginalExtension();
            $image_full_name = $image_name.'_'.$ran.'.'.$ext;
            $update->image = $request->file('image')->move('uploads', $image_full_name);
        }
        $update->save();
        return response()->json(['success'=>'Officer Successfully Updated!']);
    }

    function assignstation(Request $request, $id) {
        $update = Officer::find($id);
        if($update->status == "dispatched") {
            return response()->json(['error'=>'Officer is still Dispatched.']);
        }
        if($request->input('station_id') == "unassign") {
            $update->status = "unassigned";
            $update->station_id = null;
        } else {
            $update->status = "available";
            $update->station_id = $request->input('station_id');
        }
        $update->save();
        return response()->json(['success'=>'Officer Successfully Updated!']);
    }

    function acceptofficer($id) {
        $update = Officer::find($id);
        $update->status = "unassigned";
        $update->is_active = "1";
        $update->save();
        return response()->json(['success'=>'Police Officer Successfully Accepted.']);
    }

    function denyofficer($id) {
        $delete = Officer::find($id);
        $delete->delete();
        return response()->json(['success'=>'Police Officer Denied.']);
    }

    function transferofficer(Request $request, $id) {
        if(Hash::check($request->input('password'), Auth::guard('admin')->user()->password)) {
            $update = Officer::find($id);
            $update->status = "admin";
            $update->save();
            return response()->json(['success'=>'Police Officer Authorized!']);
        } else {
            return response()->json(['error'=>'Password Invalid.']);
        }
    }

    function removeofficer(Request $request, $id) {
        if(Hash::check($request->input('password'), Auth::guard('admin')->user()->password)) {
            $update = Officer::find($id);
            if($update->status == "dispatched") {
                return response()->json(['error'=>'Officer is still Dispatched.']);
            }
            $update->is_active = "0";
            $update->status = "removed";
            $update->station_id = null;
            $update->save();
            return response()->json(['success'=>'Data Removed!']);
        } else {
            return response()->json(['errorpw'=>'Password Invalid']);
        }
    }

    // ====================================== END OFFICER ========================

    // ===================================== INCIDENTS / CRIMES ======================

    function incidents() {
        if(request()->ajax()) {
            $data = Incident::all()->where('is_active', '1');
            return datatables()->of($data)
                ->addColumn('action', function($row) {
                    $btn = '<div class="btn-group">
                                <button type="button" class="mb-1 btn btn-warning btn-sm" data-id='.$row->id.' data-toggle="modal" data-target="#update-incident" title="Edit">
                                    <span class="mdi mdi-square-edit-outline">&nbsp;Edit</span>
                                </button>
                                <button type="button" class="mb-1 btn btn-danger btn-sm" data-id='.$row->id.' data-toggle="modal" data-target="#remove-incident" title="Remove">
                                    <span class="mdi mdi-window-close">&nbsp;Remove</span>
                                </button>
                            </div>';

                    return $btn;
                })
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
        }
        return view('PNPadmin.incidents');
    }

    function incidentreports() {
        if(request()->ajax()) {
            $data = Report::with('getIncident', 'getUser', 'getStation', 'dispatch')
                    ->whereIn('status', array('verified', 'bogus'))
                    ->orderBy('created_at', 'desc')
                    ->get();
            return datatables()->of($data)->make(true);
        }
        return view('PNPadmin.incident-reports');
    }


    function addincident(Request $request) {
        if(Incident::all()->where('type', $request->input('type'))->first()) {
            return response()->json(['error'=>'Incident Already Exists. Check Archive.']);
        }
        $add = new Incident;
        $add->type = $request->input('type');
        $add->description = $request->input('description');
        $add->save();
        return response()->json(['success'=>'Incident Created Successfully.']);
    }

    function viewincident($id) {
        $view = Incident::find($id);
        return response()->json($view);
    }

    function updateincident(Request $request, $id) {
        $update = Incident::find($id);
        if(Incident::all()->where('type', $request->input('type'))->first()) {
            return response()->json(['error'=>'Incident Already Exists.']);
        }
        $update->type = $request->input('type');
        $update->description = $request->input('description');
        $update->save();
        return response()->json(['success'=>'Incident Updated Successfully.']);
    }

    function removeincident(Request $request, $id) {
        if(Hash::check($request->input('password'), Auth::guard('admin')->user()->password)) {
            $remove = Incident::find($id);
            $remove->is_active = "0";
            $remove->save();
            return response()->json(['success'=>'Data is Removed!']);
        } else {
            return response()->json(['error'=>'Password Invalid.']);
        }
    }

    function incidentsviewdispatch($id) {
        $find = Dispatch::with('getReport.getIncident', 'getReport.getUser', 'getStation')->find($id);
        $data = Dispatch::with('getOfficer')->where('dispatch_id', $find->dispatch_id)->get();
        $evidence = Evidence::where('report_id', $find->report_id)->orderBy('filetype')->get();
        foreach($data as $key=>$row) {
            $table[] = '<tr>
                            <td>'.$row->getOfficer->last_name.', '.$row->getOfficer->first_name.'</td>
                            <td>'.$row->getOfficer->rank.'</td>
                            <td>'.$row->getOfficer->id_no.'</td>
                            <td>'.$row->getOfficer->badge_no.'</td>
                            <td>'.$row->getOfficer->contact_no.'</td>
                        </tr>';
        }
        if(count($evidence) == 0) {
            $img = '<div class="col-sm-12 text-center">
                            <h3 class="py-6">No File Available</h3>
                        </div>';
        } else {
            foreach($evidence as $row) {
                if(count($evidence) == 1) {
                    if($row->filetype == "video") {
                        $img[] = '<div class="col-sm-12 text-center">
                                        <h4>Video File Type.</h4>
                                        <a href='.url($row->filename).' target="_blank">Click Here to Download Video File</a>
                                    </div>';
                    } else {
                        $img[] = '<div class="col-sm-12">
                                        <img src='.url($row->filename).' class="img-fluid">
                                    </div>';
                    }
                } else if(count($evidence) == 2) {
                    if($row->filetype == "video") {
                        $img[] = '<div class="col-sm-6 text-center">
                                        <h4>Video File Type.</h4>
                                        <a href='.url($row->filename).' target="_blank">Click Here to Download Video File</a>
                                    </div>';
                    } else {
                        $img[] = '<div class="col-sm-6">
                                        <img src='.url($row->filename).' class="img-fluid">
                                    </div>';
                    }
                } else {
                    if($row->filetype == "video") {
                        $img[] = '<div class="col-sm-4 text-center">
                                        <h4>Video File Type.</h4>
                                        <a href='.url($row->filename).' target="_blank">Click Here to Download Video File</a>
                                    </div>';
                    } else {
                        $img[] = '<div class="col-sm-4">
                                        <img src='.url($row->filename).' class="img-fluid">
                                    </div>';
                    }
                }
            }
        }
        return response()->json(['find'=>$find, 'table'=>$table, 'img'=>$img]);
    }

    // ========================================== END INCIDENT / CRIMES =============================

    // ======================================== STATISTICAL REPORTS =====================================
    
    function statisticalreports() {
        $stations = Station::all()->where('is_active', '1');
        $incidents = Incident::all()->count();
        $stationcount = Station::all()->count();
        $users = User::all()->count();
        $reports = Report::all()->count();
        $reportlogs = Report_log::all()->count();
        return view('PNPadmin.reports')
            ->with('stations', $stations)
            ->with('incidents', $incidents)
            ->with('stationcount', $stationcount)
            ->with('users', $users)
            ->with('reports', $reports)
            ->with('reportlogs', $reportlogs);
    }

    function statisticalreportsactivity() {
        $incidents = Incident::where('is_active', '1')->get();
        $reports = Report::with('getIncident')->get();

        if(count($reports) != 0) {
            $reports_ave_day = DB::table('reports')->select(DB::raw('count(*) as total'))
            ->groupBy(DB::raw('date(created_at)'))
            ->get()
            ->avg('total');
            $reports_ave_week = DB::table('reports')->select(DB::raw('count(*) as total'))
                ->groupBy(DB::raw('week(created_at)'))
                ->get()
                ->avg('total');
            $reports_ave_month = DB::table('reports')->select(DB::raw('count(*) as total'))
                ->groupBy(DB::raw('month(created_at)'))
                ->get()
                ->avg('total');
            $reports_ave_year = DB::table('reports')->select(DB::raw('count(*) as total'))
                ->groupBy(DB::raw('year(created_at)'))
                ->get()
                ->avg('total');
        } else {
            $reports_ave_day = 0;
            $reports_ave_week = 0;
            $reports_ave_month = 0;
            $reports_ave_year = 0;
        }

        foreach($incidents as $incident) {
            if(count($reports) != 0) {
                $view[] = '<li class="nav-item">
                                <a class="nav-link pb-md-0" data-toggle="tab" data-id='.$incident->id.' style="pointer-events: none;" href="javascript:void(0);" role="tab" aria-controls="" aria-selected="false">
                                    <span class="type-name">'.$incident->type.'</span>
                                    <h4 class="d-inline-block mr-2 mb-3">'.count($reports->where('incident_id', $incident->id)).'</h4>
                                    <i class="mdi mdi-chart-bar-stacked"></i> <span class="text-info">'.round((count($reports->where('incident_id', $incident->id)) / count($reports))*100).'%</span>
                                </a>
                            </li>';
            } else {
                $view[] = '<li class="nav-item">
                                <a class="nav-link pb-md-0" data-toggle="tab" data-id='.$incident->id.' style="pointer-events: none;" href="javascript:void(0);" role="tab" aria-controls="" aria-selected="false">
                                    <span class="type-name">'.$incident->type.'</span>
                                    <h4 class="d-inline-block mr-2 mb-3">'.count($reports->where('incident_id', $incident->id)).'</h4>
                                    <i class="mdi mdi-chart-bar-stacked"></i> <span class="text-info">0%</span>
                                </a>
                            </li>';
            }
            $view2[] = $incident->type;
            $all[] = count($reports->where('incident_id', $incident->id));
            $verified[] = count($reports->where('incident_id', $incident->id)->where('status','verified'));
            $bogus[] = count($reports->where('incident_id', $incident->id)->where('status','bogus'));
        }
        return response()->json(['reports'=>$reports,
                                'view'=>$view, 
                                'view2'=>$view2, 
                                'all'=>$all, 
                                'verified'=>$verified, 
                                'bogus'=>$bogus,
                                'reports_ave_day'=>$reports_ave_day,
                                'reports_ave_week'=>$reports_ave_week,
                                'reports_ave_month'=>$reports_ave_month,
                                'reports_ave_year'=>$reports_ave_year]);
    }

    function statisticalreportsactivitysearch(Request $request) {
        $from = $request->input('from');
        $to = $request->input('to');

        $incidents = Incident::where('is_active', '1')->get();
        $reports = Report::with('getIncident')
            ->whereDate('created_at', '>=', $from)
            ->whereDate('created_at', '<=', $to)
            ->get();

        foreach($incidents as $incident) {
            if(count($reports) == 0) {
                $view[] = '<li class="nav-item">
                                <a class="nav-link pb-md-0" data-toggle="tab" data-id='.$incident->id.' style="pointer-events: none;" href="javascript:void(0);" role="tab" aria-controls="" aria-selected="false">
                                    <span class="type-name">'.$incident->type.'</span>
                                    <h4 class="d-inline-block mr-2 mb-3">'.count($reports->where('incident_id', $incident->id)).'</h4>
                                    <i class="mdi mdi-chart-bar-stacked"></i> <span class="text-info">0%</span>
                                </a>
                            </li>';
            } else {
                $view[] = '<li class="nav-item">
                                <a class="nav-link pb-md-0" data-toggle="tab" data-id='.$incident->id.' style="pointer-events: none;" href="javascript:void(0);" role="tab" aria-controls="" aria-selected="false">
                                    <span class="type-name">'.$incident->type.'</span>
                                    <h4 class="d-inline-block mr-2 mb-3">'.count($reports->where('incident_id', $incident->id)).'</h4>
                                    <i class="mdi mdi-chart-bar-stacked"></i> <span class="text-info">'.round((count($reports->where('incident_id', $incident->id)) / count($reports))*100).'%</span>
                                </a>
                            </li>';
            }
        }
        foreach($incidents as $incident) {
            $view2[] = $incident->type;
        }
        foreach($incidents as $incident1) {
            $all[] = count($reports->where('incident_id', $incident1->id));
        }
        foreach($incidents as $incident2) {
            $verified[] = count($reports->where('incident_id', $incident2->id)->where('status','verified'));
        }
        foreach($incidents as $incident3) {
            $bogus[] = count($reports->where('incident_id', $incident3->id)->where('status','bogus'));
        }
        return response()->json(['reports'=>$reports,
                                'view'=>$view, 
                                'view2'=>$view2, 
                                'all'=>$all, 
                                'verified'=>$verified, 
                                'bogus'=>$bogus]);
    }

    function statisticalreportsstation(Request $request) {
        $getId = $request->input('station_id');

        $incidents = Incident::all()->where('is_active', '1');
        $reports = Report::with('getIncident')->where('station_id', $getId)->get();
        $reports_ave_day = DB::table('reports')->select(DB::raw('count(*) as total'))
            ->where('station_id', $getId)
            ->groupBy(DB::raw('date(created_at)'))
            ->get()
            ->avg('total');
        $reports_ave_week = DB::table('reports')->select(DB::raw('count(*) as total'))
            ->where('station_id', $getId)
            ->groupBy(DB::raw('week(created_at)'))
            ->get()
            ->avg('total');
        $reports_ave_month = DB::table('reports')->select(DB::raw('count(*) as total'))
            ->where('station_id', $getId)
            ->groupBy(DB::raw('month(created_at)'))
            ->get()
            ->avg('total');
        $reports_ave_year = DB::table('reports')->select(DB::raw('count(*) as total'))
            ->where('station_id', $getId)
            ->groupBy(DB::raw('year(created_at)'))
            ->get()
            ->avg('total');

        foreach($incidents as $incident) {
            if(count($reports) != 0) {
                $view[] = '<li class="nav-item">
                                <a class="nav-link pb-md-0" data-toggle="tab" data-id='.$incident->id.' style="pointer-events: none;" href="javascript:void(0);" role="tab" aria-controls="" aria-selected="false">
                                    <span class="type-name">'.$incident->type.'</span>
                                    <h4 class="d-inline-block mr-2 mb-3">'.count($reports->where('incident_id', $incident->id)->where('station_id', $getId)).'</h4>
                                    <i class="mdi mdi-chart-bar-stacked"></i> <span class="text-info">'.round((count($reports->where('incident_id', $incident->id)->where('station_id', $getId)) / count($reports))*100).'%</span>
                                </a>
                            </li>';
            } else {
                $view[] = '<li class="nav-item">
                                <a class="nav-link pb-md-0" data-toggle="tab" data-id='.$incident->id.' style="pointer-events: none;" href="javascript:void(0);" role="tab" aria-controls="" aria-selected="false">
                                    <span class="type-name">'.$incident->type.'</span>
                                    <h4 class="d-inline-block mr-2 mb-3">'.count($reports->where('incident_id', $incident->id)->where('station_id', $getId)).'</h4>
                                    <i class="mdi mdi-chart-bar-stacked"></i> <span class="text-info">0%</span>
                                </a>
                            </li>';
            }
        }
        foreach($incidents as $incident) {
            $view2[] = $incident->type;
        }
        foreach($incidents as $incident1) {
            $all[] = count($reports->where('incident_id', $incident1->id)->where('station_id', $getId));
        }
        foreach($incidents as $incident2) {
            $verified[] = count($reports->where('incident_id', $incident2->id)->where('station_id', $getId)->where('status','verified'));
        }
        foreach($incidents as $incident3) {
            $bogus[] = count($reports->where('incident_id', $incident3->id)->where('station_id', $getId)->where('status','bogus'));
        }
        return response()->json(['station_id'=>$getId,
                                'reports'=>$reports,
                                'view'=>$view, 
                                'view2'=>$view2, 
                                'all'=>$all, 
                                'verified'=>$verified, 
                                'bogus'=>$bogus,
                                'reports_ave_day'=>$reports_ave_day,
                                'reports_ave_week'=>$reports_ave_week,
                                'reports_ave_month'=>$reports_ave_month,
                                'reports_ave_year'=>$reports_ave_year]);
    }

    function reportslist() {
        if(request()->ajax()) {
            $data = Report::with('getIncident', 'getUser', 'getStation', 'dispatch')
                    ->whereIn('status', array('verified', 'bogus'))
                    ->orderBy('created_at', 'desc')
                    ->get();
            return datatables()->of($data)->make(true);
        }
    }

    function reportslistsearchbydate(Request $request) {
        $from = $request->input('from');
        $to = $request->input('to');

        $data = Report::with('getIncident', 'getUser', 'getStation', 'dispatch')
            ->whereIn('status', array('verified', 'bogus'))
            ->whereDate('created_at', '>=', $from)
            ->whereDate('created_at', '<=', $to)
            ->orderBy('created_at', 'desc')
            ->get();
        if(count($data) == 0) {
            $view2 = '<tr>
                        <td colspan="7" align="center">No Reports From '.Carbon::parse($request->input('from'))->format('F j, Y').' to '.Carbon::parse($request->input('to'))->format('F j, Y').'</td>
                    <tr>';
            return response()->json(['view2'=>$view2, 'count2'=>count($data)]);
        } else {
            foreach($data as $row) {
                if($row->status == "verified") {
                    $view[] = '<tr>
                                    <td><a href="javascript"  data-id='.$row->dispatch->id.' data-toggle="modal" data-target="#view-dispatch">'.$row->dispatch->dispatch_id.'</a></td>
                                    <td>'.$row->getIncident->type.'</td>
                                    <td>'.$row->location.'</td>
                                    <td>'.$row->getStation->station_name.'</td>
                                    <td>'.$row->created_at->format('F j, Y').' at '.$row->created_at->format('h:i A').'</td>
                                    <td class="text-center text-success"><i class="mdi mdi-check"></i> Verified</td>
                                </tr>';
                } else {
                    $view[] = '<tr>
                                    <td><a href="javascript"  data-id='.$row->dispatch->id.' data-toggle="modal" data-target="#view-dispatch">'.$row->dispatch->dispatch_id.'</a></td>
                                    <td>'.$row->getIncident->type.'</td>
                                    <td>'.$row->location.'</td>
                                    <td>'.$row->getStation->station_name.'</td>
                                    <td>'.$row->created_at->format('F j, Y').' at '.$row->created_at->format('h:i A').'</td>
                                    <td class="text-center text-danger"><i class="mdi mdi-close"></i> BOGUS</td>
                                </tr>';
                }
            }
            return response()->json(['view'=>$view, 'count'=>count($data)]); 
        }
    }

    function usersactivity() {
        $data = User::all();
        $reports = Report::all();
        $today = User::whereDate('created_at', '>=', Carbon::now()->toDateString())
            ->whereDate('created_at', '<=', Carbon::now()->toDateString())
            ->count();
        $yesterday = User::whereDate('created_at', '>=', Carbon::yesterday())
            ->whereDate('created_at', '<=', Carbon::yesterday())
            ->count();
        $week = User::whereDate('created_at', '>=', Carbon::now()->subWeeks(1))
            ->whereDate('created_at', '<=', Carbon::now()->toDateString())
            ->count();
        $month = User::whereMonth('created_at', '>=', Carbon::now())
            ->whereMonth('created_at', '<=', Carbon::now())
            ->count();
        $year = User::whereYear('created_at', '>=', Carbon::now())
            ->whereYear('created_at', '<=', Carbon::now())
            ->count();
        $ave_day = DB::table('users')->select(DB::raw('count(*) as total'))
            ->groupBy(DB::raw('date(created_at)'))
            ->get()
            ->avg('total');
        $ave_week = DB::table('users')->select(DB::raw('count(*) as total'))
            ->groupBy(DB::raw('week(created_at)'))
            ->get()
            ->avg('total');
        $ave_month = DB::table('users')->select(DB::raw('count(*) as total'))
            ->groupBy(DB::raw('month(created_at)'))
            ->get()
            ->avg('total');
        $ave_year = DB::table('users')->select(DB::raw('count(*) as total'))
            ->groupBy(DB::raw('year(created_at)'))
            ->get()
            ->avg('total');
        foreach($data as $row) {
            $ave_day_report[] = DB::table('reports')->select(DB::raw('count(*) as total'))
                        ->where('reporter_id', $row->id)
                        ->groupBy(DB::raw('date(created_at)'))
                        ->get()
                        ->count();
        }
        foreach($data as $row) {
            $ave_week_report[] = DB::table('reports')->select(DB::raw('count(*) as total'))
                        ->where('reporter_id', $row->id)
                        ->groupBy(DB::raw('week(created_at)'))
                        ->get()
                        ->count();
        }
        foreach($data as $row) {
            $ave_month_report[] = DB::table('reports')->select(DB::raw('count(*) as total'))
                        ->where('reporter_id', $row->id)
                        ->groupBy(DB::raw('month(created_at)'))
                        ->get()
                        ->count();
        }
        foreach($data as $row) {
            $ave_year_report[] = DB::table('reports')->select(DB::raw('count(*) as total'))
                        ->where('reporter_id', $row->id)
                        ->groupBy(DB::raw('year(created_at)'))
                        ->get()
                        ->count();
        }
        return response()->json(['today'=>$today,
                                'yesterday'=>$yesterday,
                                'week'=>$week,
                                'month'=>$month,
                                'year'=>$year,
                                'count'=>count($data),
                                'ave_day'=>$ave_day,
                                'ave_week'=>$ave_week,
                                'ave_month'=>$ave_month,
                                'ave_year'=>$ave_year,
                                'ave_day_report'=>collect($ave_day_report)->avg(),
                                'ave_week_report'=>collect($ave_week_report)->avg(),
                                'ave_month_report'=>collect($ave_month_report)->avg(),
                                'ave_year_report'=>collect($ave_year_report)->avg(),]);
    }

    function userslist() {
        if(request()->ajax()) {
            $data = User::all();
            return datatables()->of($data)->make(true);
        }
    }

    function userslistsearch(Request $request) {
        $from = $request->input('from');
        $to = $request->input('to');

        $search = User::whereDate('created_at', '>=', $from)
            ->whereDate('created_at', '<=', $to)
            ->get();
        if(count($search) == 0) {
            $view2 = '<tr><td colspan="6" align="center">No Users</td></tr>';
            return response()->json(['view2'=>$view2, 'count'=>count($search)]);
        } else {
            foreach($search as $row) {
                if($row->status == "verified") {
                    $view[] = '<tr>
                                    <td>'.$row->last_name.', '.$row->first_name.' '.$row->middle_name.'</td>
                                    <td>'.$row->contact_no.'</span></td>
                                    <td>'.$row->email.'</td>
                                    <td><img class="img-fluid" src='.url('uploads/user.jpg').' style="max-height: 50px;max-width: 50px;"></td>
                                    <td>'.$row->created_at->format('F j, Y').' at '.$row->created_at->format('h:i A').'</td>
                                    <td><span class="text-success"><i class="mdi mdi-check"></i> Verified</span></td>
                                </tr>';
                } else {
                    $view[] = '<tr>
                                    <td>'.$row->last_name.', '.$row->first_name.' '.$row->middle_name.'</td>
                                    <td>'.$row->contact_no.'</span></td>
                                    <td>'.$row->email.'</td>
                                    <td><img class="img-fluid" src='.url('uploads/user.jpg').' style="max-height: 50px;max-width: 50px;"></td>
                                    <td>'.$row->created_at->format('F j, Y').' at '.$row->created_at->format('h:i A').'</td>
                                    <td>'.$row->status.'</td>
                                </tr>';
                }
            }
        }
        return response()->json(['view'=>$view, 'count'=>count($search)]);
    }

    function viewdispatch($id) {
        $find = Dispatch::with('getReport.getIncident', 'getReport.getUser', 'getStation')->find($id);
        $data = Dispatch::with('getOfficer')->where('dispatch_id', $find->dispatch_id)->get();
        $evidence = Evidence::where('report_id', $find->report_id)->orderBy('filetype')->get();
        foreach($data as $key=>$row) {
            $table[] = '<tr>
                            <td>'.$row->getOfficer->last_name.', '.$row->getOfficer->first_name.'</td>
                            <td>'.$row->getOfficer->getRank->name.' ('.$row->getOfficer->getRank->abbreviation.')</td>
                            <td>'.$row->getOfficer->id_no.'</td>
                            <td>'.$row->getOfficer->badge_no.'</td>
                            <td>'.$row->getOfficer->contact_no.'</td>
                        </tr>';
        }
        if(count($evidence) == 0) {
            $img = '<div class="col-sm-12 text-center">
                            <h3 class="py-6">No File Available</h3>
                        </div>';
        } else {
            foreach($evidence as $row) {
                if(count($evidence) == 1) {
                    if($row->filetype == "video") {
                        $img[] = '<div class="col-sm-12 text-center">
                                        <h4>Video File Type.</h4>
                                        <a href='.url($row->filename).' target="_blank">Click Here to Download Video File</a>
                                    </div>';
                    } else {
                        $img[] = '<div class="col-sm-12">
                                        <img src='.url($row->filename).' class="img-fluid">
                                    </div>';
                    }
                } else if(count($evidence) == 2) {
                    if($row->filetype == "video") {
                        $img[] = '<div class="col-sm-6 text-center">
                                        <h4>Video File Type.</h4>
                                        <a href='.url($row->filename).' target="_blank">Click Here to Download Video File</a>
                                    </div>';
                    } else {
                        $img[] = '<div class="col-sm-6">
                                        <img src='.url($row->filename).' class="img-fluid">
                                    </div>';
                    }
                } else {
                    if($row->filetype == "video") {
                        $img[] = '<div class="col-sm-4 text-center">
                                        <h4>Video File Type.</h4>
                                        <a href='.url($row->filename).' target="_blank">Click Here to Download Video File</a>
                                    </div>';
                    } else {
                        $img[] = '<div class="col-sm-4">
                                        <img src='.url($row->filename).' class="img-fluid">
                                    </div>';
                    }
                }
            }
        }
        return response()->json(['find'=>$find, 'table'=>$table, 'img'=>$img]);
    }

    function officerslist() {
        if(request()->ajax()) {
            $data = Officer::with('getStation', 'getRank')->where('is_active', '1')->get();
            return datatables()->of($data)->make(true);
        }
    }

    function stationsactivity() {
        $stations = Station::all()->where('is_active', '1');
        $reports = Report::all();

        foreach($stations as $station) {
            $station_label[] = $station->station_name;
        }
        foreach($stations as $station) {
            $station_report_count[] = count($reports->where('station_id', $station->id));
        }
        foreach($stations as $station) {
            $station_officer_count[] = count(Officer::all()->where('station_id', $station->id));
        }
        foreach($stations as $station) {
            if(count($reports->where('station_id', $station->id)) == 0) {
                $ave_day[] = 0;
            } else {
                $ave_day[] = Report::select(DB::raw('count(*) as total'))
                        ->where('station_id', $station->id)
                        ->groupBy(DB::raw('date(created_at)'))
                        ->get()
                        ->count();
            }
        }
        foreach($stations as $station) {
            if(count($reports->where('station_id', $station->id)) == 0) {
                $ave_week[] = 0;
            } else {
                $ave_week[] = Report::select(DB::raw('count(*) as total'))
                        ->where('station_id', $station->id)
                        ->groupBy(DB::raw('week(created_at)'))
                        ->get()
                        ->count();
            }
        }
        foreach($stations as $station) {
            if(count($reports->where('station_id', $station->id)) == 0) {
                $ave_month[] = 0;
            } else {
                $ave_month[] = Report::select(DB::raw('count(*) as total'))
                        ->where('station_id', $station->id)
                        ->groupBy(DB::raw('month(created_at)'))
                        ->get()
                        ->count();
            }
        }
        foreach($stations as $station) {
            if(count($reports->where('station_id', $station->id)) == 0) {
                $ave_year[] = 0;
            } else {
                $ave_year[] = Report::select(DB::raw('count(*) as total'))
                        ->where('station_id', $station->id)
                        ->groupBy(DB::raw('year(created_at)'))
                        ->get()
                        ->count();
            }
        }
        return response()->json(['station_label'=>$station_label,
                                    'station_report_count'=>$station_report_count,
                                    'station_officer_count'=>$station_officer_count,
                                    'ave_day'=>$ave_day,
                                    'ave_week'=>$ave_week,
                                    'ave_month'=>$ave_month,
                                    'ave_year'=>$ave_year]);
    }

    function stationsactivitysearch(Request $request) {
        $from = $request->input('from');
        $to = $request->input('to');

        $stations = Station::all()->where('is_active', '1');
        $reports = Report::whereDate('created_at', '>=', $from)
            ->whereDate('created_at', '<=', $to)
            ->get();

        foreach($stations as $station) {
            $station_label[] = $station->station_name;
        }
        foreach($stations as $station) {
            $station_report_count[] = count($reports->where('station_id', $station->id));
        }
        foreach($stations as $station) {
            $station_report_count_verified[] = count($reports->where('station_id', $station->id)->where('status', 'verified'));
        }
        foreach($stations as $station) {
            $station_report_count_bogus[] = count($reports->where('station_id', $station->id)->where('status', 'bogus'));
        }
        return response()->json(['station_label'=>$station_label,
                                    'station_report_count'=>$station_report_count,
                                    'station_report_count_verified'=>$station_report_count_verified,
                                    'station_report_count_bogus'=>$station_report_count_bogus]);
    }

    function reportlogs() {
        $reports = Report_log::with('getReport', 'getDispatch')->orderBy('created_at', 'desc')->get();
        foreach($reports as $report) {
            if($report->activity == "pending") {
                $view[] = '<div class="media py-3 align-items-center justify-content-between">
                                <div class="d-flex rounded-circle align-items-center justify-content-center mr-3 media-icon iconbox-45 bg-warning text-white">
                                    <i class="mdi mdi-alert-outline font-size-20"></i>
                                </div>
                                <div class="media-body pr-3 ">
                                    <p class="text-dark font-weight-bold">'.$report->getReport->getUser->first_name.' Reported an Incident</p>
                                    <p>'.$report->getReport->getUser->first_name.' reported a/an '.$report->getReport->getIncident->type.' and was sent to '.$report->getReport->getStation->station_name.'.</p>
                                    <p style="font-size: 90%"><i class="mdi mdi-clock-outline"></i>&nbsp;'.$report->created_at->format('F j, Y').' at '.$report->created_at->format('h:i A').'</p>
                                </div>
                            </div>';
            }
            else if($report->activity == "responded") {
                $view[] = '<div class="media py-3 align-items-center justify-content-between">
                                <div class="d-flex rounded-circle align-items-center justify-content-center mr-3 media-icon iconbox-45 bg-info text-white">
                                    <i class="mdi mdi-map-search-outline font-size-20"></i>
                                </div>
                                <div class="media-body pr-3 ">
                                    <p class="text-dark font-weight-bold">Responded by '.$report->getReport->getStation->station_name.'</p>
                                    <p>The Report that was on '.$report->getReport->location.' was responded by '.$report->getReport->getStation->station_name.' and dispatched officers at '.$report->updated_at->format('h:i A').', '.$report->updated_at->format('F j, Y').'.</p>
                                    <p style="font-size: 90%"><i class="mdi mdi-clock-outline"></i>&nbsp;'.$report->created_at->format('F j, Y').' at '.$report->created_at->format('h:i A').'</p>
                                </div>
                            </div>';
            }
            else if($report->activity == "verified") {
                $view[] = '<div class="media py-3 align-items-center justify-content-between">
                                <div class="d-flex rounded-circle align-items-center justify-content-center mr-3 media-icon iconbox-45 bg-success text-white">
                                    <i class="mdi mdi-check-circle-outline font-size-20"></i>
                                </div>
                                <div class="media-body pr-3 ">
                                    <p class="text-dark font-weight-bold">A Report was verified by '.$report->getReport->getStation->station_name.'.</p>
                                    <p>'.$report->getReport->getUser->first_name.' Reported an Incident and it is confirmed by '.$report->getReport->getStation->station_name.' at '.$report->updated_at->format('h:i A').', '.$report->updated_at->format('F j, Y').'.</p>
                                    <p><i class="mdi mdi-key-variant"></i>&nbsp;<a href="javascript:void(0);" data-id='.$report->dispatch_id.' data-toggle="modal" data-target="#view-dispatch">'.$report->getDispatch->dispatch_id.'</a></p>
                                    <p style="font-size: 90%"><i class="mdi mdi-clock-outline"></i>&nbsp;'.$report->created_at->format('F j, Y').' at '.$report->created_at->format('h:i A').'</p>
                                </div>
                            </div>';
            }
            else if($report->activity == "bogus") {
                $view[] = '<div class="media py-3 align-items-center justify-content-between">
                                <div class="d-flex rounded-circle align-items-center justify-content-center mr-3 media-icon iconbox-45 bg-danger text-white">
                                    <i class="mdi mdi-close-outline font-size-20"></i>
                                </div>
                                <div class="media-body pr-3 ">
                                    <p class="text-dark font-weight-bold">'.$report->getReport->getUser->first_name.' Reported a Fraud Information</p>
                                    <p>The Report was a Fraud confirmed by '.$report->getReport->getStation->station_name.' at '.$report->updated_at->format('h:i A').', '.$report->updated_at->format('F j, Y').'. Sanctions will be implemented to the Reporter.</p>
                                    <p><i class="mdi mdi-key-variant"></i>&nbsp;<a href="javascript:void(0);" data-id='.$report->dispatch_id.' data-toggle="modal" data-target="#view-dispatch">'.$report->getDispatch->dispatch_id.'</a></p>
                                    <p style="font-size: 90%"><i class="mdi mdi-clock-outline"></i>&nbsp;'.$report->created_at->format('F j, Y').' at '.$report->created_at->format('h:i A').'</p>
                                </div>
                            </div>';
            }
        }
        return response()->json(['view'=>$view]);
    }

    function mapsearch(Request $request) {
        $lat = $request->input('location_lat');
        $lng =  $request->input('location_lng');

        $reports = Report::with('getIncident', 'dispatch')
                ->select(DB::raw('*, ( 30000 * acos( cos( radians('.$lat.') ) * cos( radians( location_lat ) ) * cos( radians( location_lng ) - radians('.$lng.') ) + sin( radians('.$lat.') ) * sin( radians( location_lat ) ) ) ) AS distance'))
                ->having('distance', '<', 1)
                ->orderBy('distance')
                ->get();
                /* ->first(); */
        if(count($reports) == 0) {
            $view2 = '<tr><td colspan="3" align="center">No Reports</td></tr>';
            return response()->json($view2);
        } else {
            foreach($reports as $report) {
                $view[] = '<tr>
                                <td><a href="javascript:void(0);" data-id='.$report->dispatch->id.' data-toggle="modal" data-target="#view-dispatch">'.$report->dispatch->dispatch_id.'</a></td>
                                <td>'.$report->getIncident->type.'</td>
                                <td>'.$report->location.'</td>
                            </tr>';
            }
        }
        return response()->json($view);
    }

    // ====================================== END STATISTICAL REPORTS =====================================

    // =========================================== PRINTS ==========================================

    function printreport($id) {
        $find = Dispatch::with('getReport.getIncident', 'getReport.getUser', 'getStation')->find($id);
        $data = Dispatch::with('getOfficer')->where('dispatch_id', $find->dispatch_id)->get();
        $evidence = Evidence::where('report_id', $find->report_id)->orderBy('filetype')->get();
        return view('PNPadmin.prints.print-report')
            ->with('find', $find)
            ->with('data', $data)
            ->with('evidence', $evidence);
    }

    function printreportsactivity() {
        $incidents = Incident::all()->where('is_active', '1');
        $reports = Report::with('getIncident')->get();

        if(count($reports) != 0) {
            $reports_ave_day = DB::table('reports')->select(DB::raw('count(*) as total'))
            ->groupBy(DB::raw('date(created_at)'))
            ->get()
            ->avg('total');
            $reports_ave_week = DB::table('reports')->select(DB::raw('count(*) as total'))
                ->groupBy(DB::raw('week(created_at)'))
                ->get()
                ->avg('total');
            $reports_ave_month = DB::table('reports')->select(DB::raw('count(*) as total'))
                ->groupBy(DB::raw('month(created_at)'))
                ->get()
                ->avg('total');
            $reports_ave_year = DB::table('reports')->select(DB::raw('count(*) as total'))
                ->groupBy(DB::raw('year(created_at)'))
                ->get()
                ->avg('total');
        } else {
            $reports_ave_day = 0;
            $reports_ave_week = 0;
            $reports_ave_month = 0;
            $reports_ave_year = 0;
        }
        return view('PNPadmin.prints.print-reports-activity')
            ->with('reports', $reports)
            ->with('incidents', $incidents)
            ->with('reports_ave_day', $reports_ave_day)
            ->with('reports_ave_week', $reports_ave_week)
            ->with('reports_ave_month', $reports_ave_month)
            ->with('reports_ave_year', $reports_ave_year);
    }

    function printreportstation($id) {
        $incidents = Incident::all()->where('is_active', '1');
        $reports = Report::with('getIncident')->where('station_id', $id)->get();

        if(count($reports) != 0) {
            $reports_ave_day = DB::table('reports')->select(DB::raw('count(*) as total'))
            ->groupBy(DB::raw('date(created_at)'))
            ->get()
            ->avg('total');
            $reports_ave_week = DB::table('reports')->select(DB::raw('count(*) as total'))
                ->groupBy(DB::raw('week(created_at)'))
                ->get()
                ->avg('total');
            $reports_ave_month = DB::table('reports')->select(DB::raw('count(*) as total'))
                ->groupBy(DB::raw('month(created_at)'))
                ->get()
                ->avg('total');
            $reports_ave_year = DB::table('reports')->select(DB::raw('count(*) as total'))
                ->groupBy(DB::raw('year(created_at)'))
                ->get()
                ->avg('total');
        } else {
            $reports_ave_day = 0;
            $reports_ave_week = 0;
            $reports_ave_month = 0;
            $reports_ave_year = 0;
        }
        return view('PNPadmin.prints.print-reports-activity-station')
            ->with('reports', $reports)
            ->with('incidents', $incidents)
            ->with('reports_ave_day', $reports_ave_day)
            ->with('reports_ave_week', $reports_ave_week)
            ->with('reports_ave_month', $reports_ave_month)
            ->with('reports_ave_year', $reports_ave_year);
    }

    function printstationsactivity() {
        $stations = Station::all()->where('is_active', '1');
        $reports = Report::all();

        foreach($stations as $station) {
            $station_label[] = $station->station_name;
        }
        foreach($stations as $station) {
            $station_report_count[] = count($reports->where('station_id', $station->id));
        }
        foreach($stations as $station) {
            $station_officer_count[] = count(Officer::all()->where('station_id', $station->id));
        }
        foreach($stations as $station) {
            if(count($reports->where('station_id', $station->id)) == 0) {
                $ave_day[] = 0;
            } else {
                $ave_day[] = Report::select(DB::raw('count(*) as total'))
                        ->where('station_id', $station->id)
                        ->groupBy(DB::raw('date(created_at)'))
                        ->get()
                        ->count();
            }
        }
        foreach($stations as $station) {
            if(count($reports->where('station_id', $station->id)) == 0) {
                $ave_week[] = 0;
            } else {
                $ave_week[] = Report::select(DB::raw('count(*) as total'))
                        ->where('station_id', $station->id)
                        ->groupBy(DB::raw('week(created_at)'))
                        ->get()
                        ->count();
            }
        }
        foreach($stations as $station) {
            if(count($reports->where('station_id', $station->id)) == 0) {
                $ave_month[] = 0;
            } else {
                $ave_month[] = Report::select(DB::raw('count(*) as total'))
                        ->where('station_id', $station->id)
                        ->groupBy(DB::raw('month(created_at)'))
                        ->get()
                        ->count();
            }
        }
        foreach($stations as $station) {
            if(count($reports->where('station_id', $station->id)) == 0) {
                $ave_year[] = 0;
            } else {
                $ave_year[] = Report::select(DB::raw('count(*) as total'))
                        ->where('station_id', $station->id)
                        ->groupBy(DB::raw('year(created_at)'))
                        ->get()
                        ->count();
            }
        }
        return view('PNPadmin.prints.print-stations-activity')
            ->with('stations',$stations)
            ->with('station_report_count',json_encode($station_report_count))
            ->with('station_officer_count',json_encode($station_officer_count))
            ->with('ave_day',json_encode($ave_day))
            ->with('ave_week',json_encode($ave_week))
            ->with('ave_month',json_encode($ave_month))
            ->with('ave_year',json_encode($ave_year));
    }

    function printreportsactivitysearch(Request $request) {
        $from = $request->input('from');
        $to = $request->input('to');

        $incidents = Incident::where('is_active', '1')->get();
        $reports = Report::with('getIncident')
            ->whereDate('created_at', '>=', $from)
            ->whereDate('created_at', '<=', $to)
            ->get();

        if(count($reports) != 0) {
            $reports_ave_day = DB::table('reports')->select(DB::raw('count(*) as total'))
            ->groupBy(DB::raw('date(created_at)'))
            ->get()
            ->avg('total');
            $reports_ave_week = DB::table('reports')->select(DB::raw('count(*) as total'))
                ->groupBy(DB::raw('week(created_at)'))
                ->get()
                ->avg('total');
            $reports_ave_month = DB::table('reports')->select(DB::raw('count(*) as total'))
                ->groupBy(DB::raw('month(created_at)'))
                ->get()
                ->avg('total');
            $reports_ave_year = DB::table('reports')->select(DB::raw('count(*) as total'))
                ->groupBy(DB::raw('year(created_at)'))
                ->get()
                ->avg('total');
        } else {
            $reports_ave_day = 0;
            $reports_ave_week = 0;
            $reports_ave_month = 0;
            $reports_ave_year = 0;
        }
        return view('PNPadmin.prints.print-reports-activity-search')
            ->with('from', $from)
            ->with('to', $to)
            ->with('reports', $reports)
            ->with('incidents', $incidents)
            ->with('reports_ave_day', $reports_ave_day)
            ->with('reports_ave_week', $reports_ave_week)
            ->with('reports_ave_month', $reports_ave_month)
            ->with('reports_ave_year', $reports_ave_year);
    }

    // ========================================= END PRINTS ========================================

    // ============================================ LOGS ================================================

    function logs() {
        $data = Account_log::with('getOfficers', 'account')->orderBy('created_at', 'desc')->get();
        return view('PNPadmin.account-logs')->with('data', $data);
    }

    // ================================= END LOGS =============================


    // ============================================= TRASH =====================================

    function trashofficers() {
        if(request()->ajax()) {
            $data = Officer::with('getRank')
            ->where('is_active', '0')
            ->where('status', 'removed')
            ->whereNull('station_id')
            ->get();
            return datatables()->of($data)
                ->addColumn('action', function($row) {
                    $btn = '<div class="btn-group" role="group" aria-label="Basic example">
                                <button data-id='.$row->id.' data-toggle="modal" data-target="#view-officer" class="btn btn-primary btn-sm" title="View">
                                    <span class="mdi mdi-eye">&nbsp;View</span>
                                </button>
                                <button data-id='.$row->id.' data-toggle="modal" data-target="#restore-officer" class="btn btn-success btn-sm" title="Restore">
                                    <span class="mdi mdi-undo-variant">&nbsp;Restore</span>
                                </button>
                            </div>';
                    return $btn;
                })
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
        }
        return view('PNPadmin.trash-officers');
    }

    function viewtrashofficer($id) {
        $view = Officer::find($id);
        return response()->json($view);
    }

    function restoreofficer(Request $request, $id) {
        if(Hash::check($request->input('password'), Auth::guard('admin')->user()->password)) {
            $update = Officer::find($id);
            $update->status = "unassigned";
            $update->is_active = "1";
            $update->save();
            return response()->json(['success'=>'Data Restored!']);
        } else {
            return response()->json(['error'=>'Password invalid.']);
        }
    }

    function trashnews() {
        if(request()->ajax()) {
            $data = News::all()->where('is_active', '0');
            return datatables()->of($data)
                ->addColumn('action', function($row) {
                    $btn = '<div class="btn-group" role="group" aria-label="Basic example">
                                <button data-id='.$row->id.' data-toggle="modal" data-target="#view-news" class="btn btn-primary btn-sm" title="View">
                                    <span class="mdi mdi-eye">&nbsp;View</span>
                                </button>
                                <button data-id='.$row->id.' data-toggle="modal" data-target="#restore-news" class="btn btn-success btn-sm" title="Restore">
                                    <span class="mdi mdi-undo-variant">&nbsp;Restore</span>
                                </button>
                            </div>';
                    return $btn;
                })
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
        }
        return view('PNPadmin.trash-news');
    }

    function viewtrashnews($id) {
        $view = News::find($id);
        return response()->json($view);
    }

    function restorenews(Request $request, $id) {
        if(Hash::check($request->input('password'), Auth::guard('admin')->user()->password)) {
            $update = News::find($id);
            $update->is_active = "1";
            $update->save();
            return response()->json(['success'=>'Data Restored!']);
        } else {
            return response()->json(['error'=>'Password invalid']);
        }
    }

    function trashannouncements() {
        if(request()->ajax()) {
            $data = Announcement::with('from')
                ->where('from_type', Admin::class)
                ->where('from_id', Auth::guard('admin')->user()->id)
                ->where('is_active', '0')
                ->get();
            return datatables()->of($data)
                ->addColumn('action', function($row) {
                    $btn = '<div class="btn-group" role="group" aria-label="Basic example">
                                <button data-id='.$row->id.' data-toggle="modal" data-target="#view-announcement" class="btn btn-primary btn-sm" title="View">
                                    <span class="mdi mdi-eye">&nbsp;View</span>
                                </button>
                                <button data-id='.$row->id.' data-toggle="modal" data-target="#restore-announcement" class="btn btn-success btn-sm" title="Restore">
                                    <span class="mdi mdi-undo-variant">&nbsp;Restore</span>
                                </button>
                            </div>';
                    return $btn;
                })
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
        }
        return view('PNPadmin.trash-announcements');
    }

    function viewtrashannouncements($id) {
        $view = Announcement::find($id);
        return response()->json($view);
    }

    function restoreannouncement(Request $request, $id) {
        if(Hash::check($request->input('password'), Auth::guard('admin')->user()->password)) {
            $update = Announcement::find($id);
            $update->is_active = "1";
            $update->save();
            return response()->json(['success'=>'Data Restored!']);
        } else {
            return response()->json(['error'=>'Password invalid.']);
        }
    }

    function trashcrimes() {
        if(request()->ajax()) {
            $data = Incident::all()->where('is_active', '0');
            return datatables()->of($data)
                ->addColumn('action', function($row) {
                    $btn = '<div class="btn-group">
                                <button type="button" class="mb-1 btn btn-success btn-sm" data-id='.$row->id.' data-toggle="modal" data-target="#restore-incident" title="Restore">
                                    <span class="mdi mdi-undo-variant">&nbsp;Restore</span>
                                </button>
                            </div>';
                    return $btn;
                })
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
        }
        return view('PNPadmin.trash-crimes');
    }

    function viewtrashcrimes($id) {
        $view = Incident::find($id);
        return response()->json($view);
    }

    function restoretrashcrimes(Request $request, $id) {
        if(Hash::check($request->input('password'), Auth::guard('admin')->user()->password)) {
            $update = Incident::find($id);
            $update->is_active = "1";
            $update->save();
            return response()->json(['success'=>'Data Restored!']);
        } else {
            return response()->json(['error'=>'Password invalid.']);
        }
    }

    function trashaccounts() {
        return view('PNPadmin.trash-accounts');
    }

    function trashaccountsadmin() {
        if(request()->ajax()) {
            $data = Admin::all()->where('is_active', '0');
            return datatables()->of($data)
                ->addColumn('action', function($row) {
                    $btn = '<div class="btn-group">
                                <button type="button" class="mb-1 btn btn-success btn-sm" data-id='.$row->id.' data-toggle="modal" data-target="#restore-admin" title="Restore">
                                    <span class="mdi mdi-undo-variant">&nbsp;Restore</span>
                                </button>
                            </div>';
                    return $btn;
                })
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
        }
    }

    function trashaccountsstation() {
        if(request()->ajax()) {
            $data = Station::all()->where('is_active', '0');
            return datatables()->of($data)
                ->addColumn('action', function($row) {
                    $btn = '<div class="btn-group">
                                <button type="button" class="mb-1 btn btn-success btn-sm" data-id='.$row->id.' data-toggle="modal" data-target="#restore-station" title="Restore">
                                    <span class="mdi mdi-undo-variant">&nbsp;Restore</span>
                                </button>
                            </div>';
                    return $btn;
                })
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
        }
    }

    function viewtrashaccountsadmin($id) {
        $view = Admin::find($id);
        return response()->json($view);
    }

    function viewtrashaccountsstation($id) {
        $view = Station::find($id);
        return response()->json($view);
    }

    function restoreadmin(Request $request, $id) {
        if(Hash::check($request->input('password'), Auth::guard('admin')->user()->password)) {
            $update = Admin::find($id);
            $update->is_active = "1";
            $update->save();
            return response()->json(['success'=>'Data Restored!']);
        } else {
            return response()->json(['error'=>'Password invalid.']);
        }
    }

    function restorestation(Request $request, $id) {
        if(Hash::check($request->input('password'), Auth::guard('admin')->user()->password)) {
            $update = Station::find($id);
            $update->is_active = "1";
            $update->save();
            return response()->json(['success'=>'Data Restored!']);
        } else {
            return response()->json(['error'=>'Password invalid.']);
        }
    }

    function blockedusers() {
        if(request()->ajax()) {
            $data = User::with(['report'=>function($query) {
                $query->where('status', 'bogus');
            }, 'report.dispatch'])->where('status', 'blocked')->get();
            return datatables()->of($data)
                ->addColumn('action', function($row) {
                    $btn = '<div class="btn-group">
                                <button type="button" class="mb-1 btn btn-warning btn-sm" data-id='.$row->id.' data-toggle="modal" data-target="#unblock-user" title="Unblock">
                                    <span class="mdi mdi-alert">&nbsp;Unblock</span>
                                </button>
                            </div>';
                    return $btn;
                })
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
        }
        return view('PNPadmin.trash-blocked-users');
    }

    function viewblockeduser($id) {
        $view = User::find($id);
        return response()->json($view);
    }

    function unblockuser(Request $request, $id) {
        if(Hash::check($request->input('password'), Auth::guard('admin')->user()->password)) {
            $update = User::find($id);
            $update->status = "verified";
            $update->save();
            return response()->json(['success'=>'User Unblocked!']);
        } else {
            return response()->json(['error'=>'Password invalid.']);
        }
    }

    // ============================================== END TRASH ================================


    function testing() {
        $data = Carbon::now()->addDay();
        return view('PNPadmin.fortesting')->with('data',$data);
    }

}
