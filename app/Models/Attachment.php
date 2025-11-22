<?php
// app/Models/Attachment.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

/**
 * @property int $attach_id
 * @property string $file_path
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $tor_id
 * @property int|null $lpj_id
 * @property-read \App\Models\Lpj|null $lpj
 * @property-read \App\Models\Tor|null $tor
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attachment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attachment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attachment onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attachment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attachment whereAttachId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attachment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attachment whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attachment whereFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attachment whereLpjId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attachment whereTorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attachment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attachment withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Attachment withoutTrashed()
 * @mixin \Eloquent
 */
class Attachment extends Model
{
    use SoftDeletes;

    protected $table = 'attachment';
    protected $primaryKey = 'attach_id';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    const DELETED_AT = 'deleted_at';

    protected $fillable = [
        'file_path',
        'tor_id',
        'lpj_id',
    ];

    public function tor()
    {
        return $this->belongsTo(Tor::class, 'tor_id', 'tor_id');
    }

    public function lpj()
    {
        return $this->belongsTo(Lpj::class, 'lpj_id', 'lpj_id');
    }

    /**
     * Download file
     */
    public function download()
    {
        if (Storage::exists($this->file_path)) {
            return Storage::download($this->file_path);
        }
        throw new \Exception('File not found');
    }

    /**
     * Delete file
     */
    public function deleteFile()
    {
        if (Storage::exists($this->file_path)) {
            Storage::delete($this->file_path);
        }
        $this->delete();
    }
}
