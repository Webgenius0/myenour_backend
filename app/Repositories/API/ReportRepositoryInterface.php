<?php

namespace App\Repositories\API;

interface ReportRepositoryInterface
{
    public function inventoryReport();
    public function eventReport($startEventDate, $endEventDate);
}
