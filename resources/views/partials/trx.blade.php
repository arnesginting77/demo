@if ($rows->isEmpty())
    <div class="box empty">{{ $kosong ?? 'Belum ada transaksi.' }}</div>
@else
<div class="box"><table>
    <thead><tr><th>No.</th><th>Peminjam</th><th>Alat</th><th>Pinjam</th><th>Jatuh tempo</th><th>Status</th><th>Keterangan</th></tr></thead>
    <tbody>
    @foreach ($rows as $p)
        <tr>
            <td>{{ $p->nomor }}</td>
            <td>{{ $p->siswa->nama }}<br><small>{{ $p->siswa->kelas }}</small></td>
            <td>{{ $p->items }}</td>
            <td>{{ $p->tgl_pinjam->format('d/m/Y') }}</td>
            <td>{{ $p->jatuh_tempo->format('d/m/Y') }}</td>
            <td><span class="bdg {{ $p->badge }}">{{ $p->status_tampil }}</span></td>
            <td>{{ $p->keterangan }}</td>
        </tr>
    @endforeach
    </tbody>
</table></div>
@endif
