<?php

namespace App\Repositories\API;

use App\Models\Event;
use App\Models\Inventory;
use Illuminate\Support\Facades\Log;

class ReportRepository implements ReportRepositoryInterface
{
   public function inventoryReport(){

    try {
        $inventoryReport = Inventory::all();
        return $inventoryReport;
    } catch (\Exception $e) {
        Log::error("ReportRepository::inventoryReport", ['error' => $e->getMessage()]);
        throw $e;
    }
   }
   public function eventReport($startEventDate, $endEventDate)
{
    try {
        $eventReport = Event::whereBetween('start_event_date', [$startEventDate, $endEventDate])->get();
        return $eventReport;
    } catch (\Exception $e) {
        Log::error("ReportRepository::eventReport", ['error' => $e->getMessage()]);
        throw $e;
    }
}


}
