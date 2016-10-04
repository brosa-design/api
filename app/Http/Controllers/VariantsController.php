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
use App\Processors\VariantProcessors;

class VariantsController extends Controller 
{

    /**
     * Updates and attribute's value or Creates an attribute if it does not exist
     * depeding on the request
     * 
     * @param       Request  $request 
     * @response    JSON Object
     */
    public function store(Request $request) 
    {
        try {
            $attributes = new VariantProcessors();
            $data = $request->all();
            $variantId = func_get_arg(1);
            $attributeName = false;
            if (func_num_args() == 3) {
                $attributeName = func_get_arg(2);
            }
            $result = $attributes->processAttribute($variantId,$attributeName,$data);
            
            return json_encode($result);        
                    
        } catch (Exception $ex) {
            
            return json_encode(array("Status: " => "Error","Message" => $ex->getMessage()));
        }
    }

    
}
?>
