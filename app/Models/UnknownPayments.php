<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnknownPayments extends Model
{
    protected $fillable = ['bank_name',
        'branchcenter_name',
        'sequence_number', 
        'remarks',
        'date_of_payment',
        'total_amount',
        'refno',
        'created_at',
        'updated_at'];
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'unknown_payments';

    /**
     * Overriding defualt priamry key
     *
     * @var string
     */
    protected $primaryKey = 'id';
}
