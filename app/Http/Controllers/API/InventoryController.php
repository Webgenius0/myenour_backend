<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\InventoeyRequest;
use App\Services\API\InventoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationData;

class InventoryController extends Controller
{
    protected InventoryService $inventoryService;

    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    public function getInventory(){
        try{
            $inventories = $this->inventoryService->getInventory();
            return response()->json([
                'success' => true,
                'status' => 200,
                'message' => 'Inventory retrieved successfully',
                'data' => $inventories
            ], 200);
        }catch(\Exception $e){
            Log::error('InventoryController::getInventory', ['error' => $e->getMessage()]);
            throw $e;
        }
    }
   public function storeInventory(InventoeyRequest $inventoeyRequest){
    try{

       $inventory = $this->inventoryService->storeInventory($inventoeyRequest->all());
       return response()->json([
        'success' => true,
        'status' => 201,
        'message' => 'Inventory created successfully',
        'data' => $inventory
    ], 201);
    }catch(\Exception $e){
        Log::error('InventoryController::storeInventory', ['error' => $e->getMessage()]);
        throw $e;
    }

   }

   public function getInventoryById($id){
    try{
        $inventory = $this->inventoryService->getInventoryById($id);
        return response()->json([
            'success' => true,
            'status' => 200,
            'message' => 'Inventory retrieved successfully',
            'data' => $inventory
        ], 200);
    }catch(\Exception $e){
        Log::error('InventoryController::getInventory', ['error' => $e->getMessage()]);
        throw $e;
    }
   }
   public function updateInventory($id, InventoeyRequest $inventoeyRequest){
    try{
        $inventory = $this->inventoryService->updateInventory($id, $inventoeyRequest->all());
        return response()->json([
            'success' => true,
            'status' => 200,
            'message' => 'Inventory updated successfully',
            'data' => $inventory
        ], 200);
    }catch(\Exception $e){
        Log::error('InventoryController::updateInventory', ['error' => $e->getMessage()]);
        throw $e;
    }
   }
   public function deleteInventory($id){
    try{
        $inventory = $this->inventoryService->deleteInventory($id);
        return response()->json([
            'success' => true,
            'status' => 200,
            'message' => 'Inventory deleted successfully',
            'data' => $inventory
        ], 200);
    }catch(\Exception $e){
        Log::error('InventoryController::deleteInventory', ['error' => $e->getMessage()]);
        throw $e;
    }
   }
   public function searchInventory(Request $request){
    // dd($request);
    try{
        $searchQuery = $request->query('query');
        $inventories = $this->inventoryService->searchInventory($searchQuery);
        return response()->json([
            'success' => true,
            'status' => 200,
            'message' => 'Inventory retrieved successfully',
            'data' => $inventories
        ], 200);
    }catch(\Exception $e){
        Log::error('InventoryController::searchInventory', ['error' => $e->getMessage()]);
        throw $e;
    }
   }
}
