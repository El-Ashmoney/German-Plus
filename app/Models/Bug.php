<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bug extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    
    use HasFactory;

    protected $fillable = [
        'model_type',
        'model_id',
        'text',
        'user_id',
    ];

}
