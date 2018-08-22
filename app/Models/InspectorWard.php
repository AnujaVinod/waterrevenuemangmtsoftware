<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InspectorWard  extends Model
{
 
     protected $fillable = [
        'inspector_id','ward_id'
    ];
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'inspector_ward';

    /**
     * Overriding defualt priamry key
     *
     * @var string
     */
    protected $primaryKey = 'id';     
  

}
