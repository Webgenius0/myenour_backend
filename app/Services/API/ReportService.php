<?php

namespace App\Services\API;

use App\Repositories\API\ReportRepositoryInterface;
use Illuminate\Support\Facades\Log;

class ReportService
{
    protected ReportRepositoryInterface $reportRepository;
    public function __construct(ReportRepositoryInterface $reportRepository)
    {
        $this->reportRepository = $reportRepository;
    }

    public function inventoryReport(){
 try{
    $inventoryReport = $this->reportRepository->inventoryReport();
    return $inventoryReport;
 } catch (\Exception $e) {
    Log::error('ReportService::inventoryReport', ['error' => $e->getMessage()]);
    throw $e;
 }

    }
    public function eventReport($startEventDate, $endEventDate)
{
    try {
        $eventReport = $this->reportRepository->eventReport($startEventDate, $endEventDate);
        return $eventReport;
    } catch (\Exception $e) {
        Log::error('ReportService::eventReport', ['error' => $e->getMessage()]);
        throw $e;
    }
}


}
