<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuspensePayments extends Model
{
      protected $fillable = [
       'sequence_number', 'consumer_name', 'paid_amount', 'transaction_number', 'reason_to_skip', 'created_at','updated_at'
    ];
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'suspense_payments';

    /**
     * Overriding defualt priamry key
     *
     * @var string
     */
    protected $primaryKey = 'id';
}
