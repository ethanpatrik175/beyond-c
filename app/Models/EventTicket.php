<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventTicket extends Model
{
    use HasFactory;

    public function addedBy()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function events()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }


    // public function updatedBy()
    // {
    //     return $this->belongsTo(User::class, 'updated_by');
    // }

    // public function deletedBy()
    // {
    //     return $this->belongsTo(User::class, 'deleted_by');
    // }
}
