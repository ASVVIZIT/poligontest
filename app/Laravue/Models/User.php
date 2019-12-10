<?php

namespace App\Laravue\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * Class User
 *
 * @property string $onlineStatus
 * @property string $name
 * @property string $surname
 * @property string $firstname
 * @property string $patronymic
 * @property string $gender
 * @property string $family_status
 * @property string $avatar
 * @property string $phone1
 * @property string $phone2
 * @property string $phone3
 * @property string $phone4
 * @property string $skype
 * @property text $address1
 * @property text $address2
 * @property data $birthday
 * @property data $deleted_at
 * @property string $email
 * @property string $password
 * @property Role[] $roles
 *
 * @method static User create(array $user)
 * @package App
 */
class User extends Authenticatable implements JWTSubject
{
    use Notifiable, HasRoles, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'onlineStatus',
        'name',
        'surname',
        'firstname',
        'patronymic',
        'gender',
        'family_status',
        'avatar',
        'phone1',
        'phone2',
        'phone3',
        'phone4',
        'skype',
        'address1',
        'address2',
        'birthday',
        'deleted_at',
        'email',
        'password',
    ];

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
    ];

    /**
     * Set permissions guard to API by default
     * @var string
     */
    protected $guard_name = 'api';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * @inheritdoc
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * @inheritdoc
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * @return bool
     */
    public function isAdmin(): bool
    {
        foreach ($this->roles  as $role) {
            if ($role->isAdmin()) {
                return true;
            }
        }
        return false;
    }
    /**
     * @return bool
     */
    public function isModerator(): bool
    {
        foreach ($this->roles  as $role) {
            if ($role->isModerator()) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check is user online.
     * @return bool
     */
    public function isOnline()
    {
        return Cache::has('user-is-online-' . $this->id);
    }

    public function order()
    {
        return $this->hasMany('Order', 'user_id', 'id');
    }
}
