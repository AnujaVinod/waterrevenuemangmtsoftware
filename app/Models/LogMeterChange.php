<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;
use DB;
use App\Models\ConsumerConnection;
use App\Models\LogNameChange;

class LogMeterChange extends Model
{
 
    protected $fillable = [
        'sequence_no','old_val','updated_val','updated_reading','order_no','reason','approved_by','document','operation','order_date','meter_change_date','updated_by'
    ];
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'log_meter_change';

    /**
     * Overriding defualt priamry key
     *
     * @var string
     */
    protected $primaryKey = 'id'; 
  
    public static function meterNameChangeLogSearch($seq_no, $meter_no) {
        $result = '';
        $result2 = '';
        if ($seq_no != '' && $meter_no == '') {
            $meterLog = LogMeterChange::
                    select([
                        'log_meter_change.sequence_no', 'log_meter_change.old_val', 'log_meter_change.updated_val', 'log_meter_change.order_no', 'log_meter_change.reason', 'log_meter_change.approved_by', 'log_meter_change.document', 'log_meter_change.operation', DB::raw('log_meter_change.order_date as date'), 'log_meter_change.meter_change_date'])
                    ->where('log_meter_change.sequence_no', $seq_no);

            $nameLog = LogNameChange::
                    select([
                        'log_name_change.sequence_no', 'log_name_change.old_val', 'log_name_change.updated_val', 'log_name_change.order_no', 'log_name_change.reason', 'log_name_change.approved_by', 'log_name_change.document', 'log_name_change.operation', 'log_name_change.date', DB::raw('"" as meter_change_date')])
                    ->where('log_name_change.sequence_no', $seq_no);
            $result2 = $meterLog->union($nameLog)->get();
            return $result2;
        }

        if ($meter_no != '') {
            $sequenceFrommeterno = ConsumerConnection::where('meter_no', $meter_no)->first();
            if ($sequenceFrommeterno) {
                $meterLog = LogMeterChange::
                        select([
                            'log_meter_change.sequence_no', 'log_meter_change.old_val', 'log_meter_change.updated_val', 'log_meter_change.order_no', 'log_meter_change.reason', 'log_meter_change.approved_by', 'log_meter_change.document', 'log_meter_change.operation', DB::raw('log_meter_change.order_date as date'), 'log_meter_change.meter_change_date'])
                        ->where('log_meter_change.sequence_no', $sequenceFrommeterno->sequence_number);

                $nameLog = LogNameChange::
                        select([
                            'log_name_change.sequence_no', 'log_name_change.old_val', 'log_name_change.updated_val', 'log_name_change.order_no', 'log_name_change.reason', 'log_name_change.approved_by', 'log_name_change.document', 'log_name_change.operation', 'log_name_change.date', DB::raw('"" as meter_change_date')])
                        ->where('log_name_change.sequence_no', $sequenceFrommeterno->sequence_number);
                //  $result = $meterLog->union($nameLog)->get();
            } else {
                $sequenceFrommeterno = LogMeterChange::where('old_val', $meter_no)
                        ->orWhere('updated_val', $meter_no)
                        ->first();
                if ($sequenceFrommeterno) {
                    $meterLog = LogMeterChange::
                            select([
                                'log_meter_change.sequence_no', 'log_meter_change.old_val', 'log_meter_change.updated_val', 'log_meter_change.order_no', 'log_meter_change.reason', 'log_meter_change.approved_by', 'log_meter_change.document', 'log_meter_change.operation', DB::raw('log_meter_change.order_date as date'), 'log_meter_change.meter_change_date'])
                            ->where('log_meter_change.sequence_no', $sequenceFrommeterno->sequence_no);

                    $nameLog = LogNameChange::
                            select([
                                'log_name_change.sequence_no', 'log_name_change.old_val', 'log_name_change.updated_val', 'log_name_change.order_no', 'log_name_change.reason', 'log_name_change.approved_by', 'log_name_change.document', 'log_name_change.operation', 'log_name_change.date', DB::raw('"" as meter_change_date')])
                            ->where('log_name_change.sequence_no', $sequenceFrommeterno->sequence_no);
                    //   $result = $meterLog->union($nameLog)->get();   
                } else {
                    $meterLog = LogMeterChange::
                            select([
                                'log_meter_change.sequence_no', 'log_meter_change.old_val', 'log_meter_change.updated_val', 'log_meter_change.order_no', 'log_meter_change.reason', 'log_meter_change.approved_by', 'log_meter_change.document', 'log_meter_change.operation', DB::raw('log_meter_change.order_date as date'), 'log_meter_change.meter_change_date'])
                            ->where('log_meter_change.sequence_no', $seq_no);

                    $nameLog = LogNameChange::
                            select([
                                'log_name_change.sequence_no', 'log_name_change.old_val', 'log_name_change.updated_val', 'log_name_change.order_no', 'log_name_change.reason', 'log_name_change.approved_by', 'log_name_change.document', 'log_name_change.operation', 'log_name_change.date', DB::raw('"" as meter_change_date')])
                            ->where('log_name_change.sequence_no', $seq_no);
                    //$result = $meterLog->union($nameLog)->get();
                }
            }

            if ($seq_no != '') {
                $meterLog1 = LogMeterChange::
                        select([
                            'log_meter_change.sequence_no', 'log_meter_change.old_val', 'log_meter_change.updated_val', 'log_meter_change.order_no', 'log_meter_change.reason', 'log_meter_change.approved_by', 'log_meter_change.document', 'log_meter_change.operation', DB::raw('log_meter_change.order_date as date'), 'log_meter_change.meter_change_date'])
                        ->where('log_meter_change.sequence_no', $seq_no);

                $nameLog1 = LogNameChange::
                        select([
                            'log_name_change.sequence_no', 'log_name_change.old_val', 'log_name_change.updated_val', 'log_name_change.order_no', 'log_name_change.reason', 'log_name_change.approved_by', 'log_name_change.document', 'log_name_change.operation', 'log_name_change.date', DB::raw('"" as meter_change_date')])
                        ->where('log_name_change.sequence_no', $seq_no);
                $result = $meterLog->union($nameLog)->union($meterLog1)->union($nameLog1)->get();
            } else {
                $result = $meterLog->union($nameLog)->get();
            }
        }
        /* $query =    LogMeterChange::
          select([
          'log_meter_name_change.sequence_no','log_meter_name_change.old_val','log_meter_name_change.updated_val','log_meter_name_change.order_no','log_meter_name_change.reason','log_meter_name_change.approved_by','log_meter_name_change.document','log_meter_name_change.operation']);
          if($seq_no != ''){
          $query->where('log_meter_name_change.sequence_no', $seq_no);
          }
          if($meter_no != ''){
          $sequenceFrommeterno = ConsumerConnection::where('meter_no',$meter_no)->first();
          if($sequenceFrommeterno){
          $query->where('log_meter_name_change.sequence_no', $sequenceFrommeterno->sequence_number);
          }else{
          $sequenceFrommeterno = LogMeterNameChange::where('meter_no',$meter_no)->first();
          }
          // print_r($sequenceFrommeterno); exit;
          $query->where('log_meter_name_change.sequence_no', $seq_no);
          } */
        return $result;
    }

}
