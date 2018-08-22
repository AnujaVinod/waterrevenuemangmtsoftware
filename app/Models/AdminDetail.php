<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class AdminDetail extends Model
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'sub_category_id', 'designation_id', 'contact_no','bank_name','bank_branch'
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admin_details';

    /**
     * Overriding defualt priamry key
     *
     * @var string
     */
    protected $primaryKey = 'id';     
  
}

