<?php

namespace App\Repositories\API;

use App\Models\DailyTracking;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EventRepository implements EventRepositoryInterface
{
    public function getEvents(array $data)
    {
        try {
            $query = Event::with('inventories');

            if (!empty($data['event_name'])) {
                $query->where('event_name', 'like', '%' . $data['event_name'] . '%');
            }

            if (!empty($data['start_date'])) {
                $query->whereDate('start_date', $data['start_date']);
            }

            if (!empty($data['status'])) {
                $query->where('status', $data['status']);
            }

            if (!empty($data['number_of_days'])) {
                $query->where('number_of_days', $data['number_of_days']);
            }

            if (!empty($data['total_days'])) {
                $query->where('total_days', $data['total_days']);
            }

            return $query->paginate(10); // or ->get() if no pagination needed

        } catch (\Exception $e) {
            Log::error("EventRepository::getEvents", ['error' => $e->getMessage()]);
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

            // Step 2: Extract number_of_days and start_date
            $numberOfDays = $eventData['number_of_days'] ?? 1;
            $startDate = Carbon::parse($eventData['start_date']);

            // Step 3: Generate and attach total_days
            $totalDays = [];
            for ($i = 1; $i <= $numberOfDays; $i++) {
                $totalDays[] = 'day ' . $i;
            }
            $eventData['total_days'] = $totalDays;

            // Step 4: Create the event
            $event = Event::create($eventData);

            // ✅ Step 5: Create eventDays records in DB
            for ($i = 1; $i <= $numberOfDays; $i++) {
                $event->eventDays()->create([
                    'day_label' => 'day ' . $i,
                    'date' => $startDate->copy()->addDays($i - 1),
                ]);
            }

            // Step 6: Assign inventory items
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
            return Event::with('inventories')->findOrFail($id);
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
                $daysArray[] = 'day ' . $i;
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
    public function getAllEventList()
    {
        try {
            $events = Event::select('id', 'event_name','number_of_days')->get();
            return $events;

        } catch (\Exception $e) {
            Log::error("EventRepository::getAllEventList", ['error' => $e->getMessage()]);
            throw $e;
        }
    }
}

