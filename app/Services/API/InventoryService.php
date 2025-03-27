<?php

namespace App\Services\API;

use App\Repositories\API\InventoryRepositoryInterface;
use Illuminate\Support\Facades\Log;

class InventoryService
{
   protected InventoryRepositoryInterface $inventoryRepository;
   public function __construct(InventoryRepositoryInterface $inventoryRepository)
   {
      $this->inventoryRepository = $inventoryRepository;
   }

   public function getInventory(){
      try{
         $inventories = $this->inventoryRepository->getAllInventory();
         return $inventories;
      }catch(\Exception $e){
         Log::error("InventoryService::getAllInventory", ['error' => $e->getMessage()]);
         throw $e;
      }
    }

   public function storeInventory(array $inventoryData){
      try{
         $inventory = $this->inventoryRepository->storeInventory($inventoryData);
         return $inventory;
      }catch(\Exception $e){
         Log::error("InventoryService::storeInventory", ['error' => $e->getMessage()]);
         throw $e;
      }
   }
   public function getInventoryById($id){
      try{
         $inventory = $this->inventoryRepository->getInventoryById($id);
         return $inventory;
      }catch(\Exception $e){
         Log::error("InventoryService::getInventory", ['error' => $e->getMessage()]);
         throw $e;
      }
   }
   public function updateInventory($id, array $inventoryData){
      try{
         $inventory = $this->inventoryRepository->updateInventory($id, $inventoryData);
         return $inventory;
      }catch(\Exception $e){
         Log::error("InventoryService::updateInventory", ['error' => $e->getMessage()]);
         throw $e;
      }
   }

   public function deleteInventory($id){
      try{
         $inventory = $this->inventoryRepository->deleteInventory($id);
         return $inventory;
      }catch(\Exception $e){
         Log::error("InventoryService::deleteInventory", ['error' => $e->getMessage()]);
         throw $e;
      }
   }
   public function searchInventory($searchQuery){
      try{
         $inventories = $this->inventoryRepository->searchInventory($searchQuery);
         return $inventories;
      }catch(\Exception $e){
         Log::error("InventoryService::searchInventory", ['error' => $e->getMessage()]);
         throw $e;
      }
   }
}
