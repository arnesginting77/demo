@extends('layouts.app')
@section('content')
<div class="box loginbox">
    <div class="brand dark">SIMPAL<small>Inventaris dan peminjaman alat Bengkel TI</small></div>
    <form method="post" action="{{ route('login') }}" class="fg">
        @csrf
        <label>Username<input name="username" value="{{ old('username') }}" required autofocus></label>
        <label>Kata sandi<input name="password" type="password" required></label>
        <button class="b1">Masuk</button>
    </form>
</div>
@endsection
