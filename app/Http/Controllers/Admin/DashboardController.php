<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\User;
use App\Models\UserLog;
use Datatables;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {       
        $data = auth()->user()->toArray();
        return view('admin.dashboard',$data);
    }
    
    public function getLogData(Request $request){
        if($request->from_date == null && $request->to_date == null){
            $userLogObj = UserLog::getCustomeLogReport();
        }
        elseif(strtotime($request->from_date) == strtotime($request->to_date)){
            $from_date = date("Y-m-d", strtotime($request->from_date));
            $userLogObj = UserLog::getCustomeLogReport($from_date);
        }else{
            $from_date = date("Y-m-d", strtotime($request->from_date));
            $to_date = date("Y-m-d", strtotime($request->to_date));
            if(strtotime($request->from_date) < strtotime($request->to_date)){
                $userLogObj = UserLog::getCustomeLogReport($from_date,$to_date);
            }else{
                $userLogObj = UserLog::getCustomeLogReport($to_date,$from_date);
            }
        }  
        return Datatables::of($userLogObj)->make(true);
    }
}
