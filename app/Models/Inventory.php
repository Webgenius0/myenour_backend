<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $table = 'inventories';
    protected $fillable=['supplier_id','item_name','current_quantity','min_stock_level','max_stock_level','incoming_stock','pack_size'];


    public function supplier()
    {
        return $this->belongsTo(Supplier::class,'supplier_id');
    }
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_inventory_assignments', 'item_id', 'event_id')
            ->withPivot('quantity')
            ->withTimestamps();
    }
}
