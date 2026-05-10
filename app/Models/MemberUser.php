<?php

namespace App\Models;

use App\Models\Traits\HasPermissions;
use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class MemberUser extends Authenticatable implements AuthenticatableContract, Authorizable, JWTSubject
{
    use HasDateTimeFormatter, HasFactory, HasPermissions,Notifiable;

    protected $table = 'member_users';

    public static $status_arr = [
        0 => '禁止',
        1 => '正常',
    ];

    protected $fillable = [
        'username',
        'phone',
        'email',
        'password',
        'last_login_time',
        'last_login_ip',
        'phone_verified',
        'email_verified',
        'avatar',
        'avatar_medium',
        'avatar_big',
        'gender',
        'realname',
        'signature',
        'vip_id',
        'vip_expire',
        'nickname',
        'status',
        'balance',
        'freeze_price',
        'points',
        'group_id',
        'delete_at_time',
        'is_deleted',
        'message_count',
        'register_ip',
        'is_certified',
        'parent_id',
        'temp_parent_id',
        'junior_at',
    ];

    protected $casts = [
        'last_login_time' => 'datetime',
        'phone_verified' => 'boolean',
        'email_verified' => 'boolean',
        'gender' => 'integer',
        'vip_id' => 'integer',
        'vip_expire' => 'datetime',
        'status' => 'integer',
        'balance' => 'decimal:2',
        'freeze_price' => 'decimal:2',
        'points' => 'integer',
        'group_id' => 'integer',
        'delete_at_time' => 'integer',
        'is_deleted' => 'boolean',
        'message_count' => 'integer',
        'is_certified' => 'boolean',
        'parent_id' => 'integer',
        'temp_parent_id' => 'integer',
        'junior_at' => 'datetime',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'passwordSalt',
    ];

    /**
     * Create a new Eloquent model instance.
     */
    public function __construct(array $attributes = [])
    {

        parent::__construct($attributes);
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
