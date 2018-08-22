<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\User;
//use App\Models\Ward;
use App\Models\CorpWard;
use App\Models\ConnectionType;
use App\Models\ConsumerBillReport;
use Illuminate\Support\Facades\Validator;
use Datatables;
use Response;
use Maatwebsite\Excel\Facades\Excel;

class BillingReportController extends Controller
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
        $corpwards = CorpWard::all();
        $connType = ConnectionType::all();
        return view('admin.billing_report',['corpwards' => $corpwards,'connTypes' => $connType]);
      
    }
    public function billReportSearch(Request $request) {

        $seq_number = $request->seq_number;
        $meter_no = $request->meter_no;
        $corp_ward = $request->corp_ward;
        $con_type = $request->con_type;
        $from_date_format = $request->from_date;
        $to_date_format = $request->to_date;
        $paginate_count = $request->length;
        $search_key = $request->search_key;
        $start_limit = $request->start_limit;
        $datacheck = $request->datacheck;
        $old_data_type = $request->old_data_type;
        $from_date = $from_date_format != '' ? date("Y-m-d", strtotime($from_date_format)) : '0';
        $to_date = $to_date_format != '' ? date("Y-m-d", strtotime($to_date_format)) : '0';

        if ($datacheck == 0) { //New Records
            $report_search_data_res = ConsumerBillReport::getSearchResult($seq_number, $meter_no, $corp_ward, $con_type, $from_date, $to_date, $search_key, $start_limit, $paginate_count);
            $check = $report_search_data_res->get();
            $count = count($check);
            if ($count == 0) {

                $message = 'No Data';
                return view('admin.bill_report_table', ['result' => '0', 'report_search_data' => $message, 'i' => 0, 'list_count' => $paginate_count, 'keysearch' => $search_key]);
            } else {

                $report_search_data = $report_search_data_res->paginate($paginate_count);
                return view('admin.bill_report_table', ['result' => '1', 'report_search_data' => $report_search_data, 'i' => 0, 'list_count' => $paginate_count, 'keysearch' => $search_key, 'rowcount' => $count, 'datacheck' => $datacheck]);
            }
        } else { //Old Imported Records

            //$report_search_old_bill_res = ConsumerBillReport::getOldSearchResult($seq_number,$meter_no,$corp_ward,$con_type,$from_date,$to_date,$search_key,$start_limit,$paginate_count);


            $response = array();

            $report_search_old_bill_res = ConsumerBillReport::getOldSearchResult($seq_number, $meter_no, $corp_ward, $con_type, $from_date, $to_date, $search_key, $start_limit, $paginate_count);
            $check = $report_search_old_bill_res->get();

            $count = count($check);
            if ($count == 0) {

                $message = 'No Data';
                $result = view('admin.bill_old_report_table', ['result' => '0', 'report_search_data' => $message, 'i' => 0, 'list_count' => $paginate_count, 'keysearch' => $search_key]);
            } else {

                $report_search_data = $report_search_old_bill_res->paginate($paginate_count);
                $result = view('admin.bill_old_report_table', ['result' => '1', 'report_search_data' => $report_search_data, 'i' => 0, 'list_count' => $paginate_count, 'keysearch' => $search_key, 'rowcount' => $count, 'datacheck' => $datacheck]);
               
            }


            $report_search_old_pay_res = ConsumerBillReport::getOldPaymentDetails($seq_number, $meter_no, $corp_ward, $con_type, $from_date, $to_date, $search_key, $start_limit, $paginate_count);
            $check = $report_search_old_pay_res->get();
            $count = count($check);
            if ($count == 0) {

                $message = 'No Data';
                $result.= view('admin.bill_old_pay_report_table', ['result' => '0', 'report_search_data' => $message, 'i' => 0, 'list_count' => $paginate_count, 'keysearch' => $search_key]);
            } else {

                $report_search_data = $report_search_old_pay_res->paginate($paginate_count);
                $result.= view('admin.bill_old_pay_report_table', ['result' => '1', 'report_search_data' => $report_search_data, 'i' => 0, 'list_count' => $paginate_count, 'keysearch' => $search_key, 'rowcount' => $count, 'datacheck' => $datacheck]);
            }


            echo $result;
            //return re$response
        }
    }

    public function billReportPrint(Request $request)
    {
  
        $arrParams = $request->all();
        $sequence_number = $arrParams['sequence_number'];
	$meter_no= $arrParams['meter_no'];
        $corp_ward = $arrParams['corp_ward'];
        $con_type= $arrParams['con_type'];
        $from_date_format= $arrParams['from_date'];
        $to_date_format= $arrParams['to_date'];
        $paginate_count=$arrParams['rowcount']; 
        $search_key=$arrParams['search_key'];
        $start_limit=$arrParams['start_limit'];
        $datacheck=$arrParams['datacheck'];
        //$end_limit=$arrParams['end_limit'];
        $from_date=$from_date_format!='' ? date("Y-m-d", strtotime($from_date_format)) :'0';
        $to_date=$to_date_format!='' ? date("Y-m-d", strtotime($to_date_format)) : '0';
        $report_search_data=array();
       if($datacheck==0)
       {
        $report_search_data['meter_reading'] = ConsumerBillReport::getSearchResult($sequence_number,$meter_no,$corp_ward,$con_type,$from_date,$to_date,$search_key,$start_limit,$paginate_count)->get();
       }
       else
       {
           $report_search_old_bill_res = ConsumerBillReport::getOldSearchResult($sequence_number, $meter_no, $corp_ward, $con_type, $from_date, $to_date, $search_key, 0, 1000);
           $report_search_data['meter_reading'] = $report_search_old_bill_res->get();
           $report_search_old_pay_res = ConsumerBillReport::getOldPaymentDetails($sequence_number, $meter_no, $corp_ward, $con_type, $from_date, $to_date, $search_key,0,1000);
           $report_search_data['old_pay_data'] = $report_search_old_pay_res->get();
       }

       // $report_search_data= $report_search_data_res->get();
        $view = View("admin/bill_report_print", ['result' =>'1',"report_search_data" => $report_search_data,'datacheck'=>$datacheck])->render();
        $headers = array('Content-Type' => 'application/pdf');
        $pdf = \App::make('dompdf.wrapper', $headers);
        $pdf->setOptions(["isHtml5ParserEnabled" => TRUE, "isPhpEnabled" => TRUE, "isRemoteEnabled" => TRUE]);
        $pdf->loadHTML($view)->setPaper('a2', 'landscape');
        //$pdf->setPaper([0, 0, 595, 870], 'landscape');
        return $pdf->stream('billinfo.pdf');

      
    }
    
    public function excelReport(Request $request) {
	
        ini_set('max_execution_time', 0);
        set_time_limit(0);
        ini_set('memory_limit', '-1');

        $arrParams = $request->all();
        $sequence_number = $arrParams['sequence_number'];
     	$meter_no= $arrParams['meter_no'];
        $corp_ward = $arrParams['corp_ward'];
        $con_type= $arrParams['con_type'];
        $from_date_format= $arrParams['from_date'];
        $to_date_format= $arrParams['to_date'];
        $from_date=$from_date_format!='' ? date("Y-m-d", strtotime($from_date_format)) :'0';
        $to_date=$to_date_format!='' ? date("Y-m-d", strtotime($to_date_format)) : '0';
        $datacheck= $arrParams['datacheck'];
        $search_key=$arrParams['search_key'];
        if($datacheck==0)
        {
            
            $payments=ConsumerBillReport::getSearchResult($sequence_number,$corp_ward,$meter_no,$con_type,$from_date,$to_date,$search_key,0,1000)->get();
            
            
            
           
            $readingArray = []; 
            
            foreach ($payments as $payment) {
                $oba = \Helper::calculateOba($payment->sequence_number, $payment->id);
                $cba = 0;
                if($payment->payment_status ===1){
                    $cba = $payment->extra_amount;
                }
                else {
                    $cba = $payment->total_amount;
                }
                $payment->cba = $cba;
                $payment->oba = $oba;
                $payment = (array)$payment;
                $readingArray[] = $payment;
            }
            // echo "<pre>";
            //print_r($readingArray);
            //exit();
        // Generate and return the spreadsheet
            
            //consumer_bill_report_new_data_excel.blade
            $report_search_data['meter_reading'] = $readingArray;
        Excel::create('meter_reading_report', function($excel) use ($report_search_data) {

            // Set the spreadsheet title, creator, and description
            $excel->setTitle('Consumer Billing Report Details');
            
            $excel->setDescription('Consumer Billing Report Details');
            
            $excel->sheet('ConsumerBillingReport', function($sheet) use ($report_search_data) {
                $sheet->loadView('admin.Reports.consumer_bill_report_new_data_excel', $report_search_data)->with('report_search_data',$report_search_data);
                //$sheet->fromArray($paymentsArray, null, 'A1', false, false);
            });
                

        })->download('xlsx');
       }
       else
       {
        
        $excel_data['paymentArray'] = '';
         $report_search_old_bill_res = ConsumerBillReport::getOldSearchResult($sequence_number, $meter_no, $corp_ward, $con_type, $from_date, $to_date, 0, 0, 1000);
         $report_search_data['meter_reading'] = $report_search_old_bill_res->get();
         $report_search_old_pay_res = ConsumerBillReport::getOldPaymentDetails($sequence_number, $meter_no, $corp_ward, $con_type, $from_date, $to_date, 0,0,1000);
         $report_search_data['old_pay_data'] = $report_search_old_pay_res->get();
         
       // $excel_data['type'] = $type;
     /*   
        $fileName = "DCB Report";
        if ($type == 1) {
            $fileName.="-TARIFFWISE-";
        } else {
            $fileName.="-CORP-WARDWISE-";
        }
        */
       // $fileName.=date("d_m_Y");
       $fileName='Consumer-Billing-Report-Details-olddata';
        // Generate and return the spreadsheet
        Excel::create($fileName, function($excel) use ($report_search_data) {

            // Set the spreadsheet title, creator, and description
            $excel->setTitle('DCB Report');
            //$excel->setCreator('Laravel')->setCompany('WJ Gilmore, LLC');
            $excel->setDescription('Meter Reading Report file');

            // Build the spreadsheet, passing in the payments array
            $excel->sheet('DCB Report', function($sheet) use ($report_search_data) {
                $sheet->loadView('admin.Reports.bill_report_excel', $report_search_data)->with('datacheck',1 )->with('result',1 )->with('report_search_data',$report_search_data);
                //$sheet->fromArray($paymentsArray, null, 'A1', false, false);
            });
        })->download('xlsx');
       }
}

}
