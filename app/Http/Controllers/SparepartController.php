<?php

namespace App\Http\Controllers;

use App\Models\Sparepart;
use App\Models\SparepartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SparepartController extends Controller
{
    public function index()
    {
        $spareparts = Sparepart::withCount(['items' => function($query) {
            $query->where('status', 'available');
        }])->latest()->get();

        return view('spareparts.index', compact('spareparts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'part_name'     => 'required|string|max:255',
            'min_stock'     => 'required|integer|min:0',
            'quantity'      => 'nullable|integer|min:0',
            'serial_number' => 'nullable|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            $sparepart = Sparepart::create([
                'part_name' => $request->part_name,
                'min_stock' => $request->min_stock,
            ]);

            $qty = $request->quantity ?? 0;

            for ($i = 0; $i < $qty; $i++) {
                SparepartItem::create([
                    'sparepart_id'  => $sparepart->id,
                    'serial_number' => ($i == 0 && $request->serial_number) 
                                        ? $request->serial_number 
                                        : 'SN-' . strtoupper(uniqid()) . '-' . ($i + 1),
                    'status'        => 'available',
                ]);
            }

            DB::commit();
            return back()->with('success', "Sparepart baru berhasil didaftarkan dengan stok $qty unit!");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['msg' => 'Gagal simpan: ' . $e->getMessage()]);
        }
    }

    /**
     * Update data & Sinkronisasi Stok Aktual.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'part_name' => 'required|string|max:255',
            'min_stock' => 'required|integer|min:0',
            'quantity'  => 'required|integer|min:0', // Sekarang wajib untuk sinkronisasi stok
        ]);

        try {
            DB::beginTransaction();

            $sparepart = Sparepart::findOrFail($id);
            $sparepart->update([
                'part_name' => $request->part_name,
                'min_stock' => $request->min_stock,
            ]);

            // Hitung stok saat ini (hanya yang statusnya available)
            $currentStock = $sparepart->items()->where('status', 'available')->count();
            $targetStock = $request->quantity;

            if ($targetStock > $currentStock) {
                // Jika input lebih besar, tambahkan selisihnya
                $diff = $targetStock - $currentStock;
                for ($i = 0; $i < $diff; $i++) {
                    SparepartItem::create([
                        'sparepart_id'  => $sparepart->id,
                        'serial_number' => 'SN-' . strtoupper(uniqid()) . '-UPD',
                        'status'        => 'available',
                    ]);
                }
            } elseif ($targetStock < $currentStock) {
                // Jika input lebih kecil (misal dari 5 jadi 3), hapus selisihnya
                $diff = $currentStock - $targetStock;
                // Hapus item yang 'available' sebanyak selisih tersebut
                $sparepart->items()
                    ->where('status', 'available')
                    ->limit($diff)
                    ->delete();
            }

            DB::commit();
            return back()->with('success', "Data dan jumlah stok berhasil disinkronisasi menjadi $targetStock unit!");
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['msg' => 'Gagal update: ' . $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            $sparepart = Sparepart::findOrFail($id);
            $sparepart->items()->delete();
            $sparepart->delete();

            return back()->with('success', 'Data Sparepart berhasil dihapus secara permanen!');
        } catch (\Exception $e) {
            return back()->withErrors(['msg' => 'Gagal menghapus: ' . $e->getMessage()]);
        }
    }
}