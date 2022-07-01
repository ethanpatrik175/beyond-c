<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TravelPackage extends Model
{
    use HasFactory ,SoftDeletes;

    public function travel_type(){
        return $this->belongsTo(PackageType::class, 'package_type_id', 'id');
    }
    public function tags(){
        return $this->hasmany(TravelTags::class, 'travel_package_id', 'id');
    }

}
