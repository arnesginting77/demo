<?php

namespace App\Http\Controllers;

use App\Models\DetailPengembalian;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengembalianController extends Controller
{
    const KONDISI = ['Baik', 'Rusak Ringan', 'Rusak Berat'];

    public function index()
    {
        return view('kembali', ['rows' => Peminjaman::with('siswa', 'details.barang')->where('status', 'Dipinjam')->orderBy('id')->get(), 'kondisi' => self::KONDISI]);
    }

    public function store(Request $r, Peminjaman $peminjaman)
    {
        return DB::transaction(function () use ($r, $peminjaman) {
            $p = Peminjaman::with('details.barang')->lockForUpdate()->find($peminjaman->id);
            if ($p->status !== 'Dipinjam') return back()->with('err', 'Transaksi sudah selesai.');

            $sebelum = $p->details->sum('sisa');
            $baris = [];
            $total = 0;
            foreach ($p->details as $d) {
                $n = (int) $r->input("n.{$d->id}", 0);
                $k = $r->input("k.{$d->id}", 'Baik');
                if (! in_array($k, self::KONDISI)) $k = 'Baik';
                if ($n > $d->sisa) return back()->with('err', "Jumlah {$d->barang->nama} melebihi sisa dipinjam ({$d->sisa}).");
                if ($n > 0) { $baris[] = [$d, $n, $k]; $total += $n; }
            }
            if (! $total) return back()->with('err', 'Isi minimal satu jumlah yang dikembalikan.');

            $ret = Pengembalian::create(['peminjaman_id' => $p->id, 'tgl_kembali' => today(), 'petugas_id' => $r->user()->id]);
            $ret->update(['nomor' => 'RET-'.now()->format('ymd').'-'.str_pad($ret->id, 4, '0', STR_PAD_LEFT)]);
            foreach ($baris as [$d, $n, $k]) {
                DetailPengembalian::create(['pengembalian_id' => $ret->id, 'detail_peminjaman_id' => $d->id, 'jumlah' => $n, 'kondisi' => $k]);
                if ($k !== 'Baik') $d->barang->increment('rusak', $n);   // rusak tidak otomatis menjadi stok tersedia
            }
            $sisa = $sebelum - $total;
            if ($sisa <= 0) $p->update(['status' => 'Selesai']);
            return back()->with('ok', $sisa <= 0 ? 'Pengembalian lengkap. Transaksi selesai.' : "Pengembalian sebagian tercatat. Sisa $sisa unit.");
        });
    }
}
