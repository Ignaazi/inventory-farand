@extends('layouts.app')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
    
    /* Global Reset & Font */
    body { font-family: 'Inter', sans-serif; background-color: #f8fafc; color: #334155; }
    .font-black-custom { font-weight: 800; }
    
    /* Table Styling Sesuai Stock Management */
    .table-container { border-radius: 0.75rem; background: #ffffff; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1); }
    thead tr { background-color: #f1f5f9; border-bottom: 1px solid #e2e8f0; }
    th { color: #64748b !important; font-size: 11px !important; letter-spacing: 0.05em; text-transform: uppercase; font-weight: 700; }
    td { font-size: 13px; color: #475569; vertical-align: middle; }

    /* Status Pill Standardization (Transparan/Soft) */
    .status-pill { 
        display: inline-flex; 
        align-items: center; 
        gap: 6px; 
        padding: 4px 12px; 
        border-radius: 9999px; 
        font-size: 10px; 
        font-weight: 700; 
        text-transform: uppercase;
        letter-spacing: 0.025em;
        border: 1px solid transparent;
        min-width: 105px; /* Biar sejajar */
    }
    .status-aman { background-color: #ecfdf5; color: #059669; border-color: #d1fae5; }
    .status-pending { background-color: #fffbeb; color: #d97706; border-color: #fef3c7; }
    .status-critical { background-color: #fef2f2; color: #dc2626; border-color: #fee2e2; }

    /* Indicator Dot Animation */
    .dot { width: 6px; height: 6px; border-radius: 50%; background-color: currentColor; }
    .animate-pulse-custom { animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite; }
    @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: .5; } }

    /* Input & Search bar Styling */
    .input-custom {
        background-color: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 0.75rem;
        transition: all 0.2s;
    }
    .input-custom:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
    }

    /* Pagination Styling */
    .pagination-container { margin-top: 20px; }
    .pagination-container nav svg { width: 1.25rem; }
</style>

<div class="p-6 md:p-8" x-data="{ 
    search: '', 
    typeFilter: 'all',
    
    matches(nama, nik, partName, type) {
        const matchesSearch = (nama + nik + partName).toLowerCase().includes(this.search.toLowerCase());
        const matchesType = this.typeFilter === 'all' || type === this.typeFilter;
        return matchesSearch && matchesType;
    }
}">
    
    <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 border-b border-gray-200 pb-6">
        <div>
            <h1 class="text-2xl font-black-custom tracking-tight text-slate-900 uppercase">History Request</h1>
            <p class="text-[11px] text-slate-500 font-bold tracking-[0.1em] uppercase">Transaction Log Sparepart In & Out</p>
        </div>
        
        <div class="flex items-center gap-3">
            <div class="bg-white px-4 py-2.5 rounded-xl border border-gray-200 shadow-sm flex items-center gap-2">
                <span class="text-[10px] font-black-custom text-slate-600 uppercase">
                    Total: {{ method_exists($history, 'total') ? $history->total() : $history->count() }}
                </span>
            </div>
        </div>
    </div>

    <div class="flex flex-col md:flex-row gap-3 mb-6">
        <div class="relative flex-grow">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </span>
            <input type="text" x-model="search" placeholder="Search by name, nik, or part..." 
                   class="w-full pl-10 pr-4 py-2.5 input-custom text-sm font-medium outline-none">
        </div>

        <div class="flex gap-2">
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" 
                        class="bg-white border border-gray-200 px-4 py-2.5 rounded-xl flex items-center gap-2 text-xs font-bold text-slate-600 hover:bg-gray-50 transition min-w-[130px]">
                    <svg class="w-3.5 h-3.5" :class="typeFilter !== 'all' ? 'text-blue-600' : 'text-slate-400'" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd"></path></svg>
                    <span x-text="typeFilter === 'all' ? 'All Types' : typeFilter.toUpperCase()"></span>
                </button>
                <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-44 bg-white border border-gray-100 shadow-xl rounded-xl z-30 py-1 overflow-hidden">
                    <button @click="typeFilter = 'all'; open = false" class="w-full text-left px-4 py-2 text-[10px] font-bold text-slate-400 hover:bg-gray-50 uppercase tracking-widest border-b border-gray-50">Clear Filter</button>
                    <button @click="typeFilter = 'in'; open = false" class="w-full text-left px-4 py-2 text-xs font-bold text-green-600 hover:bg-green-50 flex items-center gap-2 font-black-custom">INBOUND</button>
                    <button @click="typeFilter = 'out'; open = false" class="w-full text-left px-4 py-2 text-xs font-bold text-red-600 hover:bg-red-50 flex items-center gap-2 font-black-custom">OUTBOUND</button>
                </div>
            </div>
        </div>
    </div>

    <div class="table-container overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr>
                        <th class="px-6 py-4 text-center border-r border-gray-100 w-16">No</th>
                        <th class="px-6 py-4">Timestamp</th>
                        <th class="px-6 py-4">Type</th>
                        <th class="px-6 py-4">Requester</th>
                        <th class="px-6 py-4">Sparepart Item</th>
                        <th class="px-6 py-4 text-center">Qty</th>
                        <th class="px-6 py-4 text-center">Final Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($history as $index => $item)
                    <tr class="hover:bg-slate-50 transition-colors" 
                        x-show="matches('{{ $item->nama }}', '{{ $item->nik }}', '{{ $item->sparepart->part_name ?? '' }}', '{{ $item->type }}')">
                        <td class="px-6 py-4 text-center text-xs text-slate-400 font-bold border-r border-gray-50">
                            {{ method_exists($history, 'firstItem') ? $history->firstItem() + $index : $index + 1 }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-700 uppercase leading-tight">{{ $item->created_at->format('d/m/Y') }}</div>
                            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">{{ $item->created_at->format('H:i') }} WIB</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded text-[10px] font-black-custom uppercase {{ $item->type == 'in' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $item->type }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-800 uppercase tracking-tight">{{ $item->nama }}</div>
                            <div class="text-[10px] font-bold text-blue-500 uppercase tracking-tighter">{{ $item->nik }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-800 uppercase tracking-tight">{{ $item->sparepart->part_name ?? 'NOT FOUND' }}</div>
                            <code class="bg-slate-100 px-1.5 py-0.5 rounded text-[9px] font-mono text-slate-500 font-bold uppercase">{{ $item->sparepart->sap_code ?? '-' }}</code>
                        </td>
                        <td class="px-6 py-4 text-center font-extrabold text-slate-700 text-sm">{{ $item->qty }}</td>
                        <td class="px-6 py-4 text-center">
                            @php
                                $statusClass = 'status-pending'; $label = 'PENDING';
                                if($item->status == 'approved') { $statusClass = 'status-aman'; $label = 'APPROVED'; }
                                elseif($item->status == 'rejected') { $statusClass = 'status-critical'; $label = 'REJECTED'; }
                            @endphp
                            <span class="status-pill {{ $statusClass }}">
                                <span class="dot {{ $item->status == 'pending' ? 'animate-pulse-custom' : '' }}"></span>
                                {{ $label }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-slate-300 font-bold uppercase tracking-widest text-xs">
                            No records available in history
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if(method_exists($history, 'links'))
    <div class="pagination-container">
        {{ $history->appends(request()->query())->links() }}
    </div>
    @endif

    <div class="mt-6 flex flex-col sm:flex-row justify-between items-center gap-4 text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">
        <p>© 2026 Production System - History Module</p>
        <div class="flex gap-4">
            <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-emerald-500"></span> Approved</span>
            <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-amber-500"></span> Pending</span>
            <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-red-500"></span> Rejected</span>
        </div>
    </div>
</div>

<script>
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: "{{ session('success') }}",
            timer: 1500,
            showConfirmButton: false,
            customClass: { popup: 'rounded-xl' }
        });
    @endif
</script>
@endsection