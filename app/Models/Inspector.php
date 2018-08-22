<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;
use DB;
class Inspector  extends Model
{
 
     protected $fillable = [
        'inspector_name','inspector_code','inspector_user_id'
    ];
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'inspector';

    /**
     * Overriding defualt priamry key
     *
     * @var string
     */
    protected $primaryKey = 'id';     
  

    
    public static function retrieveInspector($categoryId){
        $data = [];
        $data = User::
                leftjoin('inspector', 'users.id', '=', 'inspector.inspector_user_id')
                ->join('inspector_ward', 'inspector.id', '=', 'inspector_ward.inspector_id')
                ->join('master_ward', 'inspector_ward.ward_id', '=', 'master_ward.id')
                ->where('users.cat_id', $categoryId)
                    ->orderBy('users.id', 'ASC')
                    ->groupBy('users.id')                
                ->get([                    
                    'users.id', 'users.name','users.status',
                    'inspector.inspector_name', 'inspector.inspector_code',                    
                    DB::raw('group_concat(master_ward.ward_name) as ward')
                ]);
        return $data;        
    }
}
