<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\DailyTrackingRequest;
use App\Services\API\DailyTrackingService;
use Illuminate\Http\Request;

class DailyTrackingController extends Controller
{
   protected DailyTrackingService $dailyTrackingService;
   public function __construct(DailyTrackingService $dailyTrackingService)
   {
       $this->dailyTrackingService = $dailyTrackingService;
   }
   public function index(Request $request)
   {
    // dd($request->all());
       try {
           $dailyTracking = $this->dailyTrackingService->load($request);
           return response()->json(['message' => 'Daily Tracking loaded successfully', 'data' => $dailyTracking], 200);
       } catch (\Exception $e) {
           return response()->json(['message' => 'Error loading Daily Tracking', 'error' => $e->getMessage()], 500);
       }
   }
   public function updateTracking(DailyTrackingRequest $dailyTrackingRequest,)
   {
    // dd($dailyTrackingRequest->all());


       try {
        $validatedData = $dailyTrackingRequest->validated();

           $dailyTracking = $this->dailyTrackingService->updateTracking($validatedData);
           return response()->json(['message' => 'Daily Tracking saved successfully', 'data' => $dailyTracking], 201);
       } catch (\Exception $e) {
           return response()->json(['message' => 'Error saving Daily Tracking', 'error' => $e->getMessage()], 500);
       }
   }


}
