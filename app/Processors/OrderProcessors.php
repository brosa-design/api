<?php

/**
 * Processor class to process the order.
 * 
 * @package    Processors
 * @author     Vikas Thakur <vikascalls@gmail.com>
 */

namespace App\Processors;

use App\Models\StockItems;
use App\Models\Orders;
use App\Models\ProductVariants;


class OrderProcessors 
{
    
    /**
     * Process a new order
     * 
     * @param array $data
     * @return object JSON
     */
    public function processOrder($data) 
    {
        try{
            
            $order = new Orders();
            $order->name = $data['Order']['customer'];
            $order->address = $data['Order']['address'];
            $order->total = $data['Order']['total'];
            $order->source = $data['Order']['source'];
            $order->group_dispatch = $data['Order']['group_dispatch'] == 'yes' ? 1 : 0;
            $order->status = $data['Order']['Status'];
            $order->payment = $data['Order']['payment'];
            $order->date = date('Y-m-d H:i:s');
            $order->created_by = 1;
            $order->save();

            return $this->processItems($data,$order);
        
        } catch (\Exception $ex) {
            
            return json_encode(array("Status: " => "Error","Message" => $ex->getMessage()));
        }
        
    }
    
    /**
     * Counts the number of items to be processed in the
     * order to be created. Checks the available item in 
     * the table, assigns them to the order and creates
     * the balance items
     * 
     * @param array $data
     * @param object $order
     * @returns object JSON
     */
    
    public function processItems($data,$order) 
    {
        try{
            $stockItems = new StockItems();
            $items = $data['Order']['items'];
            foreach ($items as $item) {
                $sku = $item['sku'];
                $quanity = $item['quantity'];
                $inStock = $stockItems->matchSku($sku);
                if (count($inStock) > 0) {
                    $notInStock = $quanity - count($inStock);
                    if ($notInStock > 0) {
                        $this->createItem($order->id, $sku, $notInStock);
                    }
                    $this->updateItem($order->id, $inStock);
                } else {
                    $this->createItem($order->id, $sku, $quanity);
                }
            }
            
            return json_encode(array("Status: " => "Success","Message: " => "Order Processed"));
            
        } catch (\Exception $ex) {
            
            return json_encode(array("Status: " => "Error","Message" => $ex->getMessage()));
        }
    }

    /**
     * Creates a new item and assign it to the order
     * 
     * @param int $orderId
     * @param string $sku
     * @param int $count  
     */
    public function createItem($orderId, $sku, $count) 
    {
        for ($i = 1; $i <= $count; $i++) {
            $variant = ProductVariants::where('sku', $sku)->first();
            foreach ($variant->packables as $packable) {
                $package_id = $packable->id;
            }
            $variant->stockables()->save(new StockItems([
                'package_id' => $package_id,
                'product_variant_id' => $variant->id,
                'order_id' => $orderId,
                'sku' => $sku,
                'status' => 'Assigned',
                'physical_status' => 'To order',
                'created_by' => 1
            ]));
        }
    }

    /**
     * Assign available items to the order
     * 
     * @param int $orderId  
     * @param array $items  
     */
    public function updateItem($orderId, $items) 
    {
        foreach ($items as $item) {
            $item->order_id = $orderId;
            $item->status = 'Assigned';
            $item->save();
        }
    }
}
