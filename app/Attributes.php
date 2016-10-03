<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attributes extends Model 
{

    protected $table = 'attributes';
    protected $fillable = ['name', 'created_by'];

    public function attributables() 
    {

        return $this->morphMany('App\Attributables', 'attributable');
    }

    /**
     * Retrieve the attribute name by id
     * 
     * @param  int  $id
     * @return string Attribute name 
     */
    public function getAttributeName($id) 
    {
        $attributes = $this->find($id);

        return $attributes->name;
    }

}
?>
