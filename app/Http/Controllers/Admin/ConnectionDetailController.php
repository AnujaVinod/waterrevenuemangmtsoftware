<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Models\ConsumerConnection;
use App\Models\Ward;
use App\Models\CorpWard;
use App\Models\LogConnectReconnect;
use App\Models\ConnectionType;
use App\Models\ConsumerAddress;
use Illuminate\Support\Facades\Validator;
use Response;
use Datatables;
use Carbon\Carbon;
use App\Models\MeterStaus;
use App\Models\MeterReading;
use Userlogs;
use App\Helpers\UserLogHelper;

class ConnectionDetailController extends Controller
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
        
        $corpWard = CorpWard::orderBy('corp_name')->get();
        $wards = Ward::orderBy('ward_name')->get();
        $connType = ConnectionType::all();
        return view('admin.connection_details',['wards' => $wards,'corpWard' => $corpWard,'connTypes' => $connType]);
    }


    public function getConnectionDetail(Request $request)
    {
       // print_r($request->all()); 
        $columns = array( 
            0 =>'name', 
            1 =>'mobile_no',
            2=> 'sequence_number',
            3=> 'meter_no',
            4=> 'connection_name',
            5=> 'meter_status',
            6=> 'status',
            7=> 'action'
        );
        
        $totalData = ConsumerConnection::count();
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $search = $request->input('search.value'); 
        
        $connDetails = ConsumerConnection::retrieveConnectionDetails($start,$limit,$order,$dir,$search);
        if(!empty($search))
        {            
           $totalFiltered = ConsumerConnection::retrieveConnectionDetailsCount($search);
        }
        $data = array();
        if(!empty($connDetails))
        {
            foreach ($connDetails as $connDetail)
            {
                $edit =  url('/admin/EditConsumer',$connDetail->id);
                $nestedData['name'] = $connDetail->name;
                $nestedData['mobile_no'] = $connDetail->mobile_no;
                $nestedData['sequence_number'] = $connDetail->sequence_number;
                $nestedData['meter_no'] = $connDetail->meter_no;
                $nestedData['connection_name'] = $connDetail->connection_name;
                $nestedData['meter_status'] = $connDetail->meter_status;
                $nestedData['status'] = $connDetail->status;
                $nestedData['action'] = "<button type='button' class='btn btn-danger' id='viewBtn' data-toggle='modal' data-value='" . $connDetail->id . "'>View Detail</button>&nbsp; <a href='{$edit}' class='btn btn-danger' id='editBtn' data-toggle='modal' data-value='" . $connDetail->id . "'>Edit Detail</a>";
                $data[] = $nestedData;

            }
        }          
        $json_data = array(
                    "draw"            => intval($request->input('draw')),  
                    "recordsTotal"    => intval($totalData),  
                    "recordsFiltered" => intval($totalFiltered), 
                    "data"            => $data   
                    );
            
        echo json_encode($json_data);
    }
    
    public function getConnectionInfo(Request $request)
    {
        $connId = $request->connId;
        $connDetails = ConsumerConnection::getConnectionInfo($connId);
        if ($connDetails) {
            return Response::json(['success' => 'success', 'response' => $connDetails]);
        } else {
            return Response::json(['errors' => 'Error Occured']);
        }
    }
 
    
    public function connection_search(Request $request)
    {        
        $seq_no =  $request->seq_no;
        $conn_type = $request->conn_type;
        $ward = $request->ward;
        $corp_ward = $request->corp_ward;
        $meter_no = $request->meter_no;
        $connDetails = ConsumerConnection::searchConnections($seq_no, $conn_type, $ward, $corp_ward, $meter_no);
        return Datatables::of($connDetails)
                ->addColumn('action', function ($connDetails) {  
                    $edit =  url('/admin/EditConsumer',$connDetails->id);
                            return "<button type='button' class='btn btn-danger' id='viewBtn' data-toggle='modal' data-value='" . $connDetails->id . "'>View Detail</button>&nbsp; <a href='{$edit}' class='btn btn-danger' id='editBtn' data-toggle='modal' data-value='" . $connDetails->id . "'>Edit Detail</a>";
                            
                        })              
                ->rawColumns(['action' => 'action'])
                ->make(true);
    }
    
    
    public function disconnectReconnectSearchAll(Request $request)
    {
        $columns = array( 
            0 =>'name', 
            1 =>'mobile_no',
            2=> 'sequence_number',
            3=> 'meter_no',
            4=> 'connection_name',
            5=> 'status',
            6=> 'action'
        );
        
        $totalData = ConsumerConnection::count();
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $search = $request->input('search.value');
        
        $searchDetails = ConsumerConnection::disconnectReconnectSearchAll($start,$limit,$order,$dir,$search);
        if(!empty($search))
        {            
           $totalFiltered = ConsumerConnection::disconnectReconnectSearchAllCount($search);
        }
       
        $data = array();
        if(!empty($searchDetails))
        {
            foreach ($searchDetails as $connDetail)
            {
                //$edit =  url('/admin/EditConsumer',$connDetail->id);
                $nestedData['name'] = $connDetail->name;
                $nestedData['mobile_no'] = $connDetail->mobile_no;
                $nestedData['sequence_number'] = $connDetail->sequence_number;
                $nestedData['meter_no'] = $connDetail->meter_no;
                $nestedData['connection_name'] = $connDetail->connection_name;
                $nestedData['status'] = $connDetail->status;
                $nestedData['action'] = ($connDetail->connection_status_id == 1) ? '<button type="button" class="btn btn-success changestatus" data-toggle="modal" data-title="reconnect" data-sequence="' . $connDetail->sequence_number . '" data-meterno="' . $connDetail->meter_no . '" data-value="' . $connDetail->id . '">Reconnect</button>' : '<button type="button" class="btn btn-danger changestatus" data-title="disconnect" data-value="' . $connDetail->id . '" data-sequence="' . $connDetail->sequence_number . '" data-meterno="' . $connDetail->meter_no . '" data-toggle="modal" >Disconnect</i></button>';
                $data[] = $nestedData;

            }
        }          
        $json_data = array(
                    "draw"            => intval($request->input('draw')),  
                    "recordsTotal"    => intval($totalData),  
                    "recordsFiltered" => intval($totalFiltered), 
                    "data"            => $data   
                    );
            
        echo json_encode($json_data);
        
        
        /**
        
        return Datatables::of($searchDetails)
                ->addColumn('action', function ($searchDetails) {                    
                           return ($searchDetails->connection_status_id == 1) ? '<button type="button" class="btn btn-success changestatus" data-toggle="modal" data-title="reconnect" data-sequence="' . $searchDetails->sequence_number . '" data-meterno="' . $searchDetails->meter_no . '" data-value="' . $searchDetails->id . '">Reconnect</button>' : '<button type="button" class="btn btn-danger changestatus" data-title="disconnect" data-value="' . $searchDetails->id . '" data-sequence="' . $searchDetails->sequence_number . '" data-meterno="' . $searchDetails->meter_no . '" data-toggle="modal" >Disconnect</i></button>';      
                        })              
                ->rawColumns(['action' => 'action'])
                ->make(true); */
    }
    
    public function disconnectReconnectSearch(Request $request)
    {
        $seq_no =  $request->seq_no;
        $meter_no = $request->meter_no;
        
        $searchDetails = ConsumerConnection::disconnectReconnectSearch($seq_no, $meter_no);
        return Datatables::of($searchDetails)
                ->addColumn('action', function ($searchDetails) {                    
                           return ($searchDetails->connection_status_id == 1) ? '<button type="button" class="btn btn-success changestatus" data-toggle="modal" data-title="reconnect" data-sequence="' . $searchDetails->sequence_number . '" data-meterno="' . $searchDetails->meter_no . '" data-value="' . $searchDetails->id . '">Reconnect</button>' : '<button type="button" class="btn btn-danger changestatus" data-title="disconnect" data-value="' . $searchDetails->id . '" data-sequence="' . $searchDetails->sequence_number . '" data-meterno="' . $searchDetails->meter_no . '" data-toggle="modal" >Disconnect</i></button>';      
                        })              
                ->rawColumns(['action' => 'action'])
                ->make(true);
    }
    
    
    public function disconnectReconnectLogSearch(Request $request)
    {
        $seq_no =  $request->seq_no;
        $meter_no = $request->meter_no;
        
        $searchDetails = LogConnectReconnect::disconnectReconnectLogSearch($seq_no, $meter_no);        
        return Datatables::of($searchDetails)  
                ->editColumn('document', function ($searchDetails) {
                            $fileString = [];
                            $fileString = explode(",",$searchDetails->document);
                            $fileCount = count(array_filter($fileString));
                            $html = '';                           
                if($fileCount >= 1){
                            foreach ($fileString as $file){
                                $html .= $file.'&nbsp; <a onclick="printFile(this)" data-value="'.$file.'" id="imagedownload"><span class="glyphicon glyphicon-download-alt"></span></a><br>';
                            }
                }
                            return $html;
                        })
                ->editColumn('operation', function ($searchDetails) {
                            return ($searchDetails->operation == 'reconnect') ? 'Reconnected' : 'Disconnected';    
                        })
                       ->rawColumns(['document' => 'document'])
                ->make(true);
    }    
    
    
    public function addConnectionDetail()
    {         
        $corpWard = CorpWard::orderBy('corp_name')->get();
        $wards = Ward::orderBy('ward_name')->get();
        $connType = ConnectionType::all();
        $meterStatus = MeterStaus::all();
        return view('admin.add_connection_details',['wards' => $wards,'connTypes' => $connType,'corpWards' => $corpWard,'meterStatus' => $meterStatus]);
    }
    
  
    public function getCorpWardForWard(Request $request)
    {         
        $wardId = $request->wardId;
        $corpWardList = CorpWard::getCorpWardForWard($wardId);
        if($corpWardList->isNotEmpty()){
            return Response::json(['success' => '1', 'corpWard' => $corpWardList]);
        }else{
            return Response::json(['success' => '0']);
        }
    }
    
    public function saveConnectionDetail(Request $request)
    {
        $messages = array(            
            'unique' => ':attribute has already been taken',
            'cname.required' => 'The Name of the Owner field is required',          
            'ward_id.required' => 'The Ward field is required',
            'corp_ward_id.required' => 'The Corp Ward field is required',
            'connection_type.required' => 'The Tariff field is required',
            'connection_status_id.required' => 'The Connection Status field is required',
            'meter_no.required' => 'The Meter Number field is required',
            'meter_status_id.required' => 'The Meter Status field is required',
            'after' => ":attribute must be date after Meter sanctioned Date."
        );
        $validator = Validator::make($request->all(), [
            'sequence_number' => 'required|regex:/^[a-z,A-Z,0-9,-]+$/i|unique:consumer_connection,sequence_number',
            'mobile_no' => 'nullable|numeric|digits_between:10,15',
            'meter_sanctioned_date' => 'required',
            'ward_id' => 'required',
            'corp_ward_id' => 'required',
            'cname' => 'required',
            'connection_type' => 'required',
            'connection_status_id' =>'required',
            'meter_no' => 'required',
            'meter_status_id' => 'required',
            'connection_date' => 'required|after:meter_sanctioned_date'
        ],$messages);
        if ($validator->passes()) {
        $sequence_no = trim($request->sequence_number);
        $category = "Consumer Connection";
        ConsumerConnection::create([
            'sequence_number' => $sequence_no,            
            'connection_type_id' => trim($request->connection_type),  
            'connection_date' => Carbon::parse($request->connection_date)->format('Y-m-d'),  
            'name' => trim($request->cname),            
            'mobile_no' => trim($request->mobile_no),  
            'door_no' => trim($request->door_no),
            'khata_no' => trim($request->khata_no),
            'no_of_flats' => trim($request->no_of_flats),
            'ward_id' => trim($request->ward_id),
            'corp_ward_id' => trim($request->corp_ward_id),
            'connection_status_id' => trim($request->connection_status_id),
            'meter_status_id' => trim($request->meter_status_id),
            'meter_no' => trim($request->meter_no),
            'deposit_amount' => trim($request->dep_amount),
            'meter_sanctioned_date' => Carbon::parse($request->meter_sanctioned_date)->format('Y-m-d')
        ]);  
            $action="Consumer detail added";            
            UserLogHelper::createUserLog($sequence_no, $category, $action);
            return Response::json(['success' => '1']);
        }
        return Response::json(['errors' => $validator->errors()]);
    }
    
    public function editConsumer($id){
        $corpWard = CorpWard::orderBy('corp_name')->get();
        $wards = Ward::orderBy('ward_name')->get();
        $connType = ConnectionType::orderBy('connection_name')->get();
        $meterStatus = MeterStaus::orderBy('meter_status')->get(); 
        $connDetails = ConsumerConnection::getConnectionInfo($id);
        $connection_data['sequence_number'] = $connDetails[0]->sequence_number;
        $meterReadingCount = MeterReading::meterReadingDetails($connection_data)->get()->count();
        $data = array('connDetails' => $connDetails,
                    'wards' => $wards,
                    'connTypes' => $connType,
                    'corpWards' => $corpWard,
                    'meterStatus' => $meterStatus,
                    'meterReadingCount' => $meterReadingCount);
        return view('admin.edit_connection_details',$data);
    }
    
    
    public function updateConnectionDetail(Request $request){
        $messages = array(            
            'unique' => ':attribute has already been taken',
            'after' => ":attribute must be date after Meter sanctioned Date.",
            'name.required' => 'The Name of the Owner field is required',          
            'ward_id.required' => 'The Ward field is required',
            'corp_name.required' => 'The Corp Ward field is required',
            'connection_type.required' => 'The Tariff field is required',
            'connection_status_id.required' => 'The Connection Status field is required',
            'meter_no.required' => 'The Meter Number field is required',
            'meter_status_id.required' => 'The Meter Status field is required',
            'connection_date.required' => 'The Connection date field is required'
        );
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:100',         
            'ward_id' => 'required',
            'corp_name' => 'required',
            'connection_type' => 'required',
            'connection_status_id' =>'required',
            'meter_no' => 'required',
            'meter_status_id' => 'required',           
            'connection_date' => 'required'        
        ],$messages);
        if ($validator->passes()) {
            $connectionId = $request->conId;
            $sequenceNo = $request->sequence_number;
            //FOR SUPERADMINS
            if($request->edit_connection_info == '1'){
               $UpdatedConsumerInfo = array(
                'connection_type_id'=>$request->connection_type,
                'connection_date' =>Carbon::parse($request->connection_date)->format('Y-m-d'),
                'name' =>$request->name,
                'mobile_no' =>isset($request->mobile_no)? $request->mobile_no : NULL,
                'door_no' =>isset($request->door_no) ? $request->door_no : NULL,
                'khata_no' =>isset($request->khata_no) ? $request->khata_no : NULL,
                'no_of_flats' =>isset($request->no_of_flats) ? $request->no_of_flats : NULL,
                'ward_id' =>$request->ward_id,
                'corp_ward_id' =>$request->corp_id,
                'connection_status_id' =>$request->connection_status_id,
                'meter_status_id' =>$request->meter_status_id,
                'meter_no' =>$request->meter_no,
                'meter_sanctioned_date' =>isset($request->meter_sanctioned_date)? Carbon::parse($request->meter_sanctioned_date)->format('Y-m-d'): NULL,
                'updated_by' => auth()->user()->id
                );     
            }else{
               $UpdatedConsumerInfo = array(
                'name' =>$request->name,
                'mobile_no' =>isset($request->mobile_no)? $request->mobile_no : NULL,
                'door_no' =>isset($request->door_no) ? $request->door_no : NULL,
                'khata_no' =>isset($request->khata_no) ? $request->khata_no : NULL,
                'no_of_flats' =>isset($request->no_of_flats) ? $request->no_of_flats : NULL,
                'ward_id' =>$request->ward_id,
                'corp_ward_id' =>$request->corp_id,            
                'updated_by' => auth()->user()->id
                );  
            }           
            
            $UpdatedAddressInfo = array(              
                'premises_owner_name'=>isset($request->premises_owner_name) ? $request->premises_owner_name : NULL,
                'premises_address' =>isset($request->premises_address) ? $request->premises_address : NULL,
                'premises_street' =>isset($request->premises_street) ? $request->premises_street : NULL ,
                'premises_city' =>isset($request->premises_city) ? $request->premises_city : NULL,
                'premises_state' =>isset($request->premises_state) ? $request->premises_state : NULL,
                'premises_zip' =>isset($request->premises_zip) ? $request->premises_zip :NULL ,
                'updated_by' => auth()->user()->id
                );
            
            if($request->edit_connection_info == '1'){
                $UpdatedReadingInfo = array(
                'consumer_name' => $request->name,
                'door_no' => isset($request->door_no) ? $request->door_no : NULL,
                'meter_no' => $request->meter_no,
                'meter_status' => $request->meter_status_id,
                'ward_id' => $request->ward_id,
                'corpward_id' => $request->corp_id,
                'no_of_flats' => isset($request->no_of_flats) ? $request->no_of_flats : NULL
            ); 
            }else{
                $UpdatedReadingInfo = array(
                'consumer_name' => $request->name,
                'door_no' => isset($request->door_no) ? $request->door_no : NULL,           
                'ward_id' => $request->ward_id,
                'corpward_id' => $request->corp_id,
                'no_of_flats' => isset($request->no_of_flats) ? $request->no_of_flats : NULL
            ); 
            }
            
            ConsumerConnection::whereId($connectionId)->update($UpdatedConsumerInfo);
            $matchThese = array('sequence_number'=>$sequenceNo);
            ConsumerAddress::updateOrCreate($matchThese, $UpdatedAddressInfo);  
            MeterReading::whereSequenceNumber($sequenceNo)->update($UpdatedReadingInfo);
            
            if($request->ward_name != $request->ward_name_old){
                $wardaction = "Updated from " . $request->ward_name_old . " to " . $request->ward_name;
                Userlogs::createUserLog($sequenceNo,'ward',$wardaction);
            }
            
            if($request->corp_name != $request->corp_name_old){
                $corpwardaction = "Updated from " . $request->corp_name_old . " to " . $request->corp_name;       
                Userlogs::createUserLog($sequenceNo,'corpward',$corpwardaction);   
            }
            
            $connection_action = "Updated Connection Details";       
            Userlogs::createUserLog($sequenceNo,'Edit Connection',$connection_action);
            return Response::json(['success' => '1']);
        }
        return Response::json(['errors' => $validator->errors()]);
    }
}