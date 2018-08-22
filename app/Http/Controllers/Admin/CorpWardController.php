<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Models\CorpWard;
use App\Models\Ward;
use App\Models\CorpwardWard;
use Illuminate\Support\Facades\Validator;
use Response;
use Datatables;
use App\Helpers\UserLogHelper;

class CorpWardController extends Controller
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
        $wards = Ward::orderBy('ward_name')->get();
        return view('admin.corp_ward',['wards' => $wards]);
    }

    public function getCorpWard()
    {        
        $corpwards = CorpWard::retriveCorpWardInfo();
        return Datatables::of($corpwards)               
                ->make(true);
    }
    
    public function saveCorpWard(Request $request)
    {
        $messages = array(
            'regex' => 'Invalid Corp Ward',
            'ward_name.required' => 'Ward Name field is required'
        );
        $this->validate($request, [
            'ward_name.*' => 'required',
            'corp_name' => 'required|regex:/^[A-Za-z0-9\.\s-]+$/|max:50'            
        ],$messages);
        
        $corp = CorpWard::create([
            'corp_name' => trim($request->corp_name)
        ]);
        
        foreach ($request->ward_name as $ward){
            $corpward = CorpwardWard::create([
                    'corp_id' => $corp->id,
                    'ward_id' => $ward
            ]);
        } 
        $action="New corp ward added";  
        $sequenceNo = '';
        UserLogHelper::createUserLog($sequenceNo, 'corpward', $action);
        return redirect()->back()->with("success","Ward added successfully !");
    }
 
}
