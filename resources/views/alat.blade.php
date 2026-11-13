@extends('layouts.app')
@section('content')
@php $ad = auth()->user()->role === 'admin'; @endphp
<h1>{{ auth()->user()->role === 'siswa' ? 'Daftar alat' : 'Data alat' }}</h1>
<p class="sub">Tersedia = total − dipinjam − rusak.</p>
@if ($ad)
<form method="post" action="{{ $edit ? route('alat.update', $edit) : route('alat.store') }}" class="box inl">
    @csrf @if ($edit) @method('PUT') @endif
    <label>Kode<input name="kode" required value="{{ old('kode', $edit->kode ?? '') }}"></label>
    <label>Nama alat<input name="nama" required value="{{ old('nama', $edit->nama ?? '') }}"></label>
    <label>Jumlah total<input name="jumlah_total" type="number" min="1" required value="{{ old('jumlah_total', $edit->jumlah_total ?? 1) }}"></label>
    <label>Lokasi<input name="lokasi" value="{{ old('lokasi', $edit->lokasi ?? '') }}"></label>
    <button class="b1">{{ $edit ? 'Simpan perubahan' : 'Tambah alat' }}</button>
    @if ($edit)<a class="b2" href="{{ route('alat') }}">Batal</a>@endif
</form>
@endif
<div class="box"><table>
    <thead><tr><th>Kode</th><th>Nama alat</th><th>Lokasi</th><th>Total</th><th>Tersedia</th><th>Dipinjam</th><th>Rusak</th>@if ($ad)<th>Aksi</th>@endif</tr></thead>
    <tbody>
    @foreach ($barang as $b)
        <tr>
            <td>{{ $b->kode }}</td><td>{{ $b->nama }}</td><td>{{ $b->lokasi }}</td><td>{{ $b->jumlah_total }}</td>
            <td><b>{{ $b->tersedia }}</b></td><td>{{ $b->dipinjam }}</td><td>{{ $b->rusak }}</td>
            @if ($ad)
            <td>
                <a class="sm" href="{{ route('alat', ['edit' => $b->id]) }}">Ubah</a>
                @if ($b->rusak)
                <form method="post" action="{{ route('alat.pulih', $b) }}" class="ia">@csrf
                    <input name="n" type="number" min="1" max="{{ $b->rusak }}" value="1" class="mini"><button class="sm g">Pulihkan</button>
                </form>
                @endif
                <form method="post" action="{{ route('alat.destroy', $b) }}" class="ia" onsubmit="return confirm('Hapus alat ini?')">@csrf @method('DELETE')<button class="sm r">Hapus</button></form>
            </td>
            @endif
        </tr>
    @endforeach
    </tbody>
</table></div>
@endsection
