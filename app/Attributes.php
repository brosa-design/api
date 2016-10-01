<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attributes extends Model
{
    protected $table = 'attributes';
    
    protected $fillable = ['name','created_by'];
    
        
    public function attributables()
    {
        return $this->morphMany('App\Attributables', 'attributable');
    }
    
}
