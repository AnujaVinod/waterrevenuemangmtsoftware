<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class WardSequence extends Model
{
 
     protected $fillable = [
       'ward_id', 'seq_from', 'seq_from'
    ];
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'master_ward_sequence_number';

    /**
     * Overriding defualt priamry key
     *
     * @var string
     */
    protected $primaryKey = 'id';     
  
  
}
