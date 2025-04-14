<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\OrderRequest;
use App\Services\API\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    protected OrderService $orderService;
    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function getOrders(){
        try{
            $orders = $this->orderService->getAllOrders();
            return response()->json([
                'status' => true,
                'message' => 'Orders Retrieved Successfully',
                'data' => $orders
            ]);
        }catch (\Exception $e){
            Log::error('OrderController:getOrders: ' . $e->getMessage());
            throw $e;
        }
    }
    public function storeOrder(OrderRequest $orderRequest){
        try{
            // dd('hello');
            $data = $orderRequest->validated();
            $order = $this->orderService->storeOrder($data);
            return response()->json([
                'status' => true,
                'message' => 'Order Created Successfully',
                'data' => $order
            ]);
        }catch (\Exception $e){
            Log::error('OrderController:orderStore: ' . $e->getMessage());
            throw $e;
    }
}
    /**
     * Get an order by ID.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
public function getOrderById($id){
    try {
        $order = $this->orderService->getOrderById($id);
        return response()->json([
            'status' => true,
            'message' => 'Order Retrieved Successfully',
            'data' => $order
        ]);
    } catch (\Exception $e) {
        Log::error('OrderController:getOrderById: ' . $e->getMessage());
        throw $e;
    }
}

public function updateOrder(OrderRequest $orderRequest, $id){
    // dd($orderRequest);
    try {
        $data = $orderRequest->validated();
        // dd($data);
        $order = $this->orderService->updateOrder($data, $id);
        return response()->json([
            'status' => true,
            'message' => 'Order Updated Successfully',
            'data' => $order
        ]);
    } catch (\Exception $e) {
        Log::error('OrderController:updateOrder: ' . $e->getMessage());
        throw $e;
    }
}
    /**
     * Delete an order by ID.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteOrder($id){
        try {
            $this->orderService->deleteOrder($id);
            return response()->json([
                'status' => true,
                'message' => 'Order Deleted Successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('OrderController:deleteOrder: ' . $e->getMessage());
            throw $e;
        }
    }
    /**
     * Update the status of an order.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */

    public function statusUpdate($id){
        try {
            $order = $this->orderService->statusUpdate($id);
            return response()->json([
                'status' => true,
                'message' => 'Order Status Updated Successfully',
                'data' => $order
            ]);
        } catch (\Exception $e) {
            Log::error('OrderController:statusUpdate: ' . $e->getMessage());
            throw $e;
        }

    }
}
