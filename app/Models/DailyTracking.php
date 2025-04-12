<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyTracking extends Model
{
    protected $table = 'daily_trackings';

    protected $fillable = [
        'event_id',
        'item_id',
        'day_number',
        'projected_usage',
        'buffer_percentage',
        'remaining_start',
        'picked',
        'used',
        'remaining_end',
    ];
    public function events()
{
    return $this->belongsToMany(Event::class);
}
public function inventory()
{
    return $this->belongsToMany(Inventory::class, 'event_inventory_assignments', 'item_id', 'event_id');
}
public function eventsAssignments()
{
    return $this->belongsTo(EventInventoryAssignment::class, 'item_id', 'item_id');
}
protected $hidden = [
    'created_at',
    'updated_at',
];

}
