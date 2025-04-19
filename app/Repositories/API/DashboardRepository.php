<?php

namespace App\Repositories\API;

use App\Models\Event;
use App\Models\Inventory;
use App\Models\Order;
use Illuminate\Support\Facades\Log;

class DashboardRepository implements DashboardRepositoryInterface
{
    // Your Repository logic goes here
    public function getAllItems(){
        try {
            $totalInventories = Inventory::count();
            $lowStockItems = Inventory::whereColumn('current_quantity', '<=', 'min_stock_level')->get();
            $lowStockCount = $lowStockItems->count();
            //upcoming order
           $order= Order::where('status', 'Ordered')->count();

            return [
                'total_inventory_count' => $totalInventories,
                'low_stock_count' => $lowStockCount,
                // 'low_stock_items' => $lowStockItems,
                'upcoming_order_count' => $order,
            ];
        } catch (\Exception $e) {
            Log::error("DashboardRepository@getAllItems: " . $e->getMessage());
            throw $e;
        }
    }

    public function upcomingEvents(){
        try {
            // dd('upcoming events');
            $upcomingEvents = Event::where('status','upcoming')->take(5)->get();
            return [
                'upcoming_events' => $upcomingEvents,
            ];
        } catch (\Exception $e) {
            Log::error("DashboardRepository@upcomingEvents: " . $e->getMessage());
            throw $e;
        }
    }

    public function upcomingOrders(){
        try {
            // dd('upcoming orders');
            $upcomingOrders = Order::where('status','Ordered')->take(5)->get();
            return [
                'upcoming_orders' => $upcomingOrders,
            ];
        } catch (\Exception $e) {
            Log::error("DashboardRepository@upcomingOrders: " . $e->getMessage());
            throw $e;
        }
    }
}
