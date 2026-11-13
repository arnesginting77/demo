<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('siswa', function (Blueprint $t) {
            $t->id();
            $t->string('nisn', 20)->unique();
            $t->string('nama', 100);
            $t->string('kelas', 30);
            $t->string('jurusan', 50)->default('TKJ');
        });
        Schema::create('users', function (Blueprint $t) {
            $t->id();
            $t->string('username', 30)->unique();
            $t->string('password');
            $t->string('role', 10);                       // admin | kabeng | siswa
            $t->string('name', 100);
            $t->foreignId('siswa_id')->nullable()->constrained('siswa');
            $t->rememberToken();
        });
        Schema::create('barang', function (Blueprint $t) {
            $t->id();
            $t->string('kode', 20)->unique();
            $t->string('nama', 100);
            $t->unsignedInteger('jumlah_total');
            $t->unsignedInteger('rusak')->default(0);
            $t->string('lokasi', 50)->nullable();
            $t->text('deskripsi')->nullable();
        });
        Schema::create('peminjaman', function (Blueprint $t) {
            $t->id();
            $t->string('nomor', 30)->default('');
            $t->foreignId('siswa_id')->constrained('siswa');
            $t->date('tgl_pinjam');
            $t->date('jatuh_tempo');
            $t->string('keperluan', 200)->nullable();
            $t->string('status', 20)->default('Menunggu'); // Menunggu | Ditolak | Dipinjam | Selesai
            $t->foreignId('petugas_id')->nullable()->constrained('users');
            $t->string('catatan', 200)->nullable();
            $t->timestamp('created_at')->useCurrent();
        });
        Schema::create('detail_peminjaman', function (Blueprint $t) {
            $t->id();
            $t->foreignId('peminjaman_id')->constrained('peminjaman');
            $t->foreignId('barang_id')->constrained('barang');
            $t->unsignedInteger('jumlah');
        });
        Schema::create('pengembalian', function (Blueprint $t) {
            $t->id();
            $t->string('nomor', 30)->default('');
            $t->foreignId('peminjaman_id')->constrained('peminjaman');
            $t->date('tgl_kembali');
            $t->foreignId('petugas_id')->nullable()->constrained('users');
        });
        Schema::create('detail_pengembalian', function (Blueprint $t) {
            $t->id();
            $t->foreignId('pengembalian_id')->constrained('pengembalian');
            $t->foreignId('detail_peminjaman_id')->constrained('detail_peminjaman');
            $t->unsignedInteger('jumlah');
            $t->string('kondisi', 20);                    // Baik | Rusak Ringan | Rusak Berat
        });
    }

    public function down(): void
    {
        foreach (['detail_pengembalian', 'pengembalian', 'detail_peminjaman', 'peminjaman', 'barang', 'users', 'siswa'] as $tb) {
            Schema::dropIfExists($tb);
        }
    }
};
