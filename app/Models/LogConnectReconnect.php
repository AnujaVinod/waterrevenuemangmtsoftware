<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;
use DB;
class LogConnectReconnect  extends Model
{
 
     protected $fillable = [
        'sequence_no','order_no','meter_no','date','reason','document','approved_by','operation','updated_by'
    ];
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'log_connect_reconnect';

    /**
     * Overriding defualt priamry key
     *
     * @var string
     */
    protected $primaryKey = 'id'; 
  
    public static function disconnectReconnectLogSearch($seq_no, $meter_no){
        if($meter_no != ''){
            $seq_numb_from_meterno = LogMeterChange::select('sequence_no')
                    ->where('old_val',$meter_no)->orWhere('updated_val',$meter_no)->first();
                    
        }
        $query =    LogConnectReconnect::              
                    select([
                            'log_connect_reconnect.sequence_no','log_connect_reconnect.meter_no','log_connect_reconnect.order_no','log_connect_reconnect.date','log_connect_reconnect.reason','log_connect_reconnect.document','log_connect_reconnect.approved_by','log_connect_reconnect.operation']);
        if($seq_no != '' && $meter_no == ''){           
            $query->where('log_connect_reconnect.sequence_no', $seq_no);           
        }
        if($seq_no != '' && $meter_no != ''){           
            $query->where('log_connect_reconnect.sequence_no', $seq_no);
            $query->orWhere('log_connect_reconnect.meter_no', $meter_no);
        }
        if($seq_no == '' && $meter_no != ''){
                    $query->where('log_connect_reconnect.meter_no', $meter_no);
                   if($seq_numb_from_meterno){                  
                       $query->orWhere('log_connect_reconnect.sequence_no', $seq_numb_from_meterno->sequence_no);
                   }
        }
        $query->orderBy('log_connect_reconnect.id');
                return $query->get();
    }
    
    
    public static function getConnectionChangeLog($seq_no){
        $query =    LogConnectReconnect::              
                    select([
                            'log_connect_reconnect.sequence_no','log_connect_reconnect.date']);            
                    $query->where('log_connect_reconnect.sequence_no', $seq_no);
               
                    $query->orderBy('log_connect_reconnect.date','desc');
                return $query->first();
    }
}
