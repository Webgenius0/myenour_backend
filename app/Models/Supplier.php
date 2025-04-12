<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $table = 'suppliers';
    protected $fillable = ['supplier_id', 'supplier_name', 'lead_time_days', 'pack_size_constraint', 'status'];

    protected $hidden = ['created_at', 'updated_at'];
    protected $primaryKey = 'supplier_id';
    public $incrementing = true;
    protected $keyType = 'int';
}
