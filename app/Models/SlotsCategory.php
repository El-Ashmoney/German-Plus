<?php

namespace App\Models;

use App\Models\VideoSlot;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SlotsCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image',
    ];
    public function videos_slots(){
        return $this->hasMany(VideoSlot::class, 'slots_category_id');
    }
}
