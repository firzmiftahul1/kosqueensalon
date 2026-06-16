<?php

namespace App\Filament\Widgets;

use App\Models\Barang;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Http;

class AiTrendBarangWidget extends Widget
{
    protected static string $view = 'filament.widgets.ai-trend-barang-widget';

    // Membuat widget ini memakan seluruh ruang kolom (Full Width)
    protected int | string | array $columnSpan = 'full';

    public ?string $aiResponse = null;

    public function analyzeTrend()
    {
        $this->aiResponse = null;

        try {
            // Mengambil daftar nama barang dari Master Data
            $daftarBarang = Barang::pluck('nama_barang')->toArray();
            $barangString = empty($daftarBarang) ? 'Belum ada data barang' : implode(', ', $daftarBarang);

            // Membuat prompt instruksi 
            $prompt = "Berikut adalah daftar aset barang operasional kos saat ini: [{$barangString}]. Sebagai analis properti, sebutkan 3 barang yang sedang tren di kalangan anak kos sekarang yang belum ada di daftar tersebut, dan sebutkan 1 barang di daftar yang paling krusial untuk selalu diperbarui. Format output menggunakan bullet points.";

            $apiKey = env('GEMINI_API_KEY');

            if (!$apiKey) {
                $this->aiResponse = "Error: GEMINI_API_KEY belum dikonfigurasi di file .env!";
                return;
            }

            // Daftar model alternatif untuk mengantisipasi overload (503 Service Unavailable)
            $models = ['gemini-2.5-flash', 'gemini-2.0-flash', 'gemini-3.5-flash'];
            $response = null;
            $lastError = '';

            foreach ($models as $model) {
                // Melakukan HTTP Request ke Gemini API
                $response = Http::post("https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}", [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt]
                            ]
                        ]
                    ]
                ]);

                if ($response->successful()) {
                    break;
                } else {
                    $lastError = $response->body();
                }
            }

            if ($response && $response->successful()) {
                // Ekstrak teks balasan JSON API Gemini
                $this->aiResponse = $response->json('candidates.0.content.parts.0.text') ?? 'Tidak ada respon dari AI.';
            } else {
                $this->aiResponse = "Gagal menghubungi Gemini API (setelah mencoba beberapa model): " . ($lastError ?: 'Koneksi gagal');
            }

        } catch (\Exception $e) {
            $this->aiResponse = "Terjadi kesalahan sistem: " . $e->getMessage();
        }
    }
}