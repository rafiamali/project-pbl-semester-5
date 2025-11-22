<?php
// app/Models/TorApprov.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $approv_id
 * @property int|null $tor_id
 * @property int|null $user_id
 * @property int|null $role_id
 * @property string $status
 * @property string|null $catatan
 * @property string $action
 * @property \Illuminate\Support\Carbon $created_at
 * @property-read \App\Models\Role|null $role
 * @property-read \App\Models\Tor|null $tor
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TorApprov newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TorApprov newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TorApprov query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TorApprov whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TorApprov whereApprovId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TorApprov whereCatatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TorApprov whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TorApprov whereRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TorApprov whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TorApprov whereTorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TorApprov whereUserId($value)
 * @mixin \Eloquent
 */
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
