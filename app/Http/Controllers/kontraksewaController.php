<?php

namespace App\Http\Controllers;

use App\Models\KontrakSewa; // Pastikan Model ini sudah dibuat
use App\Models\Penghuni;
use App\Models\Kamar;
use Illuminate\Http\Request;

class KontrakSewaController extends Controller
{
    /**
     * Menampilkan daftar semua kontrak sewa.
     */
    public function index()
    {
        $kontrak = KontrakSewa::with(['penghuni', 'kamar'])->get();
        return view('kontrak.index', compact('kontrak'));
    }

    /**
     * Menampilkan form untuk membuat kontrak baru.
     */
    public function create()
    {
        $penghuni = Penghuni::all();
        $kamar = Kamar::where('status_kamar', 'Tersedia')->get();
        return view('kontrak.create', compact('penghuni', 'kamar'));
    }

    /**
     * Menyimpan data kontrak baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_penghuni'     => 'required|exists:penghuni,id_penghuni',
            'id_kamar'        => 'required|exists:kamar,id_kamar',
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'status'          => 'required|string',
        ]);

        KontrakSewa::create($request->all());

        return redirect()->route('kontrak.index')->with('success', 'Kontrak berhasil dibuat.');
    }

    /**
     * Menampilkan detail kontrak tertentu.
     */
    public function show($id)
    {
        $kontrak = KontrakSewa::with(['penghuni', 'kamar'])->findOrFail($id);
        return view('kontrak.show', compact('kontrak'));
    }

    /**
     * Menampilkan form edit kontrak.
     */
    public function edit($id)
    {
        $kontrak = KontrakSewa::findOrFail($id);
        $penghuni = Penghuni::all();
        $kamar = Kamar::all();
        return view('kontrak.edit', compact('kontrak', 'penghuni', 'kamar'));
    }

    /**
     * Memperbarui data kontrak di database.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'id_penghuni'     => 'required',
            'id_kamar'        => 'required',
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date',
            'status'          => 'required',
        ]);

        $kontrak = KontrakSewa::findOrFail($id);
        $kontrak->update($request->all());

        return redirect()->route('kontrak.index')->with('success', 'Kontrak berhasil diperbarui.');
    }

    /**
     * Menghapus data kontrak.
     */
    public function destroy($id)
    {
        $kontrak = KontrakSewa::findOrFail($id);
        $kontrak->delete();

        return redirect()->route('kontrak.index')->with('success', 'Kontrak berhasil dihapus.');
    }
}