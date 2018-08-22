<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\Authenticable;
use Illuminate\Auth\Authenticable as AuthenticableTrait;

class ConsumerConnection extends Authenticatable
{

    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'application_id', 'sequence_number', 'connection_type_id', 'connection_date', 'name', 'mobile_no', 'door_no', 'khata_no', 'no_of_flats', 'ward_id', 'corp_ward_id', 'connection_status_id', 'meter_no', 'meter_status_id', 'meter_sanctioned_date','deposit_amount','revised_deposit_amount','inserted_by', 'updated_by'
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'consumer_connection';

    /**
     * Overriding defualt priamry key
     *
     * @var string
     */
    protected $primaryKey = 'id';

    public static function retrieveConnectionDetails($start, $limit, $order, $dir, $search)
    {
        $query = ConsumerConnection::
                join('master_connections_type', 'consumer_connection.connection_type_id', '=', 'master_connections_type.id')
                ->join('master_connection_status', 'consumer_connection.connection_status_id', '=', 'master_connection_status.id')
                ->join('master_meter_status', 'consumer_connection.meter_status_id', '=', 'master_meter_status.id')
                // ->groupBy('consumer_connection.sequence_number') 
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir);
        if (!empty($search)) {
            $query->where('consumer_connection.name', 'LIKE', "%{$search}%")
                    ->orWhere('consumer_connection.mobile_no', 'LIKE', "%{$search}%")
                    ->orWhere('consumer_connection.sequence_number', 'LIKE', "%{$search}%")
                    ->orWhere('consumer_connection.meter_no', 'LIKE', "%{$search}%")
                    ->orWhere('master_connections_type.connection_name', 'LIKE', "%{$search}%")
                    ->orWhere('master_connection_status.status', 'LIKE', "%{$search}%");
        }

        $data = $query->get([
            'consumer_connection.name', 'consumer_connection.mobile_no', 'consumer_connection.sequence_number', 'consumer_connection.meter_no', 'consumer_connection.id',
            'master_connections_type.connection_name',
            'master_connection_status.status',
            'master_meter_status.meter_status'
        ]);

        return $data;
    }

    public static function retrieveConnectionDetailsCount($search)
    {
        $query = ConsumerConnection::
                join('master_connections_type', 'consumer_connection.connection_type_id', '=', 'master_connections_type.id')
                ->join('master_connection_status', 'consumer_connection.connection_status_id', '=', 'master_connection_status.id')
                ->where('consumer_connection.name', 'LIKE', "%{$search}%")
                ->orWhere('consumer_connection.mobile_no', 'LIKE', "%{$search}%")
                ->orWhere('consumer_connection.sequence_number', 'LIKE', "%{$search}%")
                ->orWhere('consumer_connection.meter_no', 'LIKE', "%{$search}%")
                ->orWhere('master_connections_type.connection_name', 'LIKE', "%{$search}%")
                ->orWhere('master_connection_status.status', 'LIKE', "%{$search}%");

        $data = $query->count();

        return $data;
    }

    public static function getConnectionInfo($connId)
    {
        $data = ConsumerConnection::
                join('master_connections_type', 'consumer_connection.connection_type_id', '=', 'master_connections_type.id')
                ->leftjoin('master_connection_status', 'consumer_connection.connection_status_id', '=', 'master_connection_status.id')
                ->leftjoin('master_meter_status', 'consumer_connection.meter_status_id', '=', 'master_meter_status.id')
                ->leftjoin('master_ward', 'consumer_connection.ward_id', '=', 'master_ward.id')
                ->leftjoin('master_corp', 'consumer_connection.corp_ward_id', '=', 'master_corp.id')
                ->leftjoin('consumer_address', 'consumer_connection.sequence_number', '=', 'consumer_address.sequence_number')
                ->where('consumer_connection.id', $connId)
                ->groupBy('consumer_connection.sequence_number')
                ->get([
            'consumer_connection.id', 'consumer_connection.name', 'consumer_connection.mobile_no', 'consumer_connection.sequence_number', 'consumer_connection.meter_no', 'consumer_connection.id', DB::raw("DATE_FORMAT(consumer_connection.connection_date, '%m/%d/%Y') as connection_date"), 'consumer_connection.door_no', 'consumer_connection.khata_no',
            DB::raw("DATE_FORMAT(consumer_connection.meter_sanctioned_date, '%m/%d/%Y') as meter_sanctioned_date"), 'consumer_connection.door_no', 'consumer_connection.no_of_flats',
            'master_connections_type.connection_name',
            'master_connection_status.status', DB::raw('master_connection_status.id as con_status_id'),
            'master_meter_status.meter_status', DB::raw('master_meter_status.id as meter_status_id'),
            'master_ward.ward_name', DB::raw('master_ward.id as ward_id'),
            'master_corp.corp_name', DB::raw('master_corp.id as corp_id'),
            'consumer_address.premises_owner_name', 'consumer_address.premises_address', 'consumer_address.premises_street', 'consumer_address.premises_city', 'consumer_address.premises_state', 'consumer_address.premises_zip'
        ]);
        return $data;
    }

    public static function searchConnections($seq_no, $conn_type, $ward, $corp_ward, $meter_no)
    {
        $query = ConsumerConnection::
                join('master_connections_type', 'consumer_connection.connection_type_id', '=', 'master_connections_type.id')
                ->join('master_connection_status', 'consumer_connection.connection_status_id', '=', 'master_connection_status.id')
                ->join('master_meter_status', 'consumer_connection.meter_status_id', '=', 'master_meter_status.id');
        if ($seq_no != '') {
            $query->where('consumer_connection.sequence_number', $seq_no);
        }
        if ($conn_type != '') {
            $query->where('consumer_connection.connection_type_id', $conn_type);
        }
        if ($ward != '') {
            $query->where('consumer_connection.ward_id', $ward);
        }
        if ($corp_ward != '') {
            $query->where('consumer_connection.corp_ward_id', $corp_ward);
        }
        if ($meter_no != '') {
            $query->where('consumer_connection.meter_no', $meter_no);
        }
        $query->groupBy('consumer_connection.sequence_number');

        $data = $query->get([
            'consumer_connection.name', 'consumer_connection.mobile_no', 'consumer_connection.sequence_number', 'consumer_connection.meter_no', 'consumer_connection.id',
            'master_connections_type.connection_name',
            'master_connection_status.status',
            'master_meter_status.meter_status'
        ]);
        return $data;
    }

    public static function disconnectReconnectSearchAll($start, $limit, $order, $dir, $search)
    {

        $query = ConsumerConnection::
                join('master_connections_type', 'consumer_connection.connection_type_id', '=', 'master_connections_type.id')
                ->join('master_connection_status', 'consumer_connection.connection_status_id', '=', 'master_connection_status.id')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir);
        if (!empty($search)) {
            $query->where('consumer_connection.name', 'LIKE', "%{$search}%")
                    ->orWhere('consumer_connection.mobile_no', 'LIKE', "%{$search}%")
                    ->orWhere('consumer_connection.sequence_number', 'LIKE', "%{$search}%")
                    ->orWhere('consumer_connection.meter_no', 'LIKE', "%{$search}%")
                    ->orWhere('master_connections_type.connection_name', 'LIKE', "%{$search}%")
                    ->orWhere('master_connection_status.status', 'LIKE', "%{$search}%");
        }
        $data = $query->get([
            'consumer_connection.name', 'consumer_connection.mobile_no', 'consumer_connection.sequence_number', 'consumer_connection.meter_no', 'consumer_connection.id', 'consumer_connection.connection_status_id',
            'master_connections_type.connection_name',
            'master_connection_status.status'
        ]);
        return $data;
    }

    public static function disconnectReconnectSearchAllCount($search)
    {

        $query = ConsumerConnection::
                join('master_connections_type', 'consumer_connection.connection_type_id', '=', 'master_connections_type.id')
                ->join('master_connection_status', 'consumer_connection.connection_status_id', '=', 'master_connection_status.id')
                ->where('consumer_connection.name', 'LIKE', "%{$search}%")
                ->orWhere('consumer_connection.mobile_no', 'LIKE', "%{$search}%")
                ->orWhere('consumer_connection.sequence_number', 'LIKE', "%{$search}%")
                ->orWhere('consumer_connection.meter_no', 'LIKE', "%{$search}%")
                ->orWhere('master_connections_type.connection_name', 'LIKE', "%{$search}%")
                ->orWhere('master_connection_status.status', 'LIKE', "%{$search}%");

        $data = $query->count();
        return $data;
    }

    public static function disconnectReconnectSearch($seq_no, $meter_no)
    {
        $query = ConsumerConnection::
                join('master_connections_type', 'consumer_connection.connection_type_id', '=', 'master_connections_type.id')
                ->join('master_connection_status', 'consumer_connection.connection_status_id', '=', 'master_connection_status.id');
        if ($seq_no != '') {
            $query->where('consumer_connection.sequence_number', $seq_no);
        }
        if ($meter_no != '') {
            $query->where('consumer_connection.meter_no', $meter_no);
        }
        $data = $query->get([
            'consumer_connection.name', 'consumer_connection.mobile_no', 'consumer_connection.sequence_number', 'consumer_connection.meter_no', 'consumer_connection.id', 'consumer_connection.connection_status_id',
            'master_connections_type.connection_name',
            'master_connection_status.status'
        ]);
        return $data;
    }

    public static function meterNameChangeSearch($seq_no, $meter_no)
    {
        $query = ConsumerConnection::
                leftjoin('master_connections_type', 'consumer_connection.connection_type_id', '=', 'master_connections_type.id')
                ->leftjoin('master_connection_status', 'consumer_connection.connection_status_id', '=', 'master_connection_status.id')
                ->leftjoin('master_corp', 'master_corp.id', '=', 'consumer_connection.corp_ward_id')
                ->leftjoin('master_ward', 'master_ward.id', '=', 'consumer_connection.ward_id');
        if ($seq_no != '') {
            $query->where('consumer_connection.sequence_number', $seq_no);
        }
        if ($meter_no != '') {
            $query->where('consumer_connection.meter_no', $meter_no);
        }
        $query->orderBy('consumer_connection.id');
        $data = $query->get([
            'consumer_connection.name', 'consumer_connection.mobile_no', 'consumer_connection.sequence_number', 'consumer_connection.meter_no', 'consumer_connection.id', 'consumer_connection.connection_status_id', 'consumer_connection.door_no',
            'master_connections_type.connection_name',
            'master_connection_status.status',
            'master_corp.corp_name',
            'master_ward.ward_name'
        ]);
        return $data;
    }

    public static function meterNameChangeSearchAll($start, $limit, $order, $dir, $search)
    {
        $query = ConsumerConnection::
                join('master_connections_type', 'consumer_connection.connection_type_id', '=', 'master_connections_type.id')
                ->join('master_connection_status', 'consumer_connection.connection_status_id', '=', 'master_connection_status.id')
                ->join('master_corp', 'master_corp.id', '=', 'consumer_connection.corp_ward_id')
                ->join('master_ward', 'master_ward.id', '=', 'consumer_connection.ward_id')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir);
        //$query->orderBy('consumer_connection.id');
        $data = $query->get([
            'consumer_connection.name', 'consumer_connection.mobile_no', 'consumer_connection.sequence_number', 'consumer_connection.meter_no', 'consumer_connection.id', 'consumer_connection.connection_status_id', 'consumer_connection.door_no',
            'master_connections_type.connection_name',
            'master_connection_status.status',
            'master_corp.corp_name',
            'master_ward.ward_name'
        ]);
        return $data;
    }

    public static function meterNameChangeSearchAllCount($search)
    {
        $query = ConsumerConnection::
                join('master_connections_type', 'consumer_connection.connection_type_id', '=', 'master_connections_type.id')
                ->join('master_connection_status', 'consumer_connection.connection_status_id', '=', 'master_connection_status.id')
                ->join('master_corp', 'master_corp.id', '=', 'consumer_connection.corp_ward_id')
                ->join('master_ward', 'master_ward.id', '=', 'consumer_connection.ward_id')
                //$query->orderBy('consumer_connection.id');
                ->where('consumer_connection.name', 'LIKE', "%{$search}%")
                ->orWhere('consumer_connection.mobile_no', 'LIKE', "%{$search}%")
                ->orWhere('consumer_connection.sequence_number', 'LIKE', "%{$search}%")
                ->orWhere('consumer_connection.meter_no', 'LIKE', "%{$search}%")
                ->orWhere('consumer_connection.door_no', 'LIKE', "%{$search}%")
                ->orWhere('master_corp.corp_name', 'LIKE', "%{$search}%")
                ->orWhere('master_ward.ward_name', 'LIKE', "%{$search}%")
                ->orWhere('master_connections_type.connection_name', 'LIKE', "%{$search}%")
                ->orWhere('master_connection_status.status', 'LIKE', "%{$search}%");

        $data = $query->count();
        return $data;
    }

    public static function getConnectionInfoFromSeq($seq_no, $date)
    {
        $query = ConsumerConnection::
                join('master_connections_type', 'consumer_connection.connection_type_id', '=', 'master_connections_type.id')
                ->leftjoin('master_connection_status', 'consumer_connection.connection_status_id', '=', 'master_connection_status.id')
                ->leftjoin('master_connection_minrate', 'consumer_connection.connection_type_id', '=', 'master_connection_minrate.connection_type_id')
                ->leftjoin('meter_reading', 'meter_reading.sequence_number', '=', 'consumer_connection.sequence_number')
                ->leftjoin('consumer_ledger', 'consumer_connection.application_id', '=', 'consumer_ledger.application_id')
                ->where('consumer_connection.sequence_number', $seq_no);
        if ($date != 0) {
            $query->where('meter_reading.date_of_reading', '>', "$date");
        }
        $query->groupBy('consumer_connection.sequence_number');
        $data = $query->get([
            'consumer_connection.name', 'consumer_connection.sequence_number','consumer_connection.no_of_flats', 'consumer_connection.connection_type_id', DB::raw("DATE_FORMAT(consumer_connection.connection_date, '%d/%m/%Y') as connection_date"),
            'master_connections_type.connection_name',
            'master_connection_status.status',
            'master_connection_minrate.min_price',
            DB::raw('SUM(meter_reading.total_unit_used) as total_unit_used'),
            'consumer_connection.deposit_amount', 'consumer_ledger.is_considered_dep_amount','consumer_connection.revised_deposit_amount'
        ]);
        return $data;
    }

    public static function getIndustialBillInfo($seq_no, $meter_no)
    {
        $query = ConsumerConnection::
                join('master_connections_type', 'consumer_connection.connection_type_id', '=', 'master_connections_type.id')
                ->leftjoin('master_connection_status', 'consumer_connection.connection_status_id', '=', 'master_connection_status.id')
                ->leftjoin('master_connection_minrate', 'consumer_connection.connection_type_id', '=', 'master_connection_minrate.connection_type_id')
                ->leftjoin('meter_reading', 'meter_reading.sequence_number', '=', 'consumer_connection.sequence_number')
                ->leftjoin('consumer_ledger', 'consumer_connection.application_id', '=', 'consumer_ledger.application_id')
                ->where('consumer_connection.sequence_number', $seq_no);
        if ($date != 0) {
            $query->where('meter_reading.date_of_reading', '>', "$date");
        }
        $query->groupBy('consumer_connection.sequence_number');
        $data = $query->get([
            'consumer_connection.name', 'consumer_connection.sequence_number', 'consumer_connection.connection_type_id', DB::raw("DATE_FORMAT(consumer_connection.connection_date, '%d/%m/%Y') as connection_date"),
            'master_connections_type.connection_name',
            'master_connection_status.status',
            'master_connection_minrate.min_price',
            DB::raw('SUM(meter_reading.total_unit_used) as total_unit_used'),
            'consumer_ledger.deposit_amount', 'consumer_ledger.is_considered_dep_amount'
        ]);
        return $data;
    }
    
    public static function getUserDetailBySequenceNumber($data) {
        $sql = ConsumerConnection::select('consumer_connection.id')
                ->where('consumer_connection.sequence_number', $data['sequence_number']);


        return $sql;
    }

    public static function getConnectionDetail($connection_id) {
        //DB::enableQueryLog();
        $sql = ConsumerConnection::select('consumer_connection.name as consumer_name','meter_reading.sequence_number','master_connections_type.connection_name','master_connection_status.status',
                'meter_reading.door_no','meter_reading.bill_no','meter_reading.meter_no','meter_reading.payment_due_date','meter_reading.date_of_reading','meter_reading.previous_billing_date','meter_reading.arrears',
                'meter_reading.round_off','meter_reading.extra_amount','meter_reading.fixed_charge','meter_reading.penalty','meter_reading.cess','meter_reading.ugd_cess',
                'meter_reading.total_unit_used','meter_reading.no_of_days_used','meter_reading.previous_reading','meter_reading.current_reading','meter_reading.total_amount','meter_reading.active_record','meter_reading.payment_status')
                ->leftJoin('meter_reading', 'consumer_connection.sequence_number', '=', 'meter_reading.sequence_number')
                ->leftJoin('master_connections_type', 'consumer_connection.connection_type_id', '=', 'master_connections_type.id')
                ->leftJoin('master_connection_status', 'consumer_connection.connection_status_id', '=', 'master_connection_status.id')
                ->where('consumer_connection.id',$connection_id)
                ->where('meter_reading.active_record',1)
                ->where('meter_reading.payment_status',0);
        //dd(DB::getQueryLog($sql->get()));
        return $sql;
    }

}
