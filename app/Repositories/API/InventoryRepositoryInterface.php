<?php

namespace App\Repositories\API;

interface InventoryRepositoryInterface
{
    public function getAllInventory();
    public function storeInventory(array $inventoryData);
    public function getInventoryById($id);
    public function updateInventory($id, array $inventoryData);
    public function deleteInventory($id);
    public function searchInventory($searchTerm);

}
