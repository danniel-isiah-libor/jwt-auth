<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Laravel\Lumen\Auth\Authorizable;
use Rush\JWTAuth\Traits\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Model
{
    use HasFactory, HasApiTokens, Authorizable, HasRoles;

    protected $guard_name = 'api';

    public $incrementing = false;

    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'username',
        'password',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var string[]
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast.
     * 
     * @var array
     */
    protected $casts = [
        'id' => 'string'
    ];

    /**
     * Bootstrap the model and its traits.
     * 
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($item) {
            $item->id = Str::orderedUuid()->toString();
        });
    }
}
