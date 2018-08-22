<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\User;
use Datatables;
use Response;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\ChallanReport;
use App\Models\PaymentHistory;
use App\Models\BankInfo;

class ChallanReportController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $bank_info_obj = BankInfo::select('bank_name')->where('is_olddata', 0)->whereNotNull('bank_name')->distinct()->get();
        if ($bank_info_obj)
            $data['banks'] = $bank_info_obj->toArray();
        return view('admin.challan_report', $data);
    }

    public function reportSearch(Request $request) {
        $from_date_format = $request->from_date;
        $to_date_format = $request->to_date;
        //$from_date = ($from_date_format != '' ? date("Y-m-d", strtotime($from_date_format)) : '0');
        //$to_date = ($to_date_format != '' ? date("Y-m-d", strtotime($to_date_format)) : '0');  
        $data['from_date'] = ($from_date_format != '' ? date("Y-m-d", strtotime($from_date_format)) : NULL);
        $data['to_date'] = ($to_date_format != '' ? date("Y-m-d", strtotime($to_date_format)) : NULL);
        $data['bank_name'] = $request->bank_name;
        $report_data = PaymentHistory::bankWiseCollectionReport($data)->get()->toArray(); //dd($report_data);
        return Datatables::of($report_data)
                        ->addColumn('paymentDate', function ($applictaionList) {
                            return date("d-m-Y", strtotime($applictaionList['payment_date']));
                        })
                        ->make(true);
    }

    public function printReport(Request $request) {
        ini_set('max_execution_time', 0);
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        $requestData = $request->all();
        $from_date_req = $requestData['from_date'];
        $to_date_req = $requestData['to_date'];
        //$from_date = ($from_date_req != '' ? date("Y-m-d", strtotime($from_date_req)) : '0');
        //$to_date = ($to_date_req != '' ? date("Y-m-d", strtotime($to_date_req)) : '0');
        //$report_data = ChallanReport::getSearchResult($from_date, $to_date);
        $data['from_date'] = ($from_date_req != '' ? date("Y-m-d", strtotime($from_date_req)) : NULL);
        $data['to_date'] = ($to_date_req != '' ? date("Y-m-d", strtotime($to_date_req)) : NULL);
        $data['bank_name'] = $request->bank_name;
        $report_data = PaymentHistory::bankWiseCollectionReport($data)->get()->toArray();
        $total_collection = PaymentHistory::totalCollection($data)->first()->toArray();
        $branchWiseCollection = PaymentHistory::totalCollection($data)->groupBy('bank_info.branch_name')->get()->toArray();        
        $view = View("admin/challan_report_printpdf", ["data" => $report_data,'branchWiseCollection'=>$branchWiseCollection,'total_collection' => $total_collection['total_collection']])->render();
        $headers = array('Content-Type' => 'application/pdf');
        $pdf = \App::make('dompdf.wrapper', $headers);
        $pdf->setOptions(["isHtml5ParserEnabled" => TRUE, "isPhpEnabled" => TRUE, "isRemoteEnabled" => TRUE]);
        $pdf->loadHTML($view)->setPaper('a2', 'landscape');
        return $pdf->stream('challanReport.pdf');
    }

    public function printExcelReport(Request $request) {
        ini_set('max_execution_time', 0);
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        $arrParams = $request->all();
        $from_date_format = $arrParams['from_date'];
        $to_date_format = $arrParams['to_date'];
        /* $from_date = ($from_date_format != '' ? date("Y-m-d", strtotime($from_date_format)) : '0');
          $to_date = ($to_date_format != '' ? date("Y-m-d", strtotime($to_date_format)) : '0');
          if ($from_date_format != '') {
          $from_date = date("Y-m-d", strtotime($from_date_format));
          } else {
          $from_date = '0';
          }
          if ($to_date_format != '') {
          $to_date = date("Y-m-d", strtotime($to_date_format));
          } else {
          $to_date = '0';
          }
          $report_data = ChallanReport::getSearchResult($from_date, $to_date); */
        $data['from_date'] = ($from_date_format != '' ? date("Y-m-d", strtotime($from_date_format)) : NULL);
        $data['to_date'] = ($to_date_format != '' ? date("Y-m-d", strtotime($to_date_format)) : NULL);
        $data['bank_name'] = $request->bank_name;
        $report_data = PaymentHistory::bankWiseCollectionReport($data)->get()->toArray();
        $paymentsArray = [];
        $paymentsBranchWise = [];
        $totalCollection = 0;

        $resultArray[0] = ['Sl.No',
            //'Sequence Number',
            //'Consumer Name',
            //'Mobile No', 
            //'Challan No ', 
            'Bank Name',
            'Payment Date',
            'Branch Name',
            //'Payment Mode ', 
            //'Cheque/DD', 
            //'Transaction Number', 
            'Total Amount'];
        $i = 1;
        if (!empty($report_data)) {
            foreach ($report_data as $data) {
                /* $rowdata = [];
                  $rowdata['slno'] = $i;
                  $rowdata['sequence_number'] = $data['sequence_number'];
                  $rowdata['name'] = $data['consumer_name'];
                  $rowdata['mobile_no'] = ($data['mobile_no'] == '0'? '': $data['mobile_no']);
                  $rowdata['challan_no'] = $data['challan_no'];
                  $rowdata['bank_name'] = $data['bank_name'];
                  $rowdata['payment_date'] = date("d-m-Y",strtotime($data['payment_date']));
                  $rowdata['branch_name'] = $data['branch_name'];
                  $rowdata['payment_mode'] = $data['mode_type'];
                  $rowdata['cheque_dd'] = $data['cheque_dd'];
                  $rowdata['transaction_number'] = $data['transaction_number'];
                  $rowdata['total_amount'] = $data['total_amount'];
                  $rowdata['total_amount'] = $data['total_collection'];
                  $resultArray[] = $rowdata; */

                $resultArray[$i] = array('slno' => $i,
                    'bank_name' => $data['bank_name'],
                    'payment_date' => date("d-m-Y", strtotime($data['payment_date'])),
                    'branch_name' => $data['branch_name'],
                    'total_amount' => $data['total_collection']
                );

                $i++;
                if (isset($paymentsBranchWise[$data['branch_name']])) {
                    $paymentsBranchWise[$data['branch_name']] = $paymentsBranchWise[$data['branch_name']] + $data['total_collection'];
                } else {
                    $paymentsBranchWise[$data['branch_name']] = $data['total_collection'];
                }
                $totalCollection += $data['total_collection'];
            }
            $resultArray[$i++] = array('slno' => '');
            foreach ($paymentsBranchWise as $key => $val) {
                $resultArray[$i++] = array('slno' => '',
                    'bank_name' => '',
                    'payment_date' => '',
                    'branch_name' => $key,
                    'total_amount' => $val
                );
            }
            $resultArray[$i++] = array('slno' => '',
                'bank_name' => '',
                'payment_date' => '',
                'branch_name' => 'Grand Total',
                'total_amount' => $totalCollection
            );
        }


        Excel::create('challan_report', function($excel) use ($resultArray, $paymentsBranchWise, $totalCollection) {
            $excel->setTitle('Challan Report');
            $excel->setCreator('Laravel')->setCompany('WJ Gilmore, LLC');
            $excel->setDescription('Challan Report file');
            $excel->sheet('sheet1', function($sheet) use ($resultArray) {
                $sheet->fromArray($resultArray, null, 'A1', false, false);
            });
        })->download('xlsx');
    }
    
    public static function getTotalCollection(Request $request){
        $from_date_format = $request->from_date;
        $to_date_format = $request->to_date;
        $data['from_date'] = ($from_date_format != '' ? date("Y-m-d", strtotime($from_date_format)) : NULL);
        $data['to_date'] = ($to_date_format != '' ? date("Y-m-d", strtotime($to_date_format)) : NULL);
        $data['bank_name'] = $request->bank_name;
        $totalCollection = PaymentHistory::totalCollection($data)->first()->toArray();
        echo $totalCollection['total_collection'];
    }

}
