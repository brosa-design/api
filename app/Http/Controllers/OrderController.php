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
use App\Processors\OrderProcessors;

class OrderController extends Controller 
{

    /**
     * Updates or Creates an Item depending on the
     * order requirement
     * 
     * @param  Request  $request 
     * @return JSON object
     */
    public function store(Request $request) 
    {
            $data = $request->all();
            $order = new OrderProcessors($data);

            return $order->processOrder($data);
            
    }
}
?>
