<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\DetailPeminjaman;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeminjamanController extends Controller
{
    // ---- Siswa: ajukan dan lihat riwayat ----
    public function ajukan(Request $r)
    {
        return view('ajukan', [
            'barang' => Barang::orderBy('nama')->get()->filter(fn ($b) => $b->tersedia > 0),
            'riwayat' => Peminjaman::with('siswa', 'details.barang')->where('siswa_id', $r->user()->siswa_id)->orderByDesc('id')->get(),
        ]);
    }

    public function ajukanStore(Request $r)
    {
        $r->validate([
            'tgl_pinjam' => 'required|date|after_or_equal:today', 'jatuh_tempo' => 'required|date|after_or_equal:tgl_pinjam',
            'keperluan' => 'required|max:200',
        ]);
        $it = [];
        foreach ($r->input('aid', []) as $i => $aid) {
            $n = (int) ($r->input("q.$i", 0));
            if ($aid && $n > 0) $it[$aid] = ($it[$aid] ?? 0) + $n;
        }
        if (! $it) return back()->withErrors(['aid' => 'Pilih minimal satu alat.'])->withInput();

        $bs = Barang::whereIn('id', array_keys($it))->get()->keyBy('id');
        foreach ($it as $aid => $n) {
            $b = $bs[$aid] ?? null;
            if (! $b || $n > $b->tersedia) {
                return back()->withErrors(['aid' => 'Jumlah '.($b->nama ?? 'alat').' melebihi stok tersedia ('.($b->tersedia ?? 0).').'])->withInput();
            }
        }
        DB::transaction(function () use ($r, $it) {
            $p = Peminjaman::create(['siswa_id' => $r->user()->siswa_id, 'tgl_pinjam' => $r->tgl_pinjam, 'jatuh_tempo' => $r->jatuh_tempo, 'keperluan' => $r->keperluan, 'status' => 'Menunggu']);
            $p->update(['nomor' => 'TRX-'.now()->format('ymd').'-'.str_pad($p->id, 4, '0', STR_PAD_LEFT)]);
            foreach ($it as $aid => $n) DetailPeminjaman::create(['peminjaman_id' => $p->id, 'barang_id' => $aid, 'jumlah' => $n]);
        });
        return redirect()->route('ajukan')->with('ok', 'Pengajuan terkirim. Tunggu persetujuan PLP.');
    }

    // ---- Admin: persetujuan ----
    public function setuju()
    {
        return view('setuju', ['rows' => Peminjaman::with('siswa', 'details.barang')->where('status', 'Menunggu')->orderBy('id')->get()]);
    }

    public function setujui(Request $r, Peminjaman $peminjaman)
    {
        return DB::transaction(function () use ($r, $peminjaman) {
            $p = Peminjaman::with('details')->lockForUpdate()->find($peminjaman->id);
            if ($p->status !== 'Menunggu') return back()->with('err', 'Pengajuan sudah diproses.');
            foreach ($p->details as $d) {
                $b = Barang::lockForUpdate()->find($d->barang_id);   // kunci baris agar tidak ada dua persetujuan bersamaan
                if ($b->tersedia < $d->jumlah) {
                    return back()->with('err', "Stok {$b->nama} tidak cukup: tersedia {$b->tersedia}, diminta {$d->jumlah}.");
                }
            }
            $p->update(['status' => 'Dipinjam', 'petugas_id' => $r->user()->id]);
            return back()->with('ok', 'Disetujui. Alat diserahkan dan tercatat dipinjam.');
        });
    }

    public function tolak(Request $r, Peminjaman $peminjaman)
    {
        $r->validate(['alasan' => 'required|max:200']);
        if ($peminjaman->status !== 'Menunggu') return back()->with('err', 'Pengajuan sudah diproses.');
        $peminjaman->update(['status' => 'Ditolak', 'catatan' => $r->alasan, 'petugas_id' => $r->user()->id]);
        return back()->with('ok', 'Pengajuan ditolak.');
    }
}
