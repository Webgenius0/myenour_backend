<?php
namespace App\Repositories\API;

use App\Models\DailyTracking;
use App\Models\DailyTrackingView;
use App\Models\Event;
use App\Models\EventDay;
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
        DB::beginTransaction();

        $eventId = $request->input('event_id');
        $eventDayLabel = $request->input('day_number');

        if (!$eventId || !$eventDayLabel) {
            return response()->json(['error' => 'Missing event_id or day_number'], 400);
        }

        // Fetch the event day
        $eventDay = EventDay::where('event_id', $eventId)
            ->where('day_label', $eventDayLabel)
            ->first();

        if (!$eventDay) {
            DB::rollBack();
            return response()->json(['error' => 'Invalid event day label: ' . $eventDayLabel], 400);
        }

        $dayNumber = (int) filter_var($eventDay->day_label, FILTER_SANITIZE_NUMBER_INT);
        $event = Event::findOrFail($eventId);

        // Load assigned items
        $assignments = EventInventoryAssignment::with(['inventory.supplier'])
            ->where('event_id', $eventId)
            ->get();

        // Load all tracking data for that day
        $trackingData = DailyTrackingView::where('event_id', $eventId)
            ->where('day_number', $dayNumber)
            ->get()
            ->keyBy('item_id'); // key by item_id for fast lookup

        // Merge tracking into assignments
        $result = $assignments->map(function ($assignment) use ($trackingData) {
            $itemTracking = $trackingData->get($assignment->item_id);

            return [
                'item_id' => $assignment->item_id,
                'item_name' => optional($assignment->inventory)->item_name,
                'planned_quantity' => $assignment->planned_quantity,
                'projected_usage' => $assignment->planned_quantity,
                'buffer_percentage' => $itemTracking->buffer_percentage ?? 0,
                'start_of_day' => $itemTracking->remaining_start ?? ($assignment->remaining ?? 0),
                'picked' => $itemTracking->picked ?? 0,
                'used' => $itemTracking->used ?? ($assignment->used ?? 0),
                'end_of_day' => $itemTracking->remaining_end ?? ($assignment->remaining ?? 0),
                'current_quantity' => optional($assignment->inventory)->quantity ?? 0,
                'supplier_name' => optional($assignment->inventory->supplier)->name ?? '',
            ];
        });

        DB::commit();

        return response()->json([
            'event' => $event->event_name,
            'event_category_id' => $event->event_category_id, 
            'day' => $eventDayLabel,
            'items' => $result->values(),
        ]);
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error("DailyTrackingRepository::load", [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);
        return response()->json(['error' => 'Error loading Daily Tracking'], 500);
    }
}






    // Save daily tracking data

        // Check if the event is valid
         // Check if the event is valid
         public function updateTracking(array $dailyData)
{
    try {
        DB::beginTransaction();

        // Extract event ID and day label (event_day_id)
        $eventId = $dailyData['event_id'] ?? null;
        $eventDayLabel = 'Day ' . ($dailyData['event_day_id'] ?? null);  // Format it as "Day 1", "Day 2"

        // Find the event day based on event_id and day_label
        $eventDay = EventDay::where('event_id', $eventId)
            ->where('day_label', (string)$dailyData['event_day_id']) // Directly match day_number (e.g., "1")
            ->first();

        if (!$eventDay) {
            DB::rollBack();
            return response()->json(['error' => 'Invalid event day label: ' . $eventDayLabel], 400);
        }

        // Extract the day number
        $dayNumber = (int) filter_var($eventDay->day_label, FILTER_SANITIZE_NUMBER_INT);

        // Normalize items array if needed
        $items = isset($dailyData['items'][0]) ? $dailyData['items'] : [$dailyData['items']];

        // Load the event with assignments
        $event = Event::with('assignments')->findOrFail($eventId);
        $totalDays = $event->total_days ?? 3;

        foreach ($items as $data) {
            $itemId = $data['item_id'];
            $picked = $data['picked'] ?? 0;
            $end = $data['end_of_day'] ?? 0;
            $remainingStart = $data['remaining_start'] ?? null;

            // Find the item assignment
            $assignment = $event->assignments->where('item_id', $itemId)->first();

            if (!$assignment) {
                DB::rollBack();
                return response()->json(['error' => 'Item assignment not found for item_id: ' . $itemId], 400);
            }

            $projectedUsage = $assignment->planned_quantity;

            // Calculate the buffer percentage based on the day number
            if ($dayNumber === 1) {
                $bufferPercentage = ceil($projectedUsage * 0.30);
            } elseif ($dayNumber == $totalDays) {
                $bufferPercentage = ceil($projectedUsage * 0.10);
            } else {
                $bufferPercentage = ceil($projectedUsage * 0.15);
            }

            // Check for previous day's remaining_end if no remaining_start provided
            if ($remainingStart === null) {
                $previousTracking = DailyTracking::where('event_id', $eventId)
                    ->where('event_day_id', $eventDay->id) // Match by event_day_id
                    ->where('day_number', $dayNumber - 1)
                    ->where('item_id', $itemId)
                    ->first();

                $remainingStart = ($previousTracking && $dayNumber != 1) ? ($previousTracking->remaining_end ?? 0) : 0;
            }

            // Calculate used quantity
            $used = $remainingStart + $picked - $end;

            // Save current day tracking
            $currentTracking = DailyTracking::updateOrCreate(
                [
                    'event_id' => $eventId,
                    'event_day_id' => $eventDay->id,
                    'day_number' => $dayNumber,
                    'item_id' => $itemId,
                ],
                [
                    'projected_usage' => $projectedUsage,
                    'buffer_percentage' => $bufferPercentage,
                    'picked' => $picked,
                    'used' => $used,
                    'remaining_start' => $remainingStart,
                    'remaining_end' => $end,
                ]
            );

            // Deduct used quantity from inventory
            $inventory = Inventory::find($itemId);
            if ($inventory) {
                if ($inventory->current_quantity >= $used) {
                    $inventory->decrement('current_quantity', $used);
                } else {
                    DB::rollBack();
                    return response()->json(['error' => 'Insufficient stock in inventory for item_id: ' . $itemId], 400);
                }
            }

            // Auto-update next day's tracking if it exists
            $nextDayId = $dayNumber + 1;
            if ($nextDayId <= $totalDays) {
                $nextDay = EventDay::where('event_id', $eventId)
                    ->where('day_label', (string)$nextDayId) // Match by day number (e.g., "2")
                    ->first();

                if ($nextDay) {
                    $nextBuffer = 0;
                    if ($nextDayId == 1) {
                        $nextBuffer = ceil($projectedUsage * 0.30);
                    } elseif ($nextDayId == $totalDays) {
                        $nextBuffer = ceil($projectedUsage * 0.10);
                    } else {
                        $nextBuffer = ceil($projectedUsage * 0.15);
                    }

                    $nextRemainingStart = $currentTracking->remaining_end ?? 0;
                    $nextPicklistQty = $projectedUsage - $nextRemainingStart;

                    $nextTracking = DailyTracking::updateOrCreate(
                        [
                            'event_id' => $eventId,
                            'event_day_id' => $nextDay->id,
                            'day_number' => $nextDayId,
                            'item_id' => $itemId,
                        ],
                        [
                            'projected_usage' => $projectedUsage,
                            'buffer_percentage' => $nextBuffer,
                            'picked' => 0,
                            'used' => 0,
                            'remaining_start' => $nextRemainingStart,
                            'remaining_end' => 0,
                        ]
                    );
                }
            }
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
