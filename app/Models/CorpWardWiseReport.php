<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ConnectionType;
use App\Models\MeterReading;
use Carbon\Carbon;
use DB;

class CorpWardWiseReport extends Model {

    public static function getCorpWardSearchResult($range, $corp_ward_id,$sequenceNumber) {

        $corp_ward_wise_data = MeterReading::select('meter_reading.sequence_number', 'meter_reading.consumer_name', 'meter_reading.door_no', 'meter_reading.meter_no', DB::raw('SUM(meter_reading.total_amount) as total_amount'), 'consumer_address.premises_address', 'consumer_connection.mobile_no', 'master_connections_type.connection_name', 'consumer_connection.no_of_flats', 'master_corp.corp_name', 'master_connection_status.status')
                ->leftjoin('consumer_connection', 'consumer_connection.sequence_number', '=', 'meter_reading.sequence_number')
                ->leftjoin('consumer_address', 'consumer_address.sequence_number', '=', 'consumer_connection.sequence_number')
                ->leftjoin('master_connections_type', 'master_connections_type.id', '=', 'consumer_connection.connection_type_id')
                ->leftjoin('master_connection_status', 'master_connection_status.id', '=', 'consumer_connection.connection_status_id')
                ->leftjoin('master_corp', 'master_corp.id', '=', 'consumer_connection.corp_ward_id')
                //->where('consumer_connection.ward_id', '=',$ward_id)
                //->where('consumer_connection.corp_ward_id', '=', $corp_ward_id)
                ->where('meter_reading.payment_status', '=', 0)
                ->where('meter_reading.active_record', '=', 1)
                ->groupBy('meter_reading.sequence_number');
        if($corp_ward_id !="") {
            $corp_ward_wise_data->where('consumer_connection.corp_ward_id', '=', $corp_ward_id);
        }
        if ($range == 1) {
            $corp_ward_wise_data->whereBetween("total_amount",[0,50000]);
        } elseif ($range == 2) {
            $corp_ward_wise_data->whereBetween("total_amount",[50000,100000]);
        } else if ($range == 3) {
            $corp_ward_wise_data->where("total_amount",">",100000);
        }
        
        if($sequenceNumber !="") {
            $corp_ward_wise_data->where('consumer_connection.sequence_number', '=', $sequenceNumber);
        }
        
        return $corp_ward_wise_data;
    }

}
