<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class PaymentHistory extends Model {

    protected $fillable = ['sequence_number',
        'meter_reading_id',
        'payment_date',
        'total_amount',
        'payment_mode',
        'payment_status',
        'created_at',
        'updated_at',
        'is_olddata'
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'payment_history';

    /**
     * Overriding defualt priamry key
     *
     * @var string
     */
    protected $primaryKey = 'id';

    public static function consumerWiseCollectionReport($data) {
        $from_date = $data['from_date'];
        $to_date = $data['to_date'];
        $bank_name = $data['bank_name'];
        $report_data = PaymentHistory::select('bank_info.bank_name', 'bank_info.branch_name', 'bank_info.cheque_dd', 'bank_info.challan_no', 'bank_info.transaction_number', 'payment_history.payment_date', 'payment_history.total_amount', 'master_payment_mode.mode_type', 'payment_history.sequence_number', 'new_meter_reading.consumer_name', 'consumer_connection.mobile_no')
                ->leftjoin('master_payment_mode', 'master_payment_mode.id', '=', 'payment_history.payment_mode')
                ->leftjoin('bank_info', 'payment_history.id', '=', 'bank_info.payment_id')
                ->leftjoin(DB::raw("(SELECT `id`,`sequence_number`,`consumer_name`,`door_no`,`date_of_reading`,`bill_no`,`meter_no`,`meter_status`,`meter_rent`,`payment_due_date`,`previous_billing_date`,`total_unit_used`,`no_of_days_used`,`previous_reading`,`current_reading`,`agent_id`,`no_of_flats`,`water_charge`,`supervisor_charge`,`other_charges`,`refund_amount`,`other_title_charge`,`fixed_charge`,`penalty`,`cess`,`ugd_cess`,`arrears`,`total_due`,`round_off`,`three_month_average`,`total_amount`,`active_record`,`meter_change_status`,`payment_status`,`extra_amount`,`advance_amount`,`corpward_id`,`ward_id`,`generated_by`,`approved_by`,`is_olddata` FROM meter_reading WHERE is_olddata = 0 UNION SELECT `id`,`sequence_number`,`consumer_name`,`door_no`,`date_of_reading`,`bill_no`,`meter_no`,`meter_status`,`meter_rent`,`payment_due_date`,`previous_billing_date`,`total_unit_used`,`no_of_days_used`,`previous_reading`,`current_reading`,`agent_id`,`no_of_flats`,`water_charge`,`supervisor_charge`,`other_charges`,`refund_amount`,`other_title_charge`,`fixed_charge`,`penalty`,`cess`,`ugd_cess`,`arrears`,`total_due`,`round_off`,`three_month_average`,`total_amount`,`active_record`,`meter_change_status`,`payment_status`,`extra_amount`,`advance_amount`,`corpward_id`,`ward_id`,`generated_by`,`approved_by`,`is_olddata` FROM old_meter_reading WHERE is_olddata = 0) as new_meter_reading"), 'payment_history.meter_reading_id', '=', 'new_meter_reading.id')
                ->leftjoin('consumer_connection', 'consumer_connection.sequence_number', '=', 'new_meter_reading.sequence_number')
                ->whereBetween('payment_history.payment_date', [$from_date, $to_date]);
        if ($bank_name) {
            $report_data->where('bank_info.bank_name', $bank_name);
        }
        $report_data->orderBy('payment_history.payment_date', 'desc');
        return $report_data;
    }

    public static function bankWiseCollectionReport($data) {
//        DB::enableQueryLog();
        $from_date = $data['from_date'];
        $to_date = $data['to_date'];
        $bank_name = $data['bank_name'];
        $report_data = PaymentHistory::select('bank_info.bank_name', 'bank_info.branch_name', 'payment_history.payment_date')
                ->addSelect(DB::raw("SUM(payment_history.total_amount) as total_collection"),DB::raw("(@cnt := @cnt + 1) AS rowNumber"))
                ->crossJoin(DB::raw("(SELECT @cnt := 0) AS dummy"))
                ->leftjoin('bank_info', 'payment_history.id', '=', 'bank_info.payment_id');
        if($from_date == $to_date){
            $report_data->where('payment_history.payment_date', $from_date);
        }else{
            $report_data->whereBetween('payment_history.payment_date', [$from_date, $to_date]);
        }
        if ($bank_name) {
            $report_data->where('bank_info.bank_name', $bank_name);
        }
        $report_data->orderBy('rowNumber')
                ->groupBy('bank_info.bank_name', 'bank_info.branch_name', 'payment_history.payment_date');
//        dd(DB::getQueryLog($report_data->get()));
        return $report_data;
    }
    
    
    public static function totalCollection($data){
        $from_date = $data['from_date'];
        $to_date = $data['to_date'];
        $bank_name = $data['bank_name'];
        $report_data = PaymentHistory::select(DB::raw("SUM(payment_history.total_amount) as total_collection"))
                                    ->addSelect('bank_info.branch_name')
                                    ->leftjoin('bank_info', 'payment_history.id', '=', 'bank_info.payment_id');
        if($from_date == $to_date){
            $report_data->where('payment_history.payment_date', $from_date);
        }else{
            $report_data->whereBetween('payment_history.payment_date', [$from_date, $to_date]);
        }
        if ($bank_name) {
            $report_data->where('bank_info.bank_name', $bank_name);
        }
         return $report_data;
    }

}
