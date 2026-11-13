@extends('layouts.app')
@section('content')
<h1>Laporan</h1>
<p class="sub">Dicetak {{ today()->format('d/m/Y') }}. Pilih "Cetak laporan" lalu simpan sebagai PDF bila perlu.</p>
<form method="get" class="tool">
    <select name="s" onchange="this.form.submit()" style="width:auto">@foreach ($opsi as $o)<option @selected($o === $s)>{{ $o }}</option>@endforeach</select>
    <button type="button" class="b1" onclick="window.print()">Cetak laporan</button>
</form>
<h2>Inventaris</h2>
<div class="box"><table>
    <thead><tr><th>Kode</th><th>Alat</th><th>Total</th><th>Tersedia</th><th>Dipinjam</th><th>Rusak</th></tr></thead>
    <tbody>@foreach ($barang as $b)<tr><td>{{ $b->kode }}</td><td>{{ $b->nama }}</td><td>{{ $b->jumlah_total }}</td><td>{{ $b->tersedia }}</td><td>{{ $b->dipinjam }}</td><td>{{ $b->rusak }}</td></tr>@endforeach</tbody>
</table></div>
<h2>Transaksi peminjaman ({{ $s }})</h2>
@include('partials.trx', ['rows' => $rows])
@endsection
