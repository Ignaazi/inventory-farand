@extends('layouts.app')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
    
    /* Global Reset & Font */
    body { font-family: 'Inter', sans-serif; background-color: #f8fafc; color: #334155; }
    .font-black-custom { font-weight: 800; }
    
    /* Table Styling Sesuai Master */
    .table-container { border-radius: 0.75rem; background: #ffffff; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1); }
    thead tr { background-color: #f1f5f9; border-bottom: 1px solid #e2e8f0; }
    th { color: #64748b !important; font-size: 11px !important; letter-spacing: 0.05em; text-transform: uppercase; font-weight: 700; }
    td { font-size: 13px; color: #475569; vertical-align: middle; }

    /* Status Pill Transparan (Soft) */
    .status-pill-pending { 
        display: inline-flex; 
        align-items: center; 
        gap: 6px; 
        padding: 4px 12px; 
        border-radius: 9999px; 
        font-size: 10px; 
        font-weight: 700; 
        text-transform: uppercase;
        background-color: #fffbeb; 
        color: #d97706; 
        border: 1px solid #fef3c7;
    }

    /* Status Pill History */
    .status-pill-approved {
        display: inline-flex;
        align-items: center;
        padding: 4px 12px;
        border-radius: 9999px;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        background-color: #ecfdf5;
        color: #10b981;
        border: 1px solid #d1fae5;
    }
    .status-pill-rejected {
        display: inline-flex;
        align-items: center;
        padding: 4px 12px;
        border-radius: 9999px;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        background-color: #fef2f2;
        color: #ef4444;
        border: 1px solid #fee2e2;
    }

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

    /* ADJUSTED BUTTONS STYLE */
    .btn-approve-custom {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        padding: 8px 20px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s ease;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
        border: none;
        cursor: pointer;
    }
    .btn-approve-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(16, 185, 129, 0.3);
        filter: brightness(1.1);
    }

    .btn-reject-custom {
        background-color: #ffffff;
        color: #ef4444;
        padding: 8px 14px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 800;
        border: 2px solid #fecaca;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .btn-reject-custom:hover {
        background-color: #fef2f2;
        border-color: #ef4444;
        color: #dc2626;
    }
</style>

<div class="p-6 md:p-8">
    <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 border-b border-gray-200 pb-6">
        <div>
            <h1 class="text-2xl font-black-custom tracking-tight text-slate-900 uppercase">
                {{ isset($isHistory) ? 'Approval History' : 'Approval Request' }} ({{ strtoupper($type) }})
            </h1>
            <p class="text-[11px] text-slate-500 font-bold tracking-[0.1em] uppercase">
                {{ isset($isHistory) ? 'Log of processed transactions' : 'Pending Transaction Management' }}
            </p>
        </div>
        
        <div class="flex items-center gap-3">
            <div class="bg-white px-4 py-2.5 rounded-xl border border-gray-200 shadow-sm flex items-center gap-2">
                <span class="text-[10px] font-black-custom text-slate-600 uppercase">
                    {{ isset($isHistory) ? 'Total History' : 'Waiting' }}: {{ $requests->total() }} Items
                </span>
            </div>
        </div>
    </div>

    <div class="mb-6">
        <form action="{{ request()->url() }}" method="GET" class="flex flex-col md:flex-row gap-3">
            <div class="relative flex-grow">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, nik, or part..." 
                       class="w-full pl-10 pr-4 py-2.5 input-custom text-sm font-medium outline-none">
            </div>
            <button type="submit" class="bg-slate-900 text-white px-6 py-2.5 rounded-xl text-xs font-bold uppercase tracking-widest hover:bg-black transition-all">
                Filter
            </button>
        </form>
    </div>

    <div class="table-container overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr>
                        <th class="px-6 py-4 text-center border-r border-gray-100 w-16">No</th>
                        <th class="px-6 py-4">Requester</th>
                        <th class="px-6 py-4">Sparepart Details</th>
                        <th class="px-6 py-4 text-center">Qty</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-right">{{ isset($isHistory) ? 'Processed At' : 'Action' }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($requests as $index => $req)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 text-center text-xs text-slate-400 font-bold border-r border-gray-50">
                            {{ $requests->firstItem() + $index }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-800 uppercase leading-tight">{{ $req->nama }}</div>
                            <div class="text-[10px] font-bold text-blue-500 uppercase tracking-tighter">{{ $req->nik }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-800 uppercase tracking-tight">{{ $req->sparepart->part_name }}</div>
                            <code class="bg-slate-100 px-1.5 py-0.5 rounded text-[9px] font-mono text-slate-500 font-bold uppercase">{{ $req->sparepart->sap_code ?? '-' }}</code>
                        </td>
                        <td class="px-6 py-4 text-center font-extrabold text-slate-700 text-sm">
                            <span class="bg-slate-100 px-3 py-1 rounded-lg">{{ $req->qty }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if(isset($isHistory))
                                @if($req->status == 'approved')
                                    <span class="status-pill-approved">Approved</span>
                                @else
                                    <span class="status-pill-rejected">Rejected</span>
                                @endif
                            @else
                                <span class="status-pill-pending">
                                    <span class="dot animate-pulse-custom"></span>
                                    Waiting
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            @if(isset($isHistory))
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">
                                    {{ $req->approved_at ? $req->approved_at->format('d M Y, H:i') : '-' }}
                                </span>
                            @else
                                <form action="{{ route('approvals.process', $req->id) }}" method="POST" class="inline-flex items-center gap-3">
                                    @csrf
                                    <button type="submit" name="action" value="reject" class="btn-reject-custom" title="Reject Request">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                    
                                    <button type="submit" name="action" value="approve" class="btn-approve-custom">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                        Confirm Approve
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-300 font-bold uppercase tracking-widest text-xs">
                            No {{ isset($isHistory) ? 'history' : 'pending requests' }} to display
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $requests->appends(request()->query())->links() }}
    </div>

    <div class="mt-8 flex justify-between items-center text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">
        <p>© 2026 Production System - Approval Module</p>
        <p>Type: {{ strtoupper($type) }} Transaction</p>
    </div>
</div>

<script>
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Action Successful',
            text: "{{ session('success') }}",
            timer: 1500,
            showConfirmButton: false,
            customClass: { popup: 'rounded-xl' }
        });
    @endif

    @if($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "{{ $errors->first() }}",
            customClass: { popup: 'rounded-xl' }
        });
    @endif
</script>
@endsection