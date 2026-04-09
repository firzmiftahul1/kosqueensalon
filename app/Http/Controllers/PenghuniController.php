<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penghuni;

class PenghuniController extends Controller
{
    // Menampilkan semua penghuni
    public function index()
    {
        $penghunis = Penghuni::all();
        return response()->json([
            'success' => true,
            'data' => $penghunis
        ]);
    }

    // Menampilkan satu penghuni berdasarkan id
    public function show($id)
    {
        $penghuni = Penghuni::find($id);

        if (!$penghuni) {
            return response()->json([
                'success' => false,
                'message' => 'Penghuni tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $penghuni
        ]);
    }

    // Menambahkan penghuni baru
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_hp' => 'required|string|max:20',
        ]);

        $penghuni = Penghuni::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Penghuni berhasil ditambahkan',
            'data' => $penghuni
        ], 201);
    }

    // Mengupdate data penghuni
    public function update(Request $request, $id)
    {
        $penghuni = Penghuni::find($id);

        if (!$penghuni) {
            return response()->json([
                'success' => false,
                'message' => 'Penghuni tidak ditemukan'
            ], 404);
        }

        $validated = $request->validate([
            'nama' => 'sometimes|required|string|max:255',
            'alamat' => 'sometimes|required|string',
            'no_hp' => 'sometimes|required|string|max:20',
        ]);

        $penghuni->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Data penghuni berhasil diperbarui',
            'data' => $penghuni
        ]);
    }

    // Menghapus penghuni
    public function destroy($id)
    {
        $penghuni = Penghuni::find($id);

        if (!$penghuni) {
            return response()->json([
                'success' => false,
                'message' => 'Penghuni tidak ditemukan'
            ], 404);
        }

        $penghuni->delete();

        return response()->json([
            'success' => true,
            'message' => 'Penghuni berhasil dihapus'
        ]);
    }
}