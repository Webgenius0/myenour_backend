<?php

namespace App\Repositories\API;

use App\Models\Event;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EventRepository implements EventRepositoryInterface
{
    public function getEvents()
    {
        try {
            return Event::with('inventories')->get();
        } catch (\Exception $e) {
            Log::error("EventRepository::getAllEvents", ['error' => $e->getMessage()]);
            throw $e;
        }
    }
   public function storeEvent(array $eventData)
    {


        try {
            return DB::transaction(function () use ($eventData) {
                // Create the event
                $event = Event::create([
                    'event_name' => $eventData['event_name'],
                    'start_event_date' => $eventData['start_event_date'],
                    'end_event_date' => $eventData['end_event_date'],
                    'total_event_days' => $eventData['total_event_days'],
                    'status' => $eventData['status'],
                ]);

                // Attach multiple inventory IDs
                $event->inventories()->attach($eventData['inventory_id']); // Array of inventory IDs

                return $event;
            });
            } catch (\Exception $e) {
                Log::error("EventRepository::storeEvent", ['error' => $e->getMessage()]);
                throw $e;
            }
    }
 public function getEventById($id){
        try{
            return Event::with('inventories')->find($id);
        }catch(\Exception $e){
            Log::error("EventRepository::getEventById", ['error' => $e->getMessage()]);
            throw $e;
        }
    }
    public function updateEvent(array $eventData, $id)
    {
        try {
            return DB::transaction(function () use ($eventData, $id) {
                // Find the event
                $event = Event::findOrFail($id);

                // Update event details
                $event->update([
                    'event_name' => $eventData['event_name'],
                    'start_event_date' => $eventData['start_event_date'],
                    'end_event_date' => $eventData['end_event_date'],
                    'total_event_days' => $eventData['total_event_days'],
                    'status' => $eventData['status'],
                ]);

                // Sync inventory IDs (update pivot table)
                if (isset($eventData['inventory_id'])) {
                    $event->inventories()->sync($eventData['inventory_id']); // Replace old IDs with new ones
                }

                return $event;
            });
        } catch (\Exception $e) {
            Log::error("EventRepository::updateEvent", ['error' => $e->getMessage()]);
            throw $e;
        }
    }
    public function deleteEvent($id){
        try{
            return Event::with('inventories')->find($id)->delete();
        }catch(\Exception $e){
            Log::error("EventRepository::deleteEvent", ['error' => $e->getMessage()]);
            throw $e;
        }
    }
    public function searchEvents($searchQuery){
        try{
            
            return Event::with('inventories')->where('event_name', 'like', '%' . $searchQuery . '%')->get();
        }catch(\Exception $e){
            Log::error("EventRepository::searchEvent", ['error' => $e->getMessage()]);
            throw $e;
        }
    }
}

