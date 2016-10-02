<?php

/**
 * Controller class to modify existing attribute 
 * If the attribute does not exist, creating the attribute 
 * and setting it's value.
 * 
 * 
 * @package    Http
 * @subpackage Controllers
 * @author     Vikas Thakur <vikascalls@gmail.com>
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Attributes;
use App\Attributables;
use App\ProductVariants;
use App\StockItems;

class VariantsController extends Controller {

    /**
     * Updates and attribute's value or Creates an attribute if it does not exist
     * depeding on the request
     * 
     * @param       Request  $request 
     * @response    JSON Object
     */
    public function store(Request $request) {
        try {
            $variantId = func_get_arg(1);
            if (func_num_args() == 3) {
                $attributeName = func_get_arg(2);
            }
            $data = $request->all();

            // If optional attribute name parameter is included in the URI, it is assigned 
            // to the attribute name variable else the attribute name input array parameter is assigned  
            $name = isset($attributeName) ? $attributeName : $data['attribute']['name'];
            $value = $data['attribute']['value'];
            $attribute = Attributes::firstOrNew(['name' => $name]);
            if ($attribute->exists) {
                $productAttributes = ProductVariants::find($variantId)->attributables->where('attribute_id', $attribute->id);
                if (count($productAttributes) > 0) {
                    foreach ($productAttributes as $productAttribute) {
                        $productAttribute->value = $value;
                        $productAttribute->updated_by = 1;
                        $productAttribute->save();
                    }
                } else {
                    $this->createAttributable($variantId, $attribute->id, $value);
                }
            } else {
                if (!isset($attributeName)) {
                    // If the attribute name input array parameter is provided
                    // in the request but does not exist in the table, attribute is created 
                    $attribute->name = $name;
                    $attribute->created_by = 1;
                    $attribute->save();
                    $this->createAttributable($variantId, $attribute->id, $value);
                } else {
                    
                    // If optional attribute name parameter is included in the URI
                    // but does not exist in the table, error message is returned 
                    return json_encode(array("Error: " => "No attribute with the specified name found!"));
                    
                }
            }
            
            return json_encode(array("id" => $variantId, "variant_id" => $variantId, "attribute_id" => $attribute->id, "value" => $value, "attribute_name" => $name));
            
        } catch (Exception $ex) {
            
            return json_encode(array("error" => "Error: " . $ex->getMessage()));
            
        }
    }

    /**
     * Populate Attributable table mapping the attribute
     * to the product variant
     * 
     * @param  int  $variantId
     * @param  int  $attributeId
     * @param  string  $value
     * 
     */
    public function createAttributable($variantId, $attributeId, $value) {
        $productAttribute = ProductVariants::find($variantId);
        $productAttribute->attributables()->save(Attributables::firstOrNew([
                    'attribute_id' => $attributeId,
                    'value' => $value,
                    'created_by' => 1
        ]));
    }

}
