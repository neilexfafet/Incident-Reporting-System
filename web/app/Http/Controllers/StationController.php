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

class StationController extends Controller
{
    function dashboard() {
        $getId = Auth::guard('station')->user()->id;
        $officers = Officer::all()
            ->where('station_id', $getId)
            ->count();
        $reports = Report::all()->where('station_id', $getId)->count();
        $verifiedreports = Report::all()
            ->where('station_id', $getId)
            ->where('status', 'verified')
            ->count();
        $bogusreports = Report::all()
            ->where('station_id', $getId)
            ->where('status', 'bogus')
            ->count();
        return view('PNPstation.dashboard')
            ->with('officers', $officers)
            ->with('reports', $reports)
            ->with('verifiedreports', $verifiedreports)
            ->with('bogusreports', $bogusreports);
    }

    function dashboardreports() {
        $getId = Auth::guard('station')->user()->id;
        $all = Report::all()->where('station_id', $getId)->count();
        $responded = Report::all()->where('status', 'responded')->where('station_id', $getId)->count();
        $verified = Report::all()->where('status', 'verified')->where('station_id', $getId)->count();
        $bogus = Report::all()->where('status', 'bogus')->where('station_id', $getId)->count();
        $reports = Report_log::with('getDispatch', 'getReport')
            ->whereHas('getReport', function($q) use($getId) {
                $q->where('station_id', $getId);
            })
            ->orderBy('created_at', 'desc')
            ->get();
        if(count($reports) != 0) {
            foreach($reports as $report) {
                if($report->activity == "pending") {
                    $view[] = '<div class="media py-3 align-items-center justify-content-between">
                                    <div class="d-flex rounded-circle align-items-center justify-content-center mr-3 media-icon iconbox-45 bg-warning text-white">
                                        <i class="mdi mdi-alert-outline font-size-20"></i>
                                    </div>
                                    <div class="media-body pr-3 ">
                                        <p class="text-dark font-weight-bold">'.$report->getReport->getUser->first_name.' Reported an Incident</p>
                                        <p>Received a report from '.$report->getReport->getUser->first_name.'. The Reporter reported a/an '.$report->getReport->getIncident->type.'.</p>
                                    </div>
                                </div>';
                }
                else if($report->activity == "responded") {
                    $view[] = '<div class="media py-3 align-items-center justify-content-between">
                                    <div class="d-flex rounded-circle align-items-center justify-content-center mr-3 media-icon iconbox-45 bg-info text-white">
                                        <i class="mdi mdi-map-search-outline font-size-20"></i>
                                    </div>
                                    <div class="media-body pr-3 ">
                                        <p class="text-dark font-weight-bold">Responded and Dispatched Officers</p>
                                        <p>The Report that was on '.$report->getReport->location.' was responded and dispatched officers at '.$report->created_at->format('h:i A').', '.$report->created_at->format('F j, Y').'.</p>
                                    </div>
                                </div>';
                }
                else if($report->activity == "verified") {
                    $view[] = '<div class="media py-3 align-items-center justify-content-between">
                                    <div class="d-flex rounded-circle align-items-center justify-content-center mr-3 media-icon iconbox-45 bg-success text-white">
                                        <i class="mdi mdi-check-circle-outline font-size-20"></i>
                                    </div>
                                    <div class="media-body pr-3 ">
                                        <p class="text-dark font-weight-bold">The Report was verified.</p>
                                        <p>'.$report->getReport->getUser->first_name.' Reported an Incident: '.$report->getReport->getIncident->type.' and verified at '.$report->created_at->format('h:i A').', '.$report->created_at->format('F j, Y').'</p>
                                        <p><i class="mdi mdi-key-variant"></i>&nbsp;<a href="javascript:void(0);" data-id='.$report->dispatch_id.' data-toggle="modal" data-target="#view-dispatch">'.$report->getDispatch->dispatch_id.'</a></p>
                                    </div>
                                </div>';
                }
                else if($report->activity == "bogus") {
                    $view[] = '<div class="media py-3 align-items-center justify-content-between">
                                    <div class="d-flex rounded-circle align-items-center justify-content-center mr-3 media-icon iconbox-45 bg-danger text-white">
                                        <i class="mdi mdi-close-outline font-size-20"></i>
                                    </div>
                                    <div class="media-body pr-3 ">
                                        <p class="text-dark font-weight-bold">'.$report->getReport->getUser->first_name.' Reported a Bogus Information</p>
                                        <p>The Report was a fake or bogus. Sanctions will be implemented to the Reporter.</p>
                                        <p><i class="mdi mdi-key-variant"></i>&nbsp;<a href="javascript:void(0);" data-id='.$report->dispatch_id.' data-toggle="modal" data-target="#view-dispatch">'.$report->getDispatch->dispatch_id.'</a></p>
                                    </div>
                                </div>';
                }
            }
        } else {
            $view[] = '<div class="media py-3 align-items-center justify-content-between">
                            <div class="media-body text-center pr-3 ">
                                <p>No Reports Received Yet.</p>
                            </div>
                        </div>';
        }
        return response()->json(['view'=>$view, 'all'=>$all, 'responded'=>$responded, 'verified'=>$verified, 'bogus'=>$bogus]);
    }

    /* =========================================== PROFILE ======================================= */

    function profile() {
        return view('PNPstation.profile');
    }

    function updateusername(Request $request, $id) {
        if (Admin::all()->where('username', $request->input('username'))->first()) {
            return response()->json(['error'=>'Username already Exists!']);
        }
        if (Station::all()->where('username', $request->input('username'))->first()) {
            return response()->json(['error'=>'Username already Exists!']);
        }

        $update = Station::find($id);
        if(Hash::check($request->input('confirmpassword'), $update->password)) {
            $update->username = $request->input('username');
            $update->save();
            return response()->json(['success'=>'Username Saved!']);
        } else {
            return response()->json(['errorpw'=>'Password Invalid! Please Try Again.']);
        }
        
    }

    function updateprofile(Request $request, $id) {
        $update = Station::find($id);
        $update->station_name = $request->input('station_name');
        $update->station_contactno = $request->input('station_contactno');
        $update->location_name = $request->input('location_name');
        $update->location_lat = $request->input('location_lat');
        $update->location_lng = $request->input('location_lng');
        if ($request->hasFile('image')) {
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
        return response()->json(['success'=>'Details Updated Successfully']);
    }

    function updatepassword(Request $request, $id) {
        $update = Station::find($id);
        if(Hash::check($request->input('current_password'), $update->password)) {
            $update->password = Hash::make($request->input('new_password'));
            $update->save();
            return response()->json(['success'=>'Password Successfully Changed!']);
        } else {
            return response()->json(['error'=>'Current Password Invalid! Please Try Again.']);
        }
    }

    /* ========================================= END PROFILE ======================================= */


    // ====================================== INCIDENT REPORTS ===============================

    function incidentreports() {
        if(request()->ajax()) {
            $getId = Auth::guard('station')->user()->id;
            $data = Report::with('getIncident', 'getUser', 'getStation')
                ->orderBy('created_at', 'desc')
                ->where('station_id', $getId)
                ->where('status', 'pending')
                ->get();
            return datatables()->of($data)
                ->addColumn('action', function($row) {
                    $btn = '<button type="button" class="btn btn-warning btn-sm" data-id='.$row->id.' data-toggle="modal" data-target="#respond-report">
                                <span class="mdi mdi-alert-outline">&nbsp;RESPOND</span>
                            </button>';

                    return $btn;
                })
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
        }
        return view('PNPstation.incident-reports');
    }

    function respondedincidentreports() {
        if(request()->ajax()) {
            $getId = Auth::guard('station')->user()->id;
            $data = Dispatch::with('getReport.getIncident', 'getOfficer')
                ->orderBy('created_at', 'desc')
                ->where('status', 'pending')
                ->where('station_id', $getId)
                ->groupBy('dispatch_id')
                ->get();
            return datatables()->of($data)
                ->addColumn('action', function($row) {
                    $btn2 = '<button type="button" class="btn btn-success btn-sm" data-id='.$row->id.' data-toggle="modal" data-target="#verify-report">
                                <span class="mdi mdi-check">&nbsp;Confirm</span>
                            </button>';
                    return $btn2;
                })
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
        }
    }

    function verifiedreports() {
        $getId = Auth::guard('station')->user()->id;
        $data = Dispatch::with('getReport.getIncident', 'getOfficer')
            ->orderBy('created_at', 'desc')
            ->where('station_id', $getId)
            ->whereIn('status', array('verified', 'bogus'))
            ->groupBy('dispatch_id')
            ->get();
        return datatables()->of($data)
            ->addColumn('action', function($row) {
                $btn2 = '<button type="button" class="btn btn-primary btn-sm" data-id='.$row->id.' data-toggle="modal" data-target="#view-dispatch">
                            <span class="mdi mdi-eye">&nbsp;View</span>
                        </button>';
                return $btn2;
            })
        ->rawColumns(['action'])
        ->addIndexColumn()
        ->make(true);
    }

    function unrespondedcount() {
        $getId = Auth::guard('station')->user()->id;
        $data = Report::all()->where('status', 'pending')->where('station_id', $getId)->count();
        return response()->json($data);
    }

    function respondedcount() {
        $getId = Auth::guard('station')->user()->id;
        $data = Report::all()->where('status', 'responded')->where('station_id', $getId)->count();
        return response()->json($data);
    }

    function viewincidentreport($id) {
        $view = Report::with('getIncident', 'getUser', 'getStation')->find($id);
        $evidence = Evidence::where('report_id', $id)->get();
        if(count($evidence) == 0) {
            $img[] = '<div class="carousel-item active">
                            <img class="d-block w-100" src='.url("evidence/no_image_available.jpeg").' style="max-height: 425px;">
                        </div>';
        } else {
            foreach($evidence as $key=>$row) {
                if($key == 0) {
                    if($row->filetype == "video") {
                        $img[] = '<div class="carousel-item active">
                                    <video class="d-block w-100" controls loop>
                                        <source src='.url($row->filename).'>
                                    </video>
                                    <div class="d-flex justify-content-center">
                                        <a href='.url($row->filename).' target="_blank">Download Video File</a>
                                    </div>
                                </div>';
                    } else {
                        $img[] = '<div class="carousel-item active">
                                    <img class="d-block w-100" src='.url($row->filename).'>
                                </div>';
                    }
                } else {
                    if($row->filetype == "video") {
                        $img[] = '<div class="carousel-item">
                                    <video class="d-block w-100" controls loop>
                                        <source src='.url($row->filename).'>
                                    </video>
                                    <div class="d-flex justify-content-center">
                                        <a href='.url($row->filename).' target="_blank">Download Video File</a>
                                    </div>
                                </div>';
                    } else {
                        $img[] = '<div class="carousel-item">
                                    <img class="d-block w-100" src='.url($row->filename).'>
                                </div>';
                    }
                }
            }
        }
        return response()->json(['view'=>$view, 'evidence'=>$evidence, 'img'=>$img]);
    }

    function viewreporter($id) {
        $view = User::find($id);
        return response()->json($view);
    }

    function officerstodispatch() {
        $getId = Auth::guard('station')->user()->id;
        $data = Officer::with('getRank')->where('station_id', $getId)->where('is_active', '1')->where('status', 'available')->get();
        foreach($data as $row) {
            $view[] = '<option value='.$row->id.'>'.$row->getRank->abbreviation.' '.$row->first_name.' '.$row->last_name.'</option>';
        }
        return response()->json($view);
    }

    function officersdispatched($id) {
        $get = Dispatch::find($id);
        $getId = $get->dispatch_id;
        $data = Dispatch::with('getOfficer')->where('dispatch_id', $getId)->get();
        foreach($data as $key=>$row) {
            if($key == 0) {
                $view[] = '<li class="nav-item">
                                <a href=#officer_'.$row->getOfficer->id.' class="nav-link active" data-toggle="tab" aria-expanded="true">'.$row->getOfficer->getRank->abbreviation.' '.$row->getOfficer->first_name.' '.$row->getOfficer->last_name.'</a>
                            </li>';
            } else {
                $view[] = '<li class="nav-item">
                                <a href=#officer_'.$row->getOfficer->id.' class="nav-link" data-toggle="tab" aria-expanded="false">'.$row->getOfficer->getRank->abbreviation.' '.$row->getOfficer->first_name.' '.$row->getOfficer->last_name.'</a>
                            </li>';
            }
            if($key == 0) {
                if($row->getOfficer->image == "TBD") {
                    $view2[] = '<div class="tab-pane fade active show" id=officer_'.$row->getOfficer->id.' aria-expanded="true">
                                    <div class="card card-default p-4">
                                        <a href="javascript:void(0);" class="media text-secondary" style="pointer-events: none;">
                                            <img src='.url("uploads/user.jpg").' class="mr-3 img-fluid rounded" height="100px" alt="Avatar Image">
                                            <div class="media-body">
                                                <h4 class="mt-0 mb-2 text-dark">'.$row->getOfficer->first_name.' '.$row->getOfficer->last_name.'</h4>
                                                <ul class="list-unstyled">
                                                    <li class="d-flex mb-1"><i class="mdi mdi-seal mr-1"></i><span>'.$row->getOfficer->getRank->name.' ('.$row->getOfficer->getRank->abbreviation.')</span></li>
                                                    <li class="d-flex mb-1"><i class="mdi mdi-gender-male-female mr-1"></i><span>'.$row->getOfficer->gender.'</span></li>
                                                    <li class="d-flex mb-1"><i class="mdi mdi-phone mr-1"></i><span>'.$row->getOfficer->contact_no.'</span></li>
                                                    <li class="d-flex mb-1"><i class="mdi mdi-calendar mr-1"></i><span>'.date("F j, Y", strtotime($row->getOfficer->birthday)).'</span></li>
                                                    <li class="d-flex mb-1"><i class="mdi mdi-calendar-clock mr-1"></i><span>'.Carbon::parse($row->getOfficer->birthday)->age.' Years of age</span></li>
                                                    <li class="d-flex mb-1"><i class="mdi mdi-email-outline mr-1"></i><span>'.$row->getOfficer->email.'</span></li>
                                                </ul>
                                            </div>
                                        </a>
                                    </div>
                                </div>';
                } else {
                    $view2[] = '<div class="tab-pane fade" id=officer_'.$row->getOfficer->id.' aria-expanded="false">
                                    <div class="card card-default p-4">
                                        <a href="javascript:void(0);" class="media text-secondary" style="pointer-events: none;">
                                            <img src='.url($row->getOfficer->image).' class="img-fluid mr-3 " style="max-width: 150px;max-height: 150px;" alt="Avatar Image">
                                            <div class="media-body">
                                                <h4 class="mt-0 mb-2 text-dark">'.$row->getOfficer->first_name.' '.$row->getOfficer->last_name.'</h4>
                                                <ul class="list-unstyled">
                                                    <li class="d-flex mb-1"><i class="mdi mdi-seal mr-1"></i><span>'.$row->getOfficer->getRank->name.' ('.$row->getOfficer->getRank->abbreviation.')</span></li>
                                                    <li class="d-flex mb-1"><i class="mdi mdi-gender-male-female mr-1"></i><span>'.$row->getOfficer->gender.'</span></li>
                                                    <li class="d-flex mb-1"><i class="mdi mdi-phone mr-1"></i><span>'.$row->getOfficer->contact_no.'</span></li>
                                                    <li class="d-flex mb-1"><i class="mdi mdi-calendar mr-1"></i><span>'.date("F j, Y", strtotime($row->getOfficer->birthday)).'</span></li>
                                                    <li class="d-flex mb-1"><i class="mdi mdi-calendar-clock mr-1"></i><span>'.Carbon::parse($row->getOfficer->birthday)->age.' Years of age</span></li>
                                                    <li class="d-flex mb-1"><i class="mdi mdi-email-outline mr-1"></i><span>'.$row->getOfficer->email.'</span></li>
                                                </ul>
                                            </div>
                                        </a>
                                    </div>
                                </div>';
                }
            } else {
                if($row->getOfficer->image == "TBD") {
                    $view2[] = '<div class="tab-pane fade" id=officer_'.$row->getOfficer->id.' aria-expanded="false">
                                    <div class="card card-default p-4">
                                        <a href="javascript:void(0);" class="media text-secondary" style="pointer-events: none;">
                                            <img src='.url("uploads/user.jpg").' class="mr-3 img-fluid rounded" height="100px" alt="Avatar Image">
                                            <div class="media-body">
                                                <h4 class="mt-0 mb-2 text-dark">'.$row->getOfficer->first_name.' '.$row->getOfficer->last_name.'</h4>
                                                <ul class="list-unstyled">
                                                    <li class="d-flex mb-1"><i class="mdi mdi-seal mr-1"></i><span>'.$row->getOfficer->getRank->name.' ('.$row->getOfficer->getRank->abbreviation.')</span></li>
                                                    <li class="d-flex mb-1"><i class="mdi mdi-gender-male-female mr-1"></i><span>'.$row->getOfficer->gender.'</span></li>
                                                    <li class="d-flex mb-1"><i class="mdi mdi-phone mr-1"></i><span>'.$row->getOfficer->contact_no.'</span></li>
                                                    <li class="d-flex mb-1"><i class="mdi mdi-calendar mr-1"></i><span>'.date("F j, Y", strtotime($row->getOfficer->birthday)).'</span></li>
                                                    <li class="d-flex mb-1"><i class="mdi mdi-calendar-clock mr-1"></i><span>'.Carbon::parse($row->getOfficer->birthday)->age.' Years of age</span></li>
                                                    <li class="d-flex mb-1"><i class="mdi mdi-email-outline mr-1"></i><span>'.$row->getOfficer->email.'</span></li>
                                                </ul>
                                            </div>
                                        </a>
                                    </div>
                                </div>';
                } else {
                    $view2[] = '<div class="tab-pane fade" id=officer_'.$row->getOfficer->id.' aria-expanded="false">
                                    <div class="card card-default p-4">
                                        <a href="javascript:void(0);" class="media text-secondary" style="pointer-events: none;">
                                            <img src='.url($row->getOfficer->image).' class="img-fluid mr-3 " style="max-width: 150px;max-height: 150px;" alt="Avatar Image">
                                            <div class="media-body">
                                                <h4 class="mt-0 mb-2 text-dark">'.$row->getOfficer->first_name.' '.$row->getOfficer->last_name.'</h4>
                                                <ul class="list-unstyled">
                                                    <li class="d-flex mb-1"><i class="mdi mdi-seal mr-1"></i><span>'.$row->getOfficer->getRank->name.' ('.$row->getOfficer->getRank->abbreviation.')</span></li>
                                                    <li class="d-flex mb-1"><i class="mdi mdi-gender-male-female mr-1"></i><span>'.$row->getOfficer->gender.'</span></li>
                                                    <li class="d-flex mb-1"><i class="mdi mdi-phone mr-1"></i><span>'.$row->getOfficer->contact_no.'</span></li>
                                                    <li class="d-flex mb-1"><i class="mdi mdi-calendar mr-1"></i><span>'.date("F j, Y", strtotime($row->getOfficer->birthday)).'</span></li>
                                                    <li class="d-flex mb-1"><i class="mdi mdi-calendar-clock mr-1"></i><span>'.Carbon::parse($row->getOfficer->birthday)->age.' Years of age</span></li>
                                                    <li class="d-flex mb-1"><i class="mdi mdi-email-outline mr-1"></i><span>'.$row->getOfficer->email.'</span></li>
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

    function dispatchofficers(Request $request) {
        $officers = $request->input('officer_id');
        $report_id = $request->input('report_id');

        $rand = rand();
        $report_name = date('dmy');
        $dispatch_id = "REPORT#".$report_name."-".$rand;

        foreach($officers as $officer) {
            $add = new Dispatch;
            $add->dispatch_id = $dispatch_id;
            $add->report_id = $report_id;
            $add->officer_id = $officer;
            $add->station_id = Auth::guard('station')->user()->id;
            $add->save();

            $update = Officer::find($officer);
            $update->status = "dispatched";
            $update->save();
        }
        $update2 = Report::find($report_id);
        $update2->status = "responded";
        $update2->save();

        $log = new Report_log;
        $log->activity = "responded";
        $log->report_id = $report_id;
        $log->dispatch_id = $add->id;
        $log->save();

        $notif = new Notification;
        $notif->type = "Report Feedback";
        $notif->notif_type = Report::class;
        $notif->notif_id = $request->input('report_id');
        $notif->sendto_type = User::class;
        $notif->sendto_id = $update2->reporter_id;
        $notif->save();

        return response()->json(['success'=>'Officers Dispatched in the Area!']);
    }

    function stationdetails($id) {
        $data = Station::find($id);
        return response()->json($data);
    }

    function transferreport(Request $request, $id) {
        $update = Report::find($id);
        $update->station_id = $request->input('station_id');
        $update->save();

        $notif = new Notification;
        $notif->type = "Report";
        $notif->notif_type = Report::class;
        $notif->notif_id = $id;
        $notif->sendto_type = Station::class;
        $notif->sendto_id = $request->input('station_id');
        $notif->save();

        return response()->json(['success'=>'Report has been transfered.']);
    }

    function viewrespondedincidentreport($id) {
        $view = Dispatch::with('getReport.getIncident', 'getReport.getUser', 'getOfficer')->find($id);
        $evidence = Evidence::where('report_id', $view->report_id)->get();
        $dispatched = Dispatch::with('getOfficer')->where('dispatch_id', $view->dispatch_id)->get();
        if(count($evidence) == 0) {
            $img[] = '<div class="carousel-item active">
                            <img class="d-block w-100" src='.url("evidence/no_image_available.jpeg").' style="max-height: 425px;">
                        </div>';
        } else {
            foreach($evidence as $key=>$row) {
                if($key == 0) {
                    if($row->filetype == "video") {
                        $img[] = '<div class="carousel-item active">
                                        <video class="d-block w-100" controls loop>
                                            <source src='.url($row->filename).'>
                                        </video>
                                        <div class="d-flex justify-content-center">
                                            <a href='.url($row->filename).' target="_blank">Download Video File</a>
                                        </div>
                                    </div>';
                    } else {
                        $img[] = '<div class="carousel-item active">
                                    <img class="d-block w-100" src='.url($row->filename).'>
                                </div>';
                    }
                } else {
                    if($row->type == "video") {
                        $img[] = '<div class="carousel-item">
                                        <video class="d-block w-100" controls loop>
                                            <source src='.url($row->filename).'>
                                        </video>
                                        <div class="d-flex justify-content-center">
                                            <a href='.url($row->filename).' target="_blank">Download Video File</a>
                                        </div>
                                    </div>';
                    } else {
                        $img[] = '<div class="carousel-item">
                                    <img class="d-block w-100" src='.url($row->filename).'>
                                </div>';
                    }
                }
            }
        }
        foreach($dispatched as $key=>$row) {
            if($key == 0) {
                $tab[] = '<li class="nav-item">
                                <a href=#officer2_'.$row->getOfficer->id.' class="nav-link active" data-toggle="tab" aria-expanded="true">'.$row->getOfficer->getRank->abbreviation.' '.$row->getOfficer->first_name.' '.$row->getOfficer->last_name.'</a>
                            </li>';
            } else {
                $tab[] = '<li class="nav-item">
                                <a href=#officer2_'.$row->getOfficer->id.' class="nav-link" data-toggle="tab" aria-expanded="false">'.$row->getOfficer->getRank->abbreviation.' '.$row->getOfficer->first_name.' '.$row->getOfficer->last_name.'</a>
                            </li>';
            }
            if($key == 0) {
                if($row->getOfficer->image == "TBD") {
                    $content[] = '<div class="tab-pane fade active show" id=officer2_'.$row->getOfficer->id.' aria-expanded="true">
                                    <div class="card card-default p-4">
                                        <a href="javascript:void(0);" class="media text-secondary" style="pointer-events: none;">
                                            <img src='.url("uploads/user.jpg").' class="mr-3 img-fluid rounded" height="100px" alt="Avatar Image">
                                            <div class="media-body">
                                                <h4 class="mt-0 mb-2 text-dark">'.$row->getOfficer->first_name.' '.$row->getOfficer->last_name.'</h4>
                                                <ul class="list-unstyled">
                                                    <li class="d-flex mb-1"><i class="mdi mdi-seal mr-1"></i><span>'.$row->getOfficer->getRank->name.' ('.$row->getOfficer->getRank->abbreviation.')</span></li>
                                                    <li class="d-flex mb-1"><i class="mdi mdi-gender-male-female mr-1"></i><span>'.$row->getOfficer->gender.'</span></li>
                                                    <li class="d-flex mb-1"><i class="mdi mdi-phone mr-1"></i><span>'.$row->getOfficer->contact_no.'</span></li>
                                                    <li class="d-flex mb-1"><i class="mdi mdi-calendar mr-1"></i><span>'.date("F j, Y", strtotime($row->getOfficer->birthday)).'</span></li>
                                                    <li class="d-flex mb-1"><i class="mdi mdi-calendar-clock mr-1"></i><span>'.Carbon::parse($row->getOfficer->birthday)->age.' Years of age</span></li>
                                                    <li class="d-flex mb-1"><i class="mdi mdi-email-outline mr-1"></i><span>'.$row->getOfficer->email.'</span></li>
                                                </ul>
                                            </div>
                                        </a>
                                    </div>
                                </div>';
                } else {
                    $content[] = '<div class="tab-pane fade" id=officer2_'.$row->getOfficer->id.' aria-expanded="false">
                                    <div class="card card-default p-4">
                                        <a href="javascript:void(0);" class="media text-secondary" style="pointer-events: none;">
                                            <img src='.url($row->getOfficer->image).' class="img-fluid mr-3 " style="max-width: 150px;max-height: 150px;" alt="Avatar Image">
                                            <div class="media-body">
                                                <h4 class="mt-0 mb-2 text-dark">'.$row->getOfficer->first_name.' '.$row->getOfficer->last_name.'</h4>
                                                <ul class="list-unstyled">
                                                    <li class="d-flex mb-1"><i class="mdi mdi-seal mr-1"></i><span>'.$row->getOfficer->getRank->name.' ('.$row->getOfficer->getRank->abbreviation.')</span></li>
                                                    <li class="d-flex mb-1"><i class="mdi mdi-gender-male-female mr-1"></i><span>'.$row->getOfficer->gender.'</span></li>
                                                    <li class="d-flex mb-1"><i class="mdi mdi-phone mr-1"></i><span>'.$row->getOfficer->contact_no.'</span></li>
                                                    <li class="d-flex mb-1"><i class="mdi mdi-calendar mr-1"></i><span>'.date("F j, Y", strtotime($row->getOfficer->birthday)).'</span></li>
                                                    <li class="d-flex mb-1"><i class="mdi mdi-calendar-clock mr-1"></i><span>'.Carbon::parse($row->getOfficer->birthday)->age.' Years of age</span></li>
                                                    <li class="d-flex mb-1"><i class="mdi mdi-email-outline mr-1"></i><span>'.$row->getOfficer->email.'</span></li>
                                                </ul>
                                            </div>
                                        </a>
                                    </div>
                                </div>';
                }
            } else {
                if($row->getOfficer->image == "TBD") {
                    $content[] = '<div class="tab-pane fade" id=officer2_'.$row->getOfficer->id.' aria-expanded="false">
                                    <div class="card card-default p-4">
                                        <a href="javascript:void(0);" class="media text-secondary" style="pointer-events: none;">
                                            <img src='.url("uploads/user.jpg").' class="mr-3 img-fluid rounded" height="100px" alt="Avatar Image">
                                            <div class="media-body">
                                                <h4 class="mt-0 mb-2 text-dark">'.$row->getOfficer->first_name.' '.$row->getOfficer->last_name.'</h4>
                                                <ul class="list-unstyled">
                                                    <li class="d-flex mb-1"><i class="mdi mdi-seal mr-1"></i><span>'.$row->getOfficer->getRank->name.' ('.$row->getOfficer->getRank->abbreviation.')</span></li>
                                                    <li class="d-flex mb-1"><i class="mdi mdi-gender-male-female mr-1"></i><span>'.$row->getOfficer->gender.'</span></li>
                                                    <li class="d-flex mb-1"><i class="mdi mdi-phone mr-1"></i><span>'.$row->getOfficer->contact_no.'</span></li>
                                                    <li class="d-flex mb-1"><i class="mdi mdi-calendar mr-1"></i><span>'.date("F j, Y", strtotime($row->getOfficer->birthday)).'</span></li>
                                                    <li class="d-flex mb-1"><i class="mdi mdi-calendar-clock mr-1"></i><span>'.Carbon::parse($row->getOfficer->birthday)->age.' Years of age</span></li>
                                                    <li class="d-flex mb-1"><i class="mdi mdi-email-outline mr-1"></i><span>'.$row->getOfficer->email.'</span></li>
                                                </ul>
                                            </div>
                                        </a>
                                    </div>
                                </div>';
                } else {
                    $content[] = '<div class="tab-pane fade" id=officer2_'.$row->getOfficer->id.' aria-expanded="false">
                                    <div class="card card-default p-4">
                                        <a href="javascript:void(0);" class="media text-secondary" style="pointer-events: none;">
                                            <img src='.url($row->getOfficer->image).' class="img-fluid mr-3 " style="max-width: 150px;max-height: 150px;" alt="Avatar Image">
                                            <div class="media-body">
                                                <h4 class="mt-0 mb-2 text-dark">'.$row->getOfficer->first_name.' '.$row->getOfficer->last_name.'</h4>
                                                <ul class="list-unstyled">
                                                    <li class="d-flex mb-1"><i class="mdi mdi-seal mr-1"></i><span>'.$row->getOfficer->getRank->name.' ('.$row->getOfficer->getRank->abbreviation.')</span></li>
                                                    <li class="d-flex mb-1"><i class="mdi mdi-gender-male-female mr-1"></i><span>'.$row->getOfficer->gender.'</span></li>
                                                    <li class="d-flex mb-1"><i class="mdi mdi-phone mr-1"></i><span>'.$row->getOfficer->contact_no.'</span></li>
                                                    <li class="d-flex mb-1"><i class="mdi mdi-calendar mr-1"></i><span>'.date("F j, Y", strtotime($row->getOfficer->birthday)).'</span></li>
                                                    <li class="d-flex mb-1"><i class="mdi mdi-calendar-clock mr-1"></i><span>'.Carbon::parse($row->getOfficer->birthday)->age.' Years of age</span></li>
                                                    <li class="d-flex mb-1"><i class="mdi mdi-email-outline mr-1"></i><span>'.$row->getOfficer->email.'</span></li>
                                                </ul>
                                            </div>
                                        </a>
                                    </div>
                                </div>';
                }
            }
        }
        return response()->json(['view'=>$view, 'img'=>$img, 'tab'=>$tab, 'content'=>$content]);
    }

    function confirmincidentreport(Request $request) {
        $dispatch_id = $request->input('dispatch_id');
        $dispatches = Dispatch::with('getReport')->where('dispatch_id', $dispatch_id)->get();

        if($request->input('status') == "verified") {
            foreach($dispatches as $dispatch) {
                $update = Dispatch::find($dispatch->id);
                $update->status = $request->input('status');
                $update->save();
    
                $update2 = Officer::find($dispatch->officer_id);
                $update2->status = "available";
                $update2->save();
                
                $update3 = Report::find($dispatch->report_id);
                $update3->status = $request->input('status');
                $update3->save();
    
            }
        } else {
            foreach($dispatches as $dispatch) {
                $update = Dispatch::find($dispatch->id);
                $update->status = $request->input('status');
                $update->save();
    
                $update2 = Officer::find($dispatch->officer_id);
                $update2->status = "available";
                $update2->save();
                
                $update3 = Report::find($dispatch->report_id);
                $update3->status = $request->input('status');
                $update3->save();
                
                $update4 = User::find($dispatch->getReport->reporter_id);
                $update4->status = "blocked";
                $update4->save();
            }
        }

        $log = new Report_log;
        $log->activity = $request->input('status');
        $log->report_id = $update3->id;
        $log->dispatch_id = $update->id;
        $log->save();

        return response()->json(['success'=>'Report is Verified!']);
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

    // ======================================= END INCIDENT REPORTS ==========================

    // =========================================== OFFICERS =====================================

    function officers() {
        if(request()->ajax()) {
            $getId = Auth::guard('station')->user()->id;
            $data = Officer::with('getRank')->where('station_id', $getId)->where('is_active', '1')->get();
            return datatables()->of($data)
                ->addColumn('action', function($row) {
                    $btn = '<button type="button" class="btn btn-primary btn-sm" data-id='.$row->id.' data-toggle="modal" data-target="#view-assign">
                                <span class="mdi mdi-eye">&nbsp;View</span>
                            </button>';
                    return $btn;
                })
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
        }
        return view('PNPstation.officers');
    }

    function viewofficer($id) {
        $view = Officer::with('getRank')->find($id);
        return response()->json($view);
    }

    // ========================================== END OFFICERS ==================================


    // ========================================= NOTIFICATIONS ===================================

    function notifications() {
        $getType = Station::class;
        $getId = Auth::guard('station')->user()->id;
        $data = Notification::with('notif', 'sendto')->orderBy('created_at', 'desc')
            ->where('sendto_type', $getType)
            ->where('sendto_id', $getId)
            ->get();
        return view('PNPstation.notifications')->with('data', $data);
    }

    function notificationheader() {
        $getType = Station::class;
        $getId = Auth::guard('station')->user()->id;
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
                if($row->type == "Announcement") {
                    $view[] = '<li>
                            <a href="javascript:void(0);"  data-id='.$row->notif_id.' data-toggle="modal" data-target="#notification-announcement-modal">
                                <i class="mdi mdi-alert-outline"></i>'.$row->type.'
                                <span class="font-size-12 d-inline-block float-right">'.$row->created_at->diffForHumans().' <i class="mdi mdi-clock-outline"></i></span>
                            </a>
                        </li>';
                } else {
                    $view[] = '<li>
                            <a href='.url("station/incident-reports").'>
                                <i class="mdi mdi-alert-circle-outline"></i>'.$row->type.'
                                <span class="font-size-12 d-inline-block float-right">'.$row->created_at->diffForHumans().' <i class="mdi mdi-clock-outline"></i></span>
                            </a>
                        </li>';
                }
                
            }
            return response()->json($view);
        }
        
    }

    function notificationcount() {
        $getType = Station::class;
        $getId = Auth::guard('station')->user()->id;
        $count = Notification::all()
            ->where('sendto_type', $getType)
            ->where('sendto_id', $getId)
            ->where('status', 'unread')
            ->count();
        return response()->json($count);
    }

    function notificationread() {
        $getType = Station::class;
        $getId = Auth::guard('station')->user()->id;
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
        $getType = Station::class;
        $getId = Auth::guard('station')->user()->id;
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
                } else {
                    $view[] = '<a href='.url("station/incident-reports").'>
                                    <div class="media align-items-center pb-2 justify-content-between">
                                        <div class="d-flex rounded-circle align-items-center justify-content-center mr-4 media-icon iconbox-45 bg-warning text-white">
                                            <i class="mdi mdi-alert font-size-20"></i>
                                        </div>
                                        <div class="media-body">
                                            <span class="right-sidebar-2-subtitle" style="font-size: 14px">'.$row->notif->getUser->first_name.' '.$row->notif->getUser->last_name.' reported an Incident at '.$row->notif->location.'</span>
                                            <span class="font-size-12 d-inline-block"><i class="mdi mdi-clock-outline"></i> '.$row->created_at->diffForHumans().'</span>
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

    // ======================================== END NOTIFICATIONS ================================

    /* ======================================== NEWS AND ANNOUNCEMENTS ===================================== */

    function news() {
        $data = News::orderBy('created_at', 'desc')->where('is_active', '1')->paginate(5);
        $news = News::all()->where('is_active', '1')->count();
        return view('PNPstation.news')->with('data', $data)->with('news', $news);
    }

    function announcements() {
        if(request()->ajax()) {
            $data = Announcement::with('from')
                ->where('is_active', '1')
                /* ->where('station_id', Auth::guard('station')->user()->id) */
                ->get();
            return datatables()->of($data)
                ->addColumn('action', function($row) {
                    if($row->from_id == Auth::guard('station')->user()->id && $row->from_type == Station::class) {
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
        return view('PNPstation.announcements');
    }

    function viewannouncement($id) {
        $view = Announcement::with('from')->find($id);
        return response()->json($view);
    }

    function addannouncement(Request $request) {
        $stations = Station::all();
        $admins = Admin::all();
        $users = User::all();
        if($request->hasFile('image')) {
            $add = new Announcement;
                $ran = rand(000,99999);
                $image = $request->file('image');
                $image_name = date('dmy_H_s_i');
                $ext = $image->getClientOriginalExtension();
                $image_full_name = $image_name.'_'.$ran.'.'.$ext;
                $add->image = $request->file('image')->move('announcements', $image_full_name);
            $add->from_id = Auth::guard('station')->user()->id;
            $add->from_type = Station::class;
            $add->save();
        } else {
            $add = new Announcement;
            $add->subject = $request->input('subject');
            $add->message = $request->input('message');
            $add->from_id = Auth::guard('station')->user()->id;
            $add->from_type = Station::class;
            $add->save();
        }
        foreach($stations as $station) {
            if($station->id != Auth::guard('station')->user()->id) {
                $notif = new Notification;
                $notif->type = "Announcement";
                $notif->notif_type = Announcement::class;
                $notif->notif_id = $add->id;
                $notif->sendto_type = Station::class;
                $notif->sendto_id = $station->id;
                $notif->save();
            }
        }
        foreach($admins as $admin) {
            $notif2 = new Notification;
            $notif2->type = "Announcement";
            $notif2->notif_type = Announcement::class;
            $notif2->notif_id = $add->id;
            $notif2->sendto_type = Admin::class;
            $notif2->sendto_id = $admin->id;
            $notif2->save();
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
        $update = Announcement::find($id);
        $update->is_active = "0";
        $update->save();
        return response()->json(['success'=>'Data Removed!']);
    }

    /* END ANNOUNCEMENTS */

    /* ====================================== END NEWS AND ANNOUNCEMENTS =================================== */

    /* ========================================= STATISCTICAL REPORTS ====================================== */
    
    function statisticalreports() {
        $stationreports = Report::all()->where('station_id', Auth::guard('station')->user()->id)->count();
        $reports = Report::all()->count();
        return view('PNPstation.reports')
            ->with('stationreports', $stationreports)
            ->with('reports', $reports);
    }

    function stationstatisticalreportsactivity() {
        $getId = Auth::guard('station')->user()->id;
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
            $view[] = '<li class="nav-item">
                            <a class="nav-link pb-md-0" data-toggle="tab" data-id='.$incident->id.' style="pointer-events: none;" href="javascript:void(0);" role="tab" aria-controls="" aria-selected="false">
                                <span class="type-name">'.$incident->type.'</span>
                                <h4 class="d-inline-block mr-2 mb-3">'.count($reports->where('incident_id', $incident->id)).'</h4>
                                <i class="mdi mdi-chart-bar-stacked"></i> <span class="text-info">'.round((count($reports->where('incident_id', $incident->id)) / count($reports))*100).'%</span>
                            </a>
                        </li>';
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
                                'bogus'=>$bogus,
                                'reports_ave_day'=>$reports_ave_day,
                                'reports_ave_week'=>$reports_ave_week,
                                'reports_ave_month'=>$reports_ave_month,
                                'reports_ave_year'=>$reports_ave_year]);
    }

    function statisticalreportsactivity() {
        $incidents = Incident::all()->where('is_active', '1');
        $reports = Report::with('getIncident')->get();
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
        foreach($incidents as $incident) {
            $view[] = '<li class="nav-item">
                            <a class="nav-link pb-md-0" data-toggle="tab" data-id='.$incident->id.' style="pointer-events: none;" href="javascript:void(0);" role="tab" aria-controls="" aria-selected="false">
                                <span class="type-name">'.$incident->type.'</span>
                                <h4 class="d-inline-block mr-2 mb-3">'.count($reports->where('incident_id', $incident->id)).'</h4>
                                <i class="mdi mdi-chart-bar-stacked"></i> <span class="text-info">'.round((count($reports->where('incident_id', $incident->id)) / count($reports))*100).'%</span>
                            </a>
                        </li>';
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
                                'bogus'=>$bogus,
                                'reports_ave_day'=>$reports_ave_day,
                                'reports_ave_week'=>$reports_ave_week,
                                'reports_ave_month'=>$reports_ave_month,
                                'reports_ave_year'=>$reports_ave_year]);
    }

    function stationstatisticalreportsactivitysearch(Request $request) {
        $getId = Auth::guard('station')->user()->id;
        $from = $request->input('from');
        $to = $request->input('to');

        $incidents = Incident::all()->where('is_active', '1');
        $reports = Report::with('getIncident')
            ->whereDate('created_at', '>=', $from)
            ->whereDate('created_at', '<=', $to)
            ->where('station_id', $getId)
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

    function statisticalreportsactivitysearch(Request $request) {
        $from = $request->input('from');
        $to = $request->input('to');

        $incidents = Incident::all()->where('is_active', '1');
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

    function reportslist() {
        if(request()->ajax()) {
            $data = Report::with('getIncident', 'getUser', 'getStation', 'dispatch')
                    ->whereIn('status', array('verified', 'bogus'))
                    ->orderBy('created_at', 'desc')
                    ->get();
            return datatables()->of($data)->make(true);
        }
    }

    function stationreportslist() {
        if(request()->ajax()) {
            $getId = Auth::guard('station')->user()->id;
            $data = Report::with('getIncident', 'getUser', 'getStation', 'dispatch')
                    ->whereIn('status', array('verified', 'bogus'))
                    ->where('station_id', $getId)
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
                                    <td>'.$row->description.'</td>
                                    <td>'.$row->location.'</td>
                                    <td>'.$row->getStation->station_name.'</td>
                                    <td>'.$row->created_at->format('F j, Y').' at '.$row->created_at->format('h:i A').'</td>
                                    <td class="text-center text-success"><i class="mdi mdi-check"></i> Verified</td>
                                </tr>';
                } else {
                    $view[] = '<tr>
                                    <td><a href="javascript"  data-id='.$row->dispatch->id.' data-toggle="modal" data-target="#view-dispatch">'.$row->dispatch->dispatch_id.'</a></td>
                                    <td>'.$row->getIncident->type.'</td>
                                    <td>'.$row->description.'</td>
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

    function stationreportslistsearchbydate(Request $request) {
        $getId = Auth::guard('station')->user()->id;
        $from = $request->input('from');
        $to = $request->input('to');

        $data = Report::with('getIncident', 'getUser', 'getStation', 'dispatch')
            ->where('station_id', $getId)
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
                                    <td>'.$row->description.'</td>
                                    <td>'.$row->location.'</td>
                                    <td>'.$row->getStation->station_name.'</td>
                                    <td>'.$row->created_at->format('F j, Y').' at '.$row->created_at->format('h:i A').'</td>
                                    <td class="text-center text-success"><i class="mdi mdi-check"></i> Verified</td>
                                </tr>';
                } else {
                    $view[] = '<tr>
                                    <td><a href="javascript"  data-id='.$row->dispatch->id.' data-toggle="modal" data-target="#view-dispatch">'.$row->dispatch->dispatch_id.'</a></td>
                                    <td>'.$row->getIncident->type.'</td>
                                    <td>'.$row->description.'</td>
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

    function officerslist() {
        if(request()->ajax()) {
            $getId = Auth::guard('station')->user()->id;
            $data = Officer::with('getStation', 'getRank')->where('station_id', $getId)->get();
            return datatables()->of($data)->make(true);
        }
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

    /* ========================================= STATISCTICAL REPORTS ====================================== */

    // =========================================== PRINTS ==========================================

    function printreport($id) {
        $find = Dispatch::with('getReport.getIncident', 'getReport.getUser', 'getStation')->find($id);
        $data = Dispatch::with('getOfficer')->where('dispatch_id', $find->dispatch_id)->get();
        $evidence = Evidence::where('report_id', $find->report_id)->orderBy('filetype')->get();
        return view('PNPstation.prints.print-report')
            ->with('find', $find)
            ->with('data', $data)
            ->with('evidence', $evidence);
    }

    function printreportsactivity() {
        $incidents = Incident::all()->where('is_active', '1');
        $reports = Report::with('getIncident')->where('station_id', Auth::guard('station')->user()->id)->get();

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
        return view('PNPstation.prints.print-reports-activity')
            ->with('reports', $reports)
            ->with('incidents', $incidents)
            ->with('reports_ave_day', $reports_ave_day)
            ->with('reports_ave_week', $reports_ave_week)
            ->with('reports_ave_month', $reports_ave_month)
            ->with('reports_ave_year', $reports_ave_year);
    }

    function printreportsactivitysearch(Request $request) {
        $getId = Auth::guard('station')->user()->id;
        $from = $request->input('from');
        $to = $request->input('to');

        $incidents = Incident::all()->where('is_active', '1');
        $reports = Report::with('getIncident')
            ->whereDate('created_at', '>=', $from)
            ->whereDate('created_at', '<=', $to)
            ->where('station_id', $getId)
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
        return view('PNPstation.prints.print-reports-activity-search')
            ->with('from', $from)
            ->with('to', $to)
            ->with('reports', $reports)
            ->with('incidents', $incidents)
            ->with('reports_ave_day', $reports_ave_day)
            ->with('reports_ave_week', $reports_ave_week)
            ->with('reports_ave_month', $reports_ave_month)
            ->with('reports_ave_year', $reports_ave_year);
    }


    function printreportsactivityall() {
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
        return view('PNPstation.prints.print-reports-activity')
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
        return view('PNPstation.prints.print-stations-activity')
            ->with('stations',$stations)
            ->with('station_report_count',json_encode($station_report_count))
            ->with('station_officer_count',json_encode($station_officer_count))
            ->with('ave_day',json_encode($ave_day))
            ->with('ave_week',json_encode($ave_week))
            ->with('ave_month',json_encode($ave_month))
            ->with('ave_year',json_encode($ave_year));
    }

    // ========================================= END PRINTS ========================================
}
