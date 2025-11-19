<?php
// app/Models/TorApprov.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TorApprov extends Model
{
    protected $table = 'tor_approv';
    protected $primaryKey = 'approv_id';

    const UPDATED_AT = null;

    protected $fillable = [
        'tor_id',
        'user_id',
        'role_id',
        'status',
        'action',
        'catatan',
    ];

    public function tor()
    {
        return $this->belongsTo(Tor::class, 'tor_id', 'tor_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'role_id');
    }
}
