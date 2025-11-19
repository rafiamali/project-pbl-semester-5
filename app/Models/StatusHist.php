<?php
// app/Models/StatusHist.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
