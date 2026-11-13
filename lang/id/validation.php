<?php

return [
    'required' => ':attribute wajib diisi.',
    'unique' => ':attribute sudah dipakai.',
    'integer' => ':attribute harus berupa angka bulat.',
    'date' => ':attribute bukan tanggal yang valid.',
    'array' => ':attribute tidak valid.',
    'after_or_equal' => ':attribute tidak boleh sebelum tanggal yang ditentukan.',
    'min' => ['numeric' => ':attribute minimal :min.', 'string' => ':attribute minimal :min karakter.'],
    'max' => ['numeric' => ':attribute maksimal :max.', 'string' => ':attribute maksimal :max karakter.'],
    'attributes' => [
        'username' => 'Username', 'password' => 'Kata sandi', 'kode' => 'Kode', 'nama' => 'Nama',
        'jumlah_total' => 'Jumlah total', 'lokasi' => 'Lokasi', 'nisn' => 'NISN', 'kelas' => 'Kelas',
        'tgl_pinjam' => 'Tanggal pinjam', 'jatuh_tempo' => 'Tanggal pengembalian', 'keperluan' => 'Keperluan', 'alasan' => 'Alasan', 'n' => 'Jumlah',
    ],
];
