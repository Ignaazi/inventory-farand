<?php

namespace App\Http\Controllers;

use App\Models\Line;
use App\Models\Sparepart;
use App\Models\SparepartItem;
use Illuminate\Http\Request;

class SparepartController extends Controller
{
    /**
     * Menampilkan daftar sparepart dan form input.
     */
    public function index()
    {
        $lines = Line::all();
        
        // Kita ambil sparepart, hitung jumlah item yang statusnya 'available'
        // dan urutkan berdasarkan yang terbaru diinput.
        $spareparts = Sparepart::withCount(['items' => function($query) {
            $query->where('status', 'available');
        }])->with('line')->latest()->get();

        return view('spareparts.index', compact('spareparts', 'lines'));
    }

    /**
     * Menyimpan Data Line Baru.
     */
    public function storeLine(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:lines,name'
        ]);

        Line::create(['name' => $request->name]);

        return back()->with('success', 'Line Baru Berhasil Ditambahkan!');
    }

    /**
     * Menyimpan Sparepart Baru sekaligus mendaftarkan Serial Number-nya.
     */
    public function store(Request $request)
    {
        $request->validate([
            'line_id' => 'required|exists:lines,id',
            'part_name' => 'required|string|max:255',
            'sap_code' => 'required|unique:spareparts,sap_code',
            'serial_numbers' => 'required', // Format: SN01, SN02, SN03
            'min_stock' => 'nullable|integer'
        ]);

        // 1. Buat Header Sparepart
        $sparepart = Sparepart::create([
            'line_id' => $request->line_id,
            'part_name' => $request->part_name,
            'sap_code' => $request->sap_code,
            'min_stock' => $request->min_stock ?? 5,
        ]);

        // 2. Pecah input Serial Number (pisahkan berdasarkan koma)
        $sns = explode(',', $request->serial_numbers);
        
        foreach ($sns as $sn) {
            if (!empty(trim($sn))) {
                SparepartItem::create([
                    'sparepart_id' => $sparepart->id,
                    'serial_number' => trim($sn),
                    'status' => 'available',
                ]);
            }
        }

        return back()->with('success', 'Sparepart dan Serial Number Berhasil Terdaftar!');
    }

    /**
     * Menghapus Sparepart (Otomatis menghapus semua SN terkait karena onDelete cascade)
     */
    public function destroy($id)
    {
        Sparepart::destroy($id);
        return back()->with('success', 'Data Sparepart Berhasil Dihapus!');
    }
}