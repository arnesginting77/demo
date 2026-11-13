<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class SiswaController extends Controller
{
    public function index(Request $r)
    {
        return view('siswa', ['siswa' => Siswa::orderBy('kelas')->orderBy('nama')->get(), 'edit' => $r->query('edit') ? Siswa::find($r->query('edit')) : null]);
    }

    public function store(Request $r)
    {
        $d = $r->validate(['nisn' => 'required|max:20|unique:siswa,nisn|unique:users,username', 'nama' => 'required|max:100', 'kelas' => 'required|max:30']);
        DB::transaction(function () use ($d) {
            $s = Siswa::create($d);
            User::create(['username' => $d['nisn'], 'password' => Hash::make($d['nisn']), 'role' => 'siswa', 'name' => $d['nama'], 'siswa_id' => $s->id]);
        });
        return redirect()->route('siswa')->with('ok', 'Siswa disimpan. Akun dibuat dengan NISN sebagai username dan kata sandi awal.');
    }

    public function update(Request $r, Siswa $siswa)
    {
        $d = $r->validate([
            'nisn' => ['required', 'max:20', Rule::unique('siswa', 'nisn')->ignore($siswa->id), Rule::unique('users', 'username')->ignore($siswa->id, 'siswa_id')],
            'nama' => 'required|max:100', 'kelas' => 'required|max:30',
        ]);
        DB::transaction(function () use ($d, $siswa) {
            $siswa->update($d);
            User::where('siswa_id', $siswa->id)->update(['username' => $d['nisn'], 'name' => $d['nama']]);
        });
        return redirect()->route('siswa')->with('ok', 'Data siswa diperbarui.');
    }

    public function destroy(Siswa $siswa)
    {
        if (Peminjaman::where('siswa_id', $siswa->id)->exists()) {
            return back()->with('err', 'Siswa punya riwayat transaksi, tidak dapat dihapus.');
        }
        DB::transaction(function () use ($siswa) {
            User::where('siswa_id', $siswa->id)->delete();
            $siswa->delete();
        });
        return back()->with('ok', 'Siswa dihapus.');
    }
}
