<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class ConsumerAddress extends Model
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'application_id','sequence_number','premises_owner_name','premises_address','premises_street','premises_city','premises_state','premises_zip','inserted_by','updated_by'
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'consumer_address';

    /**
     * Overriding defualt priamry key
     *
     * @var string
     */
    protected $primaryKey = 'id';     

}