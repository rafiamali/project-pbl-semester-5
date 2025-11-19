<?php

// app/Models/AnggaranTahunan.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AnggaranTahunan extends Model
{
    use SoftDeletes;

    protected $table = 'anggaran_tahunan';
    protected $primaryKey = 'idAnggaranTahunan';

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';
    const DELETED_AT = 'deletedAt';

    protected $fillable = [
        'tahun',
        'totalPaguAnggaran',
        'idUserInput',
    ];

    protected $casts = [
        'tahun' => 'integer',
        'totalPaguAnggaran' => 'double',
    ];

    /**
     * Get user who input this budget
     */
    public function userInput()
    {
        return $this->belongsTo(User::class, 'idUserInput', 'idUser');
    }

    /**
     * Calculate total disbursement
     */
    public function hitungTotalPenyerapan()
    {
        // Calculate total from approved TORs
        return Tor::where('status', 'disetujui')
            ->whereYear('tanggalPengajuan', $this->tahun)
            ->sum('jumlahAnggaranDiajukan');
    }

    /**
     * Get remaining budget
     */
    public function getSisaAnggaran()
    {
        $totalPenyerapan = $this->hitungTotalPenyerapan();
        return $this->totalPaguAnggaran - $totalPenyerapan;
    }
}
