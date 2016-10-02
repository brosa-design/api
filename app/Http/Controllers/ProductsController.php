<?php
/**
 * Controller class to list all products and to list 
 * all produst_variants of a specified product.
 * 
 * 
 * @package    Http
 * @subpackage Controllers
 * @author     Vikas Thakur <vikascalls@gmail.com>
 */
namespace App\Http\Controllers;

use App\Products;
use App\Collections;
use App\ProductVariants;
use App\Attributables;
use App\Attributes;
use App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

class ProductsController extends Controller {

    private $rpp = 25; //Results Per Page
    
    /**
     * List all products, their respective variants 
     * and the attributes of the variants
     * 
     * @return JSON object 
     */
    public function index() {
        try {
            
            $currentPage = isset($_GET['page'])?$_GET['page']:1;        
            Paginator::currentPageResolver(function() use ($currentPage){
                return $currentPage;
            });
            
            $products = Products::paginate($this->rpp);
            $products_arr = array();
            foreach ($products as $product) {
                $products_arr[] = array(
                    "id" => $product->id,
                    "name" => $product->name,
                    "Collection_name" => $this->getCategoryName($product->collection_id),
                    "variants" => $this->getVariantsByProduct($product->id),
                );
            }
            return json_encode(array(
                "products" => $products_arr,
                "count" => $products->count(),
                "pages" => ceil(($products->total()) / $this->rpp),
                "current_page" => $products->currentPage()));
        } catch (Exception $ex) {
            return json_encode(array("error" => "Error: ".$ex->getMessage()));
        }
    }

    
    /**
     * Retrieve the catergory name by id
     * 
     * @param  int  $id
     * @return string Category name 
     */
    public function getCategoryName($id) {
        $collection = Collections::find($id);
        return $collection->name;
    }

    /**
     * Retrieve the product_variants of a product and 
     * their attributes
     * 
     * @param  int  $id
     * @return array Array of all retrieved variants 
     */
    public function getVariantsByProduct($id) {
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
    public function getAttributesByVariant($id) {
        $attributes = ProductVariants::find($id)->attributables;
        $attributes_arr = array();
        foreach ($attributes as $attribute) {
            $attributes_arr[] = array(
                "id" => $attribute->id,
                "attribute_name" => $this->getAttributeName($attribute->attribute_id),
                "value" => $attribute->value
            );
        }
        return $attributes_arr;
    }

    /**
     * Retrieve the attribute name by id
     * 
     * @param  int  $id
     * @return string Attribute name 
     */
    public function getAttributeName($id) {
        $attributes = Attributes::find($id);
        return $attributes->name;
    }
    
    /**
     * Retrieve all product_variants of a product
     * 
     * @param  int  $id
     * @return JSON object  
     */
    
    public function getVariants($id) {
        $currentPage = isset($_GET['page'])?$_GET['page']:1;        
        Paginator::currentPageResolver(function() use ($currentPage){
            return $currentPage;
        });
        
        $variants = ProductVariants::where('product_id', $id)
                ->orderBy('sku', 'desc')
                ->paginate($this->rpp);
        
        $variants_arr = array();
        foreach ($variants as $variant) {
            $variants_arr[] = array(
                "id" => $variant->id,
                "product_id"=>$variant->product_id,
                "variant_id"=>$variant->id,
                "sku" => $variant->sku,
                "cost_price" => $variant->cost_price,
                "is_active" => $variant->is_active == '1' ? 'yes' : 'no'
            );
        }

        return json_encode(array(
                "product_variants" => $variants_arr,
                "count" => $variants->count(),
                "pages" => ceil(($variants->total()) / $this->rpp),
                "current_page" => $variants->currentPage()));
    }
}
