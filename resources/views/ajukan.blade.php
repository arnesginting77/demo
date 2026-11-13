@extends('layouts.app')
@section('content')
<h1>Ajukan peminjaman</h1>
<p class="sub">Pengajuan menunggu persetujuan PLP. Stok baru berkurang setelah disetujui.</p>
<form method="post" action="{{ route('ajukan.store') }}" class="box fg">
    @csrf
    @for ($i = 0; $i < 3; $i++)
    <div class="two">
        <label>Alat {{ $i + 1 }}
            <select name="aid[]">
                <option value="">Pilih alat</option>
                @foreach ($barang as $b)<option value="{{ $b->id }}" @selected(old("aid.$i") == $b->id)>{{ $b->nama }} (tersedia {{ $b->tersedia }})</option>@endforeach
            </select>
        </label>
        <label>Jumlah<input type="number" name="q[]" min="0" value="{{ old("q.$i", $i ? 0 : 1) }}"></label>
    </div>
    @endfor
    <div class="two">
        <label>Tanggal pinjam<input type="date" name="tgl_pinjam" value="{{ old('tgl_pinjam', today()->toDateString()) }}" min="{{ today()->toDateString() }}" required></label>
        <label>Rencana pengembalian<input type="date" name="jatuh_tempo" value="{{ old('jatuh_tempo', today()->addDays(3)->toDateString()) }}" min="{{ today()->toDateString() }}" required></label>
    </div>
    <label>Keperluan<input name="keperluan" value="{{ old('keperluan') }}" required></label>
    <button class="b1">Kirim pengajuan</button>
</form>
<h2>Riwayat saya</h2>
@include('partials.trx', ['rows' => $riwayat, 'kosong' => 'Belum ada pengajuan.'])
@endsection
