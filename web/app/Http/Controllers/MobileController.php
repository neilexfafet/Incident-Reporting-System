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

class MobileController extends Controller
{
    function incidenttype() {
        $data = Incident::all()->where('is_active', '1');
        return response()->json($data);
    }



    function reportincident(Request $request) {
    
            $add = new Report;
            
            $add->description = $request->input('des');
         
            
            $add->save();

          
        }
}
