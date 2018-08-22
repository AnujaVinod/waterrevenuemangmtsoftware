<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class OldMeterReading extends Model
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id',
        'sequence_number', 
        'consumer_name', 
        'door_no', 
        'date_of_reading', 
        'bill_no', 
        'meter_no', 
        'meter_status', 
        'meter_rent', 
        'payment_due_date', 
        'previous_billing_date', 
        'payment_last_date', 
        'total_unit_used', 
        'no_of_days_used', 
        'previous_reading', 
        'current_reading', 
        'agent_id', 
        'no_of_flats', 
        'water_charge', 
        'supervisor_charge', 
        'other_charges', 
        'refund_amount', 
        'other_title_charge', 
        'fixed_charge', 
        'penalty', 
        'returned_amount', 
        'cess', 
        'ugd_cess', 
        'arrears', 
        'total_due', 
        'round_off',
        'three_month_average', 
        'total_amount', 
        'active_record',
        'meter_change_status', 
        'payment_status',
        'extra_amount',
        'advance_amount',
        'corpward_id',
        'ward_id',
        'generated_by',
        'approved_by',
        'is_olddata'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'old_meter_reading';

    /**
     * Overriding defualt priamry key
     *
     * @var string
     */
    protected $primaryKey = 'old_data_id';
}
