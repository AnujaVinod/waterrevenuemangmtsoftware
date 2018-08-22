<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Models\ConnectionStatus;
use App\Models\ConnectionType;
use App\Models\ConsumerConnection;
use App\Models\LogConnectionChange;
use Illuminate\Support\Facades\Validator;
use Response;
use Datatables;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Helpers\UserLogHelper;

class ConnectionTypeController extends Controller
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
        return view('admin.connection_type');
    }

    public function getConnectionType()
    {
        $conntypes = ConnectionType::all();
        return Datatables::of($conntypes)
                        ->make(true);
    }

    public function saveConnectionType(Request $request)
    {
        $messages = array(
            'regex' => 'Invalid :attribute',
            'unique' => ':attribute has already been taken'
        );
        $this->validate($request, [
            'connection_code' => 'required|unique:master_connections_type,connection_code|regex:/^([A-Za-z])+([A-Za-z0-9_\.\s-\/])*$/|max:50',
            'connection_type' => 'required|unique:master_connections_type,connection_name|regex:/^([A-Za-z])+([A-Za-z0-9_\.\s-\/])*$/|max:100'
                ], $messages);

        $user = ConnectionType::create([
                    'connection_code' => trim($request->connection_code),
                    'connection_name' => trim($request->connection_type)
        ]);
        $sequenceNo = '';
        $action ="New connection type ".$request->connection_type." is added";
        UserLogHelper::createUserLog($sequenceNo, 'connection type', $action);
        return redirect()->back()->with("success", "Connection Type added successfully !");
    }

    public function tariffChange()
    {
        $userDesignation = ConnectionStatus::getUserDesignation();
        return view('admin.tariff_change', ['usersDesignations' => $userDesignation]);
    }

    public function tariffchangeLogSearch(Request $request)
    {
        $seq_no = $request->seq_no;
        $searchDetails = ConnectionType::tariffchangeLogSearch($seq_no);
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
                        ->rawColumns(['document' => 'document'])
                        ->make(true);
    }

    public function getTariffInfo(Request $request)
    {
        $seq_no = $request->seq_no;
        $lastDateofChange = '';
        $changeDate = LogConnectionChange::getConnectionChangeDate($seq_no);
        if ($changeDate) {
            $lastDateofChange = $changeDate->created_at;
        } else {
            $lastDateofChange = '';
        }
        $connDetails = ConsumerConnection::getConnectionInfoFromSeq($seq_no, $lastDateofChange);
        $array = array(6, 7, 8, 9, 10);
        $connectionTypes = ConnectionType::whereNotIn('id', $array)->get();
        if ($connDetails->isNotEmpty()) {

            return Response::json(['success' => 'success', 'connDetails' => $connDetails, 'connectionTypes' => $connectionTypes]);
        } else {
            return Response::json(['failure' => 'failure']);
        }
    }

    public function saveTariffChange(Request $request)
    {
        $messages = array(
            'date.required' => 'The Date field is required',
            'req_tariff.required' => 'The Requested Tariff field is required',
            'reason.required' => 'The Reason field is required',
            'order_number.required' => 'The Order number field is required',
            'documents.required' => 'The Documents field is required',
            'approved_by.required' => 'The Approved By field is required',
            'order_number.unique' => 'Order number already exists',
            'order_number.regex' => 'Invalid Order number',
            'no_flats.required' => 'No. of flat fiels is required',
            'arreas_amt.required' => 'Arrear value is required. Enter zero if no Arrear',
            'excess_amt.required' => 'Excess value is required. Enter zero if no Excess'
        );
        $validator = Validator::make($request->all(), [
                    'date' => 'required',
                    'req_tariff' => 'required',
                    'reason' => 'required',
                    'order_number' => 'required|max:100|unique:log_connection_change,order_no|regex:/^[A-Za-z0-9\.\s\/\-]+$/',
                    'documents' => 'required',
                    'no_flats' => 'required',
                    'arreas_amt' => 'required',
                    'excess_amt' => 'required',
                    'approved_by' => 'required'
                ], $messages);

        if ($validator->passes()) {
            $updatedBy = auth()->user()->id;
            $sequence_no = trim($request->sequence_no);
            $files = $request->file('documents');
            $filename = [];
            $i = 0;
            foreach ($request->documents as $photo) {
                $file = $sequence_no . '_' . round(microtime(true) * 1000); //Get Image Name
                $extension = $photo->getClientOriginalExtension();  //Get Image Extension
                $fileName = $file . '.' . $extension;
                $filename[$i] = $fileName;
                $i++;
                Storage::putFileAs('documents', $photo, $fileName);
            }
            $filenameString = implode(", ", $filename);
            $excess_amount = str_replace('-', '', $request->excess_amt);
            $user = LogConnectionChange::create([
                        'sequence_no' => $sequence_no,
                        'deposit_amount' => $request->dep_amount,
                        'no_of_flats' => $request->no_flats,
                        'old_connection_type_id' => trim($request->ex_tariff_id),
                        'new_connection_type_id' => trim($request->req_tariff),
                        'document' => $filenameString,
                        'order_no' => trim($request->order_number),
                        'current_rate' => trim($request->current_rate),
                        'revised_rate' => trim($request->revised_rate),
                        'total_consumption' => $request->tot_consumption,
                        'arrear_amt' => $request->arreas_amt,
                        'excess_amt' => -($excess_amount),
                        'required_from_date' => Carbon::parse($request->date)->format('Y-m-d'),
                        'reason' => $request->reason,
                        'approved_by' => $request->approved_by,
                        'updated_by' => $updatedBy
            ]);
            $consumerConnection_update = array('connection_type_id' => trim($request->req_tariff),
                'no_of_flats'=> $request->no_flats,
                'revised_deposit_amount'=> $request->revised_dep_amount,
                'updated_by' => $updatedBy);
            ConsumerConnection::where('sequence_number', '=', $sequence_no)
                    ->update($consumerConnection_update);

                    $action = 'Tariff changed';
                    UserLogHelper::createUserLog($sequence_no,'Tariff' , $action);

            return Response::json(['success' => '1', 'sequence_numb' => $sequence_no]);
        }
        return Response::json(['errors' => $validator->errors()]);
    }

}
