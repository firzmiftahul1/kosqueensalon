<?php

namespace App\Filament\Resources\PenghuniResource\Pages;

use App\Filament\Resources\PenghuniResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Models\MarketTrend;
use Illuminate\Support\Facades\Http;
use Filament\Notifications\Notification;
use Filament\Actions\Action;

class ListPenghunis extends ListRecords
{
    protected static string $resource = PenghuniResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('refreshAiInsights')
                ->label('Refresh AI Insights')
                ->icon('heroicon-m-sparkles')
                ->color('warning')
                ->requiresConfirmation()
                ->modalHeading('Update Analisis Tren Penghuni')
                ->modalDescription('Sistem akan menghubungi Gemini AI untuk menganalisis tren penghuni kos terbaru. Lanjutkan?')
                ->action(function () {
                    $apiKey = env('GEMINI_API_KEY');
                    $url = "https://generativelanguage.googleapis.com/v1/models/gemini-2.5-flash:generateContent?key={$apiKey}";

                    $prompt = "Analisis tren penghuni kos dan kontrakan tahun " . now()->year . ". Berikan informasi dalam format berikut: 1) nama tren utama, 2) analisis lengkap tren penghuni, 3) saran fasilitas populer yang diinginkan penghuni, 4) daftar warna interior kamar yang populer dipisah koma contoh: putih, krem, abu-abu, hijau sage. Jawab dalam Bahasa Indonesia.";

                    $response = Http::post($url, [
                        'contents' => [
                            ['parts' => [['text' => $prompt]]]
                        ]
                    ]);

                    $text = $response->json()['candidates'][0]['content']['parts'][0]['text'] ?? null;

                    if ($text) {
                        preg_match_all('/(?:warna|color)[^\n]*:\s*([^\n]+)/i', $text, $matches);
                        $warnaPopuler = !empty($matches[1])
                            ? implode(', ', array_slice($matches[1], 0, 10))
                            : 'putih, krem, abu-abu, hijau sage, biru muda';

                        MarketTrend::create([
                            'nama_tren' => 'Tren Penghuni Kos ' . now()->year,
                            'analisis_ai' => $text,
                            'saran_bahan' => '-',
                            'warna_populer' => $warnaPopuler,
                        ]);

                        Notification::make()
                            ->title('AI Insights berhasil diperbarui!')
                            ->success()
                            ->send();
                    } else {
                        Notification::make()
                            ->title('Gagal mendapatkan respons dari Gemini.')
                            ->danger()
                            ->send();
                    }
                }),

            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Widgets\TrendColorChart::class,
            \App\Filament\Widgets\TrendMaterialChart::class,
        ];
    }
}