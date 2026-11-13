<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\DetailPeminjaman;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BarangController extends Controller
{
    public function index(Request $r)
    {
        $edit = $r->user()->role === 'admin' && $r->query('edit') ? Barang::find($r->query('edit')) : null;
        return view('alat', ['barang' => Barang::orderBy('kode')->get(), 'edit' => $edit]);
    }

    public function store(Request $r)
    {
        $d = $r->validate(['kode' => 'required|max:20|unique:barang,kode', 'nama' => 'required|max:100', 'jumlah_total' => 'required|integer|min:1', 'lokasi' => 'nullable|max:50']);
        Barang::create($d);
        return redirect()->route('alat')->with('ok', 'Data alat disimpan.');
    }

    public function update(Request $r, Barang $barang)
    {
        $d = $r->validate(['kode' => ['required', 'max:20', Rule::unique('barang', 'kode')->ignore($barang->id)], 'nama' => 'required|max:100', 'jumlah_total' => 'required|integer|min:1', 'lokasi' => 'nullable|max:50']);
        $min = $barang->dipinjam + $barang->rusak;
        if ($d['jumlah_total'] < $min) {
            return back()->withErrors(['jumlah_total' => "Jumlah total tidak boleh kurang dari dipinjam + rusak ($min)."])->withInput();
        }
        $barang->update($d);
        return redirect()->route('alat')->with('ok', 'Data alat diperbarui.');
    }

    public function destroy(Barang $barang)
    {
        if (DetailPeminjaman::where('barang_id', $barang->id)->exists()) {
            return back()->with('err', 'Alat sudah punya riwayat transaksi, tidak dapat dihapus.');
        }
        $barang->delete();
        return back()->with('ok', 'Alat dihapus.');
    }

    // Alat rusak yang sudah diperbaiki dikembalikan ke stok tersedia
    public function pulih(Request $r, Barang $barang)
    {
        $n = min($barang->rusak, max(1, (int) $r->input('n', 1)));
        $barang->decrement('rusak', $n);
        return back()->with('ok', "$n unit dipulihkan menjadi stok tersedia.");
    }
}
