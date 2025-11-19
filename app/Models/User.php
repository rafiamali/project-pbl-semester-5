<?php
// app/Models/User.php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tymon\JWTAuth\Contracts\JWTSubject;

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
        return $this->role->role_def === 'sekretaris jurusan';
    }

    public function isAdmin()
    {
        return $this->role->role_def === 'admin jurusan';
    }

    public function isKetua()
    {
        return $this->role->role_def === 'ketua jurusan';
    }
}
