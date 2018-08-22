<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MeterStaus  extends Model
{
 
     protected $fillable = [
        'connection_name'
    ];
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'master_meter_status';

    /**
     * Overriding defualt priamry key
     *
     * @var string
     */
    protected $primaryKey = 'id';     
  

}
