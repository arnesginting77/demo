<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPeminjaman extends Model
{
    protected $table = 'detail_peminjaman';
    protected $guarded = [];
    public $timestamps = false;

    public function barang() { return $this->belongsTo(Barang::class); }
    public function pengembalian() { return $this->hasMany(DetailPengembalian::class, 'detail_peminjaman_id'); }

    // Sisa unit yang belum dikembalikan
    public function getSisaAttribute(): int
    {
        return $this->jumlah - (int) $this->pengembalian()->sum('jumlah');
    }
}
