<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Models\ConnectionStatus;
use App\Models\LogMeterChange;
use App\Models\LogNameChange;
use App\Models\ConsumerConnection;
use App\Models\ConsumerAddress;
use App\Models\MeterReading;
use Illuminate\Support\Facades\Validator;
use Response;
use Datatables;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use App\Helpers\UserLogHelper;

class MeterNameChangeController extends Controller
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
        $userDesignation = ConnectionStatus::getUserDesignation();
        return view('admin.meter_name_change', ['usersDesignations' => $userDesignation]);
    }

    public function meterNameChangeSearchAll(Request $request)
    {
        $columns = array(
            0 => 'name',
            1 => 'mobile_no',
            2 => 'sequence_number',
            3 => 'meter_no',
            4 => 'connection_name',
            5 => 'door_no',
            6 => 'ward_name',
            7 => 'corp_name',
            8 => 'status',
            9 => 'action'
        );

        $totalData = ConsumerConnection::count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $searchDetails = ConsumerConnection::meterNameChangeSearchAll($start, $limit, $order, $dir, $search);
        if (!empty($search)) {
            $totalFiltered = ConsumerConnection::meterNameChangeSearchAllCount($search);
        }
        $data = array();
        if (!empty($searchDetails)) {
            foreach ($searchDetails as $connDetail) {
                $nestedData['name'] = $connDetail->name;
                $nestedData['mobile_no'] = $connDetail->mobile_no;
                $nestedData['sequence_number'] = $connDetail->sequence_number;
                $nestedData['meter_no'] = $connDetail->meter_no;
                $nestedData['connection_name'] = $connDetail->connection_name;
                $nestedData['door_no'] = $connDetail->door_no;
                $nestedData['ward_name'] = $connDetail->ward_name;
                $nestedData['corp_name'] = $connDetail->corp_name;
                $nestedData['status'] = $connDetail->status;
                $nestedData['action'] = '<button type="button" class="btn btn-success statuschange" data-toggle="modal" data-title="namechange" data-sequence="' . $connDetail->sequence_number . '" data-connid="' . $connDetail->id . '">Name Change</button><br><br><button type="button" class="btn btn-warning statuschange" data-toggle="modal" data-title="meterchange" data-sequence="' . $connDetail->sequence_number . '" data-connid="' . $connDetail->id . '">Meter Change</button>';
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

    public function meterNameChangeSearch(Request $request)
    {
        $seq_no = $request->seq_no;
        $meter_no = $request->meter_no;

        $searchDetails = ConsumerConnection::meterNameChangeSearch($seq_no, $meter_no);
        return Datatables::of($searchDetails)
                        ->addColumn('action', function ($searchDetails) {
                            return '<button type="button" class="btn btn-success statuschange" data-toggle="modal" data-title="namechange" data-sequence="' . $searchDetails->sequence_number . '" data-connid="' . $searchDetails->id . '">Name Change</button><br><br><button type="button" class="btn btn-warning statuschange" data-toggle="modal" data-title="meterchange" data-sequence="' . $searchDetails->sequence_number . '" data-connid="' . $searchDetails->id . '">Meter Change</button>';
                        })
                        ->rawColumns(['action' => 'action'])
                        ->make(true);
    }

    public function meterNameChangeLogSearch(Request $request)
    {
        $seq_no = $request->seq_no;
        $meter_no = $request->meter_no;

        $searchDetails = LogMeterChange::meterNameChangeLogSearch($seq_no, $meter_no);
        return Datatables::of($searchDetails)
                        ->editColumn('document', function ($searchDetails) {
                            $fileString = [];
                            $fileString = explode(",", $searchDetails->document);
                            $fileCount = count(array_filter($fileString));
                            $html = '';
                            if ($fileCount >= 1) {
                                foreach ($fileString as $file) {
                                    $html .= $file . '&nbsp; <a onclick="printFile(this)" data-value="' . $file . '" id="imagedownload"><span class="glyphicon glyphicon-download-alt"></span></a><br>';
                                }
                            }
                            return $html;
                        })
                        ->editColumn('operation', function ($searchDetails) {
                            return ($searchDetails->operation == 'namechange') ? 'Name change' : 'Meter change';
                        })
                        ->rawColumns(['document' => 'document', 'operation' => 'operation'])
                        ->make(true);
    }

    public function saveNameChange(Request $request)
    {
        $messages = array(
            'new_name.required' => 'The New Name field is required.',
            'order_number.required' => 'The Order Number field is required.',
            'reason.required' => 'The Reason field is required.',
            'date.required' => 'The Date field is required.',
            'documents.required' => 'The Documents field is required.',
            'approved_by.required' => 'The Approved by field is required.',
            'unique' => 'Order number already exists',
            'regex' => 'Invalid :attribute',
            'after' => ":attribute must be date after Connection Date."
        );
        $validator = Validator::make($request->all(), [
                    //'new_name' => 'required|max:200|regex:/^[A-Za-z\.\s\']+$/',
					'new_name' => 'required|max:200',
                    'order_number' => 'required|max:100|unique:log_name_change,order_no|regex:/^[A-Za-z0-9\.\s\/\-]+$/',
                    'reason' => 'required',
                    'date' => 'required|after_or_equal:connection_date',
                    'documents' => 'required',
                    'approved_by' => 'required'
                        ], $messages);

        if ($validator->passes()) {
            $new_name = trim($request->new_name);
            $sequence_no = trim($request->name_sequence_no);
            $files = $request->file('documents');
            $filename = [];
            $i = 0;
            foreach ($request->documents as $photo) {
                $file = $sequence_no . '_' . round(microtime(true) * 1000); //Get Image Name
                $extension = $photo->getClientOriginalExtension();  //Get Image Extension
                $fileName = $file . '.' . $extension;
                $filename[$i] = $fileName;
                $i++;
                Storage::putFileAs('meter_change', $photo, $fileName);
            }
            $filenameString = implode(", ", $filename);
            $user = LogNameChange::create([
                        'sequence_no' => $sequence_no,
                        'old_val' => trim($request->name_cname),
                        'updated_val' => $new_name,
                        'order_no' => trim($request->order_number),
                        'date' => Carbon::parse($request->date)->format('Y-m-d'),
                        'reason' => trim($request->reason),
                        'approved_by' => $request->approved_by,
                        'document' => $filenameString,
                        'operation' => 'namechange',
                        'updated_by' => auth()->user()->id
            ]);
            ConsumerConnection::where('sequence_number', '=', $sequence_no)
                    ->update(['name' => $new_name, 'updated_by' => auth()->user()->id]);
            ConsumerAddress::where('sequence_number', '=', $sequence_no)
                    ->update(['premises_owner_name' => $new_name, 'updated_by' => auth()->user()->id]);

                    $action = 'Meter name changed';
                    UserLogHelper::createUserLog($sequence_no,'meter_status' , $action);
            return Response::json(['success' => '1', 'sequence_numb' => $sequence_no]);
        }
        return Response::json(['errors' => $validator->errors()]);
    }

    public function saveMeterChange(Request $request)
    {
        $messages = array(
            'new_meterno.required' => 'The New Meter Number field is required.',
            'new_meterreading.required' => 'Current Meter reading is required',
            'order_number.required' => 'The Order Number field is required.',
            'reason.required' => 'The Reason field is required.',
            'order_date.required' => 'The Order Date field is required.',
            'meter_change_date.required' => 'The Meter Change Date field is required.',
            'documents.required' => 'The Documents field is required.',
            'approved_by.required' => 'The Approved by field is required.',
            'unique' => ':attribute already exists',
            'regex' => 'Invalid :attribute',
            'after' => ":attribute must be date after Meter sanctioned Date.",
            'meter_change_type'=>'Meter Change Type is required',
        );
        $validator = Validator::make($request->all(), [
                    //'new_meterno' => 'required|regex:/^[A-Za-z0-9]+$/|max:25|unique:consumer_connection,meter_no',
                    'new_meterreading' => 'required',
                    'order_number' => 'required|max:100|unique:log_meter_change,order_no|regex:/^[A-Za-z0-9\.\s\/\-]+$/',
                    'reason' => 'required',
                    'order_date' => 'required|after_or_equal:meter_sanction_date',
                    'meter_change_date' => 'required',
                    'documents' => 'required',
                    'approved_by' => 'required',
                    'meter_change_type'=>'required'
                        ], $messages);

        if ($request->meter_change_type == "new") {
                    $validator = Validator::make($request->all(), [
                    'new_meterno' => 'required|regex:/^[A-Za-z0-9]+$/|max:25',//unique:consumer_connection,meter_no  removed unique meter no check
                    'new_meterreading' => 'required',
                    'order_number' => 'required|max:100|unique:log_meter_change,order_no|regex:/^[A-Za-z0-9\.\s\/\-]+$/',
                    'reason' => 'required',
                    'order_date' => 'required|after_or_equal:meter_sanction_date',
                    'meter_change_date' => 'required',
                    'documents' => 'required',
                    'approved_by' => 'required',
                    'meter_change_type'=>'required'
                        ], $messages);
        }

        if ($validator->passes()) {

            if ($request->meter_change_type == "new") {
                 $new_name = trim($request->new_meterno);
            }
            else {
                 $new_name = trim($request->oldmeter_no);
            }

            $sequence_no = trim($request->meter_sequence_no);
            $files = $request->file('documents');
            $filename = [];
            $i = 0;
            foreach ($request->documents as $photo) {
                $file = $sequence_no . '_' . round(microtime(true) * 1000); //Get Image Name
                $extension = $photo->getClientOriginalExtension();  //Get Image Extension
                $fileName = $file . '.' . $extension;
                $filename[$i] = $fileName;
                $i++;
                Storage::putFileAs('meter_change', $photo, $fileName);
            }
            $filenameString = implode(", ", $filename);
            $user = LogMeterChange::create([
                        'sequence_no' => $sequence_no,
                        'old_val' => trim($request->oldmeter_no),
                        'updated_val' => $new_name,
                        'updated_reading' => $request->new_meterreading,
                        'order_no' => trim($request->order_number),
                        'order_date' => Carbon::parse($request->order_date)->format('Y-m-d'),
                        'meter_change_date' => Carbon::parse($request->meter_change_date)->format('Y-m-d'),
                        'reason' => $request->reason,
                        'approved_by' => $request->approved_by,
                        'document' => $filenameString,
                        'operation' => 'meterchange',
                        'updated_by' => auth()->user()->id
            ]);


            ConsumerConnection::where('sequence_number', '=', $sequence_no)
                    ->update(['meter_no' => $new_name, 'meter_status_id' => 4, 'updated_by' => auth()->user()->id]);




            MeterReading::where('sequence_number', '=', $sequence_no)
                    ->where('active_record', '=', 1)
                    ->update(['meter_change_status' => 1]);

                    $action = 'Meter changed';
                    UserLogHelper::createUserLog($sequence_no,'meter_status' , $action);

            return Response::json(['success' => '1', 'sequence_numb' => $sequence_no]);
        }
        return Response::json(['errors' => $validator->errors()]);
    }

    public function downloadFile(Request $request)
    {

        if (file_exists(storage_path("app/disconnect_reconnect/{$request->filename}"))) {
            return response()->download(storage_path("app/meter_change/{$request->filename}"));
        }
    }

}
