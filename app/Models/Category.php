<?php

namespace App\Models;

use App\Models\User;
use App\Models\Word;
use App\Models\Article;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
    ];

    public function articles()
    {
    	return $this->hasMany(Article::class);
    }
    public function words()
    {
        return $this->hasMany(Word::class);
    }
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_category_trains')->withTimestamps();
    }
    public function userCompletedCategory($userId)
    {
        return $this->users()->where('user_id', $userId)->exists();
    }
}
