@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                <div>
                    <h2 class="text-xl font-bold text-slate-800">Request Sparepart {{ ucfirst($type) }}</h2>
                    <p class="text-sm text-slate-500">Silahkan isi formulir permintaan barang</p>
                </div>
                <span class="px-3 py-1 rounded-full text-xs font-bold {{ $type == 'in' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                    TYPE: {{ strtoupper($type) }}
                </span>
            </div>

            <form action="{{ route('requests.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <input type="hidden" name="type" value="{{ $type }}">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">NIK</label>
                        <input type="text" name="nik" class="w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500" placeholder="Masukkan NIK" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Lengkap</label>
                        <input type="text" name="nama" class="w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500" placeholder="Nama Peminta" required>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Pilih Sparepart</label>
                    <select name="sparepart_id" class="w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                        <option value="">-- Pilih Jenis Sparepart --</option>
                        @foreach($spareparts as $s)
                            <option value="{{ $s->id }}">{{ $s->part_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Quantity (Qty)</label>
                    <input type="number" name="qty" min="1" class="w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500" placeholder="Jumlah barang" required>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Remark / Keperluan</label>
                    <textarea name="remark" rows="3" class="w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500" placeholder="Contoh: Untuk perbaikan Mesin A..."></textarea>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl transition-all shadow-lg shadow-blue-200">
                        Kirim Request
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection