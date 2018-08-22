<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use App\Models\CorpwardWard;
class CorpWard extends Model
{

    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'corp_name', 'ward_id'
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'master_corp';

    /**
     * Overriding defualt priamry key
     *
     * @var string
     */
    protected $primaryKey = 'id';

    public static function retriveCorpWardInfo() {
        //$data = [];
        $data   = CorpWard::                
               leftjoin('corpward_ward','master_corp.id','=','corpward_ward.corp_id')
               ->leftjoin('master_ward', 'corpward_ward.ward_id', '=', 'master_ward.id') 
               ->orderBy('master_corp.corp_name', 'ASC')
                ->get([                    
                    'master_ward.ward_name',
                    'master_corp.corp_name'
                ]);
        return $data;
    }
    
    
    public static function getCorpWardForWard($wardId) {
        //$data = [];
        $data   = CorpWard::
                join('corpward_ward', 'master_corp.id', '=', 'corpward_ward.corp_id')
               ->where('corpward_ward.ward_id',$wardId)
                ->orderBy('master_corp.corp_name', 'ASC')
                ->get([
                    'master_corp.corp_name','master_corp.id'
                ]);
        return $data;
    }
}
