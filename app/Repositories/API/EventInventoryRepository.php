<?php

namespace App\Repositories\API;

use App\Models\EventInventoryAssignment;
use App\Models\Inventory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EventInventoryRepository implements EventInventoryRepositoryInterface
{
    public function assignInventory(array $data)
{
    DB::beginTransaction();

    try {
        foreach ($data['items'] as $item) {
            $planned = $item['planned_quantity'] ?? 0;
            $used = $item['used'] ?? 0;
            $itemId = $item['item_id'];

            $inventory = Inventory::findOrFail($itemId);

            if ($planned > $inventory->current_quantity) {
                throw new \Exception("Quantity not available for item: {$inventory->item_name}");
            }

            EventInventoryAssignment::updateOrCreate(
                [
                    'event_id' => $data['event_id'],
                    'item_id' => $itemId,
                ],
                [
                    'planned_quantity' => $planned,
                    'used' => $used,
                    'remaining' => max($planned - $used, 0),
                ]
            );

            $inventory->current_quantity -= $planned;
            $inventory->save();
        }

        DB::commit();
        return true;

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error("Inventory assignment failed", ['error' => $e->getMessage()]);
        throw $e;
    }
}


    public function getAssignedItems(int $eventId)
    {
        return EventInventoryAssignment::with(['inventory', 'event'])
        ->where('event_id', $eventId)
        ->get()
        ->map(function ($assignment) {
            // You can access event start_date like this
            $assignment->event_start_date = $assignment->event->start_date;
            return $assignment;
        });

    }

    public function updateAssignedInventory(array $data, int $id)
    {
        DB::beginTransaction();

        try {
            $eventId = $data['event_id'];

            foreach ($data['items'] as $item) {
                $planned = $item['planned_quantity'] ?? 0;
                $used = $item['used'] ?? 0;
                $itemId = $item['item_id'];

                $inventory = Inventory::findOrFail($itemId);
                $existingAssignment = EventInventoryAssignment::where('id', $id)
                    ->where('event_id', $eventId)
                    ->where('item_id', $itemId)
                    ->first();

                $previousPlanned = $existingAssignment ? $existingAssignment->planned_quantity : 0;

                // Restore the previously assigned quantity to inventory before rechecking
                $inventory->current_quantity += $previousPlanned;

                if ($planned > $inventory->current_quantity) {
                    throw new \Exception("Not enough stock available for item: {$inventory->item_name}");
                }

                if ($existingAssignment) {
                    $existingAssignment->update([
                        'planned_quantity' => $planned,
                        'used' => $used,
                        'remaining' => max($planned - $used, 0),
                    ]);
                } else {
                    EventInventoryAssignment::create([
                        'event_id' => $eventId,
                        'item_id' => $itemId,
                        'planned_quantity' => $planned,
                        'used' => $used,
                        'remaining' => max($planned - $used, 0),
                    ]);
                }

                // Deduct updated planned from inventory
                $inventory->current_quantity -= $planned;
                $inventory->save();
            }

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Inventory update failed", ['error' => $e->getMessage()]);
            throw $e;
        }
    }



    public function getAssignedInventory()
    {
        return EventInventoryAssignment::with('inventory')->get();
    }
}
