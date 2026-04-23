@extends('layouts.app')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
    
    body { font-family: 'Inter', sans-serif; background-color: #f8fafc; color: #334155; }
    .font-black-custom { font-weight: 800; }
    
    /* Table Styling */
    .table-container { border-radius: 0.75rem; background: #ffffff; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1); }
    thead tr { background-color: #f1f5f9; border-bottom: 1px solid #e2e8f0; }
    th { color: #64748b !important; font-size: 11px !important; letter-spacing: 0.05em; text-transform: uppercase; font-weight: 700; }
    td { font-size: 13px; color: #475569; vertical-align: middle; }

    /* Status Pill Standardization */
    .status-pill { display: inline-flex; align-items: center; gap: 6px; padding: 4px 12px; border-radius: 9999px; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.025em; border: 1px solid transparent; min-width: 105px; justify-content: center; }
    .status-ready { background-color: #ecfdf5; color: #059669; border-color: #d1fae5; }
    .status-process { background-color: #fffbeb; color: #d97706; border-color: #fef3c7; }
    .status-dirty { background-color: #fef2f2; color: #dc2626; border-color: #fee2e2; }

    /* Indicator Dot */
    .dot { width: 6px; height: 6px; border-radius: 50%; background-color: currentColor; }
    .animate-pulse-custom { animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite; }
    @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: .5; } }

    /* 3D Input Styling */
    .input-3d { background-color: #ffffff; border: 1px solid #cbd5e1; border-radius: 0.75rem; box-shadow: inset 0 2px 4px 0 rgba(0, 0, 0, 0.05), 0 1px 2px 0 rgba(0, 0, 0, 0.05); transition: all 0.2s ease; }
    .input-3d:focus { border-color: #3b82f6; box-shadow: inset 0 2px 4px 0 rgba(0, 0, 0, 0.02), 0 0 0 4px rgba(59, 130, 246, 0.15); transform: translateY(-1px); }
    .input-custom { background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 0.75rem; transition: all 0.2s; }
    [x-cloak] { display: none !important; }
</style>

<div class="p-6 md:p-8" x-data="{ 
    openModal: false, 
    editMode: false, 
    item: { part_name: '', status: 'dirty' }, 
    search: '', 
    statusFilter: 'all',
    
    matches(partName, status) {
        return partName.toLowerCase().includes(this.search.toLowerCase()) && 
               (this.statusFilter === 'all' || status === this.statusFilter);
    }
}">
    
    <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 border-b border-gray-200 pb-6">
        <div>
            <h1 class="text-2xl font-black-custom tracking-tight text-slate-900 uppercase">Cleaning Module</h1>
            <p class="text-[11px] text-slate-500 font-bold tracking-[0.1em] uppercase">Sparepart Maintenance & Sanitation Schedule</p>
        </div>
        
        <div class="flex items-center gap-3">
            <button @click="editMode = false; item = { part_name: '', status: 'dirty' }; openModal = true;" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl text-xs transition-all font-bold shadow-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                Log Cleaning Entry
            </button>
        </div>
    </div>

    <div class="flex flex-col md:flex-row gap-3 mb-6">
        <div class="relative flex-grow">
            <input type="text" x-model="search" placeholder="Search part name..." class="w-full pl-4 pr-4 py-2.5 input-custom text-sm font-medium outline-none">
        </div>
        <select x-model="statusFilter" class="bg-white border border-gray-200 px-4 py-2.5 rounded-xl text-xs font-bold text-slate-600 outline-none">
            <option value="all">ALL STATUS</option>
            <option value="ready">CLEANED</option>
            <option value="process">IN PROGRESS</option>
            <option value="dirty">NEEDS CLEANING</option>
        </select>
    </div>

    <div class="table-container overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr>
                    <th class="px-6 py-4 text-center">No</th>
                    <th class="px-6 py-4">Sparepart Item</th>
                    <th class="px-6 py-4 text-center">Last Cleaned</th>
                    <th class="px-6 py-4 text-center">Cleaning Status</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                {{-- Example Row --}}
                <tr class="hover:bg-slate-50 transition-colors" x-show="matches('Nozzle Type A', 'dirty')">
                    <td class="px-6 py-4 text-center text-xs text-slate-400 font-bold">1</td>
                    <td class="px-6 py-4 font-bold text-slate-800 uppercase">Nozzle Type A</td>
                    <td class="px-6 py-4 text-center text-slate-500 text-xs font-bold">20/04/2026</td>
                    <td class="px-6 py-4 text-center">
                        <span class="status-pill status-dirty">
                            <span class="dot animate-pulse-custom"></span> NEEDS CLEANING
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <button class="text-blue-600 font-bold text-xs uppercase hover:underline">Update</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div x-show="openModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak>
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-md" @click="openModal = false"></div>
        <div class="bg-white w-full max-w-lg rounded-[2rem] p-8 relative shadow-2xl border border-gray-200 overflow-hidden transform transition-all">
            <h3 class="text-xl font-black-custom text-slate-900 tracking-tight uppercase mb-6" x-text="editMode ? 'Update Status' : 'New Cleaning Log'"></h3>
            
            <form action="#" method="POST" class="space-y-6">
                @csrf
                <div class="space-y-2">
                    <label class="text-[11px] font-extrabold text-slate-500 uppercase tracking-widest block ml-1">Sparepart Name</label>
                    <input type="text" name="part_name" class="w-full input-3d px-5 py-4 font-bold text-slate-700 outline-none uppercase text-base" required>
                </div>
                
                <div class="space-y-2">
                    <label class="text-[11px] font-extrabold text-slate-500 uppercase tracking-widest block ml-1">Current Status</label>
                    <select name="status" class="w-full input-3d px-5 py-4 font-bold text-slate-700 outline-none text-base bg-white">
                        <option value="dirty">NEEDS CLEANING</option>
                        <option value="process">IN PROGRESS</option>
                        <option value="ready">CLEANED</option>
                    </select>
                </div>

                <div class="flex gap-4 pt-4">
                    <button type="button" @click="openModal = false" class="flex-1 bg-slate-100 text-slate-500 py-4 rounded-xl font-bold uppercase text-xs tracking-widest hover:bg-slate-200 transition-all">Cancel</button>
                    <button type="submit" class="flex-1 bg-blue-600 text-white py-4 rounded-xl font-bold uppercase text-xs tracking-widest hover:bg-blue-700 transition-all">Save Status</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection