<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\MeterReading;
use DB;

class ConnectionRate  extends Model
{
 
     protected $fillable = [
        'connection_type_id', 'from_unit', 'to_unit', 'price'
    ];
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'master_connection_rates';

    /**
     * Overriding defualt priamry key
     *
     * @var string
     */
    protected $primaryKey = 'id';     
  
    
    public static function retrieveConnectionRates(){
        $data = [];
        $data = ConnectionRate::
                join('master_connections_type', 'master_connection_rates.connection_type_id', '=', 'master_connections_type.id')
                ->orderBy('master_connection_rates.id', 'ASC')
                ->get([                    
                    'master_connection_rates.id', 'master_connection_rates.from_unit','master_connection_rates.to_unit','master_connection_rates.price',
                    'master_connections_type.connection_name'
                ]);
        return $data;        
    }
    
    
    public static function getConnectionRateInfo($connId) {
        $data   = ConnectionRate::
                 join('master_connections_type', 'master_connection_rates.connection_type_id', '=', 'master_connections_type.id')
                ->where('master_connection_rates.id', $connId) 
                ->first();
                //->value([                    
                //    'users.id', 'users.name','users.status','users.email',
                 //   'master_users_sub_category.sub_category_name',
                //    'admin_details.contact_no'
                //]);
        return $data;
    }
    
    public static function retrieveConnPrice($connType) {
        $data = ConnectionRate::where('connection_type_id',$connType)
                ->get();
        return $data;
    }
    
        
    
    public static function getRate($conn_type)
    {
        $data = ConnectionRate:: 
                select('master_connection_rates.from_unit', 'master_connection_rates.to_unit', 'master_connection_rates.price')
                ->where('master_connection_rates.connection_type_id',$conn_type)                
                ->get();
        return $data;
    }
    
    
    public static function getRateForMax($conn_type)
    {
        $data = ConnectionRate:: 
                select('master_connection_rates.price')
                ->where('master_connection_rates.connection_type_id',$conn_type)
                ->where('master_connection_rates.to_unit',-1)                
                ->first();
        return $data;
    }
    
    public static function getLastBillIssedate($seq_no)
    {
        $data = MeterReading:: 
                select('meter_reading.date_of_reading')
                ->where('meter_reading.sequence_number',$seq_no)   
                ->orderBy('meter_reading.date_of_reading','DESC') 
                ->first();
        return $data;
    }
    
    
    public static function getWaterCharge($seq_no,$date)
    {
        $query = MeterReading:: 
                select(DB::raw('SUM(water_charge) AS total_water_charge'))
                ->where('meter_reading.sequence_number',$seq_no);
                if(empty($date)){
                  $query->where('meter_reading.date_of_reading','>',"$date"); 
                }
               $data = $query->groupBy('meter_reading.sequence_number') 
                ->first();
        return $data;
    }
}
