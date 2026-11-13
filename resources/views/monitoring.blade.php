@extends('layouts.app')
@section('content')
<h1>Monitoring transaksi</h1>
<p class="sub">Keterlambatan dihitung otomatis dari tanggal jatuh tempo.</p>
<form method="get" class="tool">
    <select name="s" onchange="this.form.submit()" style="width:auto">@foreach ($opsi as $o)<option @selected($o === $s)>{{ $o }}</option>@endforeach</select>
</form>
@include('partials.trx', ['rows' => $rows, 'kosong' => 'Tidak ada transaksi dengan status ini.'])
@endsection
