<?php

namespace App\Services\API;

use App\Repositories\API\EventRepositoryInterface;
use Illuminate\Support\Facades\Log;

class EventService
{
   protected EventRepositoryInterface $eventRepository;

   public function __construct(EventRepositoryInterface $eventRepository)
   {
       $this->eventRepository = $eventRepository;
   }

   public function getEvents(array $data)
   {
       try {
           return $this->eventRepository->getEvents($data);
       } catch (\Exception $e) {
           Log::error('EventService::getEvents', ['error' => $e->getMessage()]);
           throw $e;
       }
   }

   public function StoreEvent(array $eventData)
   {
    try
    {
        return $this->eventRepository->storeEvent($eventData);
    } catch (\Exception $e) {
        Log::error('EventService::storeEvent', ['error' => $e->getMessage()]);
        throw $e;
    }
    }

    public function getEventById($id){
        try{
            return $this->eventRepository->getEventById($id);
        } catch (\Exception $e) {
            Log::error('EventService::getEventById', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function updateEvent(array $eventData, $id){
        try{

            return $this->eventRepository->updateEvent($eventData, $id);
        } catch (\Exception $e) {
            Log::error('EventService::updateEvent', ['error' => $e->getMessage()]);
            throw $e;
        }
    }
    public function deleteEvent($id){
        try{
            return $this->eventRepository->deleteEvent($id);
        } catch (\Exception $e) {
            Log::error('EventService::deleteEvent', ['error' => $e->getMessage()]);
            throw $e;
        }
    }
    public function searchEvents($searchQuery){
        try{
            return $this->eventRepository->searchEvents($searchQuery);
        } catch (\Exception $e) {
            Log::error('EventService::searchEvent', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function getAllEventList(){
        try{
            return $this->eventRepository->getAllEventList();
        } catch (\Exception $e) {
            Log::error('EventService::getAllEventList', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

}
