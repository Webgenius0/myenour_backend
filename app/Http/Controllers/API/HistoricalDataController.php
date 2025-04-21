<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\API\HistoricalDataService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HistoricalDataController extends Controller
{
    protected HistoricalDataService $historicalDataService;
    public function __construct(HistoricalDataService $historicalDataService)
    {
        $this->historicalDataService = $historicalDataService;
    }
    public function getAllHistoricalData(Request $request)
    {
        try
        {
            $historicalData = $this->historicalDataService->getHistoricalData($request);
            return response()->json([
                'status' => 'success',
                'data' => $historicalData,
            ], 200);
        }
        catch(\Exception $e)
        {
            Log::error("HistoricalDataController: getAllHistoricalData: ".$e->getMessage());
           throw $e;
        }

    }
}
