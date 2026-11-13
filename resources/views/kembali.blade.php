@extends('layouts.app')
@section('content')
<h1>Pengembalian</h1>
<p class="sub">Periksa jumlah dan kondisi fisik. Pengembalian sebagian diperbolehkan. Alat rusak tidak masuk stok tersedia.</p>
@if ($rows->isEmpty())<div class="box empty">Tidak ada alat yang sedang dipinjam.</div>@endif
@foreach ($rows as $p)
<form method="post" action="{{ route('kembali.store', $p) }}" class="box">
    @csrf
    <b>{{ $p->nomor }}</b> {{ $p->siswa->nama }} ({{ $p->siswa->kelas }})
    <span class="bdg {{ $p->badge }}">{{ $p->status_tampil }}</span> <small>jatuh tempo {{ $p->jatuh_tempo->format('d/m/Y') }}</small>
    <table>
        <thead><tr><th>Alat</th><th>Sisa dipinjam</th><th>Jumlah kembali</th><th>Kondisi</th></tr></thead>
        <tbody>
        @foreach ($p->details as $d)
            @php $sisa = $d->sisa; @endphp
            @continue($sisa < 1)
            <tr>
                <td>{{ $d->barang->nama }}</td><td>{{ $sisa }} dari {{ $d->jumlah }}</td>
                <td><input type="number" class="mini" name="n[{{ $d->id }}]" min="0" max="{{ $sisa }}" value="{{ $sisa }}"></td>
                <td><select name="k[{{ $d->id }}]">@foreach ($kondisi as $k)<option>{{ $k }}</option>@endforeach</select></td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <button class="b1">Catat pengembalian</button>
</form>
@endforeach
@endsection
