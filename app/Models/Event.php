<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $table = 'events';


    protected $fillable = [
        'event_name', 'start_event_date', 'end_event_date', 'total_event_days', 'status'
    ];
    public function inventories()
    {
        return $this->belongsToMany(Inventory::class, 'event_inventory');
    }
    protected $casts = [
        'inventory_id' => 'array', // Automatically converts JSON to array
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
