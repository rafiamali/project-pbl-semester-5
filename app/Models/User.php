<?php
// app/Models/User.php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * @property int $user_id
 * @property string $full_name
 * @property string $email
 * @property int|null $role_id
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Lpj> $lpjs
 * @property-read int|null $lpjs_count
 * @property-read \App\Models\Role|null $role
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Tor> $tors
 * @property-read int|null $tors_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutTrashed()
 * @method bool hasRole(string|array $roleNames)
 * @method bool isMahasiswa()
 * @method bool isDosen()
 * @method bool isSekretaris()
 * @method bool isAdmin()
 * @method bool isKetua()
 * @mixin \Eloquent
 */
class User extends Authenticatable implements JWTSubject
{
    use SoftDeletes;

    protected $table = 'users';
    protected $primaryKey = 'user_id';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    const DELETED_AT = 'deleted_at';

    protected $fillable = [
        'full_name',
        'email',
        'role_id',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    // Relationship
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'role_id');
    }

    public function tors()
    {
        return $this->hasMany(Tor::class, 'user_id', 'user_id');
    }

    public function lpjs()
    {
        return $this->hasMany(Lpj::class, 'user_id', 'user_id');
    }

    // Helper methods
    public function hasRole($roleNames)
    {
        if (is_array($roleNames)) {
            return in_array($this->role->role_def, $roleNames);
        }
        return $this->role->role_def === $roleNames;
    }

    public function isMahasiswa()
    {
        return $this->role->role_def === 'mahasiswa';
    }

    public function isDosen()
    {
        return $this->role->role_def === 'dosen';
    }

    public function isSekretaris()
    {
        return $this->role?->role_def === 'sekretaris jurusan';
    }

    public function isAdmin()
    {
        return $this->role?->role_def === 'admin jurusan';
    }

    public function isKetua()
    {
        return $this->role?->role_def === 'ketua jurusan';
    }
}
