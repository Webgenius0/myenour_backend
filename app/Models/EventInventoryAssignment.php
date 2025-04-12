<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventInventoryAssignment extends Model
{
    protected $fillable = ['event_id', 'item_id', 'planned_quantity', 'used', 'remaining'];
    public function inventory()
    {
        return $this->belongsTo(Inventory::class, 'item_id');
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    protected $hidden = ['created_at', 'updated_at'];
}
