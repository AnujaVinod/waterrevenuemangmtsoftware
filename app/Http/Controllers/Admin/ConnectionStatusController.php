<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Models\ConnectionStatus;
use App\Models\LogConnectReconnect;
use App\Models\ConsumerConnection;
use Illuminate\Support\Facades\Validator;
use Response;
use Datatables;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use App\Helpers\UserLogHelper;

class ConnectionStatusController extends Controller
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

        return view('admin.connection_status');
    }

    public function getConnectionStatus()
    {
        $connectionstatus = ConnectionStatus::all();
        return Datatables::of($connectionstatus)
                        ->make(true);
    }

    public function saveConnectionStatus(Request $request)
    {
        $messages = array(
            'regex' => 'Invalid connection status',
            'unique' => 'connection status has already been taken'
        );
        $this->validate($request, [
            'status' => 'required|unique:master_connection_status|regex:/^[A-Za-z0-9\s-]+$/'
                ], $messages);

        ConnectionStatus::create([
                    'status' => trim($request->status)
        ]);
        $sequenceNo = '';
        $action = 'New connection status '.$request->status.' is added';
        UserLogHelper::createUserLog($sequenceNo, 'Connection status', $action);
        return redirect()->back()->with("success", "Connection Status added successfully !");
    }

    public function connectionStatusChange()
    {
        $userDesignation = ConnectionStatus::getUserDesignation();

        return view('admin.change_connection_status', ['usersDesignations' => $userDesignation]);
    }

    public function reconnectConnection(Request $request)
    {
        $messages = array(
            'unique' => 'Order number already exists',
            'regex' => 'Invalid :attribute',
            'after' => ":attribute must be date after Connection Date."
        );
        $validator = Validator::make($request->all(), [
                    'order_no' => 'required|max:100|unique:log_connect_reconnect,order_no|regex:/^[A-Za-z0-9\.\s\/\-]+$/',
                    'reconnected_date' => 'required',
                    'reason' => 'required',
                    'approved_by' => 'required'
                        ], $messages);

        if ($validator->passes()) {
            $sequence_no = trim($request->seq_no);
            $connectionChangeLog = LogConnectReconnect::getConnectionChangeLog($sequence_no);
            if ($connectionChangeLog) {
                $req_from_date = Carbon::createFromFormat('m/d/Y H:i:s', $request->reconnected_date . ' 00:00:01');
                $connectionChangeLogDate = Carbon::createFromFormat('Y-m-d H:i:s', $connectionChangeLog->date . ' 00:00:00');

                $compareDate = $req_from_date->min($connectionChangeLogDate);
                if ($compareDate == $req_from_date) {
                    $validator->errors()->add('reconnected_date', 'Selected date is before the previously connection status changed date');
                    return Response::json(['errors' => $validator->errors()]);
                }
            }
            $user = LogConnectReconnect::create([
                        'sequence_no' => $sequence_no,
                        'order_no' => trim($request->order_no),
                        'meter_no' => trim($request->meter_no),
                        'date' => Carbon::parse($request->reconnected_date)->format('Y-m-d'),
                        'reason' => $request->reason,
                        'approved_by' => $request->approved_by,
                        'operation' => 'reconnect',
                        'updated_by' => auth()->user()->id
            ]);
            ConsumerConnection::where('sequence_number', '=', $request->seq_no)
                    ->update(['connection_status_id' => 2, 'updated_by' => auth()->user()->id]);

                    $action = 'Connection re-connected';
                    UserLogHelper::createUserLog($sequence_no,'Consumer Connection' , $action);

            return Response::json(['success' => '1', 'sequence_numb' => $sequence_no]);
        }
        return Response::json(['errors' => $validator->errors()]);
    }

    public function disconnectConnection(Request $request)
    {
        $messages = array(
            'unique' => 'Order number already exists',
            'regex' => 'Invalid :attribute',
            'after' => ":attribute must be date after Connection Date."
        );
        $validator = Validator::make($request->all(), [
                    'order_number' => 'required|max:100|unique:log_connect_reconnect,order_no|regex:/^[A-Za-z0-9\.\s\/\-]+$/',
                    'disconnected_date' => 'required',
                    'reason' => 'required',
                    'documents' => 'required',
                    'approved_by' => 'required'
                        ], $messages);

        if ($validator->passes()) {

            $sequence_no = trim($request->seq_no);
            $connectionChangeLog = LogConnectReconnect::getConnectionChangeLog($sequence_no);
            if ($connectionChangeLog) {
                $req_from_date = Carbon::createFromFormat('m/d/Y H:i:s', $request->disconnected_date . ' 00:00:01');
                $connectionChangeLogDate = Carbon::createFromFormat('Y-m-d H:i:s', $connectionChangeLog->date . ' 00:00:00');

                $compareDate = $req_from_date->min($connectionChangeLogDate);
                if ($compareDate == $req_from_date) {
                    $validator->errors()->add('disconnected_date', 'Selected date is before the previously connection status changed date');
                    return Response::json(['errors' => $validator->errors()]);
                }
            }
            $files = $request->file('documents');
            $filename = [];
            $i = 0;
            foreach ($request->documents as $photo) {
                $file = $request->seq_no . '_' . round(microtime(true) * 1000); //Get Image Name
                $extension = $photo->getClientOriginalExtension();  //Get Image Extension
                $fileName = $file . '.' . $extension;
                $filename[$i] = $fileName;
                $i++;
                Storage::putFileAs('disconnect_reconnect', $photo, $fileName);
            }
            $filenameString = implode(", ", $filename);
            $user = LogConnectReconnect::create([
                        'sequence_no' => $sequence_no,
                        'order_no' => trim($request->order_number),
                        'meter_no' => trim($request->meter_no),
                        'date' => Carbon::parse($request->disconnected_date)->format('Y-m-d'),
                        'reason' => $request->reason,
                        'document' => $filenameString,
                        'approved_by' => $request->approved_by,
                        'operation' => 'disconnect',
                        'updated_by' => auth()->user()->id
            ]);
            ConsumerConnection::where('sequence_number', '=', $request->seq_no)
                    ->update(['connection_status_id' => 1, 'updated_by' => auth()->user()->id]);

                    $action = 'Connection disconnected';
                    UserLogHelper::createUserLog($sequence_no,'Consumer Connection' , $action);

            return Response::json(['success' => '1', 'sequence_numb' => $sequence_no]);
        }
        return Response::json(['errors' => $validator->errors()]);
    }

    public function downloadFile(Request $request)
    {
        $type = $request->fileType;
        if ($type == 'disconnectReconnect') {
            if (file_exists(storage_path("app/disconnect_reconnect/{$request->filename}"))) {
                return response()->download(storage_path("app/disconnect_reconnect/{$request->filename}"));
            }
        }
        if ($type == 'meterNameChange') {
            if (file_exists(storage_path("app/meter_change/{$request->filename}"))) {
                return response()->download(storage_path("app/meter_change/{$request->filename}"));
            }
        }
        if ($type == 'tariffChange') {
            if (file_exists(storage_path("app/documents/{$request->filename}"))) {
                return response()->download(storage_path("app/documents/{$request->filename}"));
            }
        }
    }

}
