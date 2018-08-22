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
use Illuminate\Support\Facades\Auth;
class NewBill  extends Model
{
    
     public static function getCustomerBilldata($meter_no,$seq_no,$door_no) {
        
   
          $data = [];


          $query = MeterReading::select( 
                    'meter_reading.sequence_number',DB::raw('consumer_connection.name AS consumer_name'),'consumer_connection.door_no', 
                    'consumer_connection.connection_type_id','master_ward.ward_name','master_corp.corp_name','meter_reading.corpward_id',
                   DB::raw('(CASE WHEN meter_reading.meter_change_status = 1 THEN updated_meterreading.meter_change_date ELSE meter_reading.previous_billing_date END) AS previous_billing_date'),
                  'master_connections_type.connection_name','meter_reading.date_of_reading',
                    'meter_reading.bill_no', 'consumer_connection.meter_no','consumer_connection.connection_status_id',DB::raw('consumer_connection.meter_status_id AS meter_status'),
                    'meter_reading.meter_rent', 'meter_reading.total_unit_used',DB::raw('(CASE WHEN meter_reading.meter_change_status = 1 THEN updated_meterreading.updated_reading ELSE meter_reading.current_reading END) AS previous_reading'),'meter_reading.current_reading', 
                    'consumer_connection.no_of_flats','consumer_connection.ward_id','meter_reading.payment_due_date',
                    'meter_reading.water_charge', 'meter_reading.supervisor_charge', 'meter_reading.other_charges', 
                    'meter_reading.refund_amount','meter_reading.other_title_charge','meter_reading.fixed_charge',
                    'meter_reading.cess','meter_reading.ugd_cess',
                    'meter_reading.no_of_days_used',
                    'payment_history.payment_date',
                    DB::raw('(CASE WHEN meter_reading.excess_amount_flag = 0 THEN updated_connection_change.arrear_amt ELSE 0 END) AS arrear_amt'),
                    DB::raw('(CASE WHEN meter_reading.excess_amount_flag = 0 THEN updated_connection_change.excess_amt ELSE 0 END) AS excess_amt'),DB::raw('(CASE WHEN meter_reading.previous_billing_date IS NULL
                        THEN consumer_connection.connection_date
                        ELSE meter_reading.previous_billing_date
                        END
                    ) AS previous_billing_date'),'meter_reading.total_due',
                    'meter_reading.payment_status',DB::raw('meter_reading.total_due-(IFNULL(meter_reading.water_charge,0)+IFNULL(meter_reading.other_charges,0)+IFNULL(meter_reading.penalty,0))as arrears'),
                    'meter_reading.round_off',
                    DB::raw('(CASE WHEN consumer_connection.meter_status_id = 1 THEN meter_reading.three_month_average WHEN consumer_connection.meter_status_id = 2 THEN meter_reading.three_month_average WHEN consumer_connection.meter_status_id = 5 THEN meter_reading.three_month_average WHEN consumer_connection.meter_status_id = 7 THEN meter_reading.three_month_average WHEN consumer_connection.meter_status_id = 8 THEN meter_reading.three_month_average ELSE avg_amount.average_amount END) AS average_amount'),'avg_amount.mnr_count')
                    ->leftjoin('payment_history', 'payment_history.meter_reading_id', '=', 'meter_reading.id')  
                    ->leftjoin('consumer_connection','consumer_connection.sequence_number','=','meter_reading.sequence_number')
                    ->leftjoin('master_corp','master_corp.id','=','consumer_connection.corp_ward_id')
                    ->leftjoin('master_ward','master_ward.id','=','consumer_connection.ward_id')
                  //  ->leftjoin('log_connection_change','log_connection_change.sequence_no','=','meter_reading.sequence_number')
                   ->leftjoin(DB::raw("(select * from log_connection_change where id in (SELECT max(id) FROM log_connection_change GROUP BY sequence_no order by id desc )) updated_connection_change"),'meter_reading.sequence_number','=','updated_connection_change.sequence_no')
                    ->leftjoin('master_connections_type','master_connections_type.id','=','consumer_connection.connection_type_id')
                   ->leftjoin(DB::raw("(SELECT * FROM (SELECT MR1.id, MR1.sequence_number, AVG(MR2.total_amount) as average_amount,SUM(case when MR2.meter_status = 2 then 1 else 0 end) as mnr_count FROM meter_reading MR1 JOIN meter_reading MR2 ON MR1.sequence_number = MR2.sequence_number AND MR2.id >= MR1.id GROUP BY id, sequence_number HAVING COUNT(*) <= 3 ORDER BY sequence_number, id ) AS MR3 GROUP BY sequence_number) avg_amount"),'meter_reading.sequence_number','=','avg_amount.sequence_number')
                    ->leftjoin(DB::raw("(select * from log_meter_change where id in (SELECT max(id) FROM log_meter_change GROUP BY sequence_no order by id desc )) updated_meterreading"),'meter_reading.sequence_number','=','updated_meterreading.sequence_no');
;
                    $query->where('meter_reading.active_record','=','1');
                    //$query->where('meter_reading.payment_status','=','0');
                    $query->where(function($query)use ($meter_no,$seq_no,$door_no){
                    if($meter_no !='' && $seq_no=='' && $door_no=='' )
                    {
                      $query->where('meter_reading.meter_no', $meter_no) ;
                    }
                    else if($meter_no =='' && $seq_no!='' && $door_no=='')
                    {
                    $query->where('meter_reading.sequence_number', $seq_no);
                   
                    }
                    else if($meter_no =='' && $seq_no=='' && $door_no!='')
                    {
                    $query->where('consumer_connection.door_no', $door_no);
                   
                    }
                    else
                    {
                         $query->where('meter_reading.meter_no', $meter_no) ;
                         $query->where('meter_reading.sequence_number', $seq_no);
                         $query->where('consumer_connection.door_no', $door_no);
                    }
                    });
                    $query->groupBy('meter_reading.sequence_number');
 
               $data= $query->get();

              return $data;
         
     }
    
     public static function getPreviousBillData($meter_no,$seq_no,$door_no)
     {
              $query = MeterReading::select( 
                    'meter_reading.sequence_number',DB::raw('consumer_connection.name AS consumer_name'),'consumer_connection.door_no', 
                    'consumer_connection.connection_type_id','master_connections_type.connection_name','master_ward.ward_name','master_corp.corp_name','meter_reading.corpward_id',
                    DB::raw('(CASE WHEN meter_reading.meter_change_status = 1 THEN updated_meterreading.meter_change_date ELSE meter_reading.date_of_reading END) AS previous_billing_date'),DB::raw('NOW() as date_of_reading'),
                    'meter_reading.bill_no', 'meter_reading.penalty', 'consumer_connection.meter_no','consumer_connection.connection_status_id',DB::raw('consumer_connection.meter_status_id AS meter_status'),
                    'meter_reading.meter_rent',DB::raw('(CASE WHEN meter_reading.meter_change_status = 1 THEN updated_meterreading.updated_reading ELSE meter_reading.current_reading END) AS previous_reading'), 
                    'consumer_connection.no_of_flats','consumer_connection.ward_id',DB::raw('DATE_ADD(NOW(), INTERVAL 15 DAY) as payment_due_date'),
                    'meter_reading.water_charge', 'meter_reading.supervisor_charge', 'meter_reading.other_charges', 
                    'meter_reading.refund_amount','meter_reading.other_title_charge','payment_history.payment_date','meter_reading.advance_amount',
                   'meter_reading.fixed_charge','meter_reading.cess','meter_reading.ugd_cess',
                    DB::raw('(CASE WHEN meter_reading.excess_amount_flag = 0 THEN updated_connection_change.arrear_amt ELSE 0 END) AS arrear_amt'),
                    DB::raw('(CASE WHEN meter_reading.excess_amount_flag = 0 THEN updated_connection_change.excess_amt ELSE 0 END) AS excess_amt'),
                    'meter_reading.payment_status',DB::raw('(CASE WHEN meter_reading.payment_status = 0 THEN IFNULL(meter_reading.total_amount,0)+IFNULL(extra_amount,0) ELSE IFNULL(meter_reading.arrears,0)+IFNULL(extra_amount,0) END) AS arrears'),
                    DB::raw('(CASE WHEN consumer_connection.meter_status_id = 1 THEN meter_reading.three_month_average WHEN consumer_connection.meter_status_id = 2 THEN meter_reading.three_month_average WHEN consumer_connection.meter_status_id = 5 THEN meter_reading.three_month_average WHEN consumer_connection.meter_status_id = 7 THEN meter_reading.three_month_average WHEN consumer_connection.meter_status_id = 8 THEN meter_reading.three_month_average ELSE avg_amount.average_amount END) AS average_amount'),'avg_amount.mnr_count')
                    ->leftjoin('payment_history', 'payment_history.meter_reading_id', '=', 'meter_reading.id')  
                    ->leftjoin('consumer_connection','consumer_connection.sequence_number','=','meter_reading.sequence_number')
                    ->leftjoin('master_corp','master_corp.id','=','consumer_connection.corp_ward_id')
                    ->leftjoin('master_ward','master_ward.id','=','consumer_connection.ward_id')
                   // ->leftjoin('log_connection_change','log_connection_change.sequence_no','=','meter_reading.sequence_number')
                     ->leftjoin('master_connections_type','master_connections_type.id','=','consumer_connection.connection_type_id')
                     ->leftjoin(DB::raw("(select * from log_connection_change where id in (SELECT max(id) FROM log_connection_change GROUP BY sequence_no order by id desc )) updated_connection_change"),'meter_reading.sequence_number','=','updated_connection_change.sequence_no')
                    ->leftjoin(DB::raw("(SELECT * FROM (SELECT MR1.id, MR1.sequence_number, AVG(MR2.total_amount) as average_amount,SUM(case when MR2.meter_status = 2 then 1 else 0 end) as mnr_count FROM meter_reading MR1 JOIN meter_reading MR2 ON MR1.sequence_number = MR2.sequence_number AND MR2.id >= MR1.id GROUP BY id, sequence_number HAVING COUNT(*) <= 3 ORDER BY sequence_number, id ) AS MR3 GROUP BY sequence_number) avg_amount"),'meter_reading.sequence_number','=','avg_amount.sequence_number')
                     ->leftjoin(DB::raw("(select * from log_meter_change where id in (SELECT max(id) FROM log_meter_change GROUP BY sequence_no order by id desc )) updated_meterreading"),'meter_reading.sequence_number','=','updated_meterreading.sequence_no');
;
                    $query->where('meter_reading.active_record','=','1');
                    $query->where(function($query)use ($meter_no,$seq_no,$door_no){
                    if($meter_no !='' && $seq_no=='' && $door_no=='' )
                    {
                      $query->where('meter_reading.meter_no', $meter_no) ;
                    }
                    else if($meter_no =='' && $seq_no!='' && $door_no=='')
                    {
                    $query->where('meter_reading.sequence_number', $seq_no);
                   
                    }
                    else if($meter_no =='' && $seq_no=='' && $door_no!='')
                    {
                    $query->where('consumer_connection.door_no', $door_no);
                   
                    }
                    else
                    {
                         $query->where('meter_reading.meter_no', $meter_no) ;
                         $query->where('meter_reading.sequence_number', $seq_no);
                         $query->where('consumer_connection.door_no', $door_no);
                    }
                    });
                    $query->groupBy('meter_reading.sequence_number');
 
               $data= $query->first();

              return $data;
                  
              
     }

     public static function getReadingDate($meter_no,$seq_no,$door_no)
     {
          $query = MeterReading::select( 
                    'meter_reading.date_of_reading')->leftjoin('consumer_connection','consumer_connection.sequence_number','=','meter_reading.sequence_number');
                  
                    $query->where(function($query)use ($meter_no,$seq_no,$door_no){
                    if($meter_no !='' && $seq_no=='' && $door_no=='' )
                    {
                      $query->where('meter_reading.meter_no', $meter_no) ;
                      $query->where('meter_reading.active_record', '=','1');
                    }
                    else if($meter_no =='' && $seq_no!='' && $door_no=='')
                    {
                      $query->where('meter_reading.sequence_number', $seq_no);
                      $query->where('meter_reading.active_record', '=','1');
                   
                    }
                    else if($meter_no =='' && $seq_no=='' && $door_no!='')
                    {
                     $query->where('consumer_connection.door_no', $door_no);
                     $query->where('meter_reading.active_record', '=','1');
                   
                    }
                    else
                    {
                         $query->where('meter_reading.meter_no', $meter_no) ;
                         $query->where('meter_reading.sequence_number', $seq_no);
                         $query->where('consumer_connection.door_no', $door_no);
                         $query->where('meter_reading.active_record', '=','1');
                    }
                    });
                    $query->groupBy('meter_reading.sequence_number');
 
               $data= $query->first();
               return $data;
     }

     public static function billdueinfo($seq_no)
     {
           $query = MeterReading::select( 
                    'meter_reading.meter_rent','consumer_connection.no_of_flats','master_connection_minrate.min_price','meter_reading.date_of_reading',
                    'meter_reading.water_charge','meter_reading.other_charges','meter_reading.penalty','meter_reading.total_due','meter_reading.total_amount','meter_reading.advance_amount')
                     ->leftjoin('consumer_connection','consumer_connection.sequence_number','=','meter_reading.sequence_number')
                     ->leftjoin('master_connection_minrate','master_connection_minrate.connection_type_id','=','consumer_connection.connection_type_id') 
                   ->where('meter_reading.sequence_number','=',$seq_no)
                   ->where('meter_reading.active_record','=','1')->first();
           return $query;
     }
     
     public static function readingInfo($seq_no)
     {
           $query = MeterReading::select( 
                    'meter_reading.previous_reading','meter_reading.current_reading','meter_reading.total_unit_used')
                   ->where('meter_reading.sequence_number','=',$seq_no)
                   ->where('meter_reading.active_record','=','1')->get();
           return $query;
     }
     
     public static function checkbillno($bill_no)
     {
          $query = MeterReading::select( 
                    'meter_reading.bill_no')
                   ->where('meter_reading.bill_no','=',$bill_no)
                   ->get();
           return $query;
     }
     public static function getBillListForReview($data) {
        $user_id = Auth::id();
        $query = MeterReading::select('sequence_number','meter_no','meter_reading.id','bill_no', 'date_of_reading', 'consumer_name'/*, DB::raw("ward.ward_name as ward")*/, 'total_amount', DB::raw("approved_by as approved_by"))
                        //->leftJoin("master_ward as ward","ward.id","=","meter_reading.ward_id")
                        
                        ->where('meter_reading.active_record', '=', '1');
        if(isset($data['reviewer'])) {
            $query = $query->where('meter_reading.reviewer', '=', $user_id);
        }
        else if(isset($data['generated_by'])) {
            $query = $query->where('meter_reading.generated_by', '=', $user_id);
        }
       // $query = $query->limit(2);
        return $query;
    }
    
    

}
