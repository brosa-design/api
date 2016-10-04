<?php

/**
 * Processor class to process the order.
 * 
 * @package    Processors
 * @author     Vikas Thakur <vikascalls@gmail.com>
 */

namespace App\Processors;

use App\Models\Products;
use App\Models\Collections;
use App\Models\ProductVariants;
use App\Models\Attributables;
use App\Models\Attributes;


class ProductProcessors 
{
    /**
     * Fetch all products
     * 
     * @param  int  $rpp
     * @return array Array of all retrieved products 
     */
    public function fetchAllProducts($rpp)
    {
        $products = Products::paginate($rpp);
        $productsArr = array();
        foreach ($products as $product) {
            $collection = new Collections();
            $productVariants = new ProductVariants();
            $productsArr[] = array(
                "id" => $product->id,
                "name" => $product->name,
                "Collection_name" => $collection->getCategoryName($product->collection_id),
                "variants" => $this->getVariantsByProduct($product->id),
            );
        }
        
        return array(
                "products" => $productsArr,
                "count" => $products->count(),
                "pages" => ceil(($products->total()) / $rpp),
                "current_page" => $products->currentPage());
    }
    
    /**
     * Retrieve the product_variants of a product and 
     * their attributes
     * 
     * @param  int  $id
     * @return array Array of all retrieved variants 
     */
    public function getVariantsByProduct($id) 
    {
        $variants = ProductVariants::where('product_id', $id)
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
    public function getAttributesByVariant($id) 
    {
        $attributes = ProductVariants::find($id)->attributables;
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
    
    
    /**
     * Fetch all variants of a product
     * 
     * @param  int  $rpp
     * @param  int  $id
     * @return array Array of all retrieved variants 
     */
    public function fetchProductVariants($rpp,$id)
    {
        $variants = ProductVariants::where('product_id', $id)
                ->orderBy('sku', 'desc')
                ->paginate($rpp);

        $variants_arr = array();
        foreach ($variants as $variant) {
            $variants_arr[] = array(
                "id" => $variant->id,
                "product_id" => $variant->product_id,
                "variant_id" => $variant->id,
                "sku" => $variant->sku,
                "cost_price" => $variant->cost_price,
                "is_active" => $variant->is_active == '1' ? 'yes' : 'no'
            );
        }
        
        return array(
                "product_variants" => $variants_arr,
                "count" => $variants->count(),
                "pages" => ceil(($variants->total()) / $rpp),
                "current_page" => $variants->currentPage());

    }
}
