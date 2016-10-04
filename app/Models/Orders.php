<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Orders extends Model 
{

    protected $table = 'orders';

    public function stockItem() 
    {

        return $this->hasMany(StockItems::class);
    }
    
}
?>
