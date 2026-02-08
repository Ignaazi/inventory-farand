@extends('layouts.app')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
    
    body { font-family: 'Inter', sans-serif; background-color: #f8fafc; color: #334155; }
    .font-black-custom { font-weight: 800; }
    
    .table-container { border-radius: 0.75rem; background: #ffffff; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1); }
    thead tr { background-color: #f1f5f9; border-bottom: 1px solid #e2e8f0; }
    th { color: #64748b !important; font-size: 11px !important; letter-spacing: 0.05em; text-transform: uppercase; font-weight: 700; }
    td { font-size: 13px; color: #475569; vertical-align: middle; }

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

    .dot { width: 6px; height: 6px; border-radius: 50%; background-color: currentColor; }
    .animate-pulse-custom { animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite; }
    @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: .5; } }

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
    }
    .btn-approve-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(16, 185, 129, 0.3);
        filter: brightness(1.1);
    }
    .btn-approve-custom:active { transform: translateY(0); }

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
            <h1 class="text-2xl font-black-custom tracking-tight text-slate-900 uppercase">Waiting Approval ({{ $type }})</h1>
            <p class="text-[11px] text-slate-500 font-bold tracking-[0.1em] uppercase">Pending Requests Needing Review</p>
        </div>
        <div class="bg-white px-4 py-2.5 rounded-xl border border-gray-200 shadow-sm">
            <span class="text-[10px] font-black-custom text-slate-600 uppercase">Total: {{ $requests->count() }} Items</span>
        </div>
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
                        <th class="px-6 py-4 text-right">Action Controls</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($requests as $index => $req)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 text-center text-xs text-slate-400 font-bold border-r border-gray-50">{{ $index + 1 }}</td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-800 uppercase">{{ $req->nama }}</div>
                            <div class="text-[10px] font-bold text-blue-500 uppercase">{{ $req->nik }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-800 uppercase">{{ $req->sparepart->part_name }}</div>
                            <code class="bg-slate-100 px-1.5 py-0.5 rounded text-[9px] font-mono text-slate-500 font-bold uppercase">{{ $req->sparepart->sap_code ?? '-' }}</code>
                        </td>
                        <td class="px-6 py-4 text-center font-extrabold text-slate-700 text-sm">
                            <span class="bg-slate-100 px-3 py-1 rounded-lg">{{ $req->qty }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="status-pill-pending">
                                <span class="dot animate-pulse-custom"></span>
                                Waiting
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
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
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-300 font-bold uppercase tracking-widest text-xs">No pending requests</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    @if(session('success'))
        Swal.fire({ icon: 'success', title: 'Action Successful', text: "{{ session('success') }}", timer: 1500, showConfirmButton: false, customClass: { popup: 'rounded-xl' } });
    @endif
</script>
@endsection