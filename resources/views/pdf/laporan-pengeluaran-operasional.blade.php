<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Pengeluaran Operasional</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        h2, p {
            text-align: center;
            margin: 0;
        }

        p {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #000;
        }

        th {
            background-color: #eeeeee;
        }

        th, td {
            padding: 7px;
        }

        .text-right {
            text-align: right;
        }

        .total {
            font-weight: bold;
        }
    </style>
</head>
<body>

    <h2>Laporan Pengeluaran Operasional</h2>
    <p>Kos Queensalon</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Pengeluaran</th>
                <th>Tanggal</th>
                <th>Supplier</th>
                <th>Jenis Pengeluaran</th>
                <th>Jumlah</th>
                <th>Keterangan</th>
            </tr>
        </thead>

        <tbody>
            @php $total = 0; @endphp

            @foreach ($pengeluarans as $index => $pengeluaran)
                @php $total += $pengeluaran->jumlah; @endphp

                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $pengeluaran->kode_pengeluaran }}</td>
                    <td>{{ $pengeluaran->tanggal }}</td>
                    <td>{{ $pengeluaran->supplier->nama_supplier ?? '-' }}</td>
                    <td>{{ $pengeluaran->jenis_pengeluaran }}</td>
                    <td class="text-right">
                        Rp {{ number_format($pengeluaran->jumlah, 0, ',', '.') }}
                    </td>
                    <td>{{ $pengeluaran->keterangan }}</td>
                </tr>
            @endforeach

            <tr>
                <td colspan="5" class="total text-right">Total Pengeluaran</td>
                <td class="total text-right">
                    Rp {{ number_format($total, 0, ',', '.') }}
                </td>
                <td></td>
            </tr>
        </tbody>
    </table>

</body>
</html>