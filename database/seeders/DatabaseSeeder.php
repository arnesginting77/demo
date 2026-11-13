<?php

namespace Database\Seeders;

use App\Models\Barang;
use App\Models\DetailPeminjaman;
use App\Models\Peminjaman;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create(['username' => 'admin', 'password' => Hash::make('admin123'), 'role' => 'admin', 'name' => 'PLP Bengkel TI']);
        User::create(['username' => 'kabeng', 'password' => Hash::make('kabeng123'), 'role' => 'kabeng', 'name' => 'Kepala Bengkel']);

        foreach ([['1001', 'Andi Pratama', 'XI TKJ 1'], ['1002', 'Sari Br Sembiring', 'XI TKJ 1'], ['1003', 'Budi Ginting', 'XI TKJ 2'], ['1004', 'Rina Siregar', 'X TKJ 1']] as [$nisn, $nama, $kelas]) {
            $s = Siswa::create(['nisn' => $nisn, 'nama' => $nama, 'kelas' => $kelas]);
            User::create(['username' => $nisn, 'password' => Hash::make('siswa123'), 'role' => 'siswa', 'name' => $nama, 'siswa_id' => $s->id]);
        }

        foreach ([['ALT-001', 'Multimeter digital', 10, 0, 'Rak A1'], ['ALT-002', 'Tang crimping', 8, 1, 'Rak A2'], ['ALT-003', 'Laptop praktik', 12, 0, 'Lemari B'],
                  ['ALT-004', 'LAN tester', 6, 0, 'Rak A2'], ['ALT-005', 'Solder station', 5, 1, 'Rak C1'], ['ALT-006', 'Obeng set presisi', 10, 0, 'Rak A3']] as [$k, $n, $t, $r, $l]) {
            Barang::create(['kode' => $k, 'nama' => $n, 'jumlah_total' => $t, 'rusak' => $r, 'lokasi' => $l]);
        }

        // Data contoh: satu terlambat, satu masih berjalan, satu menunggu persetujuan
        foreach ([['TRX-DEMO-0001', 1, -6, -1, 'Praktik pengukuran', 'Dipinjam', 1, 3], ['TRX-DEMO-0002', 2, -2, 3, 'Instalasi jaringan', 'Dipinjam', 3, 2], ['TRX-DEMO-0003', 3, 0, 2, 'Praktik kabel UTP', 'Menunggu', 2, 2]] as [$no, $sid, $a, $b, $kep, $st, $bid, $q]) {
            $p = Peminjaman::create(['nomor' => $no, 'siswa_id' => $sid, 'tgl_pinjam' => now()->addDays($a)->toDateString(), 'jatuh_tempo' => now()->addDays($b)->toDateString(), 'keperluan' => $kep, 'status' => $st]);
            DetailPeminjaman::create(['peminjaman_id' => $p->id, 'barang_id' => $bid, 'jumlah' => $q]);
        }
    }
}
