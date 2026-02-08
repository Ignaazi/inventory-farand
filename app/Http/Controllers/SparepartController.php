<?php

namespace App\Http\Controllers;

use App\Models\Sparepart;
use App\Models\SparepartItem;
use Illuminate\Http\Request;

class SparepartController extends Controller
{
    /**
     * Menampilkan daftar sparepart.
     */
    public function index()
    {
        // Ambil data sparepart dengan hitungan stok available dari relasi items
        $spareparts = Sparepart::withCount(['items' => function($query) {
            $query->where('status', 'available');
        }])->latest()->get();

        return view('spareparts.index', compact('spareparts'));
    }

    /**
     * Menyimpan Sparepart Baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'part_name'     => 'required|string|max:255',
            'min_stock'     => 'required|integer|min:0',
            'quantity'      => 'nullable|integer|min:1', // Input quantity manual
            'serial_number' => 'nullable|string|max:255',
        ]);

        try {
            $sparepart = Sparepart::create([
                'part_name' => $request->part_name,
                'min_stock' => $request->min_stock,
            ]);

            // Ambil jumlah quantity, default 1 jika tidak diisi
            $qty = $request->quantity ?? 1;

            for ($i = 0; $i < $qty; $i++) {
                SparepartItem::create([
                    'sparepart_id'  => $sparepart->id,
                    // Baris pertama pakai SN inputan (jika ada), sisanya generate otomatis
                    'serial_number' => ($i == 0 && $request->serial_number) 
                                        ? $request->serial_number 
                                        : 'SN-' . strtoupper(uniqid()) . '-' . ($i + 1),
                    'status'        => 'available',
                ]);
            }

            return back()->with('success', "Data Sparepart Berhasil Masuk dengan $qty unit!");

        } catch (\Exception $e) {
            return back()->withErrors(['msg' => 'Gagal simpan: ' . $e->getMessage()]);
        }
    }

    /**
     * Update data sparepart (Fitur Edit & Tambah Stok).
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'part_name' => 'required|string|max:255',
            'min_stock' => 'required|integer|min:0',
            'quantity'  => 'nullable|integer|min:0', // Untuk tambah stok manual saat edit
        ]);

        try {
            $sparepart = Sparepart::findOrFail($id);
            $sparepart->update([
                'part_name' => $request->part_name,
                'min_stock' => $request->min_stock,
            ]);

            // Jika user mengisi angka di input quantity saat edit, tambahkan stok baru
            if ($request->quantity > 0) {
                for ($i = 0; $i < $request->quantity; $i++) {
                    SparepartItem::create([
                        'sparepart_id'  => $sparepart->id,
                        'serial_number' => 'SN-' . strtoupper(uniqid()) . '-ADD',
                        'status'        => 'available',
                    ]);
                }
                $msg = "Data diperbarui dan " . $request->quantity . " unit stok ditambahkan!";
            } else {
                $msg = "Data Sparepart Berhasil Diperbarui!";
            }

            return back()->with('success', $msg);
        } catch (\Exception $e) {
            return back()->withErrors(['msg' => 'Gagal update: ' . $e->getMessage()]);
        }
    }

    /**
     * Menghapus Sparepart secara total.
     */
    public function destroy($id)
    {
        try {
            $sparepart = Sparepart::findOrFail($id);
            $sparepart->items()->delete();
            $sparepart->delete();

            return back()->with('success', 'Data Sparepart dan Serial Number Berhasil Dihapus!');
        } catch (\Exception $e) {
            return back()->withErrors(['msg' => 'Gagal menghapus: ' . $e->getMessage()]);
        }
    }
}