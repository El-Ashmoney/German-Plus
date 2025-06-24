<?php

namespace App\Models;

use App\Models\Video;
use App\Models\SlotsCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VideoSlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'video_id',
        'start_time',
        'end_time',
        'featured_sentence',
    ];

    public function video(){
        return $this->belongsTo(Video::class);
    }
    public function slots_category(){
        return $this->belongsTo(SlotsCategory::class);
    }

}
