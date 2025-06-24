<?php

namespace App\Models;

use App\Models\Role;
use App\Models\Word;
use App\Models\Levels;
use App\Models\Category;
use App\Models\UserWord;
use Carbon\Carbon;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'roles' => 'array',
    ];

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function user_words()
    {
        return $this->hasMany(UserWord::class);
    }

    public function favourites()
    {
        return $this->belongsToMany(Word::class, 'favourites')->withTimestamps();
    }

    public function importants()
    {
        return $this->belongsToMany(Word::class, 'importants')->withTimestamps();
    }

    public function evaluations()
    {
        return $this->belongsToMany(Word::class, 'evaluations')->withTimestamps();
    }
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'user_category_trains')->withTimestamps();
    }
    /**
    * @param string|array $roles
    */
    public function authorizeRoles($roles)
    {
        if (is_array($roles)) {
            return $this->hasAnyRole($roles) || abort(401, 'This action is unauthorized.');
        }
        return $this->hasRole($roles) || abort(401, 'This action is unauthorized.');
    }

    /**
    * Check multiple roles
    * @param array $roles
    */
    public function hasAnyRole($roles)
    {
        return null !== $this->roles()->whereIn('name', $roles)->first();
    }

    /**
    * Check one role
    * @param string $role
    */
    public function hasRole($role)
    {
        return null !== $this->roles()->where('name', $role)->first();
    }
    public function getCreatedAtAttribute($date)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('Y/m');
    }

    public function getUpdatedAtAttribute($date)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('Y');
    }
    public function getLevelAttribute(){
        // no points so first level
        if($this->points == 0){
            return Levels::all()->sortBy('index')->first();
        }
        $points_per_level = 100;
        $levels_count = Levels::all()->count();
        $total_level_points = $levels_count*$points_per_level;
        // more than max points so last level
        if($this->points >= $total_level_points ){
            return Levels::all()->sortBy('index')->last();
        }
        // ex: complete .9 of total levels
        $percentage = $this->points / $total_level_points;
        // ex: .8*10 = 8 or .781*10 = 7.81
        $level = $percentage*$levels_count; // float
        // ex: 9.19 => 9 or 9.69 => 9
        $index = floor($level);
        return Levels::where('index','=', $index)->first();
    }
    public function getAvatarImageAttribute($value){
        if(empty($value)){
            return 'avatar.png';
        }
        return $value;
    }
    public function getPointsAttribute($value){
        if(empty($value)){
            return 0;
        }
        return $value;
    }

}
