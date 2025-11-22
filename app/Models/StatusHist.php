<?php
// app/Models/StatusHist.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $hist_id
 * @property string $status
 * @property string|null $catatan
 * @property \Illuminate\Support\Carbon $timestamp_aksi
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $user_id
 * @property int|null $tor_id
 * @property int|null $lpj_id
 * @property-read \App\Models\Lpj|null $lpj
 * @property-read \App\Models\Tor|null $tor
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StatusHist newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StatusHist newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StatusHist onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StatusHist query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StatusHist whereCatatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StatusHist whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StatusHist whereHistId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StatusHist whereLpjId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StatusHist whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StatusHist whereTimestampAksi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StatusHist whereTorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StatusHist whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StatusHist withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StatusHist withoutTrashed()
 * @mixin \Eloquent
 */
class StatusHist extends Model
{
    use SoftDeletes;

    protected $table = 'status_hist';
    protected $primaryKey = 'hist_id';

    const DELETED_AT = 'deleted_at';
    public $timestamps = false;

    protected $fillable = [
        'tor_id',
        'lpj_id',
        'user_id',
        'status',
        'catatan',
    ];

    protected $casts = [
        'timestamp_aksi' => 'datetime',
    ];

    public function tor()
    {
        return $this->belongsTo(Tor::class, 'tor_id', 'tor_id');
    }

    public function lpj()
    {
        return $this->belongsTo(Lpj::class, 'lpj_id', 'lpj_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
