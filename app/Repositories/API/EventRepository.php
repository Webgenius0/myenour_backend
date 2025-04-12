<?php

namespace App\Repositories\API;

use App\Models\DailyTracking;
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
    public function storeEvent(array $eventData): Event
{
    try {
        DB::beginTransaction();

        // Step 1: Extract and remove inventory items from request
        $items = $eventData['items'] ?? [];
        unset($eventData['items']);

        // Step 2: Generate total_days based on number_of_days
        $numberOfDays = $eventData['number_of_days'] ?? 1;
        $daysArray = [];

        for ($i = 1; $i <= $numberOfDays; $i++) {
            $daysArray[] = 'Day ' . $i;
        }

        $eventData['total_days'] = $daysArray;

        // Step 3: Create the event with total_days JSON
        $event = Event::create($eventData);

        // Step 4: Assign inventory items
        foreach ($items as $item) {
            $event->assignments()->create([
                'item_id' => $item['item_id'],
                'planned_quantity' => $item['planned_quantity'],
                'used' => $item['used'] ?? null,
                'remaining' => $item['remaining'] ?? null,
            ]);
        }

        DB::commit();
        return $event;

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error("EventRepository::storeEvent", ['error' => $e->getMessage()]);
        throw $e;
    }
}



 public function getEventById($id){
        try{
            return Event::findOrFail($id);
        }catch(\Exception $e){
            Log::error("EventRepository::getEventById", ['error' => $e->getMessage()]);
            throw $e;
        }
    }
    public function updateEvent(array $eventData, $id)
{
    try {
        DB::beginTransaction();

        $event = Event::findOrFail($id);

        // Extract items and remove from main payload
        $items = $eventData['items'] ?? [];
        unset($eventData['items']);

        // If number_of_days is updated, regenerate total_days
        if (isset($eventData['number_of_days'])) {
            $numberOfDays = $eventData['number_of_days'];
            $daysArray = [];

            for ($i = 1; $i <= $numberOfDays; $i++) {
                $daysArray[] = 'Day ' . $i;
            }

            $eventData['total_days'] = $daysArray;
        }

        // Update event base data
        $event->update($eventData);

        // Delete old assignments
        $event->assignments()->delete();

        // Re-insert assignments
        foreach ($items as $item) {
            $assignment=  $event->assignments()->create([
                'item_id' => $item['item_id'],
                'planned_quantity' => $item['planned_quantity'],
                'used' => $item['used'] ?? null,
                'remaining' => $item['remaining'] ?? null,
            ]);
            // Update the daily tracking for this item
            DailyTracking::where('event_id',$id)
            ->where('item_id',$item['item_id'])
            ->update(['projected_usage' => $item['planned_quantity']]);
        }

        DB::commit();
        return $event;

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error("EventRepository::updateEvent", ['error' => $e->getMessage()]);
        throw $e;
    }
}

public function deleteEvent($id)
{
    try {
        $event = Event::findOrFail($id);

        // This line is optional if you already have cascade delete on assignments
        $event->assignments()->delete();

        return $event->delete();

    } catch (\Exception $e) {
        Log::error("EventRepository::deleteEvent", ['error' => $e->getMessage()]);
        throw $e;
    }
}

    public function searchEvents($searchQuery){
        try{

            return Event::where('event_name', 'like', '%' . $searchQuery . '%')->get();
        }catch(\Exception $e){
            Log::error("EventRepository::searchEvent", ['error' => $e->getMessage()]);
            throw $e;
        }
    }
}

