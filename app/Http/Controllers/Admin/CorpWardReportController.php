<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\User;
use App\Models\Ward;
use App\Models\ConnectionType;
use App\Models\CorpWardWiseReport;
use Illuminate\Support\Facades\Validator;
use Datatables;
use Response;
use App\Models\CorpWard;
use Maatwebsite\Excel\Facades\Excel;
class CorpWardReportController extends Controller
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
        
        //$wards = Ward::all();
        $corpWard = CorpWard::all();
        $connType = ConnectionType::all();
        return view('admin.corp_ward_report',['corpWards' => $corpWard,'connTypes' => $connType]);
      
    }
    public function CorpWardReportSearch(Request $request)
    {
       
        $range=$request->range;
        $corp_ward_id=$request->corp_ward;
        $sequenceNumber=$request->sequence_number;
        $corp_ward_report_data = CorpWardWiseReport::getCorpWardSearchResult($range,$corp_ward_id,$sequenceNumber);
   
       return Datatables::of($corp_ward_report_data)->make(true); 
    }
    
    public function CorpWardReportPrint(Request $request)
    {
        ini_set('max_execution_time', 0);
        set_time_limit(0);
        ini_set('memory_limit', '-1');
      
        $arrParams = $request->all();
       
	$range=$request->range;
        $corp_ward_id= $arrParams['corp_ward'];
        $sequenceNumber=$request->sequence_number;
        $corp_ward_report_data = CorpWardWiseReport::getCorpWardSearchResult($range,$corp_ward_id,$sequenceNumber)->get();
         //print_r($corp_ward_report_data); exit();
        $view = View("admin/corp_ward_report_table", ["corp_ward_report_array" => $corp_ward_report_data])->render();
        $headers = array('Content-Type' => 'application/pdf');
        $pdf = \App::make('dompdf.wrapper', $headers);
        $pdf->setOptions(["isHtml5ParserEnabled" => TRUE, "isPhpEnabled" => TRUE, "isRemoteEnabled" => TRUE]);
        $pdf->loadHTML($view)->setPaper('a4', 'landscape');
        //$pdf->setPaper([0, 0, 595, 870], 'landscape');
        return $pdf->stream('corpwardreport.pdf');
    }
    
    public static function corpWardPendingExcel(Request $request){
        ini_set('max_execution_time', 0);
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        
        
        $arrParams = $request->all();
        
	$range=$request->range;
        $corp_ward_id= $arrParams['corp_ward'];
        $sequenceNumber=$request->sequence_number;
        $corp_ward_report_data = CorpWardWiseReport::getCorpWardSearchResult($range,$corp_ward_id,$sequenceNumber)->get();
        
        $excel_data['corp_ward_report_array'] = $corp_ward_report_data;
        
        
        $fileName="Corp Wardwise Pending Balance Report_".date("d_m_Y");
        // Generate and return the spreadsheet
        Excel::create($fileName, function($excel) use ($excel_data) {

            // Set the spreadsheet title, creator, and description
            $excel->setTitle('Corp Wardwise Pending Balance');
            //$excel->setCreator('Laravel')->setCompany('WJ Gilmore, LLC');
            $excel->setDescription('Corp Wardwise Pending Balance Report');

            // Build the spreadsheet, passing in the payments array
            $excel->sheet('Corp Wardwise Pending Balance', function($sheet) use ($excel_data) {
                $sheet->loadView('admin.Reports.corp_ward_pending_report_excel', $excel_data);
                //$sheet->fromArray($paymentsArray, null, 'A1', false, false);
            });
        })->download('xlsx');
    }
}
