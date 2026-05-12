<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pengeluaran Penghuni</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 12px; color: #333; }

        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h1 { font-size: 18px; font-weight: bold; margin-bottom: 4px; }
        .header p { font-size: 11px; color: #666; }

        .info { margin-bottom: 15px; font-size: 11px; }
        .info span { font-weight: bold; }

        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        thead tr { background-color: #2d6a4f; color: #fff; }
        th { padding: 8px 6px; text-align: left; font-size: 11px; }
        td { padding: 7px 6px; font-size: 11px; border-bottom: 1px solid #ddd; }
        tbody tr:nth-child(even) { background-color: #f5f5f5; }

        .status-pending  { background-color: #fef3c7; color: #92400e; padding: 2px 8px; border-radius: 10px; font-size: 10px; }
        .status-dibayar  { background-color: #d1fae5; color: #065f46; padding: 2px 8px; border-radius: 10px; font-size: 10px; }
        .status-ditolak  { background-color: #fee2e2; color: #991b1b; padding: 2px 8px; border-radius: 10px; font-size: 10px; }

        .footer { margin-top: 20px; text-align: right; font-size: 10px; color: #888; }
        .total-row { font-weight: bold; background-color: #e8f5e9; }
    </style>
</head>
<body>

    <div class="header">
        <h1>Laporan Pengeluaran Penghuni</h1>
        <p>Dicetak pada: {{ now()->format('d F Y, H:i') }} WIB</p>
    </div>

    <div class="info">
        <p>Total Data: <span>{{ $data->count() }} pengeluaran</span></p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:4%">No</th>
                <th style="width:18%">Nama Penghuni</th>
                <th style="width:20%">Nama Pengeluaran</th>
                <th style="width:20%">Keterangan</th>
                <th style="width:15%">Nominal</th>
                <th style="width:13%">Tanggal</th>
                <th style="width:10%">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->penghuni->nama ?? '-' }}</td>
                <td>{{ $item->nama_pengeluaran }}</td>
                <td>{{ $item->keterangan ?? '-' }}</td>
                <td>IDR {{ number_format($item->nominal, 0, ',', '.') }}</td>
                <td>{{ \Carbon\Carbon::parse($item->tanggal_pengeluaran)->format('d M Y') }}</td>
                <td>
                    <span class="status-{{ $item->status }}">
                        {{ ucfirst($item->status) }}
                    </span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align:center; padding: 20px; color:#999;">
                    Tidak ada data pengeluaran.
                </td>
            </tr>
            @endforelse

            @if($data->count() > 0)
            <tr class="total-row">
                <td colspan="4" style="text-align:right; padding-right:10px;">Total Nominal:</td>
                <td>IDR {{ number_format($data->sum('nominal'), 0, ',', '.') }}</td>
                <td colspan="2"></td>
            </tr>
            @endif
        </tbody>
    </table>

    <div class="footer">
        <p>Dokumen ini digenerate otomatis oleh sistem.</p>
    </div>

</body>
</html>