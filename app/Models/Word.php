<?php

namespace App\Models;

use DB;
use App\Models\Train;
use App\Models\Category;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Word extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'german',
        'arabic',
        'english',
        'image',
        'note',
        'is_favourite',
        'is_important',
        'is_valid',
        'memorize_rank',
        'sound',
    ];

    protected $appends = [
        'is_favourite',
        'is_important',
        'memorize_rank',
        'sound_url',
        'image_url'
    ];
    
    protected $with=['trains'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function is_favourite()
    {
        $user = Auth::user();
        if(!$user){
            $user = JWTAuth::parseToken()->authenticate();
        }
        if($user) {
            $exists = $user->favourites->contains($this->id);
            return (int)$exists;
        }
    }

    public function is_important()
    {
        $user = Auth::user();
        if(!$user){
            $user = JWTAuth::parseToken()->authenticate();
        }
        if($user) {
            $exists = $user->importants->contains($this->id);
            return (int)$exists;
        }
        // $user = JWTAuth::parseToken()->authenticate();
    }

    public function getIsFavouriteAttribute()
    {
        return $this->is_favourite();
    }

    public function getIsImportantAttribute()
    {
        return $this->is_important();
    }

    public function getMemorizerankAttribute()
    {
        return db::table('evaluations')->where('word_id', $this->id)->count();
    }

    public function getSoundAttribute($value)
    {
        if(!empty($value) && strpos($value, ':') ){
            $sound = str_replace('[', '', $value);
            $sound = str_replace(']', '', $sound);
            $sound = explode(':', $sound)[1];
            return $sound;
        }
        return $value;
    }

    public function getSoundUrlAttribute(){
        if(!empty($this->sound)){
            return asset('sounds/'.$this->sound);
        }
        return '';
    }

    public function trains()
    {
        return $this->hasMany(Train::class);
    }

    public function getImageUrlAttribute()
    {
        if($this->image)
            return asset('images/words/'.$this->image);
        return '';
    }
}
