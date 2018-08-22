<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\User;
use App\Models\BillPayment;
use App\Models\BankInfo;
use App\Models\MeterReading;
use Illuminate\Support\Facades\Validator;
use Datatables;
use Barryvdh\DomPDF\Facade as PDF;
use Response;
use Carbon\Carbon;
use App\Helpers\UserLogHelper;

class BillPaymentController extends Controller {

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

        return view('admin.payment');
    }

    public function getDetails(Request $request) {

        $columns = array(
            0 => 'name',
            1 => 'meter_no',
            2 => 'total_amount',
            3 => 'date_of_reading',
            4 => 'payment_due_date',
            5 => 'payment_date',
            6 => 'paystatus',
            7 => 'payment'
        );

        $totalData = BillPayment::getPaymentCount();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $payDetails = BillPayment::getPaymentInfo($start, $limit, $order, $dir, $search);

        if (!empty($search)) {
            $totalFiltered = BillPayment::getPaymentInfoCount($search);
        }
        $data = array();
        if (!empty($payDetails)) {
            foreach ($payDetails as $payDetail) {

                $nestedData['name'] = $payDetail->name;
                $nestedData['meter_no'] = $payDetail->meter_no;
                $nestedData['sequence_number'] = $payDetail->sequence_number;
                $nestedData['date_of_reading'] = $payDetail->date_of_reading;
                $nestedData['payment_due_date'] = $payDetail->payment_due_date;
                $nestedData['payment_date'] = $payDetail->payment_date;
                $nestedData['total_amount'] = $payDetail->total_amount;
                if ($payDetail->payment_status == 0) {

                    $nestedData['paystatus'] = '<p data-placement="top" data-toggle="tooltip" title="Unpaid">';
                } else if ($payDetail->payment_status == 1) {

                    $nestedData['paystatus'] = '<p data-placement="top" data-toggle="tooltip" title="Paid"><a href="#"><span class="badge badge-primary">Paid</span></p>';
                } else if ($payDetail->payment_status == 2) {
                    $nestedData['paystatus'] = '<p data-placement="top" data-toggle="tooltip" title="Verify Payment"><button type="button" class="btn btn-warning" id="verifyBtn" data-value="' . $payDetail->id . '" data-title="Edit" data-toggle="modal" data-target="#verify">Verify</button>';
                }
                if ($payDetail->payment_status == 0) {

                    $nestedData['payment'] = '<p data-placement="top" data-toggle="tooltip" title="Pay Bill"><a href=""  data-value="' . $payDetail->id . '" class="btn btn-danger payBtn">&nbsp;Pay</a></p>';
                } else if ($payDetail->payment_status == 1) {

                    $nestedData['payment'] = '<p data-placement="top" data-toggle="tooltip" title="View Paid Details"><a href="" class="btn btn-default" data-title="Edit" id="viewBtn" data-value="' . $payDetail->id . '" data-toggle="modal" data-target="#payment_detail">View</a></p>';
                } else if ($payDetail->payment_status == 2) {
                    $nestedData['payment'] = '<p data-placement="top" data-toggle="tooltip" title="View pending details"><a href="" class="btn btn-default" data-title="Edit"  id="viewBtn" data-value="' . $payDetail->id . '"  data-toggle="modal" data-target="#payment_detail">View</a></p>';
                }


                $data[] = $nestedData;
            }
        }
        $json_data = array(
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );

        echo json_encode($json_data);
    }

    public function paymentSearch(Request $request) {

        $seq_number = $request->seq_number;
        $name = $request->name;
        $meter_no = $request->meter_no;
        $payment_search_data = BillPayment::getSearchResult($seq_number, $name, $meter_no);
        return Datatables::of($payment_search_data)
                        ->addColumn('paystatus', function ($payment_search_data) {
                            if ($payment_search_data->payment_status == 0) {

                                return '<p data-placement="top" data-toggle="tooltip" title="Unpaid">';
                            } else if ($payment_search_data->payment_status == 1) {

                                return '<p data-placement="top" data-toggle="tooltip" title="Paid"><a href="#"><span class="badge badge-primary">Paid</span></p>';
                            } else if ($payment_search_data->payment_status == 2) {
                                $role1 = \Helper::getRole();
                                $sub_category = $role1->sub_category_name;
                                $category = $role1->category_name;

                                if ((strcasecmp($sub_category, "MCC") == 0) || (strcasecmp($category, "Super Admin") == 0)) {
                                    return '<p data-placement="top" data-toggle="tooltip" title="Verify Payment"><button type="button" class="btn btn-warning" id="verifyBtn" data-value="' . $payment_search_data->id . '" data-title="Edit" data-toggle="modal" data-target="#verify">Verify</button>';
                                } else if ((strcasecmp($sub_category, "Bank") == 0)) {
                                    return '<p data-placement="top" data-toggle="tooltip" title="Paid"><a href="#"><span class="badge badge-primary">Verify</span></p>';
                                }
                            }
                        })
                        ->addColumn('payment', function ($payment_search_data) {
                            if ($payment_search_data->payment_status == 0) {

                                return '<p data-placement="top" data-toggle="tooltip" title="Pay Bill"><a href=""  data-value="' . $payment_search_data->id . '" class="btn btn-danger payBtn">&nbsp;Pay</a></p>';
                            } else if ($payment_search_data->payment_status == 1) {

                                return '<p data-placement="top" data-toggle="tooltip" title="View Paid Details"><a href="" class="btn btn-default" data-title="Edit" id="viewBtn" data-value="' . $payment_search_data->id . '" data-toggle="modal" data-target="#payment_detail">View</a></p>';
                            } else if ($payment_search_data->payment_status == 2) {
                                return '<p data-placement="top" data-toggle="tooltip" title="View pending details"><a href="" class="btn btn-default" data-title="Edit"  id="viewBtn" data-value="' . $payment_search_data->id . '"  data-toggle="modal" data-target="#payment_detail">View</a></p>';
                            }
                        })
                        ->rawColumns(['payment' => 'payment', 'paystatus' => 'paystatus'])
                        ->make(true);
    }

    /**
     * Function name : viewPaymentInfo
     * Purpose       : Function to get all payemnt related details of consumer
     * Added Date    : March 5th, 2018
     * Updated Date  : 
     */
    public function viewPaymentInfo(Request $request) {

        $pay_info = BillPayment::getviewPaymentInfo($request->meter_reading_id);
        foreach ($pay_info as $getdata) {

            $pay_info['payment_date'] = $getdata->payment_date;
            $pay_info['payment_status'] = $getdata->payment_status;
            $pay_info['sequence_number'] = $getdata->sequence_number;
            $pay_info['name'] = $getdata->name;
            $pay_info['door_no'] = $getdata->door_no;
            $pay_info['meter_no'] = $getdata->meter_no;
            $pay_info['ward_name'] = $getdata->ward_name;
            $pay_info['corp_name'] = $getdata->corp_name;
            $pay_info['meter_status'] = $getdata->meter_status;
            $pay_info['connection_name'] = $getdata->connection_name;
            $pay_info['bank_name'] = $getdata->bank_name;
            $pay_info['branch_name'] = $getdata->branch_name;
            $pay_info['cheque_dd'] = $getdata->cheque_dd;
            $pay_info['remarks'] = $getdata->remarks;
            $pay_info['challan_no'] = $getdata->challan_no;
            $pay_info['date_of_reading'] = $getdata->date_of_reading;
            $pay_info['bill_no'] = $getdata->bill_no;
            $pay_info['meter_rent'] = $getdata->meter_rent;
            $pay_info['payment_due_date'] = $getdata->payment_due_date;
            $pay_info['previous_billing_date'] = $getdata->previous_billing_date;
            $pay_info['total_unit_used'] = $getdata->total_unit_used;
            $pay_info['no_of_days_used'] = $getdata->no_of_days_used;
            $pay_info['previous_reading'] = $getdata->previous_reading;
            $pay_info['current_reading'] = $getdata->current_reading;
            $pay_info['no_of_flats'] = $getdata->no_of_flats;
            $pay_info['water_charge'] = $getdata->water_charge;
            $pay_info['supervisor_charge'] = $getdata->supervisor_charge;
            $pay_info['other_charges'] = $getdata->other_charges;
            $pay_info['refund_amount'] = $getdata->refund_amount;
            $pay_info['other_title_charge'] = $getdata->other_title_charge;
            $pay_info['fixed_charge'] = $getdata->fixed_charge;
            $pay_info['penalty'] = $getdata->penalty;
            $pay_info['cess'] = $getdata->cess;
            $pay_info['ugd_cess'] = $getdata->ugd_cess;
            $arrears_amount = $getdata->total_due - ($getdata->water_charge + $getdata->other_charges + $getdata->penalty);
            $pay_info['arrears'] = $arrears_amount;
            $pay_info['total_due'] = $getdata->total_due;
            $pay_info['round_off'] = $getdata->round_off;
            $pay_info['total_amount'] = $getdata->total_amount;
            $pay_info['advance_amount'] = $getdata->advance_amount;
        }

        if ($pay_info) {
            return Response::json(['success' => '1', 'pay_info' => $pay_info]);
        } else {
            return Response::json(['errors' => 'Error Occured']);
        }
    }

    public function payBillPage(Request $request) {

        $pay_info = BillPayment::getPayBillInfo($request->meter_reading_id);
        $date = Carbon::now();
        $user = Auth::user();
        $cat_id = $user->cat_id;
        $user_id = $user->id;
        if ($cat_id == '5') {
            $get_bank_details = BillPayment::getBankUserDetails($user_id);
            $bank_name = $get_bank_details->bank_name;
            $branch_name = $get_bank_details->bank_branch;
            return view('admin.payment_detail', ['pay_info' => $pay_info, 'date' => $date, 'branch_name' => $branch_name, 'bank_name' => $bank_name]);
        } else {
            return view('admin.payment_detail', ['pay_info' => $pay_info, 'date' => $date]);
        }
    }

    public function addNewPayment(Request $request) {
        $validator = Validator::make($request->all(), [
                    'bank_name' => 'required|max:255',
                    'branch_name' => 'required|max:255',
                    'cheque_dd' => 'required|min:6',
                    'total_amount' => 'required|numeric',
        ]);
        if ($validator->passes()) {
            $paydate = $request->payment_date;
            //$payment_date=date("Y-m-d", strtotime($paydate) );
            $payment_entry = BillPayment::create([
                        'sequence_number' => $request->seq_number,
                        'meter_reading_id' => $request->meter_reading_id,
                        'payment_date' => $paydate,
                        'total_amount' => $request->total_amount,
                        'payment_mode' => '1',
                        'payment_status' => $request->payment_section
            ]);
            $current_year = date("Y");
            $current_month = date("m");
            $current_day = date("d");
            $challan_seq_no = $request->seq_number;
            $challan_number = $challan_seq_no . 'MNG' . $current_day . $current_month . $current_year;
            $bankdetails = BankInfo::create([
                        'payment_id' => $payment_entry->id,
                        'bank_name' => $request->bank_name,
                        'branch_name' => $request->branch_name,
                        'cheque_dd' => $request->cheque_dd,
                        'remarks' => $request->remarks,
                        'challan_no' => $challan_number
            ]);
            $update_pay_status = MeterReading::where('id', $request->meter_reading_id)
                    ->update(['payment_status' => $request->payment_section, 'arrears' => '0', 'advance_amount' => $request->advance_amount]);

            $get_pdf_info = BillPayment::getPaymentPdfInfo($request->seq_number, $request->meter_reading_id);
            foreach ($get_pdf_info as $getdata) {

                $payable_amount = $getdata->total_amount + $getdata->advance_amount;
                //$f = new \NumberFormatter("en_IN", \NumberFormatter::SPELLOUT);
                //$result = $f->format($payable_amount);
                $result = number_format((float) $payable_amount, 2, '.', '');
                $filldata['meter_reading_id'] = $request->meter_reading_id;
                $filldata['amount_in_words'] = ucwords($result);
                $filldata['bank_name'] = $getdata->bank_name;
                $filldata['bill_no'] = $getdata->bill_no;
                $filldata['branch_name'] = $getdata->branch_name;
                $filldata['challan_no'] = $getdata->challan_no;
                if (empty($getdata->premises_address)) {
                    $filldata['comm_address'] = $getdata->ward_name . '/' . $getdata->door_no;
                } else {
                    $address_string = $getdata->premises_address;
                    $filldata['comm_address'] = nl2br($address_string);
                }
                $filldata['date_of_reading'] = $getdata->date_of_reading;
                $filldata['payment_date'] = $getdata->payment_date;
                $filldata['meter_no'] = $getdata->meter_no;
                $filldata['name'] = $getdata->name;
                $filldata['set_advance_amt'] = $getdata->advance_amount;

                //water charge
                $watercharge = $getdata->water_charge;
                $fraction = number_format((float) $watercharge, 2, '.', '');
                $pieces = explode('.', $fraction);
                $filldata['water_charge'] = $pieces[0];
                $filldata['water_fraction'] = $pieces[1];

                //Supervisor charge
                $supercharge = $getdata->supervisor_charge;
                $fraction = number_format((float) $supercharge, 2, '.', '');
                $pieces = explode('.', $fraction);
                $filldata['supervisor_charge'] = $pieces[0];
                $filldata['supervisor_fraction'] = $pieces[1];

                //Other charge
                $other_charges = $getdata->other_charges;
                $fraction = number_format((float) $other_charges, 2, '.', '');
                $pieces = explode('.', $fraction);
                $filldata['other_charges'] = $pieces[0];
                $filldata['other_fraction'] = $pieces[1];


                //Penality Charge
                $penalty = $getdata->penalty;
                $fraction = number_format((float) $penalty, 2, '.', '');
                $pieces = explode('.', $fraction);
                $filldata['penalty'] = $pieces[0];
                $filldata['penalty_fraction'] = $pieces[1];

                //Returned Amount

                $returned_amount = $getdata->refund_amount;
                $fraction = number_format((float) $returned_amount, 2, '.', '');
                $pieces = explode('.', $fraction);
                $filldata['returned_amount'] = $pieces[0];
                $filldata['return_fraction'] = $pieces[1];

                //Cess Amount
                $cess_amount = $getdata->cess;
                $fraction = number_format((float) $cess_amount, 2, '.', '');
                $pieces = explode('.', $fraction);
                $filldata['cess'] = $pieces[0];
                $filldata['cess_fraction'] = $pieces[1];



                //Round off
                $round_off = $getdata->round_off;
                $fraction = number_format((float) $round_off, 2, '.', '');
                $pieces = explode('.', $fraction);
                $filldata['round_off'] = $pieces[0];
                $filldata['round_fraction'] = $pieces[1];


                //Total Amount
                $total_amount = $getdata->total_amount;
                $fraction = number_format((float) $total_amount, 2, '.', '');
                $pieces = explode('.', $fraction);
                $filldata['total_amount'] = $pieces[0];
                $filldata['total_fraction'] = $pieces[1];

                //Advance Amount
                $total_amount = $getdata->advance_amount;
                $fraction = number_format((float) $total_amount, 2, '.', '');
                $pieces = explode('.', $fraction);
                $filldata['advance_amount'] = $pieces[0];
                $filldata['advance_fraction'] = $pieces[1];



                //Arrers
                //$arrears_amount=$getdata->arrears;
                $total_due = $getdata->total_due;
                $arrears_amount = $total_due - ($watercharge + $other_charges + $penalty);
                $fraction = number_format((float) $arrears_amount, 2, '.', '');
                $pieces = explode('.', $fraction);
                $filldata['arrears'] = $pieces[0];
                $filldata['arrears_fraction'] = $pieces[1];

                //Payable Amount
                $total_amount = $payable_amount;
                $fraction = number_format((float) $total_amount, 2, '.', '');
                $pieces = explode('.', $fraction);
                $filldata['payable_amount'] = $pieces[0];
                $filldata['payable_fraction'] = $pieces[1];

                $previous_billing_date = $getdata->previous_billing_date;
                if ($previous_billing_date == '0000-00-00 00:00:00') {
                    $filldata['previous_billing_date'] = $getdata->connection_date;
                } else {
                    $filldata['previous_billing_date'] = $getdata->previous_billing_date;
                }

                $filldata['sequence_number'] = $getdata->sequence_number;
            }
            $action = "Challan generated for " . $challan_number;
            UserLogHelper::createUserLog($challan_seq_no, 'challan_generation', $action);
            return Response::json(['success' => '1', 'filldata' => $filldata]);
        }
        return Response::json(['errors' => $validator->errors()]);
    }

    public function verifyPayment(Request $request) {
        $bankinfo = BillPayment::getBankInfo($request->meter_reading_id);

        foreach ($bankinfo as $getdata) {

            $bank_info['meter_reading_id'] = $request->meter_reading_id;
            $bank_info['payment_id'] = $getdata->payment_id;
            $bank_info['bank_name'] = $getdata->bank_name;
            $bank_info['branch_name'] = $getdata->branch_name;
            //$bank_info['payment_date']=$getdata->payment_date;
        }

        return Response::json(['success' => '1', 'bank_info' => $bank_info]);
    }

    public function updateVerifyPayment(Request $request) {

        $validator = Validator::make($request->all(), [
                    'bank_name' => 'required|max:255',
                    'branch_name' => 'required|max:255',
                    'transaction_number' => 'required|min:6',
                    'payment_date' => 'required|date'
        ]);
        if ($validator->passes()) {
            $paydate = $request->payment_date;
            $payment_date = date("Y-m-d", strtotime($paydate));

            $update_bank_info = BankInfo::where('payment_id', $request->hidden_pay_id)
                    ->update(['bank_name' => $request->bank_name, 'branch_name' => $request->branch_name, 'transaction_number' => $request->transaction_number, 'remarks' => $request->remarks]);
            $update_pay_history = BillPayment::where('id', $request->hidden_pay_id)
                    ->update(['payment_date' => $payment_date, 'payment_status' => '1']);
            $update_meter_reading = MeterReading::where('id', $request->hidden_mr_id)
                    ->update(['payment_status' => '1', 'arrears' => '0']);

            return Response::json(['success' => '1']);
        }
        return Response::json(['errors' => $validator->errors()]);
    }

    public function duplicateBill() {
        return view('admin.duplicatebill');
    }

    public function getDuplicateBillInfo(Request $request) {

        $arrParams = $request->all();

        $billinfo = BillPayment::getDuplicateBillInfo($arrParams);
        if(!empty($billinfo)) {
            if($billinfo[0]->reviewer !== null && ($billinfo[0]->approved_by === null || $billinfo[0]->approved_by ==0)) {
                return array("error"=>"Duplicate Bill can be generated once bill approved");
            }
        }
        
        $action = 'Duplicate bill generated';
        UserLogHelper::createUserLog($arrParams['sequence_number'], 'billgenerated', $action);
        //$billinfo1['sequence_number'] = $billinfo[0]->sequence_number;
        return $billinfo;
    }

    public function printDuplicateBill(Request $request) {
        $arrParams = $request->all();
        $arrQueryParams = array();
        $arrQueryParams['sequence_number'] = $arrParams['sn'];
        $arrQueryParams['meter_number'] = $arrParams['mn'];
        
        $action = 'Duplicate bill printed';
        UserLogHelper::createUserLog($arrQueryParams['sequence_number'], 'billgenerated', $action);
        $billinfo = BillPayment::getDuplicateBillInfo($arrQueryParams);
        
        //$connection_report_data = ConnectionWiseReport::getConnectionSearchResult($ward_id,$from_date,$to_date)->toArray();

        $view = View("admin/duplicatebill_print", ["billinfo" => $billinfo])->render();
        $headers = array('Content-Type' => 'application/pdf');
        $pdf = \App::make('dompdf.wrapper', $headers);
        $pdf->setOptions(["isHtml5ParserEnabled" => TRUE, "isPhpEnabled" => TRUE, "isRemoteEnabled" => TRUE]);
        $pdf->loadHTML($view)->setPaper('a4', 'portrait');
        //$pdf->setPaper([0, 0, 595, 870], 'landscape');
        return $pdf->stream('billinfo.pdf');
    }

    public function printChallanData(Request $request) {
        $arrParams = $request->all();
        $arrQueryParams = array();
        $sequence_number = $arrParams['sequence_number'];
        $meter_reading_id = $arrParams['meter_reading_id'];
        $get_pdf_info = BillPayment::getPaymentPdfInfo($sequence_number, $meter_reading_id);
        foreach ($get_pdf_info as $getdata) {

            $payable_amount = $getdata->total_amount + $getdata->advance_amount;
            $f = new \NumberFormatter("en_IN", \NumberFormatter::SPELLOUT);
            $result = $f->format($payable_amount);
            $filldata['meter_reading_id'] = $request->meter_reading_id;
            $filldata['amount_in_words'] = ucwords($result);
            $filldata['bank_name'] = $getdata->bank_name;
            $filldata['bill_no'] = $getdata->bill_no;
            $filldata['branch_name'] = $getdata->branch_name;
            $filldata['challan_no'] = $getdata->challan_no;
            if (empty($getdata->premises_address)) {
                $filldata['comm_address'] = $getdata->ward_name . '/' . $getdata->door_no;
            } else {
                $address_string = $getdata->premises_address;
                $filldata['comm_address'] = nl2br($address_string);
            }
            $filldata['date_of_reading'] = $getdata->date_of_reading;
            $filldata['payment_date'] = $getdata->payment_date;
            $filldata['meter_no'] = $getdata->meter_no;
            $filldata['name'] = $getdata->name;
            $filldata['set_advance_amt'] = $getdata->advance_amount;

            //water charge
            $watercharge = $getdata->water_charge;
            $fraction = number_format((float) $watercharge, 2, '.', '');
            $pieces = explode('.', $fraction);
            $filldata['water_charge'] = $pieces[0];
            $filldata['water_fraction'] = $pieces[1];

            //Supervisor charge
            $supercharge = $getdata->supervisor_charge;
            $fraction = number_format((float) $supercharge, 2, '.', '');
            $pieces = explode('.', $fraction);
            $filldata['supervisor_charge'] = $pieces[0];
            $filldata['supervisor_fraction'] = $pieces[1];

            //Other charge
            $other_charges = $getdata->other_charges;
            $fraction = number_format((float) $other_charges, 2, '.', '');
            $pieces = explode('.', $fraction);
            $filldata['other_charges'] = $pieces[0];
            $filldata['other_fraction'] = $pieces[1];


            //Penality Charge
            $penalty = $getdata->penalty;
            $fraction = number_format((float) $penalty, 2, '.', '');
            $pieces = explode('.', $fraction);
            $filldata['penalty'] = $pieces[0];
            $filldata['penalty_fraction'] = $pieces[1];

            //Returned Amount

            $returned_amount = $getdata->refund_amount;
            $fraction = number_format((float) $returned_amount, 2, '.', '');
            $pieces = explode('.', $fraction);
            $filldata['returned_amount'] = $pieces[0];
            $filldata['return_fraction'] = $pieces[1];

            //Cess Amount
            $cess_amount = $getdata->cess;
            $fraction = number_format((float) $cess_amount, 2, '.', '');
            $pieces = explode('.', $fraction);
            $filldata['cess'] = $pieces[0];
            $filldata['cess_fraction'] = $pieces[1];



            //Round off
            $round_off = $getdata->round_off;
            $fraction = number_format((float) $round_off, 2, '.', '');
            $pieces = explode('.', $fraction);
            $filldata['round_off'] = $pieces[0];
            $filldata['round_fraction'] = $pieces[1];


            //Total Amount
            $total_amount = $getdata->total_amount;
            $fraction = number_format((float) $total_amount, 2, '.', '');
            $pieces = explode('.', $fraction);
            $filldata['total_amount'] = $pieces[0];
            $filldata['total_fraction'] = $pieces[1];

            //Advance Amount
            $total_amount = $getdata->advance_amount;
            $fraction = number_format((float) $total_amount, 2, '.', '');
            $pieces = explode('.', $fraction);
            $filldata['advance_amount'] = $pieces[0];
            $filldata['advance_fraction'] = $pieces[1];



            //Arrers
            //$arrears_amount=$getdata->arrears;
            $total_due = $getdata->total_due;
            $arrears_amount = $total_due - ($watercharge + $other_charges + $penalty);
            $fraction = number_format((float) $arrears_amount, 2, '.', '');
            $pieces = explode('.', $fraction);
            $filldata['arrears'] = $pieces[0];
            $filldata['arrears_fraction'] = $pieces[1];

            //Payable Amount
            $total_amount = $payable_amount;
            $fraction = number_format((float) $total_amount, 2, '.', '');
            $pieces = explode('.', $fraction);
            $filldata['payable_amount'] = $pieces[0];
            $filldata['payable_fraction'] = $pieces[1];

            $previous_billing_date = $getdata->previous_billing_date;
            if ($previous_billing_date == '0000-00-00 00:00:00') {
                $filldata['previous_billing_date'] = $getdata->connection_date;
            } else {
                $filldata['previous_billing_date'] = $getdata->previous_billing_date;
            }

            $filldata['sequence_number'] = $getdata->sequence_number;
        }
        $view = View("admin/challan_print_table", ["filldata" => $filldata])->render();
        $headers = array('Content-Type' => 'application/pdf');

        $pdf = \App::make('dompdf.wrapper', $headers);
        $pdf->setOptions(["isHtml5ParserEnabled" => TRUE, "isPhpEnabled" => TRUE, "isRemoteEnabled" => TRUE]);
        $pdf->loadHTML($view)->setPaper('a2', 'portrait');
        //$pdf->setPaper([0, 0, 595, 870], 'landscape');
        return $pdf->stream('billinfo.pdf');
    }

}
