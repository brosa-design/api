<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Packages extends Model 
{

    protected $table = 'packages';

    public function stockItem() 
    {

        return $this->hasMany(StockItems::class);
    }

    public function packable() 
    {

        return $this->morphTo();
    }

}
?>
