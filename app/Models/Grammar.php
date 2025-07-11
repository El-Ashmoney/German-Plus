<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Grammar extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    use HasFactory;

    protected $fillable = [
        'title',
        'content',
    ];
}