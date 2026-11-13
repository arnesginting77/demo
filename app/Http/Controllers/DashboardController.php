<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Peminjaman;

class DashboardController extends Controller
{
    public function index()
    {
        $barang = Barang::orderBy('nama')->get();
        $semua = Peminjaman::with('siswa', 'details.barang')->orderByDesc('id')->get();
        $kartu = [
            ['Total aset', $barang->sum('jumlah_total'), ''], ['Tersedia', $barang->sum('tersedia'), ''],
            ['Sedang dipinjam', $barang->sum('dipinjam'), ''], ['Rusak', $barang->sum('rusak'), 'r'],
            ['Menunggu persetujuan', $semua->where('status', 'Menunggu')->count(), 'w'],
            ['Terlambat', $semua->filter(fn ($p) => $p->status_tampil === 'Terlambat')->count(), 'r'],
        ];
        return view('dashboard', ['barang' => $barang, 'kartu' => $kartu, 'terbaru' => $semua->take(6)]);
    }
}
