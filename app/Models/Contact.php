<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;
    public function addedBy()
    {
        return $this->belongsTo('App\Models\User', 'added_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo('App\Models\User', 'updated_by');
    }

    public function deletedBy()
    {
        return $this->belongsTo('App\Models\User', 'deleted_by');
    }

}
