<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attributables extends Model
{
    protected $table = 'attributables';
    
    protected $fillable = ['attribute_id','value','created_by'];
    
    public function commentable()
    {
        return $this->morphTo();
    }
}
