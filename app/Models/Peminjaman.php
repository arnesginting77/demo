<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    protected $table = 'peminjaman';
    protected $guarded = [];
    public $timestamps = false;
    protected $casts = ['tgl_pinjam' => 'date', 'jatuh_tempo' => 'date'];

    public function siswa() { return $this->belongsTo(Siswa::class); }
    public function details() { return $this->hasMany(DetailPeminjaman::class); }

    public function getStatusTampilAttribute(): string
    {
        return $this->status === 'Dipinjam' && $this->jatuh_tempo->lt(today()) ? 'Terlambat' : $this->status;
    }

    public function getBadgeAttribute(): string
    {
        return ['Dipinjam' => 'wa', 'Terlambat' => 'bad', 'Ditolak' => 'bad', 'Selesai' => 'ok'][$this->status_tampil] ?? '';
    }

    public function getItemsAttribute(): string
    {
        return $this->details->map(fn ($d) => $d->barang->nama.' ×'.$d->jumlah)->implode(', ');
    }

    public function getKeteranganAttribute(): string
    {
        if ($this->status_tampil === 'Terlambat') return 'Lewat '.(int) abs($this->jatuh_tempo->diffInDays(today())).' hari';
        if ($this->status === 'Ditolak') return 'Ditolak: '.$this->catatan;
        return (string) $this->catatan;
    }
}
