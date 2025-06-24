<?php

namespace App\Models;

use App\Models\Article;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'comment',
        'approved',
        'article_id',
    ];

    public function article()
    {
        return $this->belongsTo(Article::class);
    }
}