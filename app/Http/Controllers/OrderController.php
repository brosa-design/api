<?php

/**
 * Controller class to process incoming order request.
 * The class finds the number of available items in the
 * Items(stock_items) table as mentioned in the order request.
 * The available items are assigned to the order. The unavailable 
 * items are created, assigned to the order and have their 
 * physical_status set to 'To Order'.
 * 
 * @package    Http
 * @subpackage Controllers
 * @author     Vikas Thakur <vikascalls@gmail.com>
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\StockItems;
use App\Orders;
use App\ProductVariants;

class OrderController extends Controller {

    /**
     * Updates or Creates an Iteam depeding on the
     * order requirement
     * 
     * @param  Request  $request 
     * @return JSON object
     */
    public function store(Request $request) {
        try {
            $data = $request->all();
            $order = $this->createOrder($data);
            $items = $data['Order']['items'];
            foreach ($items as $item) {
                $sku = $item['sku'];
                $quanity = $item['quantity'];
                $inStock = StockItems::where(['sku' => $sku, 'status' => 'Available'])->get();
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
            
            return json_encode(array("success: " => "Done"));
            
        } catch (Exception $ex) {
            
            return json_encode(array("error" => "Error: " . $ex->getMessage()));
            
        }
    }

    /**
     * Create a new order
     * 
     * @param array $data
     * @return Orders object
     */
    public function createOrder($data) {
        $order = new Orders;
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
        
        return $order;
        
    }

    /**
     * Creates a new item and assign it to the order
     * 
     * @param int $orderId
     * @param string $sku
     * @param int $count  
     */
    public function createItem($orderId, $sku, $count) {
        for ($i = 1; $i <= $count; $i++) {
            $variant = ProductVariants::where('sku', $sku)->first();
            foreach ($variant->packables as $packable) {
                $package_id = $packable->id;
            }
            $variant->stockables()->save(StockItems::firstOrNew([
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
    public function updateItem($orderId, $items) {
        foreach ($items as $item) {
            $item->order_id = $orderId;
            $item->status = 'Assigned';
            $item->save();
        }
    }

}
