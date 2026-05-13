<!DOCTYPE html>
<html>
<head>
    <title>Laporan Pemasukan Lain</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
    </style>
</head>
<body>

    <h2>Laporan Pemasukan Lain</h2>

    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Penghuni</th>
                <th>Kamar</th>
                <th>Jenis</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $item)
            <tr>
                <td>{{ $item->tanggal }}</td>
                <td>{{ $item->penghuni->nama_penghuni }}</td>
                <td>{{ $item->kamar->nama_kamar }}</td>
                <td>{{ $item->jenis }}</td>
                <td>Rp {{ number_format($item->total) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>