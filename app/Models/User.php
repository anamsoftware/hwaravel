<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'uid',
        'first_name',
        'last_name',
        'full_name',
        'username',
        'email',
        'email_verified_at',
        'password',
        'active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
    ];

    protected $dates = [
        'created_at'
    ];

    /**
     * Set email to lower
     *
     * @param $value
     */
    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = strtolower(trim($value));
    }

    /**
     * Get full name
     *
     * @return mixed|string
     */
    public function getFullNameAttribute()
    {
        return $this->attributes['full_name'] ?? "{$this->attributes['first_name']} {$this->attributes['last_name']}";
    }

    /**
     * Bootstrap the model and its traits.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function (Model $model) {
            $model->setAttribute('uid', uniqid());
            $model->setAttribute('full_name', "{$model->attributes['first_name']} {$model->attributes['last_name']}");
        });
    }

    /**
     * @return HasMany
     */
    public function user_metas()
    {
        return $this->hasMany(UserMeta::class, 'user_id');
    }

    /**
     * Find user by email
     *
     * @param $email
     * @return mixed
     */
    public static function findByEmail($email)
    {
        if (empty($email)) return false;

        return self::whereEmail($email)->first();
    }

    /**
     * Find user by username
     *
     * @param $username
     * @return mixed
     */
    public static function findByUserName($username)
    {
        if (empty($username)) return false;

        return self::whereUsername($username)->first();
    }

    /**
     * Find user by id with meta data
     *
     * @param $id
     * @return array|false
     */
    public static function findUserMetaByUserId($id)
    {
        if (empty($id) || !is_numeric($id)) {
            return false;
        } else {
            $user = self::find($id);
            if (!$user) {
                return false;
            }
            unset($user['user_metas']);
            return array_merge($user->toArray(), array_combine(array_column($user->user_metas->toArray(), 'meta_key'), array_column($user->user_metas->toArray(), 'meta_value')));
        }
    }

    /**
     * Find user by id with meta data
     *
     * @param $uid
     * @return array|false
     */
    public static function findUserMetaByUid($uid)
    {
        if (empty($uid) || !$user = self::with(['user_metas'])->whereUid($uid)->first()) {
            return false;
        } else {
            unset($user['user_metas']);
            return array_merge($user->toArray(), array_combine(array_column($user->user_metas->toArray(), 'meta_key'), array_column($user->user_metas->toArray(), 'meta_value')));
        }
    }

    /**
     * Get users with meta data
     *
     * @return array
     */
    public static function getUserWithMeta()
    {
        $users = self::with('user_metas')
            ->orderBy('id', 'DESC')
            ->get(['id', 'full_name', 'username', 'email', 'active', 'created_at']);
        $data = [];
        foreach ($users as $user) {
            unset($user['user_metas']);
            $data[] = array_merge($user->toArray(), array_combine(array_column($user->user_metas->toArray(), 'meta_key'), array_column($user->user_metas->toArray(), 'meta_value')));
        }
        return $data;
    }

}
