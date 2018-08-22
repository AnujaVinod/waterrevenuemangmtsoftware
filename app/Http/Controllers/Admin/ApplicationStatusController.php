<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Models\ApplicationStatus;
use Illuminate\Support\Facades\Validator;
use Response;
use Datatables;
use App\Helpers\UserLogHelper;

class ApplicationStatusController extends Controller
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
        return view('admin.application_status');
    }

    
    public function getApplicationStatus()
    {        
        $applicationstatus = ApplicationStatus::all();
        return Datatables::of($applicationstatus)               
                ->make(true);
    }
    
    public function saveApplicationStatus(Request $request)
    {       
        $messages = array(
            'regex' => 'Invalid application status',
            'unique' => 'application status has already been taken'
        );
        $this->validate($request, [           
            'status' => 'required|unique:master_application_status|regex:/^[A-Za-z0-9\s-]+$/'
        ],$messages);
        
        $inspector = ApplicationStatus::create([
                    'status' => trim($request->status)
        ]);
        $sequenceNo = '';
        $action = 'New application status '.$request->status.' is added';
        UserLogHelper::createUserLog($sequenceNo, 'Application status', $action);
        return redirect()->back()->with("success","Application Status added successfully !");
    } 
 
}
