<?php

namespace App\Filament\Resources\JournalResource\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class JurnalUmumAnalyticChart extends ChartWidget
{
    protected static ?string $heading = 'Analisis Jurnal Umum berdasarkan COA (AI Detected)';
    protected int | string | array $columnSpan = 'full';
    protected static ?string $maxHeight = '300px'; // Menjaga ukuran pie chart tetap imut dan pas
    
    protected static ?string $aiAnalysisResult = 'Sedang menganalisis data COA menggunakan AI...';

    protected function getType(): string
    {
        return 'pie'; // Kembali ke bentuk bulat utuh kesukaan kamu
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                ],
            ],
            'scales' => [
                'x' => ['display' => false], // Memastikan sumbu X mati total
                'y' => ['display' => false], // Memastikan sumbu Y mati total
            ],
        ];
    }

    protected function getData(): array
    {
        // 1. Deteksi nama tabel COA otomatis
        $coaTable = Schema::hasTable('coas') ? 'coas' : 'coa';

        // 2. QUERY BARU (SOLUSI UTAMA): 
        // Menggunakan GREATEST(SUM(debit), SUM(credit)) agar akun yang debitnya 0 tapi kreditnya ada isinya (seperti Modal & Utang)
        // nilainya tidak dianggap 0, sehingga kebagian potongan warna di Pie Chart!
        $rawData = DB::table('journals')
            ->join($coaTable, 'journals.coa_id', '=', $coaTable . '.id')
            ->selectRaw($coaTable . '.nama_coa as nama_akun, GREATEST(SUM(journals.debit), SUM(journals.credit)) as total_nominal, COUNT(journals.id) as jumlah_kemunculan')
            ->groupBy($coaTable . '.nama_coa')
            ->get();
            
        $dataJurnal = json_decode(json_encode($rawData), true);

        if (empty($dataJurnal)) {
            static::$aiAnalysisResult = 'Belum ada data transaksi akun COA di dalam Jurnal Umum.';
            return ['datasets' => [['data' => []]], 'labels' => []];
        }

        // 3. Lapisan Cache cerdas
        $cacheKey = 'gemini_coa_salon_pie_analysis_' . md5(json_encode($dataJurnal));
        
        $analysisResult = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($dataJurnal) {
            $apiKey = env('GEMINI_API_KEY');
            $url = "https://generativelanguage.googleapis.com/v1/models/gemini-1.5-flash:generateContent?key={$apiKey}";

            $prompt = "Berikut adalah data transaksi keuangan berdasarkan Akun COA dari Jurnal Umum Kos Queen Salon: " . json_encode($dataJurnal) . ". 
            Tolong lakukan analisis keuangan singkat. Kamu harus mengembalikan jawaban dalam format JSON murni dengan struktur persis seperti ini:
            {
                \"chart_data\": [
                    {\"nama_akun\": \"Nama COA\", \"total_nominal\": 10000, \"banyaknya_akun\": 2}
                ],
                \"analisis_teks\": \"Tulis 1-2 kalimat analisis keuangan bahasa Indonesia yang sangat berbobot mengenai akun COA salon yang paling aktif atau dominan saat ini.\"
            }
            PENTING: Jangan berikan kata pembuka, penutup, atau format kode markdown seperti ```json. Jawaban harus berupa JSON bersih murni.";

            try {
                $response = Http::timeout(3)->post($url, [
                    'contents' => [['parts' => [['text' => $prompt]]]]
                ]);

                if ($response->successful()) {
                    $aiResultText = $response->json()['candidates'][0]['content']['parts'][0]['text'] ?? '{}';
                    
                    $cleanJsonText = trim($aiResultText);
                    if (str_starts_with($cleanJsonText, '```json')) { $cleanJsonText = substr($cleanJsonText, 7); }
                    elseif (str_starts_with($cleanJsonText, '```')) { $cleanJsonText = substr($cleanJsonText, 3); }
                    if (str_ends_with($cleanJsonText, '```')) { $cleanJsonText = substr($cleanJsonText, 0, -3); }
                    $cleanJsonText = trim($cleanJsonText);

                    $resultArray = json_decode($cleanJsonText, true);

                    if (json_last_error() === JSON_ERROR_NONE && isset($resultArray['analisis_teks'])) {
                        return $resultArray;
                    }
                }
            } catch (\Exception $e) {
                // Timeout handle
            }

            return null;
        });

        if ($analysisResult && isset($analysisResult['analisis_teks'])) {
            static::$aiAnalysisResult = $analysisResult['analisis_teks'];
            $chartData = $analysisResult['chart_data'] ?? $dataJurnal;
        } else {
            static::$aiAnalysisResult = 'Menampilkan wawasan data akun lokal.';
            $chartData = $dataJurnal;
        }

        // 4. Susun Dataset ke Pie Chart
        $labels = [];
        $nominals = [];

        foreach ($chartData as $item) {
            $itemArray = (array) $item;
            $namaAkun = $itemArray['nama_akun'] ?? 'Tanpa Nama Akun';
            $banyaknya = $itemArray['banyaknya_akun'] ?? ($itemArray['jumlah_kemunculan'] ?? 1);
            $nominal = $itemArray['total_nominal'] ?? 0;

            $labels[] = $namaAkun . " ({$banyaknya}x)";
            $nominals[] = $nominal;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Nominal (Rp)',
                    'data' => $nominals,
                    'backgroundColor' => [
                        '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40', '#C9CBCF'
                    ],
                ],
            ],
            'labels' => $labels,
        ];
    }
}