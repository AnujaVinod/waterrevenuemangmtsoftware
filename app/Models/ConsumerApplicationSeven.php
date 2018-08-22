<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class ConsumerApplicationSeven extends Model
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'application_id', 'father_name', 'caste', 'sub_caste', 'bpl_card_no', 'annual_income', 'annual_income_verified_date', 'income_tax_paid_date', 'water_system','inserted_by' ,'updated_by'
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'application_725_details';

    /**
     * Overriding defualt priamry key
     *
     * @var string
     */
    protected $primaryKey = 'id';     
  
}

