<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class ConnectionStatus  extends Model
{
 
     protected $fillable = [
        'status'
    ];
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'master_connection_status';

    /**
     * Overriding defualt priamry key
     *
     * @var string
     */
    protected $primaryKey = 'id';     
  

    
    public static function getUserDesignation(){
        $data   = User::
                 leftjoin('admin_details', 'users.id', '=', 'admin_details.user_id')              
                ->leftjoin('master_designation','master_designation.id','=','admin_details.designation_id')
              //  ->groupBy('consumer_connection.sequence_number') 
               // ->where('users.cat_id',1)
               // ->orWhere('users.cat_id',6)
                ->Where(function($query){     
                    $query->where('users.cat_id',7);
                   // $query->where('users.cat_id',1);
                   // $query->orWhere('users.cat_id',6);
                })
                ->where('admin_details.sub_category_id', '=', 3)
                ->get([
                    'users.name','users.id','master_designation.designation'                   
                ]);
               
        return $data;       
    }
}
