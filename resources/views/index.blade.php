<x-app-layout>
    <div class="p-6">
        <h1 class="text-2xl font-bold">Halaman Inventory Sparepart</h1>
        <p class="mt-2 text-gray-600">Kalau kamu liat tulisan ini, berarti Route & Controller kamu udah Konek!</p>
        
        <form action="{{ route('lines.store') }}" method="POST" class="mt-4">
            @csrf
            <input type="text" name="name" placeholder="Input Nama Line" class="border p-2">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2">Tambah Line</button>
        </form>
    </div>
</x-app-layout>