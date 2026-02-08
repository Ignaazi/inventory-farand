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
    /** --- REQUEST SECTION (USER) --- **/

    public function createIn()
    {
        $spareparts = Sparepart::all();
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

        return redirect()->route('requests.history')->with('success', 'Permintaan berhasil dikirim.');
    }

    public function history(Request $request)
    {
        $query = SparepartRequest::with('sparepart');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'LIKE', "%{$search}%")
                  ->orWhere('nik', 'LIKE', "%{$search}%")
                  ->orWhereHas('sparepart', function($sq) use ($search) {
                      $sq->where('part_name', 'LIKE', "%{$search}%")
                        ->orWhere('sap_code', 'LIKE', "%{$search}%");
                  });
            });
        }

        $history = $query->latest()->paginate(20);
        return view('requests.history', compact('history'));
    }


    /** --- APPROVAL SECTION (ADMIN) --- **/

    public function indexIn(Request $request)
    {
        $type = 'in';
        $requests = $this->getPendingRequests($request, $type);
        return view('approvals.approval-list', compact('requests', 'type'));
    }

    public function indexOut(Request $request)
    {
        $type = 'out';
        $requests = $this->getPendingRequests($request, $type);
        return view('approvals.approval-list', compact('requests', 'type'));
    }

    public function approvalHistory(Request $request)
    {
        $query = SparepartRequest::with('sparepart')
                    ->whereIn('status', ['approved', 'rejected']);

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'LIKE', "%{$search}%")
                  ->orWhere('nik', 'LIKE', "%{$search}%")
                  ->orWhereHas('sparepart', function($sq) use ($search) {
                      $sq->where('part_name', 'LIKE', "%{$search}%")
                        ->orWhere('sap_code', 'LIKE', "%{$search}%");
                  });
            });
        }

        $requests = $query->latest()->paginate(20);
        $type = 'History'; 
        $isHistory = true; 

        return view('approvals.approval-list', compact('requests', 'type', 'isHistory'));
    }

    private function getPendingRequests(Request $request, $type)
    {
        $query = SparepartRequest::with('sparepart')
                    ->where('type', $type)
                    ->where('status', 'pending');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'LIKE', "%{$search}%")
                  ->orWhere('nik', 'LIKE', "%{$search}%")
                  ->orWhereHas('sparepart', function($sq) use ($search) {
                      $sq->where('part_name', 'LIKE', "%{$search}%")
                        ->orWhere('sap_code', 'LIKE', "%{$search}%");
                  });
            });
        }

        return $query->latest()->paginate(20);
    }

    public function process(Request $request, $id)
    {
        $req = SparepartRequest::findOrFail($id);
        
        if ($request->action == 'approve') {
            try {
                DB::beginTransaction();

                if ($req->type == 'out') {
                    $sparepart = Sparepart::findOrFail($req->sparepart_id);
                    $availableItems = $sparepart->items()->where('status', 'available');

                    if ($availableItems->count() < $req->qty) {
                        return back()->withErrors(['msg' => 'Gagal: Stok aktual tidak mencukupi!']);
                    }

                    $availableItems->limit($req->qty)->delete();
                } 
                else {
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
                return back()->with('success', 'Request Approved & Stock Updated.');

            } catch (\Exception $e) {
                DB::rollBack();
                return back()->withErrors(['msg' => 'Error: ' . $e->getMessage()]);
            }
        } 
        
        $req->update(['status' => 'rejected', 'approved_at' => now()]);
        return back()->with('success', 'Request Rejected.');
    }
}