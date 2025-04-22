<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\EventCategoryRequest;
use App\Services\API\EventCategoryService;
use FFI;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EventCategoryController extends Controller
{
  protected EventCategoryService $eventCategoryService;
  public function __construct(EventCategoryService $eventCategoryService)
  {
    $this->eventCategoryService = $eventCategoryService;
  }
  public function getEventCategories(){
    try{
        $eventCategories = $this->eventCategoryService->getEventCategories();
        if($eventCategories->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No event categories found',
                'data' => []
            ]);
        }
        return response()->json([
            'status' => true,
            'message' => 'Event categories retrieved successfully',
            'data' => $eventCategories
        ]);
    }catch(\Exception $e){
        Log::error('EventCategoryController@getEventCategories: '.$e->getMessage());
        throw $e;
    }
  }

  public function storeEventCategory(EventCategoryRequest $request){
    try{
        $eventCategory = $this->eventCategoryService->storeEventCategory($request->validated());
        return response()->json([
            'status' => true,
            'message' => 'Event category created successfully',
            'data' => $eventCategory
        ]);
    }catch(\Exception $e){
        Log::error('EventCategoryController@storeEventCategory: '.$e->getMessage());
        throw $e;
    }
  }
  public function updateEventCategory(EventCategoryRequest $request, $id){
    try{
        $eventCategory = $this->eventCategoryService->updateEventCategory($id, $request->validated());
        return response()->json([
            'status' => true,
            'message' => 'Event category updated successfully',
            'data' => $eventCategory
        ]);
    }catch(\Exception $e){
        Log::error('EventCategoryController@updateEventCategory: '.$e->getMessage());
        throw $e;
    }
  }
    public function deleteEventCategory($id){
        try{
            $eventCategory = $this->eventCategoryService->deleteEventCategory($id);
            return response()->json([
                'status' => true,
                'message' => 'Event category deleted successfully',
                'data' => $eventCategory
            ]);
        }catch(\Exception $e){
            Log::error('EventCategoryController@deleteEventCategory: '.$e->getMessage());
            throw $e;
        }
    }
    public function getEventCategoryById($id){
        try{
            $eventCategory = $this->eventCategoryService->getEventCategoryById($id);
            if(!$eventCategory) {
                return response()->json([
                    'status' => false,
                    'message' => 'Event category not found',
                    'data' => null
                ]);
            }
            return response()->json([
                'status' => true,
                'message' => 'Event category retrieved successfully',
                'data' => $eventCategory
            ]);
        }catch(\Exception $e){
            Log::error('EventCategoryController@getEventCategoryById: '.$e->getMessage());
            throw $e;
        }
    }
}
