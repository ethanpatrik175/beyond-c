<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory;
    
    use HasFactory, SoftDeletes;

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
    public function items(){
        return $this->hasmany(OrderItem::class , 'order_id','id');
    }
  
}
