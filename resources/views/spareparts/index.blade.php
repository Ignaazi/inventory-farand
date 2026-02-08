@extends('layouts.app')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
    
    /* Global Reset & Font */
    body { font-family: 'Inter', sans-serif; background-color: #f8fafc; color: #334155; }
    
    .font-black-custom { font-weight: 800; }
    
    /* Table Styling */
    .table-container { border-radius: 0.75rem; background: #ffffff; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1); }
    thead tr { background-color: #f1f5f9; border-bottom: 1px solid #e2e8f0; }
    th { color: #64748b !important; font-size: 11px !important; letter-spacing: 0.05em; text-transform: uppercase; font-weight: 700; }
    td { font-size: 13px; color: #475569; vertical-align: middle; }

    /* Status Pill Standardization */
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
    }
    .status-aman { background-color: #ecfdf5; color: #059669; border-color: #d1fae5; }
    .status-warning { background-color: #fffbeb; color: #d97706; border-color: #fef3c7; }
    .status-critical { background-color: #fef2f2; color: #dc2626; border-color: #fee2e2; }

    /* Indicator Dot Animation */
    .dot { width: 6px; height: 6px; border-radius: 50%; background-color: currentColor; }
    .animate-pulse-custom { animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite; }
    @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: .5; } }

    /* 3D Input Styling */
    .input-3d {
        background-color: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 0.75rem;
        box-shadow: inset 0 2px 4px 0 rgba(0, 0, 0, 0.05), 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        transition: all 0.2s ease;
        font-style: normal !important; /* Memastikan tidak miring */
    }
    .input-3d:focus {
        border-color: #3b82f6;
        box-shadow: inset 0 2px 4px 0 rgba(0, 0, 0, 0.02), 0 0 0 4px rgba(59, 130, 246, 0.15);
        transform: translateY(-1px);
    }

    /* Input & Search bar Styling (Main Page) */
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

    [x-cloak] { display: none !important; }
</style>

<div class="p-6 md:p-8" x-data="{ 
    openModal: false, 
    editMode: false, 
    item: { part_name: '', min_stock: 5, serial_number: '', quantity: 1 }, 
    search: '', 
    statusFilter: 'all',
    sortOrder: 'asc',
    
    matches(partName, sn, status) {
        const matchesSearch = (partName + sn).toLowerCase().includes(this.search.toLowerCase());
        const matchesStatus = this.statusFilter === 'all' || status === this.statusFilter;
        return matchesSearch && matchesStatus;
    },

    exportToCSV() {
        let csvContent = 'No,Sparepart Name,Serial Number,Stock,Threshold,Status\n';
        const rows = Array.from(document.querySelectorAll('tbody tr')).filter(row => row.style.display !== 'none');
        
        if(rows.length === 0 || rows[0].innerText.includes('No records')) {
            Swal.fire('Opps!', 'Tidak ada data untuk di-export', 'warning');
            return;
        }

        rows.forEach((row, index) => {
            const cols = row.querySelectorAll('td');
            const no = index + 1;
            const name = cols[1].innerText.trim().split('\n')[0];
            const sn = cols[2].innerText.trim();
            const stock = cols[3].innerText.trim();
            const threshold = cols[4].innerText.trim().replace(' PCS', '');
            const status = cols[5].innerText.trim();
            csvContent += `${no},'${name}','${sn}',${stock},${threshold},${status}\n`;
        });

        const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
        const url = URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.setAttribute('href', url);
        link.setAttribute('download', `Inventory_Report_${new Date().toISOString().slice(0,10)}.csv`);
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        
        Swal.fire({ icon: 'success', title: 'Export Berhasil', timer: 1000, showConfirmButton: false });
    }
}">
    
    <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 border-b border-gray-200 pb-6">
        <div>
            <h1 class="text-2xl font-black-custom tracking-tight text-slate-900 uppercase">Stock Management</h1>
            <p class="text-[11px] text-slate-500 font-bold tracking-[0.1em] uppercase">Real-time Sparepart Monitoring System</p>
        </div>
        
        <div class="flex items-center gap-3">
            <button @click="exportToCSV()" class="bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-xl text-xs transition-all font-bold shadow-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Export CSV
            </button>
            <button @click="editMode = false; item = { part_name: '', min_stock: 5, serial_number: '', quantity: 1 }; openModal = true;" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl text-xs transition-all font-bold shadow-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                New Part
            </button>
        </div>
    </div>

    <div class="flex flex-col md:flex-row gap-3 mb-6">
        <div class="relative flex-grow">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </span>
            <input type="text" x-model="search" placeholder="Search by name or serial..." 
                   class="w-full pl-10 pr-4 py-2.5 input-custom text-sm font-medium outline-none">
        </div>

        <div class="flex gap-2">
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" 
                        class="bg-white border border-gray-200 px-4 py-2.5 rounded-xl flex items-center gap-2 text-xs font-bold text-slate-600 hover:bg-gray-50 transition min-w-[130px]">
                    <svg class="w-3.5 h-3.5" :class="statusFilter !== 'all' ? 'text-blue-600' : 'text-slate-400'" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd"></path></svg>
                    <span x-text="statusFilter === 'all' ? 'All Status' : statusFilter.toUpperCase()"></span>
                </button>
                <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-44 bg-white border border-gray-100 shadow-xl rounded-xl z-30 py-1 overflow-hidden">
                    <button @click="statusFilter = 'all'; open = false" class="w-full text-left px-4 py-2 text-[10px] font-bold text-slate-400 hover:bg-gray-50 uppercase tracking-widest border-b border-gray-50">Clear Filter</button>
                    <button @click="statusFilter = 'aman'; open = false" class="w-full text-left px-4 py-2 text-xs font-bold text-green-600 hover:bg-green-50 flex items-center gap-2">Aman</button>
                    <button @click="statusFilter = 'warning'; open = false" class="w-full text-left px-4 py-2 text-xs font-bold text-yellow-600 hover:bg-yellow-50 flex items-center gap-2">Warning</button>
                    <button @click="statusFilter = 'critical'; open = false" class="w-full text-left px-4 py-2 text-xs font-bold text-red-600 hover:bg-red-50 flex items-center gap-2">Critical</button>
                </div>
            </div>
        </div>
    </div>

    <div class="table-container overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr>
                        <th class="px-6 py-4 text-center border-r border-gray-100">No</th>
                        <th class="px-6 py-4">Item Identity</th>
                        <th class="px-6 py-4">SN Label</th>
                        <th class="px-6 py-4 text-center">Stock</th>
                        <th class="px-6 py-4 text-center">Threshold</th>
                        <th class="px-6 py-4 text-center">Inventory Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($spareparts as $index => $s)
                    @php 
                        $stock = $s->items_count;
                        $min = $s->min_stock;
                        $status = 'aman'; $lbl = 'GOOD'; $class = 'status-aman';
                        if ($stock <= 0) { $status = 'critical'; $lbl = 'EMPTY'; $class = 'status-critical'; }
                        elseif ($stock <= $min) { $status = 'warning'; $lbl = 'LOW'; $class = 'status-warning'; }
                        $sn = $s->items->where('status', 'available')->first()->serial_number ?? 'NONE';
                    @endphp
                    <tr class="hover:bg-slate-50 transition-colors" x-show="matches('{{ $s->part_name }}', '{{ $sn }}', '{{ $status }}')">
                        <td class="px-6 py-4 text-center text-xs text-slate-400 font-bold border-r border-gray-50">{{ $index + 1 }}</td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-800 uppercase tracking-tight">{{ $s->part_name }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <code class="bg-slate-100 px-2 py-1 rounded text-[10px] font-mono text-slate-600 font-bold">{{ $sn }}</code>
                        </td>
                        <td class="px-6 py-4 text-center font-extrabold text-slate-700 text-sm">{{ $stock }}</td>
                        <td class="px-6 py-4 text-center text-[10px] text-slate-400 font-bold uppercase">{{ $min }} PCS</td>
                        <td class="px-6 py-4 text-center">
                            <span class="status-pill {{ $class }}">
                                <span class="dot animate-pulse-custom"></span>
                                {{ $lbl }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                <button @click="item = { id: {{ $s->id }}, part_name: '{{ $s->part_name }}', min_stock: {{ $s->min_stock }}, quantity: {{ $s->items_count }}, serial_number: '{{ $sn }}' }; editMode = true; openModal = true;" class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-md transition-all"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></button>
                                <button @click="confirmDelete('{{ route('spareparts.destroy', $s->id) }}')" class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-md transition-all"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="px-6 py-12 text-center text-slate-300 font-bold uppercase tracking-widest text-xs">No records available</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div x-show="openModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak x-transition>
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-md" @click="openModal = false"></div>
        <div class="bg-white w-full max-w-lg rounded-[2rem] p-8 relative shadow-2xl border border-gray-200 overflow-hidden transform transition-all">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h3 class="text-xl font-black-custom text-slate-900 tracking-tight uppercase" x-text="editMode ? 'Update Sparepart' : 'New Registration'"></h3>
                    <div class="h-1 w-12 bg-blue-600 rounded-full mt-1"></div>
                </div>
                <button @click="openModal = false" class="bg-slate-100 p-2 rounded-full text-slate-400 hover:text-red-500 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <form :action="editMode ? `/spareparts/${item.id}` : '{{ route('spareparts.store') }}'" method="POST" class="space-y-6">
                @csrf
                <template x-if="editMode"><input type="hidden" name="_method" value="PUT"></template>
                
                <div class="space-y-2">
                    <label class="text-[11px] font-extrabold text-slate-500 uppercase tracking-widest block ml-1">Sparepart Name</label>
                    <input type="text" name="part_name" x-model="item.part_name" class="w-full input-3d px-5 py-4 font-bold text-slate-700 outline-none focus:border-blue-500 uppercase text-base" placeholder="ENTER PART NAME..." required>
                </div>
                
                <div class="grid grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-[11px] font-extrabold text-slate-500 uppercase tracking-widest block ml-1" x-text="editMode ? 'Current Stock' : 'Initial Qty'"></label>
                        <input type="number" name="quantity" x-model="item.quantity" class="w-full input-3d px-5 py-4 font-bold text-slate-700 outline-none focus:border-blue-500 text-base" placeholder="0">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[11px] font-extrabold text-slate-500 uppercase tracking-widest block ml-1">Threshold (Min)</label>
                        <input type="number" name="min_stock" x-model="item.min_stock" class="w-full input-3d px-5 py-4 font-bold text-slate-700 outline-none focus:border-blue-500 text-base" placeholder="5" required>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[11px] font-extrabold text-slate-500 uppercase tracking-widest block ml-1">Serial Tag Number</label>
                    <input type="text" name="serial_number" x-model="item.serial_number" placeholder="SN-XXXX-XXXX" class="w-full input-3d px-5 py-4 font-bold text-slate-700 outline-none focus:border-blue-500 uppercase text-base">
                </div>

                <div class="flex gap-4 pt-6">
                    <button type="button" @click="openModal = false" class="flex-1 bg-slate-100 text-slate-500 py-4 rounded-xl font-bold uppercase text-xs tracking-widest hover:bg-slate-200 transition-all border border-slate-200">Cancel</button>
                    <button type="submit" class="flex-1 bg-blue-600 text-white py-4 rounded-xl font-bold uppercase text-xs tracking-widest shadow-[0_4px_14px_0_rgba(37,99,235,0.39)] hover:bg-blue-700 transition-all transform hover:-translate-y-1 active:scale-95">Save Changes</button>
                </div>
            </form>
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

    function confirmDelete(url) {
        Swal.fire({
            title: 'Delete Item?',
            text: "This action cannot be undone!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#000000',
            cancelButtonColor: '#f1f5f9',
            confirmButtonText: '<span class="text-xs font-bold uppercase tracking-widest">Confirm</span>',
            cancelButtonText: '<span class="text-xs font-bold uppercase tracking-widest text-gray-500">Cancel</span>',
            customClass: { confirmButton: 'rounded-lg', cancelButton: 'rounded-lg' }
        }).then((result) => {
            if (result.isConfirmed) {
                let form = document.createElement('form');
                form.action = url; form.method = 'POST';
                form.innerHTML = `@csrf @method('DELETE')`;
                document.body.appendChild(form);
                form.submit();
            }
        })
    }
</script>
@endsection