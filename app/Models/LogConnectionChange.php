<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;
use DB;
use App\Models\ConsumerConnection;
use App\Models\LogNameChange;

class LogConnectionChange extends Model
{
 
    protected $fillable = [
        'sequence_no','deposit_amount','no_of_flats','old_connection_type_id','new_connection_type_id','document','order_no','current_rate','revised_rate','total_consumption','arrear_amt','excess_amt','required_from_date','reason','approved_by','updated_by'
    ];
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'log_connection_change';

    /**
     * Overriding defualt priamry key
     *
     * @var string
     */
    protected $primaryKey = 'id';   
    
    
    public static function getConnectionChangeDate($seq_no)
    {
        $data = LogConnectionChange:: 
                select('log_connection_change.required_from_date')
                ->where('log_connection_change.sequence_no',$seq_no)                
                ->orderBy('log_connection_change.created_at','DESC') 
                ->first();
        return $data;
    }
 
}
