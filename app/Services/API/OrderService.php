<?php

namespace App\Services\API;

use App\Repositories\API\OrderRepositoryInterface;
use Illuminate\Support\Facades\Log;

class OrderService
{

    protected OrderRepositoryInterface $orderRepository;
    public function __construct(OrderRepositoryInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function getAllOrders()
    {
        try {
            $orders = $this->orderRepository->getAllOrders();
            return $orders;
        } catch (\Exception $e) {
            Log::error('OrderService:getAllOrders: ' . $e->getMessage());
            throw $e;
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
        try{
            $order = $this->orderRepository->storeOrder($data);
            return $order;
        }
        catch (\Exception $e) {
           Log::error('OrderService:storeOrder: ' . $e->getMessage());
            throw $e;
        }
    }

    public function updateOrder( array $data,$id,)
    {
        // dd($data);
        try {
            $order = $this->orderRepository->updateOrder($data,$id);
            return $order;
        } catch (\Exception $e) {
            Log::error('OrderService:updateOrder: ' . $e->getMessage());
            throw $e;
        }
    }
    public function deleteOrder($id)
    {
        try {
            $order = $this->orderRepository->deleteOrder($id);
            return $order;
        } catch (\Exception $e) {
            Log::error('OrderService:deleteOrder: ' . $e->getMessage());
            throw $e;
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
            $order = $this->orderRepository->getOrderById($id);
            return $order;
        } catch (\Exception $e) {
            Log::error('OrderService:getOrderById: ' . $e->getMessage());
            throw $e;
        }
    }

    public function statusUpdate($id)
    {
        try {
            $order = $this->orderRepository->statusUpdate($id);
            return $order;
        } catch (\Exception $e) {
            Log::error('OrderService:statusUpdate: ' . $e->getMessage());
            throw $e;
        }
    }
}
