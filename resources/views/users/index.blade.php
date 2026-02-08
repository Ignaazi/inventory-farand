@extends('layouts.app')

@section('content')
<div class="p-6" x-data="{ openModal: false, editMode: false, user: {} }">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Management User</h2>
            <p class="text-gray-500 text-sm">Kelola data akses karyawan (NIK & Role)</p>
        </div>
        <button @click="openModal = true; editMode = false; user = {}" 
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition font-semibold shadow-md">
            + Tambah Karyawan
        </button>
    </div>

    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm">
        <table class="w-full text-left text-gray-600">
            <thead class="bg-gray-50 text-gray-500 uppercase text-[11px] tracking-widest border-b border-gray-200">
                <tr>
                    <th class="px-6 py-4">Karyawan</th>
                    <th class="px-6 py-4">NIK / ID</th>
                    <th class="px-6 py-4">Level Akses</th>
                    <th class="px-6 py-4 text-right">Tindakan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($users as $u)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            <div class="h-8 w-8 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 font-bold mr-3 border border-blue-100">
                                {{ substr($u->name, 0, 1) }}
                            </div>
                            <span class="font-medium text-gray-900">{{ $u->name }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 font-mono text-sm text-gray-500">{{ $u->nik }}</td>
                    <td class="px-6 py-4">
                        @php
                            $color = $u->role == 'admin' ? 'text-red-600 bg-red-50 border-red-100' : 
                                    ($u->role == 'leader' ? 'text-green-600 bg-green-50 border-green-100' : 'text-blue-600 bg-blue-50 border-blue-100');
                        @endphp
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase border {{ $color }}">
                            {{ $u->role }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end space-x-3">
                            <button @click="openModal = true; editMode = true; user = {{ $u }}" class="text-gray-400 hover:text-blue-600 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </button>
                            <form action="{{ route('users.destroy', $u->id) }}" method="POST" onsubmit="return confirm('Hapus user ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-gray-400 hover:text-red-600 transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div x-show="openModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak x-transition>
        <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm" @click="openModal = false"></div>
        <div class="bg-white w-full max-w-md rounded-2xl p-6 relative shadow-2xl overflow-hidden border border-gray-100">
            <div class="absolute top-0 left-0 w-full h-1 bg-blue-600"></div>
            <h3 class="text-xl font-bold text-gray-800 mb-6" x-text="editMode ? 'Edit Data User' : 'Tambah User Baru'"></h3>
            
            <form :action="editMode ? `/users/${user.id}` : '{{ route('users.store') }}'" method="POST">
                @csrf
                <template x-if="editMode"><input type="hidden" name="_method" value="PUT"></template>
                
                <div class="space-y-4">
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Nama Lengkap</label>
                        <input type="text" name="name" x-model="user.name" class="w-full bg-gray-50 border border-gray-200 rounded-lg p-3 mt-1 text-gray-800 focus:border-blue-500 outline-none transition" placeholder="Masukkan nama..." required>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">NIK (ID Login)</label>
                        <input type="text" name="nik" x-model="user.nik" class="w-full bg-gray-50 border border-gray-200 rounded-lg p-3 mt-1 text-gray-800 focus:border-blue-500 outline-none transition font-mono" placeholder="Contoh: 2026001" required>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Level Jabatan</label>
                        <select name="role" x-model="user.role" class="w-full bg-gray-50 border border-gray-200 rounded-lg p-3 mt-1 text-gray-800 focus:border-blue-500 outline-none transition">
                            <option value="operator">Operator</option>
                            <option value="leader">Leader</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wider" x-text="editMode ? 'Password (Kosongkan jika tidak diubah)' : 'Password'"></label>
                        <input type="password" name="password" class="w-full bg-gray-50 border border-gray-200 rounded-lg p-3 mt-1 text-gray-800 focus:border-blue-500 outline-none transition" :required="!editMode">
                    </div>
                </div>

                <div class="flex space-x-3 mt-8">
                    <button type="button" @click="openModal = false" class="flex-1 bg-gray-100 text-gray-600 py-3 rounded-xl hover:bg-gray-200 transition font-semibold">Batal</button>
                    <button type="submit" class="flex-1 bg-blue-600 text-white py-3 rounded-xl hover:bg-blue-700 transition font-bold shadow-lg">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection