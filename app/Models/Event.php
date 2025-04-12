<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $table = 'events';


    protected $fillable = [
        'event_name', 'start_date', 'number_of_days', 'status', 'total_days',
    ];
    public function inventories()
    {
        return $this->belongsToMany(Inventory::class, 'event_inventory_assignments', 'event_id', 'item_id')
            ->withPivot('planned_quantity', 'used', 'remaining')
            ->withTimestamps();
    }
    protected $casts = [
        'inventory_id' => 'array', // Automatically converts JSON to array
        'total_days' => 'array', // Automatically converts JSON to array
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function assignments()
    {
        return $this->hasMany(EventInventoryAssignment::class);
    }
    public function dailyTracking()
    {
        return $this->hasMany(DailyTracking::class);
    }
}
