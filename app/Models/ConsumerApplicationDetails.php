<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ConsumerApplication;
use App\Models\ConsumerConnection;
use App\Models\Agent;
use App\Models\Inspector;
use App\Models\ApplicationLedger;
use App\Models\WardSequence;
use Carbon\Carbon;
use DB;

class ConsumerApplicationDetails  extends Model
{
 
    public static function getDisplayList()
    {
        
          $data=ConsumerApplication::leftjoin('master_application_status','master_application_status.id','=','consumer_application.application_status_id')
                  ->select('consumer_application.id','application_number','customer_name','application_date','khata_no','phone_number','document_name','certificate_name','remarks','application_status_id','master_application_status.status')->get();
          return $data;
    }
    
    public static function getApplicationInfo($application_id)
    {
        $data=ConsumerApplication::leftjoin('master_application_status','master_application_status.id','=','consumer_application.application_status_id')
                ->leftjoin('consumer_address','consumer_address.application_id','=','consumer_application.id')
                 ->leftjoin('master_connections_type','master_connections_type.id','=','consumer_application.connection_type_id')
                  ->leftjoin('master_ward','master_ward.id','=','consumer_application.ward_id')
                ->leftjoin('master_corp','master_corp.id','=','consumer_application.corp_ward_id')
                  ->select('consumer_application.id','application_number','customer_name','consumer_application.ward_id','consumer_application.corp_ward_id','master_corp.corp_name','master_ward.ward_name','approved_by',
                          'application_date','khata_no','phone_number','document_name','certificate_name','master_application_status.status',
                          'remarks','application_status_id','master_connections_type.connection_name','application_type_id','connection_type_id',
                          'consumer_address.premises_owner_name','consumer_address.premises_address','consumer_address.premises_street',
                          'consumer_address.premises_city','consumer_address.premises_state','consumer_address.premises_zip')
                        ->where('consumer_application.id',$application_id)->get();
       
          return $data;
    }
    
    public static function getInspectors($ward_id)
    {
         $data=Inspector::leftjoin('inspector_ward', 'inspector.id','=','inspector_ward.inspector_id')
                           ->select('inspector.id','inspector_code','inspector_name')
                           ->where('inspector_ward.ward_id',$ward_id)->get();
       
          return $data;
    }
    public static function getAgents($corp_ward_id)
    {
         $data=Agent::select('agent_user_id','agent_name','agent_code')
                        ->where('corpward_id',$corp_ward_id)->get();
       
          return $data;
    }
    public static function get_consumer_info($application_id)
    {
         $data=ConsumerApplication::leftjoin('consumer_ledger', 'consumer_ledger.application_id','=','consumer_application.id')
                 ->leftjoin('master_ward','master_ward.id','=','consumer_application.ward_id')
                 ->leftjoin('master_corp','master_corp.id','=','consumer_application.corp_ward_id')
                        ->select('consumer_application.id', 'master_corp.corp_name','master_ward.ward_name','consumer_application.connection_type_id', 'customer_name', 'phone_number', 'door_no', 'khata_no', 'no_of_house', 'consumer_application.ward_id', 'consumer_application.corp_ward_id', 'meter_no', 'meter_sanctioned_date','consumer_ledger.connection_date')
                        ->where('consumer_application.id',$application_id)->first();
         return $data;
    }
    
    public static function  get_sequence_info($application_id)
    {
         $data=ConsumerConnection::select('sequence_number','meter_no','name')
                        ->where('application_id',$application_id)->get();
       
          return $data;
    }
    
    public static function check_consumer_connection($application_id)
    {
         $data=ConsumerConnection::select('sequence_number')
                        ->where('application_id',$application_id)->get();
       
          return $data;
    }
    public static function check_meter_approval($application_id)
    {
         $data=ConsumerApplication::select('meter_no','approved_by')
                        ->where('id',$application_id)->get();
       
          return $data;
    }
    
    public static function get_ledger_info($application_id)
    {  
        $data=ApplicationLedger::select('id','no_of_flats','tap_diameter','connection_date','deposit_amount','deposit_date','order_no','deposit_challan_no','remarks','connection_charge')
                        ->where('application_id',$application_id)->get();
       
          return $data;
    }
    
    public static function get_meter_det_info($application_id)
    {
         $data=ConsumerApplication::select('meter_no','meter_sanctioned_date')
                        ->where('id',$application_id)->get();
 
          return $data;
    }
    public static function get_approval_by_info($application_id)
    {
         $data=ConsumerApplication::select('users.name as approved_by')
                    ->leftjoin('users','users.id','=','consumer_application.approved_by')
                        ->where('consumer_application.id',$application_id)->get();
       
          return $data;
    }
    
    public static function getWardRange($ward_id)
    {
          $data=WardSequence::select('seq_from')
                        ->where('ward_id',$ward_id)->first();
       
          return $data;
    }
    public static function getLastSequence($ward_id)
    {
        $data=ConsumerApplication::select('consumer_connection.sequence_number')
                        ->leftjoin('master_ward','master_ward.id','=','consumer_application.ward_id')
                         ->leftjoin('consumer_connection','consumer_connection.ward_id','=','consumer_application.ward_id') 
                        ->where('consumer_application.ward_id',$ward_id)->orderBy('consumer_connection.id', 'desc')->take(1)->get();
       
          return $data;
    }
            

}
