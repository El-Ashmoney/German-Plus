<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Option extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'name',
        'value',
        'user_id',
        'created_at',
        'updated_at',
    ];

}