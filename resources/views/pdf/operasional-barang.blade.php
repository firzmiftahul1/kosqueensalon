<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Operasional Barang</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { margin: 0; text-transform: uppercase; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #999; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .text-right { text-align: right; }
        .footer { margin-top: 30px; text-align: right; font-style: italic; }
        .total-row { background-color: #eee; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Laporan Operasional Barang</h2>
        <p>Dicetak pada: {{ now()->format('d F Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Tanggal</th>
                <th>Kamar</th>
                <th>Barang</th>
                <th>Kegiatan</th>
                <th>Biaya (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @php $totalBiaya = 0; @endphp
            @foreach($data as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->kode_op_barang }}</td>
                <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                <td>{{ $item->kamar->nama_kamar ?? '-' }}</td>
                <td>{{ $item->barang->nama_barang ?? '-' }}</td>
                <td>{{ $item->kegiatan }}</td>
                <td class="text-right">{{ number_format($item->biaya, 0, ',', '.') }}</td>
            </tr>
            @php $totalBiaya += $item->biaya; @endphp
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="6" class="text-right">Total Seluruh Biaya:</td>
                <td class="text-right">Rp {{ number_format($totalBiaya, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>Laporan ini dihasilkan secara otomatis oleh sistem.</p>
    </div>
</body>
</html>