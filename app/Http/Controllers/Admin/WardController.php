<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Models\Ward;
use App\Models\Zone;
use Illuminate\Support\Facades\Validator;
use Response;
use Datatables;
use App\Helpers\UserLogHelper;

class WardController extends Controller
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
        $zones = Zone::all();
        return view('admin.ward',['zones' => $zones]);
    }
    
    public function getWard()
    {
        $wards = Ward::retriveWardInfo();
        return Datatables::of($wards)               
                ->make(true);
    }
    
    public function saveWard(Request $request)
    {      
        $messages = array(
            'regex' => 'Invalid Ward',
            'unique' => 'Ward name has already been taken'
        );
        $this->validate($request, [
            'zone' => 'required',
            'ward_name' => 'required|unique:master_ward|regex:/^[A-Za-z0-9\.\s-]+$/|max:50'            
        ],$messages);
        
        $user = Ward::create([
                    'ward_name' => trim($request->ward_name),
                    'zone_id' => $request->zone
        ]);
        $sequenceNo = '';
        $action="New ward added";            
        UserLogHelper::createUserLog($sequenceNo, 'ward', $action);
        return redirect()->back()->with("success","Ward added successfully !");
    }
 
}
