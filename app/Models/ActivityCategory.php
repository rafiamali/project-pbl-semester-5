<?php
// app/Models/ActivityCategory.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
