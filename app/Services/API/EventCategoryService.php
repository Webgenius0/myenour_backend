<?php

namespace App\Services\API;

use App\Repositories\API\EventCategoryRepositoryInterface;
use Illuminate\Support\Facades\Log;

class EventCategoryService
{
    protected EventCategoryRepositoryInterface $eventCategoryRepository;
    public function __construct(EventCategoryRepositoryInterface $eventCategoryRepository)
    {
        $this->eventCategoryRepository = $eventCategoryRepository;
    }
    public function getEventCategories()
    {
        try{
            return $this->eventCategoryRepository->getEventCategories();
        }catch (\Exception $e){
            Log::error('EventCategoryService@getEventCategories: '.$e->getMessage());
            throw $e;
        }
    }
    public function storeEventCategory(array $eventCategoryData)
    {
        try{
            return $this->eventCategoryRepository->storeEventCategory($eventCategoryData);
        }catch (\Exception $e){
            Log::error('EventCategoryService@storeEventCategory: '.$e->getMessage());
            throw $e;
        }
    }
    public function updateEventCategory($id, array $eventCategoryData)
    {
        try{
            return $this->eventCategoryRepository->updateEventCategory($id, $eventCategoryData);
        }catch (\Exception $e){
            Log::error('EventCategoryService@updateEventCategory: '.$e->getMessage());
            throw $e;
        }
    }
    public function deleteEventCategory($id)
    {
        try{
            return $this->eventCategoryRepository->deleteEventCategory($id);
        }catch (\Exception $e){
            Log::error('EventCategoryService@deleteEventCategory: '.$e->getMessage());
            throw $e;
        }
    }
    public function getEventCategoryById($id)
    {
        try{
            return $this->eventCategoryRepository->getEventCategoryById($id);
        }catch (\Exception $e){
            Log::error('EventCategoryService@getEventCategoryById: '.$e->getMessage());
            throw $e;
        }
    }

    public function getEventCategoryList()
    {
        try{
            return $this->eventCategoryRepository->getEventCategoryList();
        }catch (\Exception $e){
            Log::error('EventCategoryService@getEventCategoryList: '.$e->getMessage());
            throw $e;
        }
    }
}
