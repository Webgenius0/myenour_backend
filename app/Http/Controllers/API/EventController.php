<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\EventRequest;
use App\Services\API\EventService;
use Illuminate\Http\Request;

class EventController extends Controller
{
    protected EventService $eventService;

    public function __construct(EventService $eventService)
    {
        $this->eventService = $eventService;
    }

    public function getEvents()
    {
        try {
            $events = $this->eventService->getEvents();
            return response()->json(['message' => 'Events fetched successfully', 'events' => $events], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error fetching events', 'error' => $e->getMessage()], 500);
        }
    }


    public function storeEvent(EventRequest $eventRequest)
    {

       try {
        // dd($eventRequest);

        $eventData = $eventRequest->validated();
        $event = $this->eventService->StoreEvent($eventData);
        return response()->json(['message' => 'Event created successfully', 'event' => $event], 201);
       } catch (\Exception $e) {
        return response()->json(['message' => 'Error creating event', 'error' => $e->getMessage()], 500);
       }
    }

    public function getEventById($id)
    {
        try {
            $event = $this->eventService->getEventById($id);
            return response()->json(['message' => 'Event retrieved successfully', 'event' => $event], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error retrieving event', 'error' => $e->getMessage()], 500);
        }
    }
    public function updateEvent(EventRequest $eventRequest, $id)
    {

        try {
            $eventData = $eventRequest->validated();

            $event = $this->eventService->updateEvent($eventData, $id);
            return response()->json(['message' => 'Event updated successfully', 'event' => $event], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error updating event', 'error' => $e->getMessage()], 500);
        }
    }
    public function deleteEvent($id)
    {
        try {
            $this->eventService->deleteEvent($id);
            return response()->json(['message' => 'Event deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error deleting event', 'error' => $e->getMessage()], 500);
        }
    }
    public function searchEvent(Request $request)
    {

        try {
            $searchTerm = $request->input('query');
            $events = $this->eventService->searchEvents($searchTerm);
            return response()->json(['message' => 'Events fetched successfully', 'events' => $events], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error fetching events', 'error' => $e->getMessage()], 500);
        }
    }

}
