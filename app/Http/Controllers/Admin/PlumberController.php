<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Models\Plumber;
use Illuminate\Support\Facades\Validator;
use Response;
use Datatables;
use App\Helpers\UserLogHelper;

class PlumberController extends Controller
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
        return view('admin.plumber');
    }

    
    public function getPlumber()
    {        
        $plumbers = Plumber::all();
        return Datatables::of($plumbers)               
                ->make(true);
    }
    
    public function savePlumber(Request $request)
    {   
        $messages = array(
            'regex' => 'Invalid :attribute',
            'unique' => ':attribute has already been taken'
        );
        $this->validate($request, [           
            'plumber_name' => 'required|unique:plumber|regex:/^[A-Za-z\.\s\']+$/',
            'contact_no' => 'nullable|numeric|digits_between:10,15',
        ],$messages);
        
        $inspector = Plumber::create([
                    'plumber_name' => $request->plumber_name,
                    'license_number' => $request->license_number,
                    'contact_no' => $request->contact_no
        ]);
        $sequenceNo = '';
        $action = 'New plumber '.$request->plumber_name.' is added';
        UserLogHelper::createUserLog($sequenceNo, 'Plumber', $action);
        return redirect()->back()->with("success","Plumber added successfully !");
    }
 
}
