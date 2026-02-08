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

    /* Status Pill / Role Standardization */
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
    .role-admin { background-color: #fef2f2; color: #dc2626; border-color: #fee2e2; }
    .role-leader { background-color: #fffbeb; color: #d97706; border-color: #fef3c7; }
    .role-operator { background-color: #ecfdf5; color: #059669; border-color: #d1fae5; }

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
        font-style: normal !important;
    }
    .input-3d:focus {
        border-color: #3b82f6;
        box-shadow: inset 0 2px 4px 0 rgba(0, 0, 0, 0.02), 0 0 0 4px rgba(59, 130, 246, 0.15);
        transform: translateY(-1px);
    }

    [x-cloak] { display: none !important; }
</style>

<div class="p-6 md:p-8" x-data="{ openModal: false, editMode: false, user: {} }">
    
    <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 border-b border-gray-200 pb-6">
        <div>
            <h1 class="text-2xl font-black-custom tracking-tight text-slate-900 uppercase">User Management</h1>
            <p class="text-[11px] text-slate-500 font-bold tracking-[0.1em] uppercase">Employee Access Control System (NIK & Role)</p>
        </div>
        
        <div class="flex items-center gap-3">
            <button @click="openModal = true; editMode = false; user = {}" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl text-xs transition-all font-bold shadow-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path d="M12 4v16m8-8H4"></path></svg>
                New Employee
            </button>
        </div>
    </div>

    <div class="table-container overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr>
                        <th class="px-6 py-4 text-center border-r border-gray-100">No</th>
                        <th class="px-6 py-4">Employee Details</th>
                        <th class="px-6 py-4">NIK / ID Tag</th>
                        <th class="px-6 py-4 text-center">Access Level</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($users as $index => $u)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 text-center text-xs text-slate-400 font-bold border-r border-gray-50">{{ $index + 1 }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="h-9 w-9 rounded-xl bg-slate-900 flex items-center justify-center text-white font-black text-xs mr-3 shadow-sm uppercase">
                                    {{ substr($u->name, 0, 1) }}
                                </div>
                                <div class="font-bold text-slate-800 uppercase tracking-tight">{{ $u->name }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <code class="bg-slate-100 px-2 py-1 rounded text-[10px] font-mono text-slate-600 font-bold tracking-wider">{{ $u->nik }}</code>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @php
                                $roleClass = $u->role == 'admin' ? 'role-admin' : ($u->role == 'leader' ? 'role-leader' : 'role-operator');
                            @endphp
                            <span class="status-pill {{ $roleClass }}">
                                <span class="dot animate-pulse-custom"></span>
                                {{ $u->role }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                <button @click="openModal = true; editMode = true; user = {{ $u }}" class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-md transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </button>
                                <button @click="confirmDelete('{{ route('users.destroy', $u->id) }}')" class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-md transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div x-show="openModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak x-transition>
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-md" @click="openModal = false"></div>
        <div class="bg-white w-full max-w-lg rounded-[2rem] p-8 relative shadow-2xl border border-gray-200 overflow-hidden transform transition-all">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h3 class="text-xl font-black-custom text-slate-900 tracking-tight uppercase" x-text="editMode ? 'Update Employee' : 'New Registration'"></h3>
                    <div class="h-1 w-12 bg-blue-600 rounded-full mt-1"></div>
                </div>
                <button @click="openModal = false" class="bg-slate-100 p-2 rounded-full text-slate-400 hover:text-red-500 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <form :action="editMode ? `/users/${user.id}` : '{{ route('users.store') }}'" method="POST" class="space-y-6">
                @csrf
                <template x-if="editMode"><input type="hidden" name="_method" value="PUT"></template>
                
                <div class="space-y-2">
                    <label class="text-[11px] font-extrabold text-slate-500 uppercase tracking-widest block ml-1">Full Name</label>
                    <input type="text" name="name" x-model="user.name" class="w-full input-3d px-5 py-4 font-bold text-slate-700 outline-none focus:border-blue-500 uppercase text-base" placeholder="ENTER NAME..." required>
                </div>
                
                <div class="grid grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-[11px] font-extrabold text-slate-500 uppercase tracking-widest block ml-1">NIK (Login ID)</label>
                        <input type="text" name="nik" x-model="user.nik" class="w-full input-3d px-5 py-4 font-bold text-slate-700 outline-none focus:border-blue-500 text-base" placeholder="2026XXX" required>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[11px] font-extrabold text-slate-500 uppercase tracking-widest block ml-1">Access Role</label>
                        <select name="role" x-model="user.role" class="w-full input-3d px-5 py-4 font-bold text-slate-700 outline-none focus:border-blue-500 text-base appearance-none">
                            <option value="operator">OPERATOR</option>
                            <option value="leader">LEADER</option>
                            <option value="admin">ADMIN</option>
                        </select>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[11px] font-extrabold text-slate-500 uppercase tracking-widest block ml-1" x-text="editMode ? 'Change Password (Optional)' : 'Password'"></label>
                    <input type="password" name="password" class="w-full input-3d px-5 py-4 font-bold text-slate-700 outline-none focus:border-blue-500 text-base" :required="!editMode">
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
    // Seragamkan Notifikasi Success (Create & Update)
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

    // Seragamkan Notifikasi Delete dengan Konfirmasi Modern
    function confirmDelete(url) {
        Swal.fire({
            title: 'Delete User?',
            text: "This employee will lose access to the system.",
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