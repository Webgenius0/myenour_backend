<?php

namespace App\Repositories\API;

use App\Models\Inventory;
use Illuminate\Support\Facades\Log;
use PhpParser\Node\Expr\FuncCall;

class InventoryRepository implements InventoryRepositoryInterface
{

    public function getAllInventory(){
        try{
            $inventories = Inventory::with('supplier')->paginate(10);
            return $inventories;
        }catch(\Exception $e){
            Log::error("InventoryRepository::getAllInventory", ['error' => $e->getMessage()]);
            throw $e;
        }
    }
    public function storeInventory(array $inventoryData){
        try{
            $inventory = Inventory::create($inventoryData);
            return $inventory;
        }catch(\Exception $e){
           Log::error("InventoryRepository::storeInventory", ['error' => $e->getMessage()]);
           throw $e;
        }
    }
    public function getInventoryById($id){
        try{
            $inventory = Inventory::find($id);
            return $inventory;
        }catch(\Exception $e){
            Log::error("InventoryRepository::getInventoryById", ['error' => $e->getMessage()]);
            throw $e;
        }
    }
    public function updateInventory($id, array $inventoryData){
        try{
            $inventory = Inventory::find($id);
            $inventory->update($inventoryData);
            return $inventory;
        }catch(\Exception $e){
            Log::error("InventoryRepository::updateInventory", ['error' => $e->getMessage()]);
            throw $e;
        }
    }
    public function deleteInventory($id){
        try{
            $inventory = Inventory::find($id);
            $inventory->delete();
            return $inventory;
        }catch(\Exception $e){
            Log::error("InventoryRepository::deleteInventory", ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function searchInventory($searchQuery){

        try{

            return Inventory::where('item_name', 'like', '%' . $searchQuery . '%')->get();
        }catch(\Exception $e){
            Log::error("InventoryRepository::searchInventory", ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function filteringData(array $data)
{
    try {
        $query = Inventory::query()->with(['supplier' => function ($q) {
            $q->select('supplier_id', 'supplier_name'); // 'id' is required to keep the relationship working
        }]);

        // Apply filters
        if (!empty($data['item_name'])) {
            $query->where('item_name', 'like', '%' . $data['item_name'] . '%');
        }

        if (!empty($data['supplier_id'])) {
            $query->where('supplier_id', $data['supplier_id']);
        }

        if (!empty($data['current_quantity'])) {
            $query->where('current_quantity', '=', $data['current_quantity']);
        }

        if (!empty($data['min_stock_level'])) {
            $query->where('min_stock_level', '=', $data['min_stock_level']);
        }

        if (!empty($data['max_stock_level'])) {
            $query->where('max_stock_level', '=', $data['max_stock_level']);
        }

        if (!empty($data['incoming_stock'])) {
            $query->where('incoming_stock', '=', $data['incoming_stock']);
        }
        if (!empty($data['pack_size'])) {
            $query->where('pack_size', '=', $data['pack_size']);
        }

        // Always return paginated results
        return $query->paginate(10);

    } catch (\Exception $e) {
        Log::error('Inventory filtering failed: ' . $e->getMessage());
        return response()->json([
            'message' => 'Failed to fetch inventory.',
            'error' => $e->getMessage(),
        ], 500);
    }
}


    public function getAllInventoryList(){
        try{
            $inventories = Inventory::select('id', 'item_name')->get();
            return $inventories;
        }catch(\Exception $e){
            Log::error("InventoryRepository::getAllInventoryList", ['error' => $e->getMessage()]);
            throw $e;
        }
    }


}
