<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\User;
use App\Models\CorpWard;
use App\Models\ConnectionType;
use App\Models\WardWiseDCBReport;
use Illuminate\Support\Facades\Validator;
use Datatables;
use Response;
use Maatwebsite\Excel\Facades\Excel;

class WardDCBReportController extends Controller {

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

        $corpwards = CorpWard::all();
        $connType = ConnectionType::all();
        return view('admin.wardwise_dcb_report', ['corpwards' => $corpwards, 'connTypes' => $connType]);
    }

    public function DCBReportSearch(Request $request) {
        $type = $request->type;
        $ward = $request->ward;
        $con_type = $request->con_type;
        $from_date_format = $request->from_date;
        $to_date_format = $request->to_date;
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


        $dcb_report_search_data = WardWiseDCBReport::getWardDCBSearchResult($type, $ward, $con_type, $from_date, $to_date);

        //dd($dcb_report_search_data->get()->toArray());

        return Datatables::of($dcb_report_search_data)
                        ->addColumn('name', function ($applictaionList)use ($type) {
                            if ($type == 1) {
                                return $applictaionList->connection_name;
                            } else {
                                return $applictaionList->corp_name;
                            }
                        })
                        ->addColumn('demand', function ($applictaionList) {
                            return ($applictaionList->water_charge + $applictaionList->other_charges + $applictaionList->penalty);
                        })
                        ->addColumn('revised', function ($applictaionList) {
                            return 0;
                        })
                        ->addColumn('opening_balance', function ($applictaionList) {
                            return ($applictaionList->prev_demand - $applictaionList->prev_collection);
                        })
                        ->addColumn('collection', function ($applictaionList) {
                            return ($applictaionList->collection);
                        })
                        ->addColumn('closing', function ($applictaionList) {
                            $oldBalance = ($applictaionList->prev_demand - $applictaionList->prev_collection);
                            $demand = ($applictaionList->water_charge + $applictaionList->other_charges + $applictaionList->penalty);
                            $collection = $applictaionList->collection;
                            return (($oldBalance + $demand) - $collection);
                        })
                        ->make(true);
    }

    public function DCBReportPrint(Request $request) {

        $arrParams = $request->all();
        $ward = $arrParams['ward'];
        $con_type = $arrParams['con_type'];
        $from_date_format = $arrParams['from_date'];
        $to_date_format = $arrParams['to_date'];
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

        $dcb_report_search_data = WardWiseDCBReport::getWardDCBSearchResult($ward, $con_type, $from_date, $to_date);

        //return view('admin.dcb_report_table', ['dcb_report_array' => $dcb_report_search_data]);
        $view = View("admin/dcb_report_table", ["dcb_report_array" => $dcb_report_search_data])->render();
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
        $type = $request->type;
        $ward = $arrParams['ward'];
        $con_type = $arrParams['con_type'];
        $from_date_format = $arrParams['from_date'];
        $to_date_format = $arrParams['to_date'];
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

        $dcb_report_search_data = WardWiseDCBReport::getWardDCBSearchResult($type, $ward, $con_type, $from_date, $to_date)->get();
       // dd($dcb_report_search_data->toArray());

        $paymentsArray = [];

        $column = "";
        if ($type == 1) {
            $column = "Connection Name";
        } else {
            $column = "Corp Ward";
        }

        

        foreach ($dcb_report_search_data as $payment) {

            $data = array();
            $data['type'] = $type;
            if ($type == 1) {
                $data['name'] = $payment->connection_name; //"Connection Name";
            } else {
                $data['name'] = $payment->corp_name; //"Corp Ward";
            }
            
            $oldBalance = ($payment->prev_demand - $payment->prev_collection);
            $demand = ($payment->water_charge + $payment->other_charges + $payment->penalty);
            $collection = $payment->collection;
            
            $data['total_installation'] = $payment->total_installation;
            $data['live'] = $payment->live;
            $data['bill_count'] = $payment->bill_count;
            $data['total_unit_used'] = $payment->total_unit_used;
            $data['old_balance'] = $oldBalance;
            //$data['old_balance'] = $payment->old_balance;
            $data['water_charge'] = $payment->water_charge;
            $data['other_charges'] = $payment->other_charges;
            $data['penalty'] = $payment->penalty;
            $data['demand'] = $demand;
            $data['collection'] = $collection;

            
            
            
            
            $current_balance = ($oldBalance + $demand) - $collection;
            //$payment->current_balance = $current_balance;
            $data['current_balance'] = $current_balance;
            $paymentsArray[] = $data;
        }
        
        
        $excel_data['paymentArray'] = $paymentsArray;
        $excel_data['type'] = $type;
        
        $fileName = "DCB Report";
        if ($type == 1) {
            $fileName.="-TARIFFWISE-";
        } else {
            $fileName.="-CORP-WARDWISE-";
        }
        
        $fileName.=date("d_m_Y");
        // Generate and return the spreadsheet
        Excel::create($fileName, function($excel) use ($excel_data) {

            // Set the spreadsheet title, creator, and description
            $excel->setTitle('DCB Report');
            //$excel->setCreator('Laravel')->setCompany('WJ Gilmore, LLC');
            $excel->setDescription('Meter Reading Report file');

            // Build the spreadsheet, passing in the payments array
            $excel->sheet('DCB Report', function($sheet) use ($excel_data) {
                $sheet->loadView('admin.Reports.DCBReportExcelView', $excel_data);
                //$sheet->fromArray($paymentsArray, null, 'A1', false, false);
            });
        })->download('xlsx');
    }

}
