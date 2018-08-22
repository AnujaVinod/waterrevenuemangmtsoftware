<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\MeterReading;
use App\Models\ConsumerConnection;
use App\Models\OldMeterReading;
use App\Models\PaymentHistory;
use Carbon\Carbon;
use DB;

class ConsumerBillReport  extends Model
{

    public static function getSearchResult($seq_number,$meter_no,$corp_ward,$con_type,$from_date,$to_date,$search_key,$start_limit,$paginate_count)
    {
       
         $old_billing_details= DB::table("old_meter_reading")->select(                   
                 'consumer_connection.sequence_number','consumer_connection.name','old_meter_reading.id',
                 'consumer_connection.connection_date','consumer_address.premises_address','old_meter_reading.active_record',
                 'consumer_connection.meter_no','master_connections_type.connection_name',
                 'master_ward.ward_name','master_corp.corp_name','users.name as agent_name', 
                 DB::raw('(CASE WHEN date_of_reading!="0000-00-00 00:00:00" THEN YEAR(date_of_reading) ELSE "-" END) AS year,(CASE WHEN date_of_reading!="0000-00-00 00:00:00" THEN MONTH(date_of_reading) ELSE "-" END) AS month'),
                 'old_meter_reading.date_of_reading','old_meter_reading.import_flag',
                 'old_meter_reading.previous_billing_date','old_meter_reading.previous_reading',
                 'old_meter_reading.current_reading','old_meter_reading.water_charge','old_meter_reading.extra_amount','old_meter_reading.advance_amount',
                 'old_meter_reading.other_charges','old_meter_reading.penalty','old_meter_reading.meter_status','old_meter_reading.bill_no','old_meter_reading.payment_status',
                 DB::raw('round(old_meter_reading.total_amount) as total_amount '),
                                'old_meter_reading.total_unit_used',
                  DB::raw('SUM(IFNULL(payment_history.total_amount,0)) as paid_amount'),DB::raw('round(IFNULL(old_meter_reading.water_charge,0)+IFNULL(old_meter_reading.other_charges,0)+IFNULL(old_meter_reading.penalty,0))as demand'),
                 'payment_history.payment_date','bank_info.transaction_number')
                  ->leftjoin('payment_history','payment_history.meter_reading_id','=','old_meter_reading.old_data_id')
                 ->leftjoin('consumer_connection','consumer_connection.sequence_number','=','old_meter_reading.sequence_number')
                 ->leftjoin('master_ward','master_ward.id','=','consumer_connection.ward_id')
                 ->leftjoin('master_corp','master_corp.id','=','consumer_connection.corp_ward_id')
                 ->leftjoin('users','users.id','=','old_meter_reading.agent_id')
                 ->leftjoin('consumer_address','consumer_address.sequence_number','=','consumer_connection.sequence_number')
                 ->leftjoin('master_meter_status','master_meter_status.id','=','old_meter_reading.meter_status')
                 ->leftjoin('master_connections_type','master_connections_type.id','=','consumer_connection.connection_type_id')     
                 ->leftjoin('bank_info','bank_info.payment_id','=','payment_history.id')

                 ->where(function($old_billing_details)use ($seq_number,$corp_ward,$meter_no,$con_type,$from_date,$to_date,$search_key){
                      if($from_date !=0 && $to_date!=0)
                    {
                        $old_billing_details->where(DB::raw('date(old_meter_reading.date_of_reading)'), '>=',$from_date);
                        $old_billing_details->where(DB::raw('date(old_meter_reading.date_of_reading)'), '<=',$to_date);
                    }
                   if($seq_number !='0')
                    {
                    $old_billing_details->where('consumer_connection.sequence_number', '=',$seq_number);
                   
                    } 
                    if($corp_ward!='0')
                    {
                        $old_billing_details->where('old_meter_reading.corpward_id', '=',$corp_ward);
                    }
                    if($meter_no!='0')
                    {
                           $old_billing_details->where('consumer_connection.meter_no', '=',$meter_no);
                    }
                    if($con_type!='0')
                    {
                        $old_billing_details->where('consumer_connection.connection_type_id', '=',$con_type);

                    }
                    if($search_key!='')
                    {
                        $old_billing_details->whereRaw('(consumer_connection.sequence_number LIKE  "%'.$search_key.'%" OR consumer_connection.name 
                                   LIKE "%'.$search_key.'%" OR consumer_address.premises_address LIKE "%'.$search_key.'%" OR 
                                    consumer_connection.meter_no LIKE "%'.$search_key.'%" OR master_connections_type.connection_name LIKE "%'.$search_key.'%" OR 
                                    master_ward.ward_name LIKE "%'.$search_key.'%" OR master_corp.corp_name LIKE "%'.$search_key.'%" OR users.name LIKE "%'.$search_key.'%" OR  
                                    old_meter_reading.bill_no LIKE "%'.$search_key.'%" OR 
                                    payment_history.payment_date LIKE "%'.$search_key.'%" OR bank_info.transaction_number LIKE "%'.$search_key.'%")');
                    }
                    
                  })
                ->where('old_meter_reading.is_olddata','=',0)
                ->groupBy('old_meter_reading.sequence_number') ;

                  
        $billing_details= DB::table("meter_reading")->select(                   
                 'consumer_connection.sequence_number','consumer_connection.name','meter_reading.id',
                 'consumer_connection.connection_date','consumer_address.premises_address','meter_reading.active_record',
                 'consumer_connection.meter_no','master_connections_type.connection_name',
                 'master_ward.ward_name','master_corp.corp_name','users.name as agent_name', 
                 DB::raw('YEAR(date_of_reading) AS year, MONTH(date_of_reading) AS month'),
                 'meter_reading.date_of_reading','meter_reading.import_flag',
                 'meter_reading.previous_billing_date','meter_reading.previous_reading',
                 'meter_reading.current_reading','meter_reading.water_charge','meter_reading.extra_amount','meter_reading.advance_amount',
                 'meter_reading.other_charges','meter_reading.penalty','master_meter_status.meter_status','meter_reading.bill_no','meter_reading.payment_status',
                 DB::raw('round(meter_reading.total_amount) as total_amount '),				
                                'meter_reading.total_unit_used',
                  DB::raw('SUM(IFNULL(payment_history.total_amount,0)) as paid_amount'),DB::raw('round(IFNULL(meter_reading.water_charge,0)+IFNULL(meter_reading.other_charges,0)+IFNULL(meter_reading.penalty,0))as demand'),
                 'payment_history.payment_date','bank_info.transaction_number')
                 ->leftjoin('consumer_connection','consumer_connection.sequence_number','=','meter_reading.sequence_number')
                 ->leftjoin('master_ward','master_ward.id','=','consumer_connection.ward_id')
                 ->leftjoin('master_corp','master_corp.id','=','consumer_connection.corp_ward_id')
                 ->leftjoin('users','users.id','=','meter_reading.agent_id')
                 ->leftjoin('consumer_address','consumer_address.sequence_number','=','consumer_connection.sequence_number')
                 ->leftjoin('master_meter_status','master_meter_status.id','=','meter_reading.meter_status')
                 ->leftjoin('master_connections_type','master_connections_type.id','=','consumer_connection.connection_type_id')
                 ->leftjoin('payment_history','payment_history.meter_reading_id','=','meter_reading.id')
                 ->leftjoin('bank_info','bank_info.payment_id','=','payment_history.id')

                 ->where(function($billing_details)use ($seq_number,$corp_ward,$meter_no,$con_type,$from_date,$to_date,$search_key){
                      if($from_date !=0 && $to_date!=0)
                    {
                        $billing_details->where(DB::raw('date(meter_reading.date_of_reading)'), '>=',$from_date);
                        $billing_details->where(DB::raw('date(meter_reading.date_of_reading)'), '<=',$to_date);
                    }
                   if($seq_number !='0')
                    {
                    $billing_details->where('consumer_connection.sequence_number', '=',$seq_number);
                   
                    }
                    if($corp_ward!='0')
                    {
                        $billing_details->where('meter_reading.corpward_id', '=',$corp_ward);
                    }
                    if($meter_no!='0')
                    {
                           $billing_details->where('consumer_connection.meter_no', '=',$meter_no);
                    }
                    if($con_type!='0')
                    {
                        $billing_details->where('consumer_connection.connection_type_id', '=',$con_type);

                    }
                    if($search_key!='')
                    {
                        $billing_details->whereRaw('(consumer_connection.sequence_number LIKE  "%'.$search_key.'%" OR consumer_connection.name 
                                   LIKE "%'.$search_key.'%" OR consumer_address.premises_address LIKE "%'.$search_key.'%" OR 
                                    consumer_connection.meter_no LIKE "%'.$search_key.'%" OR master_connections_type.connection_name LIKE "%'.$search_key.'%" OR 
                                    master_ward.ward_name LIKE "%'.$search_key.'%" OR master_corp.corp_name LIKE "%'.$search_key.'%" OR users.name LIKE "%'.$search_key.'%" OR  
                                    meter_reading.bill_no LIKE "%'.$search_key.'%" OR 
                                    payment_history.payment_date LIKE "%'.$search_key.'%" OR bank_info.transaction_number LIKE "%'.$search_key.'%")');
                    }
                    
                  })
				->where('meter_reading.is_olddata',0)
                ->groupBy('meter_reading.id') 
                ->orderBy('meter_reading.date_of_reading','asc')
                ->orderBy('meter_reading.sequence_number');

		$final = $old_billing_details->union($billing_details);
                $querySql = $final->toSql();
                $all_content_query = DB::table(DB::raw("($querySql) as a"))->mergeBindings($final);	
		$all_content_query->offset($start_limit)
				->limit($paginate_count);

            return $all_content_query; 
      }

    public static function getOldSearchResult($seq_number,$meter_no,$corp_ward,$con_type,$from_date,$to_date,$search_key,$start_limit,$paginate_count)
    {
       
         $billing_details= OldMeterReading::select(                   
                 'consumer_connection.sequence_number','consumer_connection.name','old_meter_reading.id',
                 'consumer_connection.connection_date','consumer_address.premises_address','old_meter_reading.active_record',
                 'consumer_connection.meter_no','master_connections_type.connection_name',
                 'master_ward.ward_name','master_corp.corp_name','users.name as agent_name', 
                 DB::raw('(CASE WHEN date_of_reading!="0000-00-00 00:00:00" THEN YEAR(date_of_reading) ELSE "-" END) AS year,(CASE WHEN date_of_reading!="0000-00-00 00:00:00" THEN MONTH(date_of_reading) ELSE "" END) AS month'),
                 'old_meter_reading.date_of_reading','old_meter_reading.import_flag',
                 'old_meter_reading.previous_billing_date','old_meter_reading.previous_reading',
                 'old_meter_reading.current_reading','old_meter_reading.water_charge','old_meter_reading.extra_amount','old_meter_reading.advance_amount',
                 'old_meter_reading.other_charges','old_meter_reading.penalty','master_meter_status.meter_status','old_meter_reading.bill_no','old_meter_reading.payment_status',
                 DB::raw('round(old_meter_reading.total_amount) as total_amount '),'old_meter_reading.total_unit_used',
                 DB::raw('round(IFNULL(old_meter_reading.water_charge,0)+IFNULL(old_meter_reading.other_charges,0)+IFNULL(old_meter_reading.penalty,0))as demand'))
                 ->leftjoin('consumer_connection','consumer_connection.sequence_number','=','old_meter_reading.sequence_number')
                 ->leftjoin('master_ward','master_ward.id','=','consumer_connection.ward_id')
                 ->leftjoin('master_corp','master_corp.id','=','consumer_connection.corp_ward_id')
                 ->leftjoin('users','users.id','=','old_meter_reading.agent_id')
                 ->leftjoin('consumer_address','consumer_address.sequence_number','=','consumer_connection.sequence_number')
                 ->leftjoin('master_meter_status','master_meter_status.id','=','old_meter_reading.meter_status')
                 ->leftjoin('master_connections_type','master_connections_type.id','=','consumer_connection.connection_type_id')       
                 

                 ->where(function($billing_details)use ($seq_number,$corp_ward,$meter_no,$con_type,$from_date,$to_date,$search_key){
                      if($from_date !=0 && $to_date!=0)
                    {
                        $billing_details->where(DB::raw('date(old_meter_reading.date_of_reading)'), '>=',$from_date);
                        $billing_details->where(DB::raw('date(old_meter_reading.date_of_reading)'), '<=',$to_date);
                    }
                   if($seq_number !='0')
                    {
                    $billing_details->where('consumer_connection.sequence_number', '=',$seq_number);
                   
                    }

                    if($corp_ward!='0')
                    {
                        $billing_details->where('old_meter_reading.corpward_id', '=',$corp_ward);
                    }
                    if($meter_no!='0')
                    {
                           $billing_details->where('consumer_connection.meter_no', '=',$meter_no);
                    }
                    if($con_type!='0')
                    {
                        $billing_details->where('consumer_connection.connection_type_id', '=',$con_type);

                    }
                    if($search_key!='')
                    {
                        $billing_details->whereRaw('(consumer_connection.sequence_number LIKE  "%'.$search_key.'%" OR consumer_connection.name 
                                   LIKE "%'.$search_key.'%" OR consumer_address.premises_address LIKE "%'.$search_key.'%" OR 
                                    consumer_connection.meter_no LIKE "%'.$search_key.'%" OR master_connections_type.connection_name LIKE "%'.$search_key.'%" OR 
                                    master_ward.ward_name LIKE "%'.$search_key.'%" OR master_corp.corp_name LIKE "%'.$search_key.'%" OR users.name LIKE "%'.$search_key.'%" OR  
                                    old_meter_reading.bill_no LIKE "%'.$search_key.'%"');
                    }
                    
                  })
                ->where('old_meter_reading.is_olddata','=',1)
               //->groupBy('meter_reading.sequence_number') 
                ->orderBy('old_meter_reading.date_of_reading','asc')
                ->orderBy('old_meter_reading.sequence_number')        
                ->offset($start_limit)
                ->limit($paginate_count);


           return $billing_details; 
           
      }

    public static function getOldPaymentDetails($seq_number,$meter_no,$corp_ward,$con_type,$from_date,$to_date,$search_key,$start_limit,$paginate_count)
    {
       
         $billing_details= PaymentHistory::select(                   
                 'consumer_connection.sequence_number','consumer_connection.name',
                 'consumer_connection.connection_date','consumer_address.premises_address',
                 'consumer_connection.meter_no','master_connections_type.connection_name',
                 'master_ward.ward_name','master_corp.corp_name','payment_history.total_amount','payment_history.payment_date','bank_info.transaction_number')
                 ->leftjoin('consumer_connection','consumer_connection.sequence_number','=','payment_history.sequence_number')
                 ->leftjoin('bank_info','bank_info.payment_id','=','payment_history.id')
                 ->leftjoin('master_ward','master_ward.id','=','consumer_connection.ward_id')
                 ->leftjoin('master_corp','master_corp.id','=','consumer_connection.corp_ward_id')
                 ->leftjoin('consumer_address','consumer_address.sequence_number','=','consumer_connection.sequence_number')     
                 ->leftjoin('master_connections_type','master_connections_type.id','=','consumer_connection.connection_type_id')       
                 

                 ->where(function($billing_details)use ($seq_number,$corp_ward,$meter_no,$con_type,$from_date,$to_date,$search_key){
                      if($from_date !=0 && $to_date!=0)
                    {
                        $billing_details->where(DB::raw('date(payment_history.payment_date)'), '>=',$from_date);
                        $billing_details->where(DB::raw('date(payment_history.payment_date)'), '<=',$to_date);
                    }
                   if($seq_number !='0')
                    {
                    $billing_details->where('consumer_connection.sequence_number', '=',$seq_number);
                   
                    }

                    if($corp_ward!='0')
                    {
                        $billing_details->where('consumer_connection.corp_ward_id', '=',$corp_ward);
                    }
                    if($meter_no!='0')
                    {
                           $billing_details->where('consumer_connection.meter_no', '=',$meter_no);
                    }
                    if($con_type!='0')
                    {
                        $billing_details->where('consumer_connection.connection_type_id', '=',$con_type);

                    }
                    if($search_key!='')
                    {
                        $billing_details->whereRaw('(consumer_connection.sequence_number LIKE  "%'.$search_key.'%" OR consumer_connection.name 
                                   LIKE "%'.$search_key.'%" OR consumer_address.premises_address LIKE "%'.$search_key.'%" OR 
                                    consumer_connection.meter_no LIKE "%'.$search_key.'%" OR master_connections_type.connection_name LIKE "%'.$search_key.'%" OR 
                                    master_ward.ward_name LIKE "%'.$search_key.'%" OR master_corp.corp_name LIKE "%'.$search_key.'%" OR users.name LIKE "%'.$search_key.'%"');
                    }
                    
                  })
                ->where('payment_history.is_olddata','=',1)
               //->groupBy('meter_reading.sequence_number') 
                ->orderBy('payment_history.payment_date','asc')
                ->orderBy('payment_history.sequence_number')        
                ->offset($start_limit)
                ->limit($paginate_count);


           return $billing_details; 
           
      }
    public static function createExcel($seq_number,$corp_ward,$meter_no,$con_type,$from_date,$to_date)
     {
        
          $billing_details = MeterReading::select('meter_reading.sequence_number' ,'meter_reading.consumer_name','master_connections_type.connection_name',
                                                    'meter_reading.door_no','meter_reading.date_of_reading','meter_reading.bill_no',
                                                  'meter_reading.previous_reading','meter_reading.current_reading','meter_reading.total_amount',
                                                  'master_corp.corp_name','agents.agent_name')  

                    ->leftjoin('agents','agents.agent_user_id','=','meter_reading.agent_id')
                    ->leftjoin('master_corp','master_corp.id','=','meter_reading.corpward_id') 
                   ->leftjoin('consumer_connection','consumer_connection.sequence_number','=','meter_reading.sequence_number')
                  ->leftjoin('master_connections_type','master_connections_type.id','=','consumer_connection.connection_type_id')
                  ->where(function($billing_details)use ($seq_number,$corp_ward,$meter_no,$con_type,$from_date,$to_date){
                      if($from_date !=0 && $to_date!=0)
                    {
                        $billing_details->where(DB::raw('date(meter_reading.date_of_reading)'), '>=',$from_date);
                        $billing_details->where(DB::raw('date(meter_reading.date_of_reading)'), '<=',$to_date);
                    }
                   if($seq_number !='0')
                    {
                    $billing_details->where('meter_reading.sequence_number', '=',$seq_number);
                   
                    }
                    if($corp_ward!='0')
                    {
                        $billing_details->where('meter_reading.corpward_id', '=',$corp_ward);
                    }
                    if($meter_no!='0')
                    {
                           $billing_details->where('meter_reading.meter_no', '=',$meter_no);
                    }
                    if($con_type!='0')
                    {
                        $billing_details->where('consumer_connection.connection_type_id', '=',$con_type);
                    }
                    })
                    ->where('meter_reading.is_olddata',0)
                    ->orderBy('meter_reading.created_at','desc')->get();


               return $billing_details; 

     }

}
