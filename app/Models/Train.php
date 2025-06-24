<?php

namespace App\Models;

use App\Models\Word;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Train extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */

    protected $fillable = [
        'word_id',
        'type',
        'is_choices_random',
        'choices_order',
    ];
    public function word()
    {
        return $this->belongsTo(Word::class);
    }
    public function getIsChoicesRandomAttribute($value)
    {
        return $value;
    }

}
