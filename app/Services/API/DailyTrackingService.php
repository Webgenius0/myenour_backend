<?php

namespace App\Services\API;

use App\Repositories\API\DailyTrackingRepositoryInterface;
use Illuminate\Support\Facades\Log;

class DailyTrackingService
{
    protected DailyTrackingRepositoryInterface $dailyTrackingRepository;
    public function __construct(DailyTrackingRepositoryInterface $dailyTrackingRepository)
    {
        $this->dailyTrackingRepository = $dailyTrackingRepository;
    }
    public function load($request)
    {
        try {
            return $this->dailyTrackingRepository->load($request);
        } catch (\Exception $e) {
           Log::error("DailyTrackingService::load", ['error' => $e->getMessage()]);
            throw $e;
        }

    }
    public function updateTracking( array $dailyData)
    {

        try {
            return $this->dailyTrackingRepository->updateTracking($dailyData);
        } catch (\Exception $e) {
            Log::error("DailyTrackingService::save", ['error' => $e->getMessage()]);
            throw $e;
        }

    }
}
