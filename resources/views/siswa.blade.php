@extends('layouts.app')
@section('content')
<h1>Data siswa</h1>
<p class="sub">Akun siswa dibuat otomatis. Username dan kata sandi awal sama dengan NISN.</p>
<form method="post" action="{{ $edit ? route('siswa.update', $edit) : route('siswa.store') }}" class="box inl">
    @csrf @if ($edit) @method('PUT') @endif
    <label>NISN<input name="nisn" required value="{{ old('nisn', $edit->nisn ?? '') }}"></label>
    <label>Nama<input name="nama" required value="{{ old('nama', $edit->nama ?? '') }}"></label>
    <label>Kelas<input name="kelas" required value="{{ old('kelas', $edit->kelas ?? '') }}"></label>
    <button class="b1">{{ $edit ? 'Simpan perubahan' : 'Tambah siswa' }}</button>
    @if ($edit)<a class="b2" href="{{ route('siswa') }}">Batal</a>@endif
</form>
<div class="box"><table>
    <thead><tr><th>NISN</th><th>Nama</th><th>Kelas</th><th>Aksi</th></tr></thead>
    <tbody>
    @foreach ($siswa as $s)
        <tr>
            <td>{{ $s->nisn }}</td><td>{{ $s->nama }}</td><td>{{ $s->kelas }}</td>
            <td>
                <a class="sm" href="{{ route('siswa', ['edit' => $s->id]) }}">Ubah</a>
                <form method="post" action="{{ route('siswa.destroy', $s) }}" class="ia" onsubmit="return confirm('Hapus siswa ini?')">@csrf @method('DELETE')<button class="sm r">Hapus</button></form>
            </td>
        </tr>
    @endforeach
    </tbody>
</table></div>
@endsection
