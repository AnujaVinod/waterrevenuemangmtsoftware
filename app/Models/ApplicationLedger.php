<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicationLedger extends Model
{
 
     protected $fillable = [
     'application_id', 'connection_type_id', 'agent_code_id', 'inspector_id', 'no_of_flats', 'tap_diameter', 'connection_date', 'application_date', 'deposit_amount', 'deposit_date', 'application_status_id', 'order_no', 'deposit_challan_no', 'remarks', 'connection_charge','inserted_by' ,'updated_by'
    ];
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'consumer_ledger';

    /**
     * Overriding defualt priamry key
     *
     * @var string
     */
    protected $primaryKey = 'id';     
  
  
}
