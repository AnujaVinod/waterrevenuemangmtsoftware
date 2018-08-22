<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plumber  extends Model
{
 
     protected $fillable = [
        'plumber_name','license_number','contact_no'
    ];
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'plumber';

    /**
     * Overriding defualt priamry key
     *
     * @var string
     */
    protected $primaryKey = 'id';     
  

}
