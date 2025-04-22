<?php

namespace App\Repositories\API;

use App\Models\EventCategory;
use Illuminate\Support\Facades\Log;

class EventCategoryRepository implements EventCategoryRepositoryInterface
{

    public function getEventCategories(){
        try{
            $eventCategories= EventCategory::paginate(10);
            return $eventCategories;
        }catch (\Exception $e){
            Log::error('EventCategoryRepository@getEventCategories: '.$e->getMessage());
            throw $e;
        }
    }
    public function storeEventCategory(array $eventCategoryData){
        try{
            $eventCategory = EventCategory::create($eventCategoryData);
            return $eventCategory;
        }catch (\Exception $e){
            Log::error('EventCategoryRepository@storeEventCategory: '.$e->getMessage());
            throw $e;
        }
    }
    public function updateEventCategory($id, array $eventCategoryData){
        try{
            $eventCategory = EventCategory::find($id);
            $eventCategory->update($eventCategoryData);
            return $eventCategory;
        }catch (\Exception $e){
            Log::error('EventCategoryRepository@updateEventCategory: '.$e->getMessage());
            throw $e;
        }
    }
    public function deleteEventCategory($id){
        try{
            $eventCategory = EventCategory::find($id);
            $eventCategory->delete();
            return $eventCategory;
        }catch (\Exception $e){
            Log::error('EventCategoryRepository@deleteEventCategory: '.$e->getMessage());
            throw $e;
        }
    }
    public function getEventCategoryById($id){
        try{
            $eventCategory = EventCategory::find($id);
            return $eventCategory;
        }catch (\Exception $e){
            Log::error('EventCategoryRepository@getEventCategoryById: '.$e->getMessage());
            throw $e;
        }
    }
    public function getEventCategoryList(){
        try{
            $eventCategoryList = EventCategory::all();
            return $eventCategoryList;
        }catch (\Exception $e){
            Log::error('EventCategoryRepository@getEventCategoryList: '.$e->getMessage());
            throw $e;
        }
    }
}
