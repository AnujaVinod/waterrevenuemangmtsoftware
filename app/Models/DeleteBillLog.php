<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class DeleteBillLog extends Model
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sequence_number', 'date_of_reading', 'deleted_by'
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'meter_reading_delete_log';

    /**
     * Overriding defualt priamry key
     *
     * @var string
     */
    protected $primaryKey = 'id';     
  
}

