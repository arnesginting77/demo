<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Peminjaman;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    const STATUS = ['Semua', 'Menunggu', 'Dipinjam', 'Terlambat', 'Selesai', 'Ditolak'];

    private function data(Request $r): array
    {
        $s = in_array($r->query('s'), self::STATUS) ? $r->query('s') : 'Semua';
        $rows = Peminjaman::with('siswa', 'details.barang')->orderByDesc('id')->get();
        return [$s, $s === 'Semua' ? $rows : $rows->filter(fn ($p) => $p->status_tampil === $s)->values()];
    }

    public function monitoring(Request $r)
    {
        [$s, $rows] = $this->data($r);
        return view('monitoring', ['s' => $s, 'rows' => $rows, 'opsi' => self::STATUS]);
    }

    public function laporan(Request $r)
    {
        [$s, $rows] = $this->data($r);
        return view('laporan', ['s' => $s, 'rows' => $rows, 'opsi' => self::STATUS, 'barang' => Barang::orderBy('kode')->get()]);
    }
}
