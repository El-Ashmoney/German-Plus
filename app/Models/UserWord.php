<?php

namespace App\Models;

use App\Models\Word;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserWord extends Model
{
    use HasFactory;

    protected $fillable = [
        'german',
        'arabic',
        'note',
        'image',
        'user_id',
    ];
    
    public function word()
    {
        return $this->belongsTo(Word::class);
    }
}