@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Waiting Approval ({{ strtoupper($type) }})</h2>
            <p class="text-slate-500 text-sm">Daftar permintaan sparepart yang perlu diproses</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead class="bg-slate-50 border-b border-slate-100">
                <tr>
                    <th class="p-4 text-xs font-bold text-slate-500 uppercase">Peminta</th>
                    <th class="p-4 text-xs font-bold text-slate-500 uppercase">Sparepart</th>
                    <th class="p-4 text-xs font-bold text-slate-500 uppercase text-center">Qty</th>
                    <th class="p-4 text-xs font-bold text-slate-500 uppercase">Remark</th>
                    <th class="p-4 text-xs font-bold text-slate-500 uppercase text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($requests as $req)
                <tr class="hover:bg-slate-50 transition">
                    <td class="p-4">
                        <div class="font-bold text-slate-800">{{ $req->nama }}</div>
                        <div class="text-xs text-slate-400">{{ $req->nik }}</div>
                    </td>
                    <td class="p-4 font-medium text-slate-700">{{ $req->sparepart->part_name }}</td>
                    <td class="p-4 text-center">
                        <span class="px-2.5 py-1 rounded-lg bg-blue-50 text-blue-700 font-bold">{{ $req->qty }}</span>
                    </td>
                    <td class="p-4 text-sm text-slate-500">{{ $req->remark ?? '-' }}</td>
                    <td class="p-4 text-right">
                        <form action="{{ route('approvals.process', $req->id) }}" method="POST" class="inline-flex gap-2">
                            @csrf
                            <button name="action" value="reject" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition" title="Reject">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                            <button name="action" value="approve" class="flex items-center gap-1 bg-green-500 hover:bg-green-600 text-white px-3 py-1.5 rounded-lg font-bold text-sm shadow-sm transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Approve
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-10 text-center text-slate-400 italic">Tidak ada permintaan pending saat ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection