<?php

namespace App\Services\API;

use App\Repositories\API\HistoricalDataRepositoryInterface;
use Illuminate\Support\Facades\Log;

class HistoricalDataService
{
    protected HistoricalDataRepositoryInterface $historicalDataRepository;
    public function __construct(HistoricalDataRepositoryInterface $historicalDataRepository)
    {
        $this->historicalDataRepository = $historicalDataRepository;
    }
    public function getHistoricalData($request)
    {
        try{
            return $this->historicalDataRepository->getHistoricalData($request);
        }
        catch(\Exception $e){
            Log::error("HistoricalDataService: getHistoricalData: ".$e->getMessage());
            throw $e;
        }
    }
}
