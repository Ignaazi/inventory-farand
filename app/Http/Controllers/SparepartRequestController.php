<?php

namespace App\Http\Controllers;

use App\Models\Sparepart;
use App\Models\SparepartRequest;
use App\Models\SparepartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SparepartRequestController extends Controller
{
    /** --- REQUEST SECTION --- **/

    public function createIn()
    {
        $spareparts = Sparepart::all(); // Ambil jenis sparepart untuk dropdown
        $type = 'in';
        return view('requests.form', compact('spareparts', 'type'));
    }

    public function createOut()
    {
        $spareparts = Sparepart::all();
        $type = 'out';
        return view('requests.form', compact('spareparts', 'type'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nik'          => 'required|string',
            'nama'         => 'required|string',
            'sparepart_id' => 'required|exists:spareparts,id',
            'qty'          => 'required|integer|min:1',
            'type'         => 'required|in:in,out',
            'remark'       => 'nullable|string',
        ]);

        SparepartRequest::create([
            'nik'          => $request->nik,
            'nama'         => $request->nama,
            'sparepart_id' => $request->sparepart_id,
            'qty'          => $request->qty,
            'type'         => $request->type,
            'remark'       => $request->remark,
            'user_id'      => Auth::id(),
            'status'       => 'pending',
        ]);

        return redirect()->route('requests.history')->with('success', 'Permintaan berhasil dikirim dan menunggu approval.');
    }

    public function history()
    {
        $history = SparepartRequest::with('sparepart')->latest()->get();
        return view('requests.history', compact('history'));
    }


    /** --- APPROVAL SECTION --- **/

    public function indexIn()
    {
        $requests = SparepartRequest::with('sparepart')
                    ->where('type', 'in')
                    ->where('status', 'pending')
                    ->get();
        
        $type = 'in'; // Definisi variabel agar aman di compact()
        return view('approvals.index', compact('requests', 'type'));
    }

    public function indexOut()
    {
        $requests = SparepartRequest::with('sparepart')
                    ->where('type', 'out')
                    ->where('status', 'pending')
                    ->get();

        $type = 'out'; // Definisi variabel agar aman di compact()
        return view('approvals.index', compact('requests', 'type'));
    }

    public function process(Request $request, $id)
    {
        $req = SparepartRequest::findOrFail($id);
        
        if ($request->action == 'approve') {
            try {
                DB::beginTransaction();

                if ($req->type == 'out') {
                    // Logic Kurangi Stok
                    $sparepart = Sparepart::findOrFail($req->sparepart_id);
                    $availableItems = $sparepart->items()->where('status', 'available');

                    if ($availableItems->count() < $req->qty) {
                        return back()->withErrors(['msg' => 'Gagal: Stok aktual tidak mencukupi!']);
                    }

                    // Hapus item sejumlah qty yang diminta
                    $availableItems->limit($req->qty)->delete();
                } 
                else {
                    // Logic Tambah Stok (In)
                    for ($i = 0; $i < $req->qty; $i++) {
                        SparepartItem::create([
                            'sparepart_id'  => $req->sparepart_id,
                            'serial_number' => 'SN-' . strtoupper(uniqid()) . '-IN',
                            'status'        => 'available',
                        ]);
                    }
                }

                $req->update([
                    'status' => 'approved',
                    'approved_at' => now()
                ]);

                DB::commit();
                return back()->with('success', 'Request berhasil disetujui dan stok telah diperbarui.');

            } catch (\Exception $e) {
                DB::rollBack();
                return back()->withErrors(['msg' => 'Terjadi kesalahan: ' . $e->getMessage()]);
            }
        } 
        
        // Jika Rejected
        $req->update(['status' => 'rejected']);
        return back()->with('success', 'Request telah ditolak.');
    }
}