<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class FonnteService
{
    public static function send($nomor, $pesan)
    {
        $token = env('FONNTE_TOKEN');

        $response = Http::withHeaders([
            'Authorization' => $token,
        ])->post('https://api.fonnte.com/send', [
            'target' => $nomor,
            'message' => $pesan,
        ]);

        return $response->json();
    }
}