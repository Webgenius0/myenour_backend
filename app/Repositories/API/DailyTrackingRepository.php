<?php
namespace App\Repositories\API;

use App\Models\DailyTracking;
use App\Models\Event;
use App\Models\EventInventoryAssignment;
use App\Models\Inventory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DailyTrackingRepository implements DailyTrackingRepositoryInterface
{
    public function load(Request $request)
{
    try {
        $eventId = $request->input('event_id');
        $dayNumber = $request->input('day_number');

        if (!$eventId || !$dayNumber) {
            return response()->json(['error' => 'Missing event_id or day_number'], 400);
        }

        // Load the event
        $event = Event::findOrFail($eventId);

        // Load assigned inventory for the event
        $assignedItems = $event->assignments;


        // Load daily tracking data
        $dailyTrackings = DailyTracking::where('event_id', $eventId)
            ->where('day_number', $dayNumber)
            ->get()
            ->keyBy('item_id');


        // Prepare result per inventory item
        $result = $assignedItems->map(function ($assignment) use ($dailyTrackings) {
            $tracking = $dailyTrackings->get($assignment->item_id);
            // dd($tracking);
            return [
                'item_id' => $assignment->item_id,
                'planned_quantity' => $assignment->planned_quantity,
                'projected_usage' => $tracking ? $tracking->projected_usage : $assignment->planned_quantity,
                'buffer_percentage' => $tracking ? $tracking->buffer_percentage : 0,
                'start_of_day' => $tracking ? $tracking->remaining_start : 0,
                'picked' => $tracking ? $tracking->picked : 0,
                'used' => $tracking ? $tracking->used : 0,
                'end_of_day' => $tracking ? $tracking->remaining_end : 0,
            ];
        });

        return response()->json([
            'event' => $event->event_name,
            'day' => $dayNumber,
            'items' => $result,
        ]);
    } catch (\Exception $e) {
        Log::error("DailyTrackingRepository::load", [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);
        return response()->json(['error' => 'Error loading Daily Tracking'], 500);
    }
}



    // Save daily tracking data

        // Check if the event is valid
        public function updateTracking(array $dailyData)
{
    try {
        // dd($dailyData);
        DB::beginTransaction();

        $eventId = $dailyData['event_id'];
        $dayNumber = $dailyData['day_number'];
        $items = isset($dailyData['items'][0]) ? $dailyData['items'] : [$dailyData['items']];

        $event = Event::with('assignments')->findOrFail($eventId);

        foreach ($items as $data) {
            $itemId = $data['item_id'];
            $picked = $data['picked'] ?? 0;
            $end = $data['end_of_day'] ?? 0;

            $assignment = $event->assignments->where('item_id', $itemId)->first();

            if (!$assignment) {
                DB::rollBack();
                return response()->json(['error' => 'Item assignment not found for item_id: ' . $itemId], 400);
            }
            // dd($assignment);

            $projectedUsage = $assignment->planned_quantity;
            $totalDays = $event->total_days ?? 3; // Event table e jodi total_days thake

            // Buffer percentage based on day
            if ((int)$dayNumber === 1) {
                $bufferPercentage = ceil($projectedUsage * 0.30); // First Day: 30%
            } elseif ((int)$dayNumber == $totalDays) {
                $bufferPercentage = ceil($projectedUsage * 0.10); // Last Day: 10%
            } else {
                $bufferPercentage = ceil($projectedUsage * 0.15); // Middle Days: 15%
            }

            $dayNumber = (string) $dailyData['day_number'];
$previousDay = (string)((int)$dayNumber - 1);

            $previous = DailyTracking::where('event_id', $eventId)
    ->where('day_number', $previousDay)
    ->where('item_id', $itemId)
    ->first();

            $start = $dayNumber == '1'

                ? $projectedUsage
                : ($previous->used ?? $projectedUsage);

            if ($picked > $start) {
                DB::rollBack();
                return response()->json(['error' => 'Picked quantity cannot exceed start of day quantity'], 400);
            }

            $used = $start-$end;

// dd($used,$dayNumber,$itemId);
DailyTracking::updateOrCreate(
    [
        'event_id' => $eventId,
        'day_number' => $dayNumber,
        'item_id' => $itemId,
    ],
    [
        'day_number' => $dayNumber,
        'projected_usage' => $projectedUsage,
        'buffer_percentage' => $bufferPercentage,
        'picked' => $picked,
        'used' => $used,
        'remaining_start' => $start,
        'remaining_end' => $end,
    ]
);

        }

        DB::commit();
        return response()->json(['message' => 'Daily Tracking updated successfully'], 200);

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error("DailyTrackingRepository::updateTracking", [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);
        return response()->json(['error' => 'Error updating Daily Tracking'], 500);
    }
}




}
