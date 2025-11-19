<?php
// app/Models/LpjApprov.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LpjApprov extends Model
{
    protected $table = 'lpj_approv';
    protected $primaryKey = 'approv_id';

    const UPDATED_AT = null;

    protected $fillable = [
        'lpj_id',
        'user_id',
        'role_id',
        'status',
        'action',
        'catatan',
    ];

    public function lpj()
    {
        return $this->belongsTo(Lpj::class, 'lpj_id', 'lpj_id');
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
