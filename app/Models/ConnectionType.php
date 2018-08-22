<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class ConnectionType  extends Model
{
 
     protected $fillable = [
        'connection_name','connection_code'
    ];
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'master_connections_type';

    /**
     * Overriding defualt priamry key
     *
     * @var string
     */
    protected $primaryKey = 'id';     
  
    
    public static function tariffchangeLogSearch($seq_no){
        $query =    DB::table('log_connection_change')
                    ->join('master_connections_type as old_connection_type','log_connection_change.old_connection_type_id','=','old_connection_type.id')
                ->join('master_connections_type as new_connection_type','log_connection_change.new_connection_type_id','=','new_connection_type.id')
                ->leftjoin('users','log_connection_change.updated_by','=','users.id')
                ->select([
                            'log_connection_change.sequence_no','log_connection_change.deposit_amount','log_connection_change.no_of_flats','log_connection_change.old_connection_type_id','log_connection_change.new_connection_type_id','log_connection_change.document','log_connection_change.order_no','log_connection_change.current_rate','log_connection_change.revised_rate','log_connection_change.total_consumption',DB::raw("DATE_FORMAT(log_connection_change.required_from_date, '%d/%m/%Y') as required_from_date"),'log_connection_change.reason','log_connection_change.approved_by',DB::raw('old_connection_type.connection_name as old_connection_type'),DB::raw('new_connection_type.connection_name as new_connection_type'),'users.name'])
        ->where('log_connection_change.sequence_no', $seq_no)
        ->orderBy('log_connection_change.id');
        return $query->get();
    }

}
