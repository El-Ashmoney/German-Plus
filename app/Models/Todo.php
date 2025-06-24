<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Todo extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'is_done',
        'priority',
    ];
    
    function getCreatedSinceAttribute()
    {
        return $this->created_at->diffForHumans(Carbon::now());
    }
    function getColorSinceAttribute()
    {
        $interval = $this->created_at->diff(Carbon::now());
        $days = $interval->format('%a');
        if($days>1){
            return 'danger';
        }
        return 'success';
    }
}