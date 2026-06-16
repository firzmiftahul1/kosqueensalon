<!DOCTYPE html>
<html>
<head>
    <title>Laporan Jurnal Umum</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 11px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th { background-color: #f2f2f2; padding: 8px; border: 1px solid #000; }
        td { padding: 6px; border: 1px solid #000; vertical-align: top; }
        .text-right { text-align: right; }
        .total-row { font-weight: bold; background-color: #eee; }
        .indent-kredit { padding-left: 30px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>KOS QUEEN SALON</h2>
        <div>LAPORAN JURNAL UMUM</div>
        <div>Tanggal: {{ date('d/m/Y') }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th width="12%">Tanggal</th>
                <th width="15%">No. Ref</th>
                <th>Keterangan / Akun</th>
                <th width="18%" class="text-right">Debit (Rp)</th>
                <th width="18%" class="text-right">Kredit (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @php $totalDebit = 0; $totalCredit = 0; @endphp
            @foreach($jurnal as $j)
            <tr>
                <td style="text-align: center;">{{ \Carbon\Carbon::parse($j->transaction_date)->format('d/m/Y') }}</td>
                <td style="text-align: center;">{{ $j->reference_no }}</td>
                <td>
                    <strong>{{ $j->description }}</strong><br>
                    <span class="{{ $j->credit > 0 ? 'indent-kredit' : '' }}">
                        {{ $j->coa->kode_coa ?? '-' }} - {{ $j->coa->nama_coa ?? '-' }}
                    </span>
                </td>
                <td class="text-right">{{ number_format($j->debit, 2, ',', '.') }}</td>
                <td class="text-right">{{ number_format($j->credit, 2, ',', '.') }}</td>
            </tr>
            @php 
                $totalDebit += $j->debit; 
                $totalCredit += $j->credit; 
            @endphp
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="3" class="text-right">TOTAL</td>
                <td class="text-right">{{ number_format($totalDebit, 2, ',', '.') }}</td>
                <td class="text-right">{{ number_format($totalCredit, 2, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>