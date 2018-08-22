<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ConnectionType;
use App\Models\ConsumerConnection;

use Carbon\Carbon;
use DB;

class ConnectionWiseReport  extends Model
{
 
    public static function getConnectionSearchResult($ward_id,$from_date,$to_date)
    {
 
         $connection_wise_data= ConsumerConnection::select(                   
                'master_connections_type.connection_name',DB::raw('COUNT(CASE WHEN consumer_connection.connection_status_id = 2 THEN 1 END) AS live'),
                 DB::raw('COUNT(CASE WHEN consumer_connection.connection_status_id = 1 THEN 1 END) AS disconnected'),DB::raw('COUNT(consumer_connection.connection_status_id ) AS total'))
               
                 ->leftjoin('master_connections_type' ,'consumer_connection.connection_type_id', '=','master_connections_type.id')
                 ->where(DB::raw('date(consumer_connection.connection_date)'), '>=',$from_date)
                 ->where(DB::raw('date(consumer_connection.connection_date)'), '<=',$to_date)
                 ->orwhere('consumer_connection.ward_id', '=',$ward_id)
                 ->groupBy('consumer_connection.connection_type_id')->get();
    
          

           return $connection_wise_data; 
      }

 
}
