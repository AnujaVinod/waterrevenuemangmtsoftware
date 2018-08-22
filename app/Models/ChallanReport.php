<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ConsumerConnection;
use App\Models\BankInfo;
use Carbon\Carbon;
use DB;

class ChallanReport extends Model
{

    public static function getSearchResult($from_date, $to_date)
    {
        $report_data = BankInfo::select('bank_info.bank_name', 'bank_info.branch_name', 'bank_info.cheque_dd', 'bank_info.challan_no', 'bank_info.transaction_number', 'payment_history.payment_date', 'payment_history.total_amount', 'master_payment_mode.mode_type', 'payment_history.sequence_number', 'consumer_connection.name', 'consumer_connection.mobile_no')
                ->leftjoin('payment_history', 'payment_history.id', '=', 'bank_info.payment_id')
                ->leftjoin('consumer_connection', 'consumer_connection.sequence_number', '=', 'payment_history.sequence_number')
                ->leftjoin('master_payment_mode', 'master_payment_mode.id', '=', 'payment_history.payment_mode')
                ->where(function($report_data)use ($from_date, $to_date) {
                    if ($from_date != 0 && $to_date != 0) {
                        $report_data->where(DB::raw('date(bank_info.created_at)'), '>=', $from_date);
                        $report_data->where(DB::raw('date(bank_info.created_at)'), '<=', $to_date);
                    }
                });
              //  ->groupBy('payment_history.sequence_number');
        $data_result = $report_data->get()->toArray();
        if (empty($data_result)) {
            $data_result = [];
            return $data_result;
        } else {

            return $data_result;
        }
    }
}
