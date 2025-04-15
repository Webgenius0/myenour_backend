<?php

namespace App\Repositories\API;

use App\Models\DailyTrackingView;
use App\Models\Event;
use App\Models\Inventory;
use App\Models\Order;
use Illuminate\Support\Facades\Log;

class ReportRepository implements ReportRepositoryInterface
{
   public function inventoryReport(){

    try {
        $inventoryReport = Inventory::with('supplier')->get();
        return $inventoryReport;
    } catch (\Exception $e) {
        Log::error("ReportRepository::inventoryReport", ['error' => $e->getMessage()]);
        throw $e;
    }
   }
   public function eventReport()
{
    try {
        $eventReport = DailyTrackingView::all();
        // if ($eventReport->isEmpty()) {
        //     return response()->json(['message' => 'No event data found.'], 404);
        // }
        return $eventReport;
    } catch (\Exception $e) {
        Log::error("ReportRepository::eventReport", ['error' => $e->getMessage()]);
        throw $e;
    }
}
public function orderReport()
{
    try {
        $orderReport = Order::with('inventory')->get();
        return $orderReport;
    } catch (\Exception $e) {
        Log::error("ReportRepository::orderReport", ['error' => $e->getMessage()]);
        throw $e;
    }
}


}
