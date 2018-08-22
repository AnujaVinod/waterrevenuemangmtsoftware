<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use DB;
use App\Models\MeterReading;
use App\Models\Agent;

class InspectorReport  extends Model
{
    
    public static function retriveAgentReport($inspector_id,$from_date,$to_date)
    {       
         
          $data = [];          
          
          $query = MeterReading::select(DB::raw("count('*') as no_of_bill"),'master_ward.ward_name','users.name')
                ->leftjoin('agents', 'meter_reading.agent_id', '=', 'agents.agent_user_id')  
                ->leftjoin('users','agents.agent_user_id','=','users.id')
                ->leftjoin('master_ward','meter_reading.ward_id','=','master_ward.id')
                ->leftjoin('inspector','agents.inspector_id','=','inspector.id');
                $query->where('inspector.inspector_user_id','=',$inspector_id);
                $query->whereRaw("DATE(meter_reading.date_of_reading) >= ?", [$from_date]);
                $query->whereRaw("DATE(meter_reading.date_of_reading) <= ?", [$to_date]);
                $query->groupBy('meter_reading.agent_id');                  
                $data['report_details'] = $query->get();                
            
         return $data;
         
     }
}
