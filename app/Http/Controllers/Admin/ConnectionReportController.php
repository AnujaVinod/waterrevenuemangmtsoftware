<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\User;
use App\Models\Ward;
use App\Models\ConnectionWiseReport;
use Illuminate\Support\Facades\Validator;
use Datatables;
use Response;

class ConnectionReportController extends Controller
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
        
        $wards = Ward::all();
        return view('admin.connection_report',['wards' => $wards]);
      
    }
    public function ConnectionReportSearch(Request $request)
    {
       $ward_id= $request->ward;
       $from_date_format= $request->from_date;
       $to_date_format= $request->to_date;
       $from_date= date("Y-m-d", strtotime($from_date_format));
       $to_date= date("Y-m-d", strtotime($to_date_format) );
       $connection_report_data = ConnectionWiseReport::getConnectionSearchResult($ward_id,$from_date,$to_date);
   
       return Datatables::of($connection_report_data)->make(true); 
    }
    public function ConnectionReportPrint(Request $request)
    {

        $arrParams = $request->all();
	$ward_id = $arrParams['ward'];
        $from_date_format= $arrParams['from_date'];
        $to_date_format= $arrParams['to_date'];

       $from_date= date("Y-m-d", strtotime($from_date_format));
       $to_date= date("Y-m-d", strtotime($to_date_format) );
       $connection_report_data = ConnectionWiseReport::getConnectionSearchResult($ward_id,$from_date,$to_date);
       
      // return view('admin.connection_report_table', ['connection_report_array' => $connection_report_data]);
        $view = View("admin/connection_report_table", ["connection_report_array" => $connection_report_data])->render();
        $headers = array('Content-Type' => 'application/pdf');
        $pdf = \App::make('dompdf.wrapper', $headers);
        $pdf->setOptions(["isHtml5ParserEnabled" => TRUE, "isPhpEnabled" => TRUE, "isRemoteEnabled" => TRUE]);
        $pdf->loadHTML($view)->setPaper('a4', 'landscape');
        //$pdf->setPaper([0, 0, 595, 870], 'landscape');
        return $pdf->stream('connectionreport.pdf');
    }

}
