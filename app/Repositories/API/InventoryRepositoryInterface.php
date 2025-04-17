<?php

namespace App\Repositories\API;

use Illuminate\Http\Client\Request;

interface InventoryRepositoryInterface
{
    public function getAllInventory();
    public function storeInventory(array $inventoryData);
    public function getInventoryById($id);
    public function updateInventory($id, array $inventoryData);
    public function deleteInventory($id);
    public function searchInventory($searchQuery);
    public function filteringData(array $data);

}
