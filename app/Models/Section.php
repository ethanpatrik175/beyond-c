<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\SectionContent;

class Section extends Model
{
    use HasFactory , SoftDeletes;

    public function single_content(){
        // dd("test");
        return $this->belongsTo(SectionContent::class,'id' ,'section_id');
    }
    public function sectioncontent(){
        // dd("test");
        return $this->hasMany(SectionContent::class,'section_id' ,'id');
    }
}
