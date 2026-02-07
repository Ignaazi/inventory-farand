<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Production Activity Monitoring') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-blue-500">
                    <p class="text-sm font-medium text-gray-500 uppercase">Total Sparepart</p>
                    <p class="text-3xl font-bold text-gray-900">1,240</p>
                    <p class="text-xs text-blue-600 mt-2 font-semibold">Semua Line</p>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-green-500">
                    <p class="text-sm font-medium text-gray-500 uppercase">Stock Aman</p>
                    <p class="text-3xl font-bold text-gray-900">850</p>
                    <p class="text-xs text-green-600 mt-2 font-semibold">Status: Normal</p>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-yellow-500">
                    <p class="text-sm font-medium text-gray-500 uppercase">Stock Warning</p>
                    <p class="text-3xl font-bold text-gray-900">12</p>
                    <p class="text-xs text-yellow-600 mt-2 font-semibold">Perlu Re-order</p>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-red-500">
                    <p class="text-sm font-medium text-gray-500 uppercase">Stock Habis</p>
                    <p class="text-3xl font-bold text-gray-900">3</p>
                    <p class="text-xs text-red-600 mt-2 font-semibold">Segera Input Stock</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 bg-white p-6 rounded-xl shadow-sm">
                    <h3 class="text-lg font-bold text-gray-700 mb-4">Activity In/Out Sparepart</h3>
                    <div class="h-64">
                        <canvas id="activityChart"></canvas>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm">
                    <h3 class="text-lg font-bold text-gray-700 mb-4">Cleaning Monitor</h3>
                    <div class="space-y-4">
                        <div class="flex items-center text-sm border-b pb-2">
                            <span class="w-2 h-2 rounded-full bg-blue-500 mr-2"></span>
                            <p class="flex-1"><strong>SN-001</strong> sedang di cleaning</p>
                            <span class="text-gray-400 text-xs">2m ago</span>
                        </div>
                        <div class="flex items-center text-sm border-b pb-2">
                            <span class="w-2 h-2 rounded-full bg-green-500 mr-2"></span>
                            <p class="flex-1"><strong>SN-098</strong> selesai cleaning</p>
                            <span class="text-gray-400 text-xs">15m ago</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('activityChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                datasets: [{
                    label: 'Sparepart Out',
                    data: [12, 19, 3, 5, 2, 3, 9],
                    borderColor: 'rgb(239, 68, 68)',
                    tension: 0.3,
                    fill: true,
                    backgroundColor: 'rgba(239, 68, 68, 0.1)'
                }, {
                    label: 'Sparepart In',
                    data: [10, 15, 8, 12, 7, 10, 14],
                    borderColor: 'rgb(34, 197, 94)',
                    tension: 0.3,
                    fill: true,
                    backgroundColor: 'rgba(34, 197, 94, 0.1)'
                }]
            },
            options: {
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    </script>
</x-app-layout>