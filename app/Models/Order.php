<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
  protected $table = 'orders';
  protected $fillable = [
    'supplier_id',
    'item_id',
    'order_quantity',
    'status',
  ];
  protected $hidden = [
    'created_at',
    'updated_at',
    'deleted_at',
  ];

  public function supplier()
  {
    return $this->belongsTo(Supplier::class, 'supplier_id', 'supplier_id');
  }
  public function inventory()
  {
      return $this->belongsTo(Inventory::class, 'item_id', 'id');
  }


}
