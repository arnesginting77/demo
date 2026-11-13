@extends('layouts.app')
@section('content')
<h1>Dashboard</h1><p class="sub">Semua angka dihitung dari data transaksi. Hari ini {{ today()->format('d/m/Y') }}.</p>
<div class="cards">
    @foreach ($kartu as [$label, $nilai, $kelas])
        <div class="card {{ $kelas }}"><b>{{ $nilai }}</b><span>{{ $label }}</span></div>
    @endforeach
</div>
<h2>Komposisi stok per alat</h2>
<div class="box">
    <p class="lgd"><span class="t">Tersedia</span><span class="d">Dipinjam</span><span class="x">Rusak</span></p>
    <table><tbody>
    @foreach ($barang as $b)
        @php $tot = max(1, $b->jumlah_total); $tsd = $b->tersedia; $dip = $b->dipinjam; @endphp
        <tr>
            <td>{{ $b->nama }}</td>
            <td style="width:50%"><div class="bar"><i class="t" style="width:{{ $tsd / $tot * 100 }}%"></i><i class="d" style="width:{{ $dip / $tot * 100 }}%"></i><i class="x" style="width:{{ $b->rusak / $tot * 100 }}%"></i></div></td>
            <td>{{ $tsd }} dari {{ $b->jumlah_total }} tersedia</td>
        </tr>
    @endforeach
    </tbody></table>
</div>
<h2>Transaksi terbaru</h2>
@include('partials.trx', ['rows' => $terbaru])
@endsection
