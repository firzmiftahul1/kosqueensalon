<?php

namespace App\Filament\Resources\KontrakSewaResource\Pages;

use App\Filament\Resources\KontrakSewaResource;
use App\Models\TransaksiPembayaran;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Filament\Resources\Pages\CreateRecord;

class CreateKontraksewa extends CreateRecord
{
    protected static string $resource = KontrakSewaResource::class;

    protected ?int $metodePembayaranId = null;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->metodePembayaranId = $data['id_metode'] ?? null;
        unset($data['id_metode']);

        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        return DB::transaction(function () use ($data): Model {
            $record = static::getModel()::create($data);

            TransaksiPembayaran::create([
                'tanggal' => $record->tanggal_masuk,
                'id_penghuni' => $record->penghuni_id,
                'id_kontrak' => $record->id,
                'id_metode' => $this->metodePembayaranId,
                'jenis_transaksi' => 'pemasukan',
                'total_bayar' => $record->harga_sewa,
                'keterangan' => 'Pembayaran kontrak ' . $record->kode_kontrak,
            ]);

            return $record;
        });
    }
}
