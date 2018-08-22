<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\MeterReading;

class BankInfo extends Model
{
 
     protected $fillable = [
         'payment_id', 
         'bank_name', 
         'branch_name', 
         'cheque_dd', 
         'remarks', 
         'challan_no',
         'transaction_number',
         'is_olddata'
    ];
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bank_info';

    /**
     * Overriding defualt priamry key
     *
     * @var string
     */
    protected $primaryKey = 'id';     
  
  
}
