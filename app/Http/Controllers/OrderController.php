<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\StockItems;
use App\Orders;
use App\ProductVariants;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{
            $data = $request->all();
            $order = $this->createOrder($data);
            $items = $data['Order']['items'];
            foreach($items as $item){
                $sku = $item['sku'];
                $quanity = $item['quantity'];
                $inStock = StockItems::where(['sku' => $sku, 'status' => 'Available'])->get();
                if(count($inStock)>0){
                    $notInStock = $quanity-count($inStock);
                    if($notInStock>0){
                        $this->createItem($order->id,$sku,$notInStock);
                    }
                    $this->updateItem($order->id,$inStock);
                }else{
                    $this->createItem($order->id,$sku,$quanity);
                }
            }
            return json_encode(array("Success: "=>"Done"));
        } catch (Exception $ex) {
                return json_encode(array("error" => "Error: ".$ex->getMessage()));
        }
        
    }
    
    public function createOrder($data){
        $order = new Orders;
        $order->name = $data['Order']['customer'];
        $order->address = $data['Order']['address'];
        $order->total = $data['Order']['total'];
        $order->source = $data['Order']['source'];
        $order->group_dispatch = $data['Order']['group_dispatch']=='yes'?1:0;
        $order->status = $data['Order']['Status'];
        $order->payment = $data['Order']['payment'];
        $order->date = date('Y-m-d H:i:s');
        $order->created_by = 1;
        $order->save();
        return $order;
    }
    
    public function createItem($orderId,$sku,$count){
        for($i=1;$i<=$count;$i++){
            $variant = ProductVariants::where('sku',$sku)->first();
            foreach($variant->packables as $packable){
                $package_id = $packable->id;
            }
            $variant->stockables()->save(StockItems::firstOrNew([
                'package_id'=>$package_id,
                'product_variant_id'=>$variant->id,
                'order_id'=>$orderId,
                'sku'=>$sku,
                'status'=>'Assigned',
                'physical_status'=>'To order',
                'created_by'=>1
            ]));
        }
    }
    
    public function updateItem($orderId,$items){
        foreach($items as $item){
            $item->order_id = $orderId;
            $item->status = 'Assigned';
            $item->save();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
