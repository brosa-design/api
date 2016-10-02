<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Attributes;
use App\Attributables;
use App\ProductVariants;
use App\StockItems;

class VariantsController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{
            $variantId=func_get_arg(1);
            if(func_num_args()==3){
                $attributeName = func_get_arg(2);
            }
            $data = $request->all();
            $name = isset($attributeName)?$attributeName:$data['attribute']['name'];
            $value = $data['attribute']['value'];
            $attribute = Attributes::firstOrNew(['name' => $name]);
            if($attribute->exists){
                $productAttributes = ProductVariants::find($variantId)->attributables->where('attribute_id',$attribute->id);
                if(count($productAttributes)>0){
                    foreach($productAttributes as $productAttribute){
                        $productAttribute->value = $value;
                        $productAttribute->updated_by = 1;
                        $productAttribute->save();  
                    }
                }else{
                    $this->createAttributable($variantId,$attribute->id,$value);
                }
            }else{
                if(!isset($attributeName)){
                    $attribute->name = $name;
                    $attribute->created_by = 1;
                    $attribute->save();
                    $this->createAttributable($variantId,$attribute->id,$value);
                }else{
                    return json_encode(array("Error: "=>"No attribute with the specified name found!"));
                }
            }
            return json_encode(array("id"=>$variantId,"variant_id"=>$variantId,"attribute_id"=>$attribute->id,"value"=>$value,"attribute_name"=>$name));
        } catch (Exception $ex) {
            return json_encode(array("error" => "Error: ".$ex->getMessage()));
        }
    }

    public function createAttributable($variantId,$attributeId,$value){
        $productAttribute = ProductVariants::find($variantId);
        $productAttribute->attributables()->save(Attributables::firstOrNew([
                'attribute_id'=>$attributeId,
                'value'=>$value,
                'created_by'=>1
            ]));
    }
}
