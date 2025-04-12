<?php

namespace App\Repositories\API;

use Illuminate\Http\Request;

interface DailyTrackingRepositoryInterface
{
    public function load( Request $request);
    public function updateTracking(array $dailyData);


}
