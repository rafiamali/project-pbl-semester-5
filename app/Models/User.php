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
    protected $primaryKey = 'idUser';

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';
    const DELETED_AT = 'deletedAt';

    protected $fillable = [
        'ssoId',
        'namaLengkap',
        'email',
        'role',
    ];

    protected $hidden = [
        'password',
    ];

    /**
     * Get JWT identifier
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Get JWT custom claims
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * Check if user is admin
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is department head
     */
    public function isKepalaDepartemen()
    {
        return $this->role === 'kepala_departemen';
    }

    /**
     * Get user's TOR submissions
     */
    public function ajukanTor()
    {
        return $this->hasMany(Tor::class, 'idPengguna');
    }

    /**
     * Get user's budget inputs
     */
    public function ajukanAnggaran()
    {
        return $this->hasMany(AnggaranTahunan::class, 'idUserInput');
    }

    /**
     * Setup document for verification
     */
    public function setupDokumen()
    {
        // Logic untuk setup dokumen
    }

    /**
     * Verify document
     */
    public function verifikasiDokumen()
    {
        // Logic untuk verifikasi dokumen
    }

    /**
     * Reject document
     */
    public function tolakDokumen()
    {
        // Logic untuk tolak dokumen
    }
}
