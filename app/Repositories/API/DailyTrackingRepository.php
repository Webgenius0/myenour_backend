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
        $eventId = $request->input('event_id');
        $dayNumber = $request->input('day_number');

        if (!$eventId || !$dayNumber) {
            return response()->json(['error' => 'Missing event_id or day_number'], 400);
        }

        // Load the event
        $event = Event::findOrFail($eventId);

        // First check if data exists in DailyTrackingView
        $trackingData = DailyTrackingView::where('event_id', $eventId)
            ->where('day_number', $dayNumber)
            ->get();

        // If tracking data exists, map and return it
        if ($trackingData->isNotEmpty()) {
            $result = $trackingData->map(function ($row) {
                return [
                    'item_id' => $row->item_id,
                    'item_name' => $row->item_name,
                    'planned_quantity' => $row->planned_quantity,
                    'projected_usage' => $row->projected_usage,
                    'buffer_percentage' => $row->buffer_percentage,
                    'start_of_day' => $row->remaining_start,
                    'picked' => $row->picked,
                    'used' => $row->used,
                    'end_of_day' => $row->remaining_end,
                    'current_quantity' => $row->current_quantity,
                    'supplier_name' => $row->supplier_name,
                ];
            });
        } else {
            // Load from EventInventoryAssignment if no daily tracking exists
            $assignments = EventInventoryAssignment::with('inventory')
                ->where('event_id', $eventId)
                ->get();

            $result = $assignments->map(function ($assignment) {
                return [
                    'item_id' => $assignment->item_id,
                    'item_name' => optional($assignment->inventory)->item_name,
                    'planned_quantity' => $assignment->planned_quantity,
                    'projected_usage' => $assignment->planned_quantity,
                    'buffer_percentage' => 0,
                    'start_of_day' => $assignment->remaining ?? 0,
                    'picked' => 0,
                    'used' => $assignment->used ?? 0,
                    'end_of_day' => $assignment->remaining ?? 0,
                    'current_quantity' => optional($assignment->inventory)->quantity ?? 0,
                    'supplier_name' => optional($assignment->inventory->supplier)->name ?? '',
                ];
            });
        }

        return response()->json([
            'event' => $event->event_name,
            'day' => $dayNumber,
            'items' => $result->values(), // reset keys
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
         // Check if the event is valid
         public function updateTracking(array $dailyData)
         {
             try {
                 DB::beginTransaction();

                 // Ensure event_id and event_day_id exist in the request data
                 $eventId = $dailyData['event_id'] ?? null;
                 $eventDayId = $dailyData['event_day_id'] ?? null;

                 if (!$eventId || !$eventDayId) {
                     DB::rollBack();
                     return response()->json(['error' => 'event_id or event_day_id is missing'], 400);
                 }

                 // Ensure that the items key is an array
                 $items = isset($dailyData['items'][0]) ? $dailyData['items'] : [$dailyData['items']];

                 // Load the event with its assignments
                 $event = Event::with('assignments')->findOrFail($eventId);
                 $totalDays = $event->total_days ?? 3;

                 foreach ($items as $data) {
                     $itemId = $data['item_id'];
                     $picked = $data['picked'] ?? 0;
                     $end = $data['end_of_day'] ?? 0;
                     $remainingStart = $data['remaining_start'] ?? null;  // Accept remaining_start as input

                     // Fetch the item assignment for the event
                     $assignment = $event->assignments->where('item_id', $itemId)->first();

                     if (!$assignment) {
                         DB::rollBack();
                         return response()->json(['error' => 'Item assignment not found for item_id: ' . $itemId], 400);
                     }

                     $projectedUsage = $assignment->planned_quantity;

                     // Determine the buffer percentage
                     if ((int)$eventDayId === 1) {
                         $bufferPercentage = ceil($projectedUsage * 0.30);
                     } elseif ((int)$eventDayId == $totalDays) {
                         $bufferPercentage = ceil($projectedUsage * 0.10);
                     } else {
                         $bufferPercentage = ceil($projectedUsage * 0.15);
                     }

                     // Get previous day's tracking if no remaining_start is provided
                     if (!$remainingStart) {
                         $previousDay = (string)((int)$eventDayId - 1);
                         $previous = DailyTracking::where('event_id', $eventId)
                             ->where('event_day_id', $eventDayId)
                             ->where('day_number', $previousDay)
                             ->where('item_id', $itemId)
                             ->first();

                         $remainingStart = ($previous && $eventDayId != 1) ? ($previous->remaining_end ?? 0) : 0;
                     }

                     // Calculate the used quantity
                     $used = $remainingStart + $picked - $end;

                     // Save current day tracking
                     $currentTracking = DailyTracking::updateOrCreate(
                         [
                             'event_id' => $eventId,
                             'event_day_id' => $eventDayId,
                             'day_number' => $eventDayId,
                             'item_id' => $itemId,
                         ],
                         [
                             'day_number' => $eventDayId,
                             'projected_usage' => $projectedUsage,
                             'buffer_percentage' => $bufferPercentage,
                             'picked' => $picked,
                             'used' => $used,
                             'remaining_start' => $remainingStart,
                             'remaining_end' => $end,
                         ]
                     );
                     // === Deduct used quantity from main inventory ===
                     $inventory = Inventory::where('id', $itemId)->first();

                     if ($inventory) {
                         if ($inventory->current_quantity >= $used) {
                             $inventory->decrement('current_quantity', $used);
                         } else {
                             DB::rollBack();
                             return response()->json(['error' => 'Insufficient stock in inventory for item_id: ' . $itemId], 400);
                         }
                     }


                  // === AUTO UPDATE NEXT DAY IF EXISTS ===
$nextDayId = (int)$eventDayId + 1;

if ($nextDayId <= $totalDays) {
    // Find the corresponding EventDay
    $nextDay = EventDay::where('event_id', $eventId)
        ->where('day_label', $nextDayId)
        ->first();

    if ($nextDay) {
        // Buffer percentage for the next day
        $nextBuffer = 0;
        if ($nextDayId == 1) {
            $nextBuffer = ceil($projectedUsage * 0.30);
        } elseif ($nextDayId == $totalDays) {
            $nextBuffer = ceil($projectedUsage * 0.10);
        } else {
            $nextBuffer = ceil($projectedUsage * 0.15);
        }

        $nextRemainingStart = $currentTracking->remaining_end ?? 0;
        $nextPicklistQty =($projectedUsage - $nextRemainingStart);

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
                'picked' => $nextPicklistQty,
                'used' => 0,
                'remaining_start' => $nextRemainingStart,
                'remaining_end' => 0,
            ]
        );
    }}
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
