<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Barang extends Model
{
    protected $table = 'barang';
    protected $guarded = [];
    public $timestamps = false;

    // Jumlah unit yang masih dipinjam (belum dikembalikan) pada transaksi berstatus Dipinjam
    public function getDipinjamAttribute(): int
    {
        return (int) DB::table('detail_peminjaman as d')
            ->join('peminjaman as p', 'p.id', '=', 'd.peminjaman_id')
            ->where('d.barang_id', $this->id)->where('p.status', 'Dipinjam')
            ->selectRaw('COALESCE(SUM(d.jumlah - COALESCE((SELECT SUM(x.jumlah) FROM detail_pengembalian x WHERE x.detail_peminjaman_id = d.id), 0)), 0) as n')
            ->value('n');
    }

    // Stok tersedia = total - rusak - sedang dipinjam
    public function getTersediaAttribute(): int
    {
        return $this->jumlah_total - $this->rusak - $this->dipinjam;
    }
}
