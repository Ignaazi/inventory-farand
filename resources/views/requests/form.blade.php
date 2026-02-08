@extends('layouts.app')

@section('content')
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
    
    body { font-family: 'Inter', sans-serif; background-color: #f8fafc; color: #334155; overflow-x: hidden; }
    .font-black-custom { font-weight: 800; }
    
    /* Container 3D Styling - Responsive Border */
    .card-request-3d { 
        background: #ffffff;
        border-radius: 1rem; 
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
    }
    @media (min-width: 768px) { .card-request-3d { border-radius: 1.25rem; } }

    /* Theme Colors */
    .type-in { border-left: 5px solid #22c55e; border-bottom: 5px solid #22c55e; }
    .type-out { border-left: 5px solid #ef4444; border-bottom: 5px solid #ef4444; }
    @media (min-width: 768px) {
        .type-in { border-left-width: 8px; border-bottom-width: 8px; }
        .type-out { border-left-width: 8px; border-bottom-width: 8px; }
    }

    /* Input & Select Styling - Mobile Friendly */
    .form-input-custom {
        width: 100%;
        border-radius: 0.75rem;
        border: 1px solid #e2e8f0;
        padding: 0.875rem 1rem; /* Padding lebih besar untuk touch target */
        font-size: 0.875rem;
        font-weight: 600;
        transition: all 0.2s;
        -webkit-appearance: none; /* Reset CSS for mobile Safari */
    }
    .form-input-custom:focus {
        outline: none;
        border-color: #1e293b;
        box-shadow: 0 0 0 4px rgba(226, 232, 240, 0.5);
    }

    /* Animation */
    @keyframes slideIn {
        from { transform: translateY(-20px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    .animate-entry { animation: slideIn 0.5s ease-out forwards; }
</style>

<div class="p-4 md:p-10 max-w-4xl mx-auto" x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 100)">
    
    @if(session('success') || session('error'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
         class="fixed top-4 right-4 left-4 md:left-auto z-[9999] md:min-w-[350px]">
        <div class="p-4 rounded-xl border-l-8 shadow-2xl flex items-center justify-between bg-white {{ session('success') ? 'border-green-500' : 'border-red-500' }}">
            <div class="flex items-center gap-3">
                <span class="{{ session('success') ? 'text-green-500' : 'text-red-500' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="{{ session('success') ? 'M5 13l4 4L19 7' : 'M6 18L18 6M6 6l12 12' }}"></path></svg>
                </span>
                <p class="text-[10px] md:text-xs font-black-custom uppercase tracking-wide text-slate-700">{{ session('success') ?? session('error') }}</p>
            </div>
            <button @click="show = false" class="text-slate-400"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
        </div>
    </div>
    @endif

    <div class="mb-6 md:mb-10 transition-all duration-700 transform"
         :class="loaded ? 'translate-y-0 opacity-100' : '-translate-y-4 opacity-0'">
        <h1 class="text-xl md:text-3xl font-black-custom tracking-tight text-slate-900 uppercase">Request Form</h1>
        <div class="flex items-center gap-2 mt-1">
            <span class="w-8 h-1 {{ $type == 'in' ? 'bg-green-500' : 'bg-red-500' }} rounded-full"></span>
            <p class="text-[10px] md:text-[11px] text-slate-500 font-bold tracking-[0.2em] uppercase">Inventory {{ $type }} System</p>
        </div>
    </div>

    <div class="card-request-3d {{ $type == 'in' ? 'type-in' : 'type-out' }} transition-all duration-700 delay-100 transform"
         :class="loaded ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0'">
        
        <div class="p-5 md:p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50 rounded-t-[1rem] md:rounded-t-[1.25rem]">
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-xl flex items-center justify-center text-white {{ $type == 'in' ? 'bg-green-500' : 'bg-red-500' }} shadow-lg shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                </div>
                <div class="hidden sm:block">
                    <h2 class="text-[11px] font-black-custom text-slate-800 uppercase tracking-tight">Transaction Detail</h2>
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Type: {{ strtoupper($type) }}</p>
                </div>
            </div>
            <div class="bg-white border border-slate-200 px-3 py-1.5 rounded-lg">
                <p class="text-[9px] font-black-custom text-slate-400 uppercase leading-none mb-1">Status</p>
                <p class="text-[10px] font-black-custom {{ $type == 'in' ? 'text-green-600' : 'text-red-600' }} uppercase leading-none">Ready to Process</p>
            </div>
        </div>

        <form action="{{ route('requests.store') }}" method="POST" class="p-5 md:p-10 space-y-6">
            @csrf
            <input type="hidden" name="type" value="{{ $type }}">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 md:gap-8">
                <div class="space-y-2">
                    <label class="text-[10px] font-black-custom text-slate-400 uppercase tracking-[0.15em] ml-1">NIK Requester</label>
                    <input type="text" name="nik" class="form-input-custom text-slate-700" placeholder="Input NIK..." required>
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black-custom text-slate-400 uppercase tracking-[0.15em] ml-1">Full Name</label>
                    <input type="text" name="nama" class="form-input-custom text-slate-700" placeholder="Input Nama..." required>
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-black-custom text-slate-400 uppercase tracking-[0.15em] ml-1">Sparepart Name</label>
                <div class="relative">
                    <select name="sparepart_id" class="form-input-custom text-slate-700 bg-white" required>
                        <option value="" disabled selected>Search & Select Sparepart</option>
                        @foreach($spareparts as $s)
                            <option value="{{ $s->id }}">{{ strtoupper($s->part_name) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="space-y-2">
                    <label class="text-[10px] font-black-custom text-slate-400 uppercase tracking-[0.15em] ml-1">Quantity (PCS)</label>
                    <input type="number" name="qty" min="1" class="form-input-custom text-slate-700" placeholder="0" required>
                </div>
                <div class="md:col-span-2 space-y-2">
                    <label class="text-[10px] font-black-custom text-slate-400 uppercase tracking-[0.15em] ml-1">Remark / Purpose</label>
                    <input type="text" name="remark" class="form-input-custom text-slate-700" placeholder="Contoh: Perbaikan Mesin Line 1">
                </div>
            </div>

            <div class="pt-6">
                <button type="submit" class="w-full bg-slate-900 hover:bg-black text-white py-4 rounded-xl text-xs font-black-custom uppercase tracking-[0.3em] transition-all shadow-xl active:scale-95 flex items-center justify-center gap-4">
                    Send Request
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </button>
            </div>
        </form>
    </div>

    <p class="text-center mt-8 text-[9px] font-bold text-slate-400 uppercase tracking-[0.2em]">
        Ensure all data is correct before submitting
    </p>
</div>
@endsection