<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $table = 'inventories';
    protected $fillable=['supplier_id','item_name','quantity_in_stock','min_stock_level','max_stock_level','pack_size'];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class,'supplier_id');
    }
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
