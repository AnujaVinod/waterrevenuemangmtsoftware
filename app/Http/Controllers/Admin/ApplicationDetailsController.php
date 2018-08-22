<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Models\ConsumerConnection;
use App\Models\Ward;
use App\Models\CorpWard;
use App\Models\Agent;
use App\Models\ConnectionType;
use App\Models\ApplicationStatus;
use App\Models\Inspector;
use App\Models\ConsumerAddress;
use App\Models\ConnectionStatus;
use App\Models\WardSequence;
use App\Models\ApplicationLedger;
use Illuminate\Support\Facades\Validator;
use Response;
use Carbon\Carbon;
use App\Models\MeterStaus;
use App\Models\ConsumerApplicationDetails;
use App\Models\ConsumerApplication;
use Datatables;
use Illuminate\Support\Facades\Storage;
use App\Helpers\UserLogHelper;

class ApplicationDetailsController extends Controller {

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

        return view('admin.add_application_details');
    }

    public function getInspectorList(Request $request) {
        $wardid = $request->wardid;
        $inspectorlist = ConsumerApplicationDetails::getInspectors($wardid);
        if ($inspectorlist->isNotEmpty()) {
            return Response::json(['success' => '1', 'inspectorlist' => $inspectorlist]);
        } else {
            return Response::json(['success' => '0']);
        }
    }

    public function getInspectorAgent(Request $request) {
        // $inspector_id = $request->inspector_id;
        $corp_ward_id = $request->corp_ward_id;
        $agentlist = ConsumerApplicationDetails::getAgents($corp_ward_id);
        if ($agentlist->isNotEmpty()) {
            return Response::json(['success' => '1', 'agentlist' => $agentlist]);
        } else {
            return Response::json(['success' => '0']);
        }
    }

    public function getList() {
        $applictaionList = ConsumerApplicationDetails::getDisplayList();

        return Datatables::of($applictaionList)
                        ->addColumn('certificate_name', function ($applictaionList) {
                            $data = '';
                            $string = preg_replace('/\.$/', '', $applictaionList->certificate_name);
                            $array = explode(', ', $string);
                            foreach ($array as $value) {

                                $path = asset('storage/images/' . $value);
                                if ($value != '') {

                                    //$data.='<a href="" data-toggle="modal" class="cretificate_popup" data-target="#attach-certificate" data-application="' . $applictaionList->id . '" data-cert_name="' . $path . '" >Click to view Certificate</a><br>';
                                    $data.=$value . '&nbsp; <a onclick="printFile(this)" data-value="' . $value . '" id="imagedownload"><span class="glyphicon glyphicon-download-alt"></span></a><br>';
                                }
                            }
                            return $data;
                        })
                        ->addColumn('document_name', function ($applictaionList) {

                            $data = '';
                            $string = preg_replace('/\.$/', '', $applictaionList->document_name);
                            $array = explode(', ', $string);
                            foreach ($array as $value) {
                                $path = asset('storage/images/' . $value);
                                if ($value != '') {
                                    //$data.='<a href="" data-toggle="modal" class="cretificate_popup" data-target="#attach-certificate" data-application="' . $applictaionList->id . '" data-cert_name="' . $path . '" >Click to view Document</a><br>';
                                    $data.=$value . '&nbsp; <a onclick="printFile(this)" data-value="' . $value . '" id="imagedownload"><span class="glyphicon glyphicon-download-alt"></span></a><br>';
                                }
                            }
                            return $data;
                        })
                        ->addColumn('remarks', function ($applictaionList) {

                            $remarks = $applictaionList->remarks;
                            if ($remarks != '') {
                                return $data = '<a href="" data-toggle="modal" class="remarks_popup" data-target="#view-comment" data-application="' . $applictaionList->id . '" data-remarks="' . $remarks . '" >Click to view Comments</a><br>';
                            }
                        })
                        ->addColumn('edit_option', function ($applictaionList) {

                            return '<button type="button" class="btn btn-danger edit_pop_up" data-toggle="modal" data-application="' . $applictaionList->id . '" data-target="#modal-Edit-tap">Edit</button>';
                        })
                        ->rawColumns(['edit_option' => 'edit_option', 'document_name' => 'document_name', 'certificate_name' => 'certificate_name', 'remarks' => 'remarks'])
                        ->make(true);
    }

    public function getAppInfo(Request $request) {
        $application_id = $request->application_id;
        $applictaionInfo = ConsumerApplicationDetails::getApplicationInfo($application_id);
        $userDesignation = ConnectionStatus::getUserDesignation();
        if ($applictaionInfo) {
            return Response::json(['success' => 'success', 'response' => $applictaionInfo, 'userDesignation' => $userDesignation]);
        } else {
            return Response::json(['errors' => 'Error Occured']);
        }
    }

    public function saveLedgerDetails(Request $request) {
        $validator = Validator::make($request->all(), [
                    'connection_date' => 'required',
                    'agent_code_id' => 'required',
                    'inspector_id' => 'required',
                    'no_of_flats' => 'numeric|min:1'
        ]);
        if ($validator->passes()) {


            $ledger_details = ApplicationLedger::create([
                        'application_id' => $request->application_id,
                        'connection_type_id' => $request->connection_type_id,
                        'agent_code_id' => $request->agent_code_id,
                        'inspector_id' => $request->inspector_id,
                        'no_of_flats' => $request->no_of_flats,
                        'tap_diameter' => $request->tap_diameter,
                        'connection_date' => Carbon::parse($request->connection_date)->format('Y-m-d'),
                        'application_date' => $request->application_date,
                        'deposit_amount' => $request->deposit_amount,
                        'deposit_date' => Carbon::parse($request->deposit_date)->format('Y-m-d'),
                        'application_status_id' => 2,
                        'order_no' => $request->order_no,
                        'deposit_challan_no' => $request->deposit_challan_no,
                        'remarks' => $request->remarks,
                        'connection_charge' => $request->connection_charge,
                        'inserted_by' => auth()->user()->id
            ]);
            $sequence_no = '';
            $applicationDetail = ConsumerApplication::find($request->application_id);
            $action = 'Ledger detail added for ' . $applicationDetail->application_number;
            UserLogHelper::createUserLog($sequence_no, 'New Application', $action);
            return Response::json(['success' => '1']);
        }
        return Response::json(['errors' => $validator->errors()]);
    }

    public function saveMeterDetails(Request $request) {

        $validator = Validator::make($request->all(), [
                    'meter_no' => 'required|unique:consumer_application,meter_no|unique:consumer_connection,meter_no',
                    'meter_sanctioned_date' => 'required',
        ]);
        if ($validator->passes()) {

            $update_meter_info = ConsumerApplication::where('id', $request->application_id)
                    ->update(['meter_no' => $request->meter_no, 'updated_by' => auth()->user()->id,
                'meter_sanctioned_date' => Carbon::parse($request->meter_sanctioned_date)->format('Y-m-d'),
            ]);
            $applicatinoDetail = ConsumerApplication::find($request->application_id);
            $sequence_no = '';
            $action = 'Meter number ' . $request->meter_no . ' is updated for ' . $applicatinoDetail->application_number;
            UserLogHelper::createUserLog($sequence_no, 'New Application', $action);
            return Response::json(['success' => '1']);
        }
        return Response::json(['errors' => $validator->errors()]);
    }

    public function saveApprovalDetails(Request $request) {

        $validator = Validator::make($request->all(), [
                    'approved_by' => 'required',
        ]);
        if ($validator->passes()) {

            $save_approval_info = ConsumerApplication::where('id', $request->application_id)
                    ->update(['approved_by' => $request->approved_by, 'updated_by' => auth()->user()->id
            ]);
            $applicatinoDetail = ConsumerApplication::find($request->application_id);
            $sequence_no = '';
            $action = 'Approval detail is updated for ' . $applicatinoDetail->application_number;
            UserLogHelper::createUserLog($sequence_no, 'New Application', $action);
            return Response::json(['success' => '1']);
        }
        return Response::json(['errors' => $validator->errors()]);
    }

    public function approveApplication(Request $request) {
        $application_id = $request->application_id;
        $get_cus_info = ConsumerApplicationDetails::check_consumer_connection($application_id);

        if ($get_cus_info->isNotEmpty()) {
            return Response::json(['success' => '1']);
        } else {

            $get_meter_app_info = ConsumerApplicationDetails::check_meter_approval($application_id);
            $get_ledger_info = ConsumerApplicationDetails::get_ledger_info($application_id);
            
            foreach ($get_meter_app_info as $consumer_app) {
                $meter_no = $consumer_app->meter_no;
                $approved_by = $consumer_app->approved_by;
            }

            if ($meter_no == '') {
                return Response::json(['success' => 2]);
            } else if ($approved_by == '') {
                return Response::json(['success' => 3]);
            } else if ($get_ledger_info->isEmpty()) {
                return Response::json(['success' => 4]);
            } else {
                $update_app_status = ConsumerApplication::where('id', $application_id)
                        ->update(['application_status_id' => 1, 'updated_by' => auth()->user()->id
                ]);

                $get_app_info = ConsumerApplicationDetails::get_consumer_info($application_id);
                
              /*  foreach ($get_app_info as $consumer_app) {
                    $app_id = $consumer_app->id;
                    $corp_name = $consumer_app->corp_name;
                    $connection_type_id = $consumer_app->connection_type_id;
                    $ward_name = $consumer_app->ward_name;
                    $customer_name = $consumer_app->customer_name;
                    $phone_number = $consumer_app->phone_number;
                    $door_no = $consumer_app->door_no;
                    $khata_no = $consumer_app->khata_no;
                    //$no_of_house = $consumer_app->no_of_house;
                    $ward_id = $consumer_app->ward_id;
                    $corp_ward_id = $consumer_app->corp_ward_id;
                    $meter_no = $consumer_app->meter_no;
                    $meter_sanctioned_date = $consumer_app->meter_sanctioned_date;
                    $connection_date = $consumer_app->connection_date;
                } */
                
                    $app_id = $get_app_info->id;
                    $corp_name = $get_app_info->corp_name;
                    $connection_type_id = $get_app_info->connection_type_id;
                    $ward_name = $get_app_info->ward_name;
                    $customer_name = $get_app_info->customer_name;
                    $phone_number = $get_app_info->phone_number;
                    $door_no = $get_app_info->door_no;
                    $khata_no = $get_app_info->khata_no;
                    //$no_of_house = $consumer_app->no_of_house;
                    $ward_id = $get_app_info->ward_id;
                    $corp_ward_id = $get_app_info->corp_ward_id;
                    $meter_no = $get_app_info->meter_no;
                    $meter_sanctioned_date = $get_app_info->meter_sanctioned_date;
                    $connection_date = $get_app_info->connection_date;
                    
                   
                $ward_two = substr($ward_name, 0, 3);
                $get_last_ward = ConsumerApplicationDetails::getLastSequence($ward_id);
                /* $ward_range=ConsumerApplicationDetails::getWardRange($ward_id);

                  $sequence_from=$ward_range->seq_from;
                  foreach($get_last_ward as $last_seq)
                  {
                  $last_seq_no=$last_seq->sequence_number;

                  }

                  if($last_seq_no=='')
                  {

                  $sequence_number=$ward_two.$sequence_from;

                  }
                  else
                  {
                  $check_substring=substr($last_seq_no, 0, 3);
                  if($ward_two==$check_substring)
                  {
                  $new_seq_no=substr($last_seq_no, strpos($ward_two, "_") + 3);
                  $create_last_no=$new_seq_no+1;
                  $sequence_number=$ward_two.$create_last_no;
                  }
                  else
                  {

                  $sequence_number=$ward_two.$sequence_from;

                  }

                  } */
                foreach ($get_last_ward as $last_seq) {
                    $last_seq_no = $last_seq->sequence_number;
                }
                if ($last_seq_no == '') {

                    $sequence_number = $ward_two . '100001';
                } else {
                    $check_substring = substr($last_seq_no, 0, 3);
                    if ($ward_two == $check_substring) {
                        $new_seq_no = substr($last_seq_no, strpos($ward_two, "_") + 3);
                        $create_last_no = $new_seq_no + 1;
                        $sequence_number = $ward_two . $create_last_no;
                    } else {

                        $sequence_number = $ward_two . '100001';
                    }
                }
                $update_seq_address = ConsumerAddress::where('application_id', $application_id)
                        ->update(['sequence_number' => $sequence_number, 'updated_by' => auth()->user()->id
                ]);
                $new_consumer_connection = ConsumerConnection::create([
                            'application_id' => $app_id,
                            'sequence_number' => $sequence_number,
                            'connection_type_id' => $connection_type_id,
                            'connection_date' => Carbon::parse($request->connection_date)->format('Y-m-d'),
                            'name' => $customer_name,
                            'mobile_no' => $phone_number,
                            'door_no' => $door_no,
                            'khata_no' => $khata_no,
                            'no_of_flats' => $get_ledger_info[0]->no_of_flats,
                            'ward_id' => $ward_id,
                            'corp_ward_id' => $corp_ward_id,
                            'connection_status_id' => 2,
                            'meter_status_id' => 4,
                            'meter_no' => $meter_no,
                            'meter_sanctioned_date' => $meter_sanctioned_date,
                            'deposit_amount' => $get_ledger_info[0]->deposit_amount,
                            'inserted_by' => auth()->user()->id
                ]);
                if ($new_consumer_connection) {
                    $applicationDetail = ConsumerApplication::find($application_id);
                    $action = 'Application (' . $applicationDetail->application_number . ') is approved';
                    UserLogHelper::createUserLog($sequence_number, 'New Application', $action);
                    return Response::json(['success' => '1']);
                } else {

                    return Response::json(['errors' => 0]);
                }
            }
        }
    }

    public function showSuccessPage(Request $request) {
        $application_id = $request->application_id;
        $get_seq_info = ConsumerApplicationDetails::get_sequence_info($application_id);
        foreach ($get_seq_info as $consumer_approve) {

            $customer_name = $consumer_approve->name;
            $meter_no = $consumer_approve->meter_no;
            $sequence_number = $consumer_approve->sequence_number;
        }
        return view('admin.approve_apaplication', ['customer_name' => $customer_name, 'meter_no' => $meter_no, 'sequence_number' => $sequence_number]);
    }

    public function rejectApplication(Request $request) {
        $application_id = $request->application_id;
        $applicationDetail = ConsumerApplication::find($application_id);
        $update_app_status = ConsumerApplication::where('id', $application_id)
                ->update(['application_status_id' => 3, 'updated_by' => auth()->user()->id
        ]);
        $sequence_no = '';
        $action = 'Application (' . $applicationDetail->application_number . ') status changed to REJECTED';
        UserLogHelper::createUserLog($sequence_no, 'New Application', $action);
    }

    public function holdApplication(Request $request) {
        $application_id = $request->application_id;
        $applicationDetail = ConsumerApplication::find($application_id);
        $update_app_status = ConsumerApplication::where('id', $application_id)
                ->update(['application_status_id' => 4, 'updated_by' => auth()->user()->id
        ]);

        $sequence_no = '';
        $action = 'Application (' . $applicationDetail->application_number . ') status changed to ON HOLD';
        UserLogHelper::createUserLog($sequence_no, 'New Application', $action);
    }

    public function checkLedger(Request $request) {
        $application_id = $request->application_id;
        $get_ledger_info = ConsumerApplicationDetails::get_ledger_info($application_id);
        if ($get_ledger_info->isNotEmpty()) {
            return Response::json(['success' => 'success', 'response' => $get_ledger_info]);
        } else {
            return Response::json(['errors' => 'Error Occured']);
        }
    }

    public function checkMeterDetails(Request $request) {
        $application_id = $request->application_id;
        $get_meter_info = ConsumerApplicationDetails::get_meter_det_info($application_id);
        foreach ($get_meter_info as $consumer_meter) {
            $meter_no = $consumer_meter->meter_no;
        }
        if ($meter_no == null) {
            return Response::json(['errors' => 'Error Occured']);
        } else {

            return Response::json(['success' => 'success', 'response' => $get_meter_info]);
        }
    }

    public function checkApprovalDetails(Request $request) {
        $application_id = $request->application_id;
        $get_appro_info = ConsumerApplicationDetails::get_approval_by_info($application_id);
        foreach ($get_appro_info as $consumer_appr) {
            $app_by = $consumer_appr->approved_by;
        }
        if ($app_by == null) {
            return Response::json(['errors' => 'Error Occured']);
        } else {
            return Response::json(['success' => 'success', 'response' => $get_appro_info]);
        }
    }

    public function downloadFile(Request $request) {
        $type = $request->fileType;

        if ($type == 'appdoc') {
            if (file_exists(storage_path("app/public/images/{$request->filename}"))) {

                return response()->download(storage_path("app/public/images/{$request->filename}"));
            }
        }
    }

}
