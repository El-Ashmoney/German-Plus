<?php

namespace App\Models;

use App\Models\VideoSlot;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Video extends Model
{
    use HasFactory;

    protected $fillable = [
        'video_id',
        'arabic_script',
        'german_script',
        'featured_image',
    ];
    
    public function video_slots(){
        return $this->hasMany(VideoSlot::Class);
    }

}