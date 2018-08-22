<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ConsumerConnection;

use Carbon\Carbon;
use DB;

class WardWiseDCBReport  extends Model
{
 
    public static function getWardDCBSearchResult($type, $ward, $con_type, $from_date, $to_date) {

        $dcb_ward_wise_data = ConsumerConnection::select('master_corp.corp_name', 
                                                    'master_connections_type.connection_name',
                                                    DB::raw('count(*) as total_installation'),
                                                    DB::raw('SUM(if(consumer_connection.connection_status_id = 2,1,0)) as live'),
                
                                                    DB::raw('COUNT(meter_reading_curr.bill_no) AS bill_count'),
                                                    DB::raw('COALESCE(SUM(meter_reading_curr.total_unit_used),0) as total_unit_used'),
                                                    DB::raw('SUM(CASE WHEN meter_reading_curr.bill_no IS NULL THEN (IFNULL(meter_reading_curr.total_amount,0))-(IFNULL(meter_reading_curr.advance_amount,0)) ELSE (IFNULL(meter_reading_curr.water_charge,0)) END) as water_charge'),
                                                    //DB::raw('COALESCE(SUM(meter_reading_curr.water_charge),0) as water_charge'),
                                                    DB::raw('COALESCE(SUM(meter_reading_curr.other_charges),0) as other_charges'),
                                                    DB::raw('COALESCE(SUM(meter_reading_curr.penalty),0) as penalty'),
                                                    

                                                    //DB::raw('SUM(IFNULL(meter_reading.water_charge,0))+SUM(IFNULL(meter_reading.other_charges,0))+SUM(IFNULL(meter_reading.penalty,0)) as demand'),
                                                    /* old method 
                                                      DB::raw('SUM(IFNULL(meter_reading.water_charge,0))+SUM(IFNULL(meter_reading.other_charges,0))+SUM(IFNULL(meter_reading.penalty,0))-SUM(IFNULL(log_connection_change.excess_amt,0)) as demand'),
                                                      DB::raw('(CASE WHEN old_record.payment_status=1 THEN SUM(IFNULL(old_record.advance_amount,0))+SUM(IFNULL(old_record.extra_amount,0)) ELSE SUM(IFNULL(old_record.total_amount,0)) END) as old_balance'),
                                                     */ 
                
                                                     // use
                                                    //DB::raw('(CASE WHEN meter_reading_curr.payment_status=1 THEN SUM(IFNULL(meter_reading_curr.extra_amount,0)) ELSE SUM(IFNULL(meter_reading_curr.total_amount,0)) END) as old_balance'),
                                                    //DB::raw('(CASE WHEN old_meter.payment_status=1 THEN SUM(IFNULL(old_meter.extra_amount,0)) ELSE SUM(IFNULL(old_meter.total_amount,0)) END) as old_meter_balance'),
                                                    
                                                    //
                                                    ////DB::raw('SUM(IFNULL(old_record.total_amount,0)) as old_balance'),
                                                    //DB::raw('(SUM(IFNULL(meter_reading.extra_amount,0))+SUM(IFNULL(meter_reading.advance_amount,0))) as extra_amount'),
                                                    
                
                                                    //DB::raw('SUM(payment_history.total_amount) as collection'),
                                                    DB::raw('COALESCE(SUM(payment_history.total_amount),0) as collection'),
                
                                                    DB::raw('SUM(CASE WHEN meter_reading_prev.bill_no IS NULL THEN COALESCE(meter_reading_prev.total_amount,0)-COALESCE(meter_reading_prev.advance_amount,0) ELSE COALESCE((meter_reading_prev.water_charge),0)+COALESCE((meter_reading_prev.other_charges),0)+COALESCE((meter_reading_prev.penalty),0) END) as prev_demand'),
                                                    DB::raw('COALESCE(SUM(payment_history_prev.total_amount),0) as prev_collection')
                                                    //DB::raw("GROUP_CONCAT(old_meter.id) as ids")
                )
                // DB::raw('SUM(CASE WHEN meter_reading.payment_status =0 THEN meter_reading.total_amount END) as current_balance'))
                //DB::raw('old_balance+demand as current_balance'))
                
                
                
                /*->leftJoin('meter_reading', function($join)use($from_date,$to_date) {
                    $join->on('consumer_connection.sequence_number', '=', 'meter_reading.sequence_number')
                    ->where("meter_reading.is_olddata",'=', '0')
                    ->whereBetween(DB::raw('date(meter_reading.date_of_reading)'),[$from_date,$to_date]);
                })*/
                
                /*->leftJoin('old_meter_reading as old_meter_reading', function($join)use($from_date,$to_date) {
                    $join->on('consumer_connection.sequence_number', '=', 'old_meter_reading.sequence_number')
                    ->where("old_meter_reading.is_olddata",'=', '0')
                    ->whereBetween(DB::raw('date(old_meter_reading.date_of_reading)'),[$from_date,$to_date]);
                })*/
                // ->leftjoin('master_ward' ,'master_ward.id', '=','consumer_connection.ward_id')
                ->leftJoin('master_corp', 'master_corp.id', '=', 'consumer_connection.corp_ward_id')
                ->leftJoin('master_connections_type', 'master_connections_type.id', '=', 'consumer_connection.connection_type_id')
                //->leftJoin('payment_history', 'payment_history.meter_reading_id', '=', 'meter_reading.id')
               // ->leftjoin('log_connection_change', 'log_connection_change.sequence_no', '=', 'meter_reading.sequence_number')
                ->leftJoin(DB::raw('(SELECT
                                        id,sequence_number,bill_no,total_unit_used,water_charge,other_charges,penalty,payment_status, extra_amount,total_amount,advance_amount
                                    FROM
                                        meter_reading
                                    WHERE
                                        is_olddata = 0 AND DATE(meter_reading.date_of_reading) BETWEEN "'.$from_date.'" AND "'.$to_date.'"
                                    UNION ALL
                                        SELECT
                                            id,sequence_number,bill_no,total_unit_used,water_charge,other_charges,penalty,payment_status, extra_amount,total_amount,advance_amount
                                        FROM
                                            old_meter_reading
                                        WHERE
                                            is_olddata = 0 AND DATE(old_meter_reading.date_of_reading) BETWEEN "'.$from_date.'" AND "'.$to_date.'"
                                    ) as meter_reading_curr'), 'consumer_connection.sequence_number', '=', 'meter_reading_curr.sequence_number')
                ->leftjoin('payment_history', 'meter_reading_curr.id', '=', 'payment_history.meter_reading_id')
                
                
                ->leftJoin(DB::raw('(SELECT
                                        id,sequence_number,bill_no,total_unit_used,water_charge,other_charges,penalty,payment_status, extra_amount,total_amount,advance_amount
                                    FROM
                                        meter_reading
                                    WHERE
                                        is_olddata = 0 AND DATE(meter_reading.date_of_reading) < "'.$from_date.'"
                                    UNION ALL
                                        SELECT
                                            id,sequence_number,bill_no,total_unit_used,water_charge,other_charges,penalty,payment_status, extra_amount,total_amount,advance_amount
                                        FROM
                                            old_meter_reading
                                        WHERE
                                            is_olddata = 0 AND DATE(old_meter_reading.date_of_reading) < "'.$from_date.'"
                                    ) as meter_reading_prev'), 'consumer_connection.sequence_number', '=', 'meter_reading_prev.sequence_number')
                
                ->leftjoin('payment_history as payment_history_prev', 'meter_reading_prev.id', '=', 'payment_history_prev.meter_reading_id');

                //->leftJoin("( SELECT id, sequence_number, bill_no, total_unit_used, water_charge, other_charges, penalty, payment_status, extra_amount, total_amount FROM meter_reading WHERE `date_of_reading` < DATE('$from_date') ) UNION ( SELECT id, sequence_number, bill_no, total_unit_used, water_charge, other_charges, penalty, payment_status, extra_amount, total_amount FROM old_meter_reading WHERE `date_of_reading` < DATE('$from_date') AND old_meter_reading.is_olddata = 0 )");

        if ($from_date != 0 && $to_date != 0) {
            //$dcb_ward_wise_data->where(DB::raw('date(meter_reading.date_of_reading)'), '>=', $from_date);
           // $dcb_ward_wise_data->where(DB::raw('date(meter_reading.date_of_reading)'), '<=', $to_date);
        }

        if ($type == 1) {
            if ($con_type != 0) {
                $dcb_ward_wise_data->where('consumer_connection.connection_type_id', '=', $con_type);
            }
            $dcb_ward_wise_data->groupBy('consumer_connection.connection_type_id');
            $dcb_ward_wise_data->orderBy('master_connections_type.connection_name');
        } else {
            if ($ward != 0) {
                $dcb_ward_wise_data->where('consumer_connection.corp_ward_id', '=', $ward);
            }

            $dcb_ward_wise_data->groupBy('consumer_connection.corp_ward_id');
            $dcb_ward_wise_data->orderBy('master_corp.corp_name');
        }




        /* if($ward!=0 && $con_type==0 )
          {
          $dcb_ward_wise_data->where('consumer_connection.corp_ward_id', '=',$ward);
          }

          if($con_type!=0 && $ward==0)
          {
          $dcb_ward_wise_data->where('consumer_connection.connection_type_id', '=',$con_type);

          }
          if($con_type!=0 && $ward!=0)
          {
          $dcb_ward_wise_data->where('consumer_connection.ward_id', '=',$ward);
          $dcb_ward_wise_data->where('consumer_connection.connection_type_id', '=',$con_type);
          } */


        

        //$dcb_ward_wise_data_result = $dcb_ward_wise_data->get()->toArray();
        $dcb_ward_wise_data_result = $dcb_ward_wise_data;
        return $dcb_ward_wise_data_result;
    }

    /*public static function getWardDCBExcel($ward,$con_type,$from_date,$to_date)
    {
    
        $dcb_ward_wise_data_result= ConsumerConnection::select('master_corp.corp_name',
                'master_connections_type.connection_name',
                 DB::raw('count(*) as total_installation') ,
                 DB::raw('SUM(if(consumer_connection.connection_status_id = 2,1,0)) as live'),
                 DB::raw('COUNT(meter_reading.bill_no) AS bill_count'),
                 DB::raw('SUM(meter_reading.total_unit_used) as total_unit_used'), 
                 DB::raw('SUM(meter_reading.water_charge) as water_charge'),
                 DB::raw('SUM(meter_reading.other_charges) as other_charges'),                 
                 DB::raw('SUM(meter_reading.penalty) as penalty'),
                 DB::raw('SUM(IFNULL(meter_reading.water_charge,0))+SUM(IFNULL(meter_reading.other_charges,0))+SUM(IFNULL(meter_reading.penalty,0))-SUM(IFNULL(log_connection_change.excess_amt,0)) as demand'),
                 DB::raw('(CASE WHEN old_record.payment_status=1 THEN SUM(IFNULL(old_record.advance_amount,0))+SUM(IFNULL(old_record.extra_amount,0)) ELSE SUM(IFNULL(old_record.total_amount,0)) END) as old_balance'),
                 DB::raw('SUM(payment_history.total_amount) as collection'))
                // DB::raw('SUM(CASE WHEN meter_reading.payment_status =0 THEN meter_reading.total_amount END) as current_balance'))
                 //DB::raw('old_balance+demand as current_balance'))
                 ->leftJoin('meter_reading', function($join){
                        $join->on('consumer_connection.sequence_number', '=', 'meter_reading.sequence_number')
                             ->where('meter_reading.active_record', 1);
                    })
                // ->leftjoin('master_ward' ,'master_ward.id', '=','consumer_connection.ward_id')
				 ->leftjoin('master_corp' ,'master_corp.id', '=','consumer_connection.corp_ward_id')
                 ->leftjoin('master_connections_type' ,'master_connections_type.id', '=','consumer_connection.connection_type_id')
                 ->leftjoin('payment_history' ,'payment_history.meter_reading_id', '=','meter_reading.id')
                 ->leftjoin('log_connection_change','log_connection_change.sequence_no','=','meter_reading.sequence_number')
                 ->leftjoin(DB::raw('(select * from meter_reading where id in(select max(id) from meter_reading where active_record=0 group by meter_reading.sequence_number)) old_record '),'meter_reading.sequence_number','=','old_record.sequence_number')

                 ->where('meter_reading.is_olddata','=','0')
                 ->where(function($dcb_ward_wise_data)use ($ward,$con_type,$from_date,$to_date){
                    if($from_date !=0 && $to_date!=0)
                    {
                        $dcb_ward_wise_data->where(DB::raw('date(meter_reading.date_of_reading)'), '>=',$from_date);
                        $dcb_ward_wise_data->where(DB::raw('date(meter_reading.date_of_reading)'), '<=',$to_date);
                    }
                   if($ward!=0 && $con_type==0 )
                    {
                        $dcb_ward_wise_data->where('consumer_connection.corp_ward_id', '=',$ward);
                    }
                  
                    if($con_type!=0 && $ward==0)
                    {
                        $dcb_ward_wise_data->where('consumer_connection.connection_type_id', '=',$con_type);

                    }
                    if($con_type!=0 && $ward!=0)
                    {
                         $dcb_ward_wise_data->where('consumer_connection.ward_id', '=',$ward);
                         $dcb_ward_wise_data->where('consumer_connection.connection_type_id', '=',$con_type);
                    }
                    })
                ->groupBy('consumer_connection.connection_type_id')
                ->groupBy('consumer_connection.corp_ward_id')->get();
                
                
        // $dcb_ward_wise_data_result = $dcb_ward_wise_data->get()->toArray();
         if(empty($dcb_ward_wise_data_result))
            {
               $dcb_ward_wise_data_result=[];
               return $dcb_ward_wise_data_result; 
           
            }
            else
            {
               
              return $dcb_ward_wise_data_result; 
            } 
                

        } */
 
}
