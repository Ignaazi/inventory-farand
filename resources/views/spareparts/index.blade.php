@extends('layouts.app')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
    body { font-family: 'Inter', sans-serif; background-color: #f3f4f6; color: #1f2937; }
    
    .font-black-custom { font-weight: 800; }
    .card-flat { border-radius: 1rem; background: #ffffff; border: 1px solid #e5e7eb; }
    
    .status-pill { display: inline-flex; align-items: center; gap: 6px; padding: 4px 12px; border-radius: 9999px; font-size: 11px; font-weight: 800; border: 1px solid transparent; }
    .status-aman { background-color: #ecfdf5; color: #059669; border-color: #d1fae5; }
    .status-warning { background-color: #fffbeb; color: #d97706; border-color: #fef3c7; }
    .status-critical { background-color: #fef2f2; color: #dc2626; border-color: #fee2e2; }

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
    
    <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-black-custom tracking-tight text-gray-900 uppercase italic">Stock Management</h1>
            <p class="text-xs text-gray-500 font-medium tracking-wide uppercase">Real-time Sparepart Monitoring System</p>
        </div>
        
        <div class="flex items-center gap-2">
            <button @click="exportToCSV()" class="bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-xl text-sm transition-all font-bold shadow-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Export CSV
            </button>
            <button @click="editMode = false; item = { part_name: '', min_stock: 5, serial_number: '', quantity: 1 }; openModal = true;" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl text-sm transition-all font-bold shadow-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                New Part
            </button>
        </div>
    </div>

    <div class="flex flex-col md:flex-row gap-2 mb-4">
        <div class="relative flex-grow">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </span>
            <input type="text" x-model="search" placeholder="Search by name or serial..." 
                   class="w-full pl-10 pr-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm font-medium focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all outline-none">
        </div>

        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" 
                    class="bg-white border border-gray-200 px-4 py-2.5 rounded-xl flex items-center gap-2 text-sm font-bold text-gray-600 hover:bg-gray-50 transition min-w-[120px]">
                <svg class="w-4 h-4" :class="statusFilter !== 'all' ? 'text-blue-600' : 'text-gray-400'" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd"></path></svg>
                <span x-text="statusFilter === 'all' ? 'Status' : statusFilter.toUpperCase()"></span>
            </button>
            <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-44 bg-white border border-gray-100 shadow-xl rounded-xl z-30 py-1 overflow-hidden">
                <button @click="statusFilter = 'all'; open = false" class="w-full text-left px-4 py-2 text-xs font-bold text-gray-400 hover:bg-gray-50 uppercase tracking-widest border-b border-gray-50">Clear Filter</button>
                <button @click="statusFilter = 'aman'; open = false" class="w-full text-left px-4 py-2 text-xs font-bold text-green-600 hover:bg-green-50 flex items-center gap-2"><span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span> AMAN</button>
                <button @click="statusFilter = 'warning'; open = false" class="w-full text-left px-4 py-2 text-xs font-bold text-yellow-600 hover:bg-yellow-50 flex items-center gap-2"><span class="w-1.5 h-1.5 bg-yellow-500 rounded-full"></span> WARNING</button>
                <button @click="statusFilter = 'critical'; open = false" class="w-full text-left px-4 py-2 text-xs font-bold text-red-600 hover:bg-red-50 flex items-center gap-2"><span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span> CRITICAL</button>
            </div>
        </div>

        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" 
                    class="bg-white border border-gray-200 px-4 py-2.5 rounded-xl flex items-center gap-2 text-sm font-bold text-gray-600 hover:bg-gray-50 transition min-w-[120px]">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path></svg>
                <span x-text="sortOrder === 'asc' ? 'A - Z' : 'Z - A'"></span>
            </button>
            <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-32 bg-white border border-gray-100 shadow-xl rounded-xl z-30 py-1 overflow-hidden">
                <button @click="sortOrder = 'asc'; open = false" class="w-full text-left px-4 py-2 text-xs font-bold text-gray-600 hover:bg-gray-50 uppercase tracking-widest">A - Z</button>
                <button @click="sortOrder = 'desc'; open = false" class="w-full text-left px-4 py-2 text-xs font-bold text-gray-600 hover:bg-gray-50 uppercase tracking-widest">Z - A</button>
            </div>
        </div>
    </div>

    <div class="card-flat overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr class="text-[10px] font-black-custom text-gray-400 uppercase tracking-[0.15em]">
                        <th class="px-6 py-4 text-center">No</th>
                        <th class="px-6 py-4">Item Identity</th>
                        <th class="px-6 py-4">SN Label</th>
                        <th class="px-6 py-4 text-center">Stock</th>
                        <th class="px-6 py-4 text-center">Threshold</th>
                        <th class="px-6 py-4 text-center">Inventory Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($spareparts as $index => $s)
                    @php 
                        $stock = $s->items_count;
                        $min = $s->min_stock;
                        $status = 'aman'; $lbl = 'GOOD'; $class = 'status-aman';
                        if ($stock <= 0) { $status = 'critical'; $lbl = 'EMPTY'; $class = 'status-critical'; }
                        elseif ($stock <= $min) { $status = 'warning'; $lbl = 'LOW'; $class = 'status-warning'; }
                        $sn = $s->items->where('status', 'available')->first()->serial_number ?? 'NONE';
                    @endphp
                    <tr class="hover:bg-blue-50/10 transition" x-show="matches('{{ $s->part_name }}', '{{ $sn }}', '{{ $status }}')">
                        <td class="px-6 py-5 text-center text-xs text-gray-400 font-bold">{{ $index + 1 }}</td>
                        <td class="px-6 py-5">
                            <div class="font-bold text-gray-800 uppercase text-sm tracking-tight">{{ $s->part_name }}</div>
                        </td>
                        <td class="px-6 py-5 font-mono text-[10px]">{{ $sn }}</td>
                        <td class="px-6 py-5 text-center font-black text-gray-700">{{ $stock }}</td>
                        <td class="px-6 py-5 text-center text-[10px] text-gray-400 font-bold uppercase">{{ $min }} PCS</td>
                        <td class="px-6 py-5 text-center">
                            <span class="status-pill {{ $class }}">
                                <span class="w-1 h-1 bg-current rounded-full"></span>
                                {{ $lbl }}
                            </span>
                        </td>
                        <td class="px-6 py-5 text-right">
                            <div class="flex justify-end gap-1">
                                <button @click="item = { id: {{ $s->id }}, part_name: '{{ $s->part_name }}', min_stock: {{ $s->min_stock }}, quantity: {{ $s->items_count }}, serial_number: '{{ $sn }}' }; editMode = true; openModal = true;" class="p-2 text-gray-400 hover:text-blue-600 transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></button>
                                <button @click="confirmDelete('{{ route('spareparts.destroy', $s->id) }}')" class="p-2 text-gray-400 hover:text-red-600 transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="px-6 py-16 text-center text-gray-300 font-bold uppercase tracking-widest text-xs">No records available</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div x-show="openModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak x-transition>
        <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm" @click="openModal = false"></div>
        <div class="bg-white w-full max-w-md rounded-2xl p-8 relative shadow-2xl border border-gray-100 transform transition-all">
            <h3 class="text-xl font-black-custom text-gray-900 tracking-tight uppercase mb-6 italic" x-text="editMode ? 'Update Data' : 'New Registration'"></h3>
            
            <form :action="editMode ? `/spareparts/${item.id}` : '{{ route('spareparts.store') }}'" method="POST" class="space-y-5">
                @csrf
                <template x-if="editMode"><input type="hidden" name="_method" value="PUT"></template>
                
                <div class="space-y-1">
                    <label class="text-[10px] font-black-custom text-gray-400 uppercase tracking-widest ml-1">Sparepart Name</label>
                    <input type="text" name="part_name" x-model="item.part_name" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3.5 font-bold text-gray-800 outline-none focus:border-blue-500 transition-all uppercase" required>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="text-[10px] font-black-custom text-gray-400 uppercase tracking-widest ml-1" x-text="editMode ? 'Current Stock' : 'Initial Stock'"></label>
                        <input type="number" name="quantity" x-model="item.quantity" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3.5 font-bold text-gray-800 outline-none focus:border-blue-500 transition-all">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-black-custom text-gray-400 uppercase tracking-widest ml-1">Min Threshold</label>
                        <input type="number" name="min_stock" x-model="item.min_stock" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3.5 font-bold text-gray-800 outline-none focus:border-blue-500 transition-all" required>
                    </div>
                </div>

                <div class="space-y-1">
                    <label class="text-[10px] font-black-custom text-gray-400 uppercase tracking-widest ml-1">Serial Tag (Optional)</label>
                    <input type="text" name="serial_number" x-model="item.serial_number" placeholder="SN-XXXX" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3.5 font-bold text-gray-800 outline-none focus:border-blue-500 transition-all uppercase">
                </div>

                <div class="flex gap-3 pt-3">
                    <button type="button" @click="openModal = false" class="flex-1 bg-gray-100 text-gray-500 py-3 rounded-xl font-bold uppercase text-xs tracking-widest hover:bg-gray-200 transition">Cancel</button>
                    <button type="submit" class="flex-1 bg-blue-600 text-white py-3 rounded-xl font-bold uppercase text-xs tracking-widest shadow-lg shadow-blue-100 hover:bg-blue-700 transition transform hover:-translate-y-0.5">Confirm</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Flash Message Popups
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            timer: 2000,
            showConfirmButton: false,
            customClass: { popup: 'rounded-2xl' }
        });
    @endif

    function confirmDelete(url) {
        Swal.fire({
            title: 'Hapus Data?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#1e293b',
            cancelButtonColor: '#f1f5f9',
            confirmButtonText: '<span class="text-xs font-bold uppercase tracking-widest">Ya, Hapus</span>',
            cancelButtonText: '<span class="text-xs font-bold uppercase tracking-widest text-gray-500">Batal</span>',
            customClass: { confirmButton: 'rounded-xl', cancelButton: 'rounded-xl' }
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