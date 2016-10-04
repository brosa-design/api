<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Collections extends Model 
{

    protected $table = 'collections';

    public function product() 
    {

        return $this->hasMany(Products::class);
    }

    /**
     * Retrieve the catergory name by id
     * 
     * @param  int  $id
     * @return string Category name 
     */
    public function getCategoryName($id) 
    {
        $collection = $this->find($id);

        return $collection->name;
    }

}
?>
