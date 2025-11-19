<?php
// app/Models/Attachment.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

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
