<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;
use DB;
class LogNameChange extends Model
{
 
    protected $fillable = [
        'sequence_no','old_val','updated_val','order_no','reason','approved_by','document','operation','date','updated_by'
    ];
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'log_name_change';

    /**
     * Overriding defualt priamry key
     *
     * @var string
     */
    protected $primaryKey = 'id'; 
  
    public static function disconnectReconnectLogSearch($seq_no, $meter_no){
        $query =    LogConnectReconnect::              
                    select([
                            'log_connect_reconnect.sequence_no','log_connect_reconnect.meter_no','log_connect_reconnect.order_no','log_connect_reconnect.date','log_connect_reconnect.reason','log_connect_reconnect.document','log_connect_reconnect.approved_by','log_connect_reconnect.operation']);
        if($seq_no != ''){           
        $query->where('log_connect_reconnect.sequence_no', $seq_no);
        }
        if($meter_no != ''){
                   $query->Where('log_connect_reconnect.meter_no', $meter_no);
        }
                return $query->get();
    }
}
