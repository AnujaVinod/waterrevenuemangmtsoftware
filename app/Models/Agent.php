<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Models\InspectorWard;

class Agent extends Model
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'agent_user_id', 'agent_name', 'agent_code', 'inspector_id','corpward_id'
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'agents';

    /**
     * Overriding defualt priamry key
     *
     * @var string
     */
    protected $primaryKey = 'id';   
    
    public static function retrieveAgents($categoryId){
        $data = [];
        $data = User::
                join('agents', 'users.id', '=', 'agents.agent_user_id')
                ->join('master_corp', 'agents.corpward_id', '=', 'master_corp.id')
                ->join('inspector', 'agents.inspector_id', '=', 'inspector.id')
                ->where('users.cat_id', $categoryId)
                    ->orderBy('users.id', 'ASC')
                ->get([                    
                    'users.id', 'users.name','users.status',
                    'agents.agent_code', 
                    'master_corp.corp_name',
                    'inspector.inspector_name'
                ]);
        return $data;        
    }
    
    public static function getInspectorWard($wardId)
    {
        
        $data = InspectorWard::
                join('inspector','inspector_ward.inspector_id', '=', 'inspector.id')
                ->join('users', 'users.id', '=', 'inspector.inspector_user_id')
                ->where('inspector_ward.ward_id', $wardId)
                ->where('users.status',1)
                ->get([
                    'inspector.id','inspector.inspector_name'
                ]);
        return $data;
    }
  
}
