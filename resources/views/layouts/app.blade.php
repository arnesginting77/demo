<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Inventaris </title>
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="{{ auth()->check() ? '' : 'lg' }}">
@auth
@php
    $role = auth()->user()->role;
    $menu = [
        'admin' => [['dashboard', 'Dashboard'], ['alat', 'Data alat'], ['siswa', 'Data siswa'], ['setuju', 'Persetujuan'], ['kembali', 'Pengembalian'], ['monitoring', 'Monitoring'], ['laporan', 'Laporan']],
        'kabeng' => [['dashboard', 'Dashboard'], ['alat', 'Data alat'], ['monitoring', 'Monitoring'], ['laporan', 'Laporan']],
        'siswa' => [['alat', 'Daftar alat'], ['ajukan', 'Ajukan dan riwayat']],
    ][$role];
    $label = ['admin' => 'Admin / PLP', 'kabeng' => 'Kepala bengkel', 'siswa' => 'Siswa'][$role];
@endphp
<aside>
    <div class="brand">Inventaris <small>Bengkel TI</small></div>
    <nav>
        @foreach ($menu as [$rt, $lb])
            <a href="{{ route($rt) }}" class="{{ request()->routeIs($rt) ? 'on' : '' }}">{{ $lb }}</a>
        @endforeach
    </nav>
    <div class="me">{{ auth()->user()->name }}<br><small>{{ $label }}</small>
        <form method="post" action="{{ route('logout') }}">@csrf<button class="out">Keluar</button></form>
    </div>
</aside>
@endauth
<main>
    @if (session('ok'))<div class="flash">{{ session('ok') }}</div>@endif
    @if (session('err'))<div class="flash bad">{{ session('err') }}</div>@endif
    @if ($errors->any())<div class="flash bad">{{ $errors->first() }}</div>@endif
    @yield('content')
</main>
</body>
</html>
