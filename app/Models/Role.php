<?php
// app/Models/Role.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $role_id
 * @property string $role_def
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereRoleDef($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereRoleId($value)
 * @mixin \Eloquent
 */
class Role extends Model
{
    protected $table = 'roles';
    protected $primaryKey = 'role_id';
    public $timestamps = false;

    protected $fillable = ['role_def'];

    public function users()
    {
        return $this->hasMany(User::class, 'role_id', 'role_id');
    }
}
