<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class ConsumerApplication extends Model
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'application_number', 'application_type_id', 'customer_name', 'application_date','ward_id','corp_ward_id', 'door_no', 'khata_no', 'phone_number', 'property_id', 'service_id', 'connection_type_id', 'plumber_id', 'document_name','certificate_name', 'no_of_house', 'recommended_by', 'remarks', 'ts_no', 'rs_no', 'application_status_id', 'inserted_by' ,'updated_by'
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'consumer_application';

    /**
     * Overriding defualt priamry key
     *
     * @var string
     */
    protected $primaryKey = 'id';     
  
}

