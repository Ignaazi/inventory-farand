@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-slate-800">History Request</h2>
        <p class="text-slate-500 text-sm">Daftar riwayat permintaan Sparepart In & Out</p>
    </div>

    @if(session('success'))
    <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700">
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="p-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Tanggal</th>
                        <th class="p-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Tipe</th>
                        <th class="p-4 text-xs font-bold text-slate-500 uppercase tracking-wider">NIK / Nama</th>
                        <th class="p-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Sparepart</th>
                        <th class="p-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Qty</th>
                        <th class="p-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($history as $item)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="p-4 text-sm text-slate-600">{{ $item->created_at->format('d/m/Y H:i') }}</td>
                        <td class="p-4">
                            <span class="px-2 py-1 rounded-md text-[10px] font-bold uppercase {{ $item->type == 'in' ? 'bg-emerald-100 text-emerald-600' : 'bg-rose-100 text-rose-600' }}">
                                {{ $item->type }}
                            </span>
                        </td>
                        <td class="p-4 text-sm">
                            <div class="font-medium text-slate-700">{{ $item->nama }}</div>
                            <div class="text-xs text-slate-400">{{ $item->nik }}</div>
                        </td>
                        <td class="p-4 text-sm font-medium text-slate-700">
                            {{ $item->sparepart->part_name ?? 'Sparepart Tidak Ditemukan' }}
                        </td>
                        <td class="p-4 text-sm font-bold text-center text-slate-700">{{ $item->qty }}</td>
                        <td class="p-4 text-center">
                            @if($item->status == 'pending')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                    <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                    Pending
                                </span>
                            @elseif($item->status == 'approved')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Approved
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    Rejected
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-8 text-center text-slate-400 italic">
                            Belum ada riwayat permintaan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection