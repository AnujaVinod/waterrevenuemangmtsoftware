<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicationStatus  extends Model
{
 
     protected $fillable = [
        'status'
    ];
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'master_application_status';

    /**
     * Overriding defualt priamry key
     *
     * @var string
     */
    protected $primaryKey = 'id';     
  

}
