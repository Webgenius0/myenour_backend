<?php

namespace App\Repositories\API;

use App\Models\HistoricalDataView;
use Illuminate\Support\Facades\Log;

class HistoricalDataRepository implements HistoricalDataRepositoryInterface
{
    public function getHistoricalData($request)
    {
        try{
        $historicalData=HistoricalDataView::all();
        return $historicalData;
        }catch(\Exception $e){
            Log::error("HistoricalDataRepository: getHistoricalData: ".$e->getMessage());
            throw $e;
        }

    }
}
