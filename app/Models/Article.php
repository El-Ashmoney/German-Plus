<?php

namespace App\Models;

use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Article extends Model
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
        'image',
        'slug',
        'category_id',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

}