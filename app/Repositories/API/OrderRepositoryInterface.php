<?php

namespace App\Repositories\API;

interface OrderRepositoryInterface
{
    public function getAllOrders();
    /**
     * Store a new order.
     *
     * @param array $data
     * @return \App\Models\Order|null
     */
    public function storeOrder(array $data);
    /**
     * Get an order by ID.
     *
     * @param int $id
     * @return \App\Models\Order|null
     */
    public function getOrderById($id);
    /**
     * Update an order by ID.
     *
     * @param int $id
     * @param array $data
     * @return \App\Models\Order|null
     */
    public function updateOrder( array $data,$id);
    /**
     * Delete an order by ID.
     *
     * @param int $id
     * @return \App\Models\Order|null
     */
    public function deleteOrder($id);
    /**
     * Update the status of an order and adjust inventory.
     *
     * @param int $id
     * @return \App\Models\Order|null
     */
    public function statusUpdate($id);
}
