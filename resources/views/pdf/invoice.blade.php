<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Pembayaran</title>
    <style>
        body { font-family: sans-serif; color: #333; line-height: 1.6; }
        .container { width: 100%; max-width: 800px; margin: 0 auto; padding: 20px; }
        .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 24px; text-transform: uppercase; }
        .header p { margin: 5px 0 0; font-size: 14px; color: #555; }
        .info-table { width: 100%; margin-bottom: 20px; }
        .info-table td { padding: 5px; vertical-align: top; }
        .info-table .label { font-weight: bold; width: 150px; }
        .amount { margin-top: 30px; border-top: 1px solid #ddd; padding-top: 10px; }
        .amount h2 { text-align: right; margin: 0; }
        .footer { margin-top: 50px; text-align: center; font-size: 12px; color: #888; border-top: 1px solid #ddd; padding-top: 10px; }
        .badge { display: inline-block; padding: 3px 8px; border-radius: 4px; background: #28a745; color: #fff; font-size: 12px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>INVOICE PEMBAYARAN KOS</h1>
            <p>Kos Queen Salon - Laporan Bukti Pembayaran</p>
        </div>

        <table class="info-table">
            <tr>
                <td class="label">ID Transaksi</td>
                <td>: {{ $transaksi->id_transaksi ?? $transaksi->id }}</td>
            </tr>
            <tr>
                <td class="label">Tanggal</td>
                <td>: {{ \Carbon\Carbon::parse($transaksi->tanggal)->translatedFormat('d F Y') }}</td>
            </tr>
            <tr>
                <td class="label">Penghuni</td>
                <td>: {{ $transaksi->penghuni->nama_penghuni ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Kontrak Sewa (Kode)</td>
                <td>: {{ $transaksi->kontrakSewa->kode_kontrak ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Metode Pembayaran</td>
                <td>: {{ $transaksi->metodePembayaran->nama_metode ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Keterangan</td>
                <td>: {{ $transaksi->keterangan ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Status</td>
                <td>: <span class="badge">LUNAS</span></td>
            </tr>
        </table>

        <div class="amount">
            <h2>Total Bayar: <br> Rp {{ number_format($transaksi->total_bayar, 0, ',', '.') }}</h2>
        </div>

        <div class="footer">
            <p>Terima kasih atas pembayaran Anda.</p>
            <p>Dokumen ini diterbitkan secara otomatis oleh Sistem Kos Queen Salon dan sah sebagai tanda terima pembayaran.</p>
        </div>
    </div>
</body>
</html>