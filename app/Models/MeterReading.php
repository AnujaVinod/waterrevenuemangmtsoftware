<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class MeterReading extends Model
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['sequence_number', 'consumer_name', 'door_no', 'date_of_reading', 'bill_no', 'meter_no', 'meter_status', 'meter_rent', 'payment_due_date', 'previous_billing_date', 'payment_last_date', 'total_unit_used', 'no_of_days_used', 'previous_reading', 'current_reading', 'agent_id', 'no_of_flats', 'water_charge', 'supervisor_charge', 'other_charges', 'refund_amount', 'other_title_charge', 'fixed_charge', 'penalty', 'returned_amount', 'cess', 'ugd_cess', 'arrears', 'total_due', 'round_off','three_month_average', 'total_amount', 'active_record','meter_change_status', 'payment_status','extra_amount','advance_amount','corpward_id','ward_id','generated_by','approved_by','reviewer','is_olddata'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'meter_reading';

    /**
     * Overriding defualt priamry key
     *
     * @var string
     */

    protected $primaryKey = 'id'; 
    
    public static function getOldRecord($sequence_no){
        return MeterReading::where('sequence_number', 'like', $sequence_no)
                             ->where('active_record',0)
                             ->orderBy('id');
    }
   
    
    
    public static function getBillDetails($data) {
         $query = MeterReading::select('meter_reading.*',DB::raw("ward.ward_name as ward"),DB::raw("master_corp.corp_name as corp_name"),DB::raw("master_meter_status.meter_status as meter_status"),DB::raw("master_connections_type.connection_name as connection_name"))
                        ->leftJoin("master_ward as ward","ward.id","=","meter_reading.ward_id")
                        ->leftJoin("master_corp as master_corp","master_corp.id","=","meter_reading.corpward_id")
                        ->leftJoin("master_meter_status as master_meter_status","master_meter_status.id","=","meter_reading.meter_status")
                        ->leftjoin('consumer_connection','consumer_connection.sequence_number','=','meter_reading.sequence_number')
                        ->leftjoin('master_connections_type','master_connections_type.id','=','consumer_connection.connection_type_id')
                        ->where('meter_reading.id', '=', $data['id'])
                        ->where('meter_reading.active_record', '=', '1');
         if(isset($data['reviewer'])) {
            $query->where('meter_reading.reviewer', '=', $data['reviewer']);
         }
        return $query->first();
    }
    
    public static function getOldMeeterRecords(){
        return MeterReading::select("*")
                            ->where('active_record',0)
                             ->orderBy('id')
                             ->groupBy("sequence_number")
                             ->havingRaw('count(sequence_number) > 1')
                             ->get();
    }
    
    public static function meterReadingDetails($data){
        $sql = MeterReading::where('sequence_number','like',$data['sequence_number']);
        return $sql;
    }
}
