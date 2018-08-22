<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\User;
use App\Models\ConsumerConnection;

class IndustrialBillController extends Controller
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
        
       // $wards = Ward::all();
      //  $connType = ConnectionType::all();
      //  return view('admin.billing_report',['wards' => $wards,'connTypes' => $connType]);
        return view('admin.industrial_bill');
      
    }
    
    public function getIndustialBillInfo(Request $request)
    {
        $seq_no = $request->seq_no;
        $meter_no = $request->meter_no;                
        $connDetails = ConsumerConnection::meterNameChangeSearch($seq_no,$meter_no);
        $billDetails = ConsumerConnection::getIndustialBillInfo($seq_no,$meter_no);
        if($connDetails->isNotEmpty()){
            return Response::json(['success' => 'success', 'connDetails' => $connDetails,'connectionTypes' =>$connectionTypes]);
        } else {
            return Response::json(['failure' => 'failure']);
        }
    }

}
