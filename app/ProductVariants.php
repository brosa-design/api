<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Attributes;


class ProductVariants extends Model {

    protected $table = 'product_variants';

    public function product() {
        return $this->belongsTo(Products::class);
    }

    public function attributables() {
        return $this->morphMany('App\Attributables', 'attributable');
    }

    public function stockables() {
        return $this->morphMany('App\StockItems', 'stockable');
    }

    public function packables() {
        return $this->morphMany('App\Packages', 'packable');
    }
    
    /**
     * Retrieve the product_variants of a product and 
     * their attributes
     * 
     * @param  int  $id
     * @return array Array of all retrieved variants 
     */
    public function getVariantsByProduct($id) {
        $variants = $this->where('product_id', $id)
                ->orderBy('sku', 'desc')
                ->get();
        $variants_arr = array();
        foreach ($variants as $variant) {
            $variants_arr[] = array(
                "id" => $variant->id,
                //"name"=>$variant->name,
                "sku" => $variant->sku,
                "cost_price" => $variant->cost_price,
                "is_active" => $variant->is_active == '1' ? 'yes' : 'no',
                "attributes" => $this->getAttributesByVariant($variant->id)
            );
        }

        return $variants_arr;
    }
    
    /**
     * Retrieve the attributes of a product variant
     * 
     * @param  int  $id
     * @return array Array of all retrieved attributes 
     */
    public function getAttributesByVariant($id) {
        $attributes = $this->find($id)->attributables;
        $attributes_arr = array();
        $att = new Attributes();
        foreach ($attributes as $attribute) {
            $attributes_arr[] = array(
                "id" => $attribute->id,
                "attribute_name" => $att->getAttributeName($attribute->attribute_id),
                "value" => $attribute->value
            );
        }
        
        return $attributes_arr;
        
    }

}
