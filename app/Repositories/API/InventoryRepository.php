<?php

namespace App\Repositories\API;

use App\Models\Inventory;
use Illuminate\Support\Facades\Log;
use PhpParser\Node\Expr\FuncCall;

class InventoryRepository implements InventoryRepositoryInterface
{

    public function getAllInventory(){
        try{
            $inventories = Inventory::all();
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
}
