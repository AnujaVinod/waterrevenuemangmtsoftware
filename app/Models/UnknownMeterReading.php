<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnknownMeterReading extends Model
{
    protected $fillable = ['sequence_number',
                        'previous_reading',
                        'current_reading',
                        'previous_billing_date',
                        'date_of_reading',
                        'total_unit_used',
                        'water_charge',
                        'other_charge',
                        'total_amount',
                        'penalty',
                        'arrears',
                        'meter_status',
                        'created_at',
                        'updated_at'
    ];
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'unknown_meter_reading';

    /**
     * Overriding defualt priamry key
     *
     * @var string
     */
    protected $primaryKey = 'id';
}
