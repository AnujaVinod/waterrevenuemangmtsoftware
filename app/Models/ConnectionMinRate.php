<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConnectionMinRate  extends Model
{
 
     protected $fillable = [
        'connection_type_id', 'from_unit', 'to_unit', 'min_price'
    ];
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'master_connection_minrate';

    /**
     * Overriding defualt priamry key
     *
     * @var string
     */
    protected $primaryKey = 'id';     
  
    
    public static function getMinRate($conn_type)
    {
        $data = ConnectionMinRate:: 
                select('master_connection_minrate.from_unit','master_connection_minrate.to_unit','master_connection_minrate.min_price')
                ->where('master_connection_minrate.connection_type_id',$conn_type)
               // ->WhereRaw('? BETWEEN master_connection_minrate.from_unit AND master_connection_minrate.to_unit', [$consumption])
                ->first();
        return $data;
    }
}
