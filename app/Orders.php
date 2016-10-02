<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Orders extends Model {

    protected $table = 'orders';

    public function stockItem() {
        return $this->hasMany(StockItems::class);
    }
    
    /**
     * Create a new order
     * 
     * @param array $data
     * @return Orders object
     */
    public function createOrder($data) {
        $order = $this;
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
}
