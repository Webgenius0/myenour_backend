<?php

namespace App\Repositories\API;

use App\Models\Inventory;
use App\Models\Order;
use Illuminate\Support\Facades\Log;

class OrderRepository implements OrderRepositoryInterface
{

    public function getAllOrders(array $data)
    {
        try {
            $orderQuery = Order::with('supplier', 'inventory');

            if (!empty($data['supplier_id'])) {
                $orderQuery->where('supplier_id', 'like', '%' . $data['supplier_id'] . '%');
            }

            if (!empty($data['item_id'])) {
                $orderQuery->where('item_id', $data['item_id']);
            }

            if (!empty($data['order_quantity'])) {
                $orderQuery->where('order_quantity', '>=', $data['order_quantity']);
            }

            return $orderQuery->paginate(10);

        } catch (\Exception $e) {
            Log::error('OrderRepository:getAllOrders: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to fetch orders.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    /**
     * Store a new order.
     *
     * @param array $data
     * @return \App\Models\Order|null
     */

    public function storeOrder(array $data)
{
    try {
        $order = Order::create([
            'supplier_id'    => $data['supplier_id'],
            'item_id'        => $data['item_id'],
            'order_quantity' => $data['order_quantity'],
            'status'         => $data['status'] ?? 'Ordered',
        ]);

        return $order;
    } catch (\Exception $e) {
        Log::error('OrderRepository:storeOrder: ' . $e->getMessage());
        return null;
    }
}
    /**
     * Get an order by ID.
     *
     * @param int $id
     * @return \App\Models\Order|null
     */
    public function getOrderById($id)
    {
        try {
            return Order::with('supplier', 'inventory')->findOrFail($id);
        } catch (\Exception $e) {
            Log::error('OrderRepository:getOrderById: ' . $e->getMessage());
            return null;
        }
    }
    /**
     * Update an order by ID.
     *
     * @param int $id
     * @param array $data
     * @return \App\Models\Order|null
     */
public function updateOrder( array $data,$id)
{
    // dd($data);
    try {
        // dd($data);
        $order = Order::findOrFail($id);
        $order->update($data);
        return $order;
    } catch (\Exception $e) {
        Log::error('OrderRepository:updateOrder: ' . $e->getMessage());
        return null;
    }
}
    /**
     * Delete an order by ID.
     *
     * @param int $id
     * @return bool|null
     */
public function deleteOrder($id)
{
    try {
        $order = Order::findOrFail($id);
        $order->delete();
        return true;
    } catch (\Exception $e) {
        Log::error('OrderRepository:deleteOrder: ' . $e->getMessage());
        return null;
    }
}
    /**
     * Update the status of an order and adjust inventory.
     *
     * @param int $id
     * @return \App\Models\Order|null
     */
public function statusUpdate($id)
{
    try {
        $order = Order::with('inventory')->findOrFail($id);

        if ($order->status !== 'Received') {
            $order->status = 'Received';
            $order->save();

            // Update related inventory's quantity
            if ($order->inventory) {
                $order->inventory->current_quantity += $order->order_quantity;
                $order->inventory->save();
            }
        }

        return $order;
    } catch (\Exception $e) {
        Log::error('OrderRepository:statusUpdate: ' . $e->getMessage());
        return null;
    }
}



}
