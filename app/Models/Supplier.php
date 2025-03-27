<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $table = 'suppliers';
    protected $fillable = ['supplier_id', 'supplier_name', 'item_provider', 'order_item', 'order_date', 'status'];

    protected $hidden = ['created_at', 'updated_at'];
    protected $primaryKey = 'supplier_id';
    public $incrementing = true;
    protected $keyType = 'int';
}
