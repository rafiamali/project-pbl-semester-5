<?php
// app/Models/LpjApprov.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $approv_id
 * @property int|null $lpj_id
 * @property int|null $user_id
 * @property int|null $role_id
 * @property string $status
 * @property string|null $catatan
 * @property string $action
 * @property \Illuminate\Support\Carbon $created_at
 * @property-read \App\Models\Lpj|null $lpj
 * @property-read \App\Models\Role|null $role
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LpjApprov newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LpjApprov newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LpjApprov query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LpjApprov whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LpjApprov whereApprovId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LpjApprov whereCatatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LpjApprov whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LpjApprov whereLpjId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LpjApprov whereRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LpjApprov whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LpjApprov whereUserId($value)
 * @mixin \Eloquent
 */
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
