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
        $query = Inventory::query();

        // Filter by item_name (partial match)
        if (!empty($data['item_name'])) {
            $query->where('item_name', 'like', '%' . $data['item_name'] . '%');
        }

        // Filter by supplier_id
        if (!empty($data['supplier_id'])) {
            $query->where('supplier_id', $data['supplier_id']);
        }

        // Filter by current_quantity range
        if (!empty($data['min_quantity'])) {
            $query->where('current_quantity', '>=', $data['min_quantity']);
        }

        if (!empty($data['max_quantity'])) {
            $query->where('current_quantity', '<=', $data['max_quantity']);
        }

        // Filter by created_at date range
        if (!empty($data['from_date'])) {
            $query->whereDate('created_at', '>=', $data['from_date']);
        }

        if (!empty($data['to_date'])) {
            $query->whereDate('created_at', '<=', $data['to_date']);
        }

        return $query->get();

    } catch (\Exception $e) {
        Log::error('Inventory filtering failed: ' . $e->getMessage());
        return collect(); // Return empty collection on failure
    }
}


}
