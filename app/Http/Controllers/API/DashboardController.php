<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\API\DashboardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{

    protected DashboardService $dashboardService;
    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }
    /**
     * Example method to get dashboard data.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllItems(){
        try{

            $data=$this->dashboardService->getAllItems();
            return response()->json([
                'status' => true,
                'message' => 'All items retrieved successfully',
                'data' => $data
            ], 200);
        }catch(\Exception $e){
            Log::error("DashboardController@getAllItems: ".$e->getMessage());
            throw $e;
        }
    }

    public function upcomingEvents(){
        // dd('upcoming events');
        try{
            $data=$this->dashboardService->upcomingEvents();
            return response()->json([
                'status' => true,
                'message' => 'All items retrieved successfully',
                'data' => $data
            ], 200);
        }catch(\Exception $e){
            Log::error("DashboardController@getAllItems: ".$e->getMessage());
            throw $e;
        }
    }

    /**
     * Example method to get dashboard data.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function upcomingOrders(){
        // dd('upcoming orders');
        try{
            $data=$this->dashboardService->upcomingOrders();
            return response()->json([
                'status' => true,
                'message' => 'All items retrieved successfully',
                'data' => $data
            ], 200);
        }catch(\Exception $e){
            Log::error("DashboardController@getAllItems: ".$e->getMessage());
            throw $e;
        }
    }
    //
}
