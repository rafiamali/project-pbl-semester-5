<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tor extends Model
{
    use SoftDeletes;

    protected $table = 'tor';
    protected $primaryKey = 'idTor';

    const UPDATED_AT = 'updatedAt';
    const DELETED_AT = 'deletedAt';
    public $timestamps = false;

    protected $fillable = [
        'idPengguna',
        'idKategori',
        'judulKegiatan',
        'tujuanKegiatan',
        'jadwalMulai',
        'jadwalSelesai',
        'jumlahAnggaranDiajukan',
        'status',
        'tanggalPengajuan',
    ];

    protected $casts = [
        'jadwalMulai' => 'date',
        'jadwalSelesai' => 'date',
        'tanggalPengajuan' => 'date',
        'jumlahAnggaranDiajukan' => 'double',
    ];

    // Relationships
    public function pengguna()
    {
        return $this->belongsTo(User::class, 'idPengguna', 'idUser');
    }

    public function riwayatStatus()
    {
        return $this->hasMany(RiwayatStatus::class, 'idTor');
    }

    public function lampiran()
    {
        return $this->hasMany(Lampiran::class, 'idTor');
    }

    public function lpj()
    {
        return $this->hasOne(Lpj::class, 'idTor');
    }

    /**
     * Submit TOR
     *
     * @param int $idUserAksi
     */
    public function ajukan($idUserAksi)
    {
        $this->status = 'diajukan';
        $this->tanggalPengajuan = now();
        $this->save();

        $this->updateStatus('diajukan', 'TOR diajukan untuk persetujuan', $idUserAksi);
    }

    /**
     * Update TOR status
     *
     * @param string $statusBaru
     * @param string|null $catatan
     * @param int $idUserAksi
     */
    public function updateStatus($statusBaru, $catatan = null, $idUserAksi)
    {
        $this->status = $statusBaru;
        $this->save();

        RiwayatStatus::create([
            'idTor' => $this->idTor,
            'idUserAksi' => $idUserAksi,
            'statusBaru' => $statusBaru,
            'catatan' => $catatan,
        ]);
    }

    /**
     * Add attachment
     *
     * @param array $fileData
     */
    public function tambahLampiran($fileData)
    {
        return Lampiran::create([
            'idTor' => $this->idTor,
            'namaFile' => $fileData['namaFile'],
            'pathFile' => $fileData['pathFile'],
            'tipeFile' => $fileData['tipeFile'],
            'ukuranFile' => $fileData['ukuranFile'],
        ]);
    }

    /**
     * Get all attachments
     */
    public function getLampiran()
    {
        return $this->lampiran;
    }

    /**
     * Get status history
     */
    public function getRiwayatStatus()
    {
        return $this->riwayatStatus()
            ->with('userAksi')
            ->orderBy('timestampAksi', 'desc')
            ->get();
    }
}
