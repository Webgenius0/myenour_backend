<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\AssignInventoryRequest;
use App\Models\Event;
use App\Services\API\EventInventoryService;
use Illuminate\Http\Request;

class EventInventoryController extends Controller
{
    protected EventInventoryService $eventInventoryService;
    public function __construct(EventInventoryService $eventInventoryService)
    {
        $this->eventInventoryService = $eventInventoryService;
    }
    /**
     * Assign inventory to an event.
     *
     * @param \App\Http\Requests\API\AssignInventoryRequest $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function assignInventory(AssignInventoryRequest $request)
    {
    //    dd('hello');
        try {
            $data = $request->validated();
            $this->eventInventoryService->assignInventory($data);
            return response()->json(['message' => 'Inventory assigned successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to assign inventory: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get assigned items for an event.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $eventId
     * @return \Illuminate\Http\JsonResponse
     */

    public function getAssignedItems(Request $request, int $eventId)
    {
        try {
            $assignedItems = $this->eventInventoryService->getAssignedItems($eventId);
            return response()->json($assignedItems, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve assigned items: ' . $e->getMessage()], 500);
        }
    }
    /**
     * Get all events.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateAssignedInventory(AssignInventoryRequest $request, int $id)
    {

        try {
            $data = $request->validated();
            $this->eventInventoryService->updateAssignedInventory($data,$id);
            return response()->json(['message' => 'Inventory updated successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update inventory: ' . $e->getMessage()], 500);
        }
    }
    /**
     * Get all eventsInventoryItem .
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAssignedInventory(){

        try {
            $assignedItems = $this->eventInventoryService->getAssignedInventory();
            return response()->json($assignedItems, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve assigned items: ' . $e->getMessage()], 500);
        }
    }


}
