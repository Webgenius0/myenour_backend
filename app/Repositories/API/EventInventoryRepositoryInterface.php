<?php

namespace App\Repositories\API;

interface EventInventoryRepositoryInterface
{
    public function assignInventory(array $data);
    public function getAssignedItems(int $eventId);
    public function updateAssignedInventory(array $data, int $id);
    public function getAssignedInventory();
}
