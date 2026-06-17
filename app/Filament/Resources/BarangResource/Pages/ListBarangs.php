<?php

namespace App\Filament\Resources\BarangResource\Pages;

use App\Filament\Resources\BarangResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

// tambahan
use App\Models\MarketTrend;
use Illuminate\Support\Facades\Http;
use Filament\Notifications\Notification;

class ListBarangs extends ListRecords
{
    protected static string $resource = BarangResource::class;

    protected function getHeaderActions(): array
    {
        // Ambil tahun target (misal tahun depan)
        $targetYear = now()->year;

        return [
            // tambahan
            Actions\Action::make('refreshAiInsights')
                ->label('Refresh AI Insights')
                ->visible(true) // Memaksa tombol agar selalu terlihat
                ->icon('heroicon-m-sparkles')
                ->color('danger')
                ->requiresConfirmation() // Opsional: Tambahkan konfirmasi agar tidak boros kuota
                ->modalHeading('Update Analisis Tren $targetYear')
                ->modalDescription('Sistem akan menghubungi Gemini AI untuk menganalisis tren barang terbaru tahun $targetYear. Lanjutkan?')
                ->action(function () use ($targetYear) {
                    $apiKey = env('GEMINI_API_KEY');
                    $url = "https://generativelanguage.googleapis.com/v1/models/gemini-2.5-flash:generateContent?key=" . $apiKey;

                    try {
                        $response = Http::timeout(30)->post($url, [
                            'contents' => [
                                [
                                    'parts' => [
                                        ['text' => "Berikan analisis tren barang tahun $targetYear. WAJIB dalam format JSON murni (tanpa markdown/backtick) dengan key: 'nama_tren', 'analisis', 'bahan', 'warna'. Gunakan Bahasa Indonesia."]
                                    ]
                                ]
                            ]
                        ]);

                        if ($response->successful()) {
                            $rawText = $response->json()['candidates'][0]['content']['parts'][0]['text'];
                            
                            // Pembersihan teks: Hilangkan blok kode markdown jika AI bandel memberikannya
                            $cleanJson = str_replace(['```json', '```'], '', $rawText);
                            $data = json_decode(trim($cleanJson), true);

                            if (!$data) {
                                // Jika JSON gagal di-decode, tampilkan isinya untuk dicek
                                Notification::make()->title('Gagal Parsing JSON')->body($rawText)->danger()->send();
                                return;
                            }

                            if ($data) {
                                // Jika Gemini mengirim array of objects
                                // Kita lakukan looping agar semua tren tersimpan
                                $items = isset($data[0]) ? $data : [$data];
                                foreach ($items as $item) {
                                    MarketTrend::create([
                                        'nama_tren'    => $item['nama_tren'] ?? 'Tren Barang '.$targetYear,
                                        'analisis_ai'  => $item['analisis'] ?? '',
                                        'saran_barang'  => $item['bahan'] ?? '',
                                        'warna_populer'=> $item['warna'] ?? '',
                                    ]);
                                }

                                Notification::make()
                                    ->title('Tren Berhasil Diperbarui')
                                    ->success()
                                    ->send();
                                    
                                // Refresh halaman agar widget terupdate
                                $this->redirect(BarangResource::getUrl('index'));
                            }
                        } else {
                            throw new \Exception("API Error: " . $response->status());
                        }
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Gagal Update Tren')
                            ->body('Kemungkinan kuota API habis atau koneksi terputus.')
                            ->danger()
                            ->send();
                    }
                }),
            // akhir tambahan
            Actions\CreateAction::make(),
        ];
    }

    // Pastikan widget terdaftar di sini agar muncul di atas tabel
    protected function getHeaderWidgets(): array
    {
        return [
            // Menampilkan Widget Chart Baru (yang sudah dinamis tahunnya)
            BarangResource\Widgets\TrendColorChart::class,
            BarangResource\Widgets\TrendMaterialChart::class,
        ];
    }
}