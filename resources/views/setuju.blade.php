@extends('layouts.app')
@section('content')
<h1>Persetujuan</h1>
<p class="sub">Stok diperiksa ulang saat Anda menyetujui. Pengajuan yang menunggu belum mengurangi stok.</p>
@if ($rows->isEmpty())<div class="box empty">Tidak ada pengajuan yang menunggu.</div>@endif
@foreach ($rows as $p)
<div class="box">
    <b>{{ $p->nomor }}</b> {{ $p->siswa->nama }} ({{ $p->siswa->kelas }})<br>{{ $p->items }}<br>
    <small>Pinjam {{ $p->tgl_pinjam->format('d/m/Y') }}, kembali {{ $p->jatuh_tempo->format('d/m/Y') }}. Keperluan: {{ $p->keperluan }}</small>
    <div class="acts">
        <form method="post" action="{{ route('setuju.ya', $p) }}" class="ia">@csrf<button class="sm g">Setujui</button></form>
        <form method="post" action="{{ route('setuju.tolak', $p) }}" class="ia">@csrf<input name="alasan" placeholder="Alasan penolakan" required><button class="sm r">Tolak</button></form>
    </div>
</div>
@endforeach
@endsection
