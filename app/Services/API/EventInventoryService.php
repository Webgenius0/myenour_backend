<?php

namespace App\Services\API;

use App\Repositories\API\EventInventoryRepositoryInterface;

class EventInventoryService
{
    protected  EventInventoryRepositoryInterface $eventInventoryRepository;

    public function __construct(EventInventoryRepositoryInterface $eventInventoryRepository)
    {
        $this->eventInventoryRepository = $eventInventoryRepository;
    }

    public function assignInventory(array $data)
    {
        return $this->eventInventoryRepository->assignInventory($data);
    }

    public function getAssignedItems(int $eventId)
    {
        return $this->eventInventoryRepository->getAssignedItems($eventId);
    }

    public function updateAssignedInventory(array $data, int $id)
    {
        return $this->eventInventoryRepository->updateAssignedInventory($data, $id);
    }
    public function getAssignedInventory()
    {
        return $this->eventInventoryRepository->getAssignedInventory();

    }


}
