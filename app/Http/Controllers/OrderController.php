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
            $orders = new Orders();
            $productVariants = new ProductVariants();
            $stockItems = new StockItems();
            
            $data = $request->all();
            $order = $orders->createOrder($data);
            $items = $data['Order']['items'];
            foreach ($items as $item) {
                $sku = $item['sku'];
                $quanity = $item['quantity'];
                $inStock = $stockItems->matchSku($sku);
                if (count($inStock) > 0) {
                    $notInStock = $quanity - count($inStock);
                    if ($notInStock > 0) {
                        $productVariants->createItem($order->id, $sku, $notInStock);
                    }
                    $productVariants->updateItem($order->id, $inStock);
                } else {
                    $productVariants->createItem($order->id, $sku, $quanity);
                }
            }
            
            return json_encode(array("success: " => "Done"));
            
        } catch (Exception $ex) {
            
            return json_encode(array("error" => "Error: " . $ex->getMessage()));
            
        }
    }
}
