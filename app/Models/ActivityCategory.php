<?php
// app/Models/ActivityCategory.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $category_id
 * @property string $category_def
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Tor> $tors
 * @property-read int|null $tors_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityCategory onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityCategory whereCategoryDef($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityCategory whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityCategory whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityCategory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityCategory withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ActivityCategory withoutTrashed()
 * @mixin \Eloquent
 */
class ActivityCategory extends Model
{
    use SoftDeletes;

    protected $table = 'activity_category';
    protected $primaryKey = 'category_id';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    const DELETED_AT = 'deleted_at';

    protected $fillable = ['category_def'];

    public function tors()
    {
        return $this->hasMany(Tor::class, 'category_id', 'category_id');
    }
}
