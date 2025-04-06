<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\API\ReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ReportController extends Controller
{
    protected ReportService $reportService;
    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }
    public function inventoryReport(){
        try
        {
            $inventoryReport = $this->reportService->inventoryReport();
            return response()->json(['message' => 'Inventory Report fetched successfully', 'inventoryReport' => $inventoryReport], 200);
        } catch (\Exception $e) {
            Log::error('ReportController::inventoryReport', ['error' => $e->getMessage()]);
            throw $e;
        }

    }
    public function eventReport(Request $request)
{
    try {
        $startEventDate = $request->input('start_event_date');
        $endEventDate = $request->input('end_event_date');

        if (!$startEventDate || !$endEventDate) {
            return response()->json(['message' => 'Start and End Event Dates are required.'], 400);
        }

        $eventReport = $this->reportService->eventReport($startEventDate, $endEventDate);

        return response()->json([
            'message' => 'Event Report fetched successfully',
            'eventReport' => $eventReport
        ], 200);
    } catch (\Exception $e) {
        Log::error('ReportController::eventReport', ['error' => $e->getMessage()]);
        return response()->json(['message' => 'An error occurred while fetching the event report.'], 500);
    }
}


}
