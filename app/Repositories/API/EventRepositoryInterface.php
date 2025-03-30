<?php

namespace App\Repositories\API;

interface EventRepositoryInterface
{

    public function storeEvent(array $eventData);
    public function getEvents();
    public function getEventById($id);
    public function updateEvent( array $eventData, $id);
    public function deleteEvent($id);
    public function searchEvents($searchQuery);
}
