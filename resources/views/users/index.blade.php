<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('User Access Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-200">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold text-slate-700">Daftar Pengguna Sistem</h3>
                        <span class="bg-blue-100 text-blue-700 text-xs font-bold px-3 py-1 rounded-full uppercase">
                            {{ $users->count() }} Terdaftar
                        </span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-100">
                                    <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500">Nama</th>
                                    <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500">Email</th>
                                    <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500">Status Role</th>
                                    <th class="px-6 py-4 text-xs font-bold uppercase text-slate-500 text-center">Update Access</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($users as $user)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4 font-medium text-slate-800">{{ $user->name }}</td>
                                    <td class="px-6 py-4 text-slate-600">{{ $user->email }}</td>
                                    <td class="px-6 py-4 text-sm">
                                        @php
                                            $badge = [
                                                'admin' => 'bg-purple-100 text-purple-700 border-purple-200',
                                                'leader' => 'bg-blue-100 text-blue-700 border-blue-200',
                                                'operator' => 'bg-gray-100 text-gray-700 border-gray-200'
                                            ][$user->role] ?? 'bg-gray-50 text-gray-500';
                                        @endphp
                                        <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase border {{ $badge }}">
                                            {{ $user->role }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <form action="{{ route('users.update-role', $user->id) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <select name="role" onchange="this.form.submit()" class="text-xs rounded-lg border-gray-300 bg-gray-50 focus:ring-blue-500">
                                                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                                <option value="leader" {{ $user->role == 'leader' ? 'selected' : '' }}>Leader</option>
                                                <option value="operator" {{ $user->role == 'operator' ? 'selected' : '' }}>Operator</option>
                                            </select>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>