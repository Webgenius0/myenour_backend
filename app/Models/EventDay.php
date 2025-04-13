<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventDay extends Model
{
    protected $fillable =['event_id','day_label'];


    public function event()
{
    return $this->belongsTo(Event::class);
}

}
