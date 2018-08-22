<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ConnectionRate;
use App\Models\ConnectionType;
use App\Models\MeterStaus;
use App\Models\ConnectionMinRate;
use App\Models\ConsumerConnection;
use Carbon\Carbon;
use DB;

class Billing  extends Model
{
    
     public static function retriveBilldata($agent_id,$date,$corp_ward_id) {
        
   
          $data = [];
          $data['server_date']=Carbon::now()->timestamp;


          $query = MeterReading::select( 
                    'meter_reading.sequence_number',DB::raw('consumer_connection.name AS consumer_name'),'consumer_connection.door_no', 
                    'consumer_connection.connection_type_id','master_ward.ward_name','master_corp.corp_name','meter_reading.corpward_id',
                     DB::raw('(CASE WHEN meter_reading.meter_change_status = 1 THEN updated_meterreading.meter_change_date ELSE meter_reading.date_of_reading END) AS previous_billing_date'),
                    'meter_reading.bill_no', 'consumer_connection.meter_no','consumer_connection.connection_status_id',DB::raw('consumer_connection.meter_status_id AS meter_status'),
                    'meter_reading.meter_rent', 'meter_reading.total_unit_used',DB::raw('(CASE WHEN meter_reading.meter_change_status = 1 THEN updated_meterreading.updated_reading ELSE meter_reading.current_reading END) AS previous_reading'), 
                    'consumer_connection.no_of_flats','consumer_connection.ward_id',
                    'meter_reading.water_charge', 'meter_reading.supervisor_charge', 'meter_reading.other_charges', 
                    'meter_reading.refund_amount','meter_reading.other_title_charge','payment_history.payment_date','meter_reading.total_amount','meter_reading.advance_amount',
                    'meter_reading.total_due',DB::raw('(CASE WHEN meter_reading.excess_amount_flag = 0 THEN updated_connection_change.arrear_amt ELSE 0 END) AS arrear_amt'),DB::raw('(CASE WHEN meter_reading.excess_amount_flag = 0 THEN updated_connection_change.excess_amt ELSE 0 END) AS excess_amt'),
                    'meter_reading.payment_status',DB::raw('(CASE WHEN meter_reading.payment_status = 0 THEN IFNULL(meter_reading.total_amount,0)+IFNULL(extra_amount,0) ELSE IFNULL(extra_amount,0)+IFNULL(arrears,0) END) AS arrears'),
                    'meter_reading.round_off',DB::raw('(CASE WHEN consumer_connection.meter_status_id = 1 THEN meter_reading.three_month_average WHEN consumer_connection.meter_status_id = 2 THEN meter_reading.three_month_average WHEN consumer_connection.meter_status_id = 5 THEN meter_reading.three_month_average WHEN consumer_connection.meter_status_id = 7 THEN meter_reading.three_month_average WHEN consumer_connection.meter_status_id = 8 THEN meter_reading.three_month_average ELSE avg_amount.average_amount END) AS average_amount'),
					DB::raw('(CASE WHEN meter_reading.meter_change_status = 1  THEN 0 ELSE avg_amount.mnr_count END) AS mnr_count'))
                    ->leftjoin('payment_history', 'payment_history.meter_reading_id', '=', 'meter_reading.id')  
                    ->leftjoin('consumer_connection','consumer_connection.sequence_number','=','meter_reading.sequence_number')
                    ->leftjoin('master_corp','master_corp.id','=','consumer_connection.corp_ward_id')
                    ->leftjoin('master_ward','master_ward.id','=','consumer_connection.ward_id')
                   // ->leftjoin('log_connection_change','log_connection_change.sequence_no','=','meter_reading.sequence_number')
                    ->leftjoin(DB::raw("(select * from log_connection_change where id in (SELECT max(id) FROM log_connection_change GROUP BY sequence_no order by id desc )) updated_connection_change"),'meter_reading.sequence_number','=','updated_connection_change.sequence_no')
                    ->leftjoin(DB::raw("(SELECT * FROM (SELECT MR1.id, MR1.sequence_number, AVG(MR2.total_amount) as average_amount,SUM(case when MR2.meter_status = 2 then 1 else 0 end) as mnr_count FROM meter_reading MR1 JOIN meter_reading MR2 ON MR1.sequence_number = MR2.sequence_number AND MR2.id >= MR1.id GROUP BY id, sequence_number HAVING COUNT(*) <= 3 ORDER BY sequence_number, id ) AS MR3 GROUP BY sequence_number) avg_amount"),'meter_reading.sequence_number','=','avg_amount.sequence_number')
                    ->leftjoin(DB::raw("(select * from log_meter_change where id in (SELECT max(id) FROM log_meter_change GROUP BY sequence_no order by id desc )) updated_meterreading"),'meter_reading.sequence_number','=','updated_meterreading.sequence_no');
                   // $query->where('meter_reading.agent_id', $agent_id) ;
                    $query->where('meter_reading.corpward_id', $corp_ward_id) ;
                    $query->where('meter_reading.active_record','=','1');
                    $query->where(function($query)use ($date){
                    if($date !='0')
                    {
                    $query->orwhere('meter_reading.created_at', '>=', "$date");
                    $query->orWhere('meter_reading.updated_at','>=',"$date");
                    $query->orwhere('consumer_connection.created_at', '>=', "$date");
                    $query->orWhere('consumer_connection.updated_at','>=',"$date");
                    $query->orwhere('updated_connection_change.created_at', '>=', "$date");
                    $query->orWhere('updated_connection_change.updated_at','>=',"$date");
                    }
                    });
                $query->groupBy('meter_reading.sequence_number');
             
                
            $data['reading_details'] = $query->get();
                
            if($date =='0')
            {
          
                $data['users'] = \DB::select('select id, name,cat_id,status from users where cat_id IN(3,4)');
                   $data['connection_rate'] = ConnectionRate::
                                  select('id', 'connection_type_id','from_unit','to_unit','price')->get();
                   $data['connection_min_rate'] = ConnectionMinRate::
                                  select('id', 'connection_type_id','from_unit','to_unit','min_price')->get();
                   $data['connection_type'] = ConnectionType::
                                  select('id', 'connection_name')->get();
                   $data['meter_status'] = MeterStaus::
                                  select('id', 'meter_status')->get();

                  $data['new_consumer_connection'] = \DB::select("select consumer_connection.sequence_number,consumer_connection.name as consumer_name, consumer_connection.door_no, 
                        consumer_connection.connection_type_id,consumer_connection.meter_no,consumer_connection.connection_status_id, 
                       consumer_connection.connection_date as connection_date,
                        master_ward.ward_name,master_corp.corp_name,consumer_connection.corp_ward_id,consumer_connection.ward_id,
                        meter_reading.date_of_reading as previous_billing_date, 
                        meter_reading.bill_no, 
                        4 AS meter_status,
                        log_connection_change.arrear_amt,log_connection_change.excess_amt,
                        meter_reading.meter_rent, meter_reading.total_unit_used, meter_reading.previous_reading, consumer_connection.no_of_flats,
                        meter_reading.water_charge, meter_reading.supervisor_charge, meter_reading.other_charges, meter_reading.refund_amount,
                        meter_reading.other_title_charge,meter_reading.total_amount,meter_reading.total_due,meter_reading.payment_status,meter_reading.arrears,meter_reading.round_off 
                        from consumer_connection
                        left join master_corp on master_corp.id=consumer_connection.corp_ward_id 
                        left join master_ward on master_ward.id=consumer_connection.ward_id
                        left join agents on agents.corpward_id=consumer_connection.corp_ward_id 
                        left join log_connection_change on log_connection_change.sequence_no =consumer_connection.sequence_number
                        left outer join meter_reading on consumer_connection.sequence_number=meter_reading.sequence_number where  agents.corpward_id='$corp_ward_id' and  meter_reading.sequence_number is null");
                   
            }
            else
            {
              

                $data['users'] = \DB::select('select id, name,cat_id,status from users where cat_id IN(3,4) and (created_at > ? or updated_at > ?)', array($date,$date));                      
                $data['connection_rate'] = ConnectionRate::
                                         select('id', 'connection_type_id','from_unit','to_unit','price')
                                         ->where('created_at', '>=', "$date")
                                         ->orwhere('updated_at','>=',"$date")
                                         ->get();
                 $data['connection_min_rate'] = ConnectionMinRate::
                                  select('id', 'connection_type_id','from_unit','to_unit','min_price')
                                 ->where('created_at', '>=', "$date")
                                 ->orwhere('updated_at','>=',"$date")
                                 ->get();
                $data['connection_type'] = ConnectionType::
                                         select('id', 'connection_name')
                                         ->where('created_at', '>=', "$date")
                                         ->orwhere('updated_at','>=',"$date")
                                         ->get();
                 $data['meter_status'] = MeterStaus::
                                         select('id', 'meter_status')
                                         ->where('created_at', '>=', "$date")
                                         ->orwhere('updated_at','>=',"$date")
                                         ->get();
                 $data['new_consumer_connection'] = \DB::select("select consumer_connection.sequence_number,consumer_connection.name, consumer_connection.door_no, 
                        consumer_connection.connection_type_id,consumer_connection.meter_no,consumer_connection.connection_status_id, consumer_connection.connection_date as connection_date,
                        master_ward.ward_name,master_corp.corp_name,consumer_connection.corp_ward_id,consumer_connection.ward_id,
                        meter_reading.date_of_reading as previous_billing_date, 
                        meter_reading.bill_no, 
                        4 AS meter_status,
                        log_connection_change.arrear_amt,log_connection_change.excess_amt,
                        meter_reading.meter_rent, meter_reading.total_unit_used, meter_reading.previous_reading,consumer_connection.no_of_flats,
                        meter_reading.water_charge, meter_reading.supervisor_charge, meter_reading.other_charges, meter_reading.refund_amount,
                        meter_reading.other_title_charge,meter_reading.total_amount,meter_reading.total_due,meter_reading.payment_status,meter_reading.arrears,meter_reading.round_off 
                        from consumer_connection
                        left join master_corp on master_corp.id=consumer_connection.corp_ward_id 
                        left join master_ward on master_ward.id=consumer_connection.ward_id
                        left join agents on agents.corpward_id=consumer_connection.corp_ward_id
                        left join log_connection_change on log_connection_change.sequence_no =consumer_connection.sequence_number
                        left outer join meter_reading on consumer_connection.sequence_number=meter_reading.sequence_number where agents.corpward_id='$corp_ward_id' and meter_reading.sequence_number is null and (consumer_connection.created_at >'$date' or consumer_connection.updated_at > '$date') " );
                   
            }
           
         return $data;
         
     }
}