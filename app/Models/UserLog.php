<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class UserLog extends Model {

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_log';

    /**
     * Overriding defualt priamry key
     *
     * @var string
     */
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id', 'ipaddress', 'sequence_no', 'category', 'action', 'created_at' . 'updated_at'
    ];

    public static function getCustomeLogReport($from_date = NULL,$to_date = NULL) {
        $sql = UserLog::select('users.name', 'user_log.ipaddress', 'user_log.sequence_no', 'user_log.category', 'user_log.action', 'user_log.created_at')
                ->leftJoin('users', 'users.id', '=', 'user_log.user_id');
        if ($from_date != NULL && $to_date != NULL) {   
            $from_date = $from_date." 00:00:00";
            $to_date = $to_date." 23:59:59";            
            $sql->whereBetween('user_log.created_at', [$from_date,$to_date]);            
        }else if($from_date != NULL){
            $sql->where('user_log.created_at','like',$from_date."%");
        }
        $sql->orderBy('user_log.created_at','DESC');
        return $sql;
    }

}
