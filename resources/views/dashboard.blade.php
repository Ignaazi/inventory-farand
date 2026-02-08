@extends('layouts.app')

@section('content')
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
    
    body { font-family: 'Inter', sans-serif; background-color: #f8fafc; color: #334155; overflow-x: hidden; }
    .font-black-custom { font-weight: 800; }
    
    /* 3D Stat Card Styling */
    .stat-card-3d { 
        background: #ffffff;
        border-radius: 1rem; 
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid #e2e8f0;
    }
    
    @media (min-width: 768px) {
        .stat-card-3d:hover { transform: translateY(-8px); box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1); }
        .stat-card-3d { border-radius: 1.25rem; }
    }

    /* Variasi Warna 3D - Border Responsif */
    .card-blue { border-left: 4px solid #2563eb; border-bottom: 4px solid #2563eb; }
    .card-green { border-left: 4px solid #22c55e; border-bottom: 4px solid #22c55e; }
    .card-yellow { border-left: 4px solid #f59e0b; border-bottom: 4px solid #f59e0b; }
    .card-red { border-left: 4px solid #ef4444; border-bottom: 4px solid #ef4444; }

    @media (min-width: 768px) {
        .card-blue, .card-green, .card-yellow, .card-red { border-left-width: 6px; border-bottom-width: 6px; }
    }

    .container-flat { border-radius: 1rem; background: #ffffff; border: 1px solid #e2e8f0; }
    @media (min-width: 768px) { .container-flat { border-radius: 1.25rem; } }
    
    .dot { width: 8px; height: 8px; border-radius: 50%; background-color: currentColor; }
    .animate-pulse-custom { animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite; }
    @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: .5; } }
</style>

<div class="p-4 md:p-8 max-w-[1600px] mx-auto" x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 100)">
    
    <div class="mb-6 md:mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-gray-200 pb-6 transition-all duration-700 transform"
         :class="loaded ? 'translate-y-0 opacity-100' : '-translate-y-4 opacity-0'">
        <div>
            <h1 class="text-xl md:text-2xl font-black-custom tracking-tight text-slate-900 uppercase">Dashboard Monitoring</h1>
            <p class="text-[10px] md:text-[11px] text-slate-500 font-bold tracking-[0.1em] uppercase">Production Activity Monitoring System</p>
        </div>
        
        <div class="flex items-center gap-2 bg-white px-3 py-1.5 md:px-4 md:py-2 rounded-xl border border-gray-200 shadow-sm self-end sm:self-auto">
            <span class="dot animate-pulse-custom text-green-500"></span>
            <span class="text-[9px] md:text-[10px] font-bold text-slate-600 uppercase tracking-widest text-nowrap">
                LIVE: <span id="realtime-clock">00:00:00</span>
            </span>
        </div>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-6 mb-8 transition-all duration-700 delay-100 transform"
         :class="loaded ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0'">
        <div class="stat-card-3d card-blue p-4 md:p-6">
            <p class="text-[9px] md:text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-1">Total Part</p>
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-1">
                <p class="text-xl md:text-3xl font-black-custom text-slate-900 leading-none">1,240</p>
                <span class="text-[8px] md:text-[9px] font-bold text-blue-600 bg-blue-50 px-1.5 py-0.5 rounded uppercase w-fit">All Lines</span>
            </div>
        </div>
        <div class="stat-card-3d card-green p-4 md:p-6">
            <p class="text-[9px] md:text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-1">Stock Safe</p>
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-1">
                <p class="text-xl md:text-3xl font-black-custom text-slate-900 leading-none">850</p>
                <span class="text-[8px] md:text-[9px] font-bold text-green-600 bg-green-50 px-1.5 py-0.5 rounded uppercase w-fit">Normal</span>
            </div>
        </div>
        <div class="stat-card-3d card-yellow p-4 md:p-6">
            <p class="text-[9px] md:text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-1">Warning</p>
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-1">
                <p class="text-xl md:text-3xl font-black-custom text-slate-900 leading-none">12</p>
                <span class="text-[8px] md:text-[9px] font-bold text-amber-600 bg-amber-50 px-1.5 py-0.5 rounded uppercase w-fit">Re-order</span>
            </div>
        </div>
        <div class="stat-card-3d card-red p-4 md:p-6">
            <p class="text-[9px] md:text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-1">Critical</p>
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-1">
                <p class="text-xl md:text-3xl font-black-custom text-slate-900 leading-none">3</p>
                <span class="text-[8px] md:text-[9px] font-bold text-red-600 bg-red-50 px-1.5 py-0.5 rounded uppercase w-fit">Zero</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-8 mb-8 transition-all duration-700 delay-200 transform"
         :class="loaded ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0'">
        <div class="lg:col-span-2 container-flat p-4 md:p-6 shadow-sm">
            <h3 class="text-[10px] md:text-xs font-black-custom text-slate-900 uppercase tracking-widest mb-6 flex items-center gap-2">
                <span class="w-1.5 h-4 bg-blue-600 rounded-full"></span> Activity In/Out
            </h3>
            <div class="h-[250px] md:h-[300px] w-full"><canvas id="activityChart"></canvas></div>
        </div>

        <div class="container-flat p-4 md:p-6 shadow-sm flex flex-col">
            <h3 class="text-[10px] md:text-xs font-black-custom text-slate-900 uppercase tracking-widest mb-6 flex items-center gap-2">
                <span class="w-1.5 h-4 bg-slate-900 rounded-full"></span> Cleaning Monitor
            </h3>
            <div class="space-y-3 flex-1 overflow-y-auto max-h-[250px] md:max-h-none pr-1">
                <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 border border-slate-100">
                    <div class="h-8 w-8 rounded-lg bg-blue-600 flex items-center justify-center text-white shrink-0"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg></div>
                    <div class="flex-1 text-[10px] font-bold truncate"><p class="text-slate-800 uppercase">SN-001</p><p class="text-blue-600 uppercase">Cleaning...</p></div>
                    <span class="text-[8px] font-bold text-slate-400">2m</span>
                </div>
                <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 border border-slate-100">
                    <div class="h-8 w-8 rounded-lg bg-green-500 flex items-center justify-center text-white shrink-0"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg></div>
                    <div class="flex-1 text-[10px] font-bold truncate"><p class="text-slate-800 uppercase">SN-098</p><p class="text-green-600 uppercase">Finished</p></div>
                    <span class="text-[8px] font-bold text-slate-400">15m</span>
                </div>
            </div>
            <button class="mt-4 w-full py-3 bg-slate-900 rounded-xl text-[9px] font-black-custom text-white uppercase tracking-widest hover:bg-slate-800 transition-all active:scale-95">View All Activity</button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 md:gap-8 transition-all duration-700 delay-300 transform"
         :class="loaded ? 'translate-y-0 opacity-100' : 'translate-y-8 opacity-0'">
        <div class="container-flat p-4 md:p-6 shadow-sm">
            <h3 class="text-[10px] md:text-xs font-black-custom text-slate-900 uppercase tracking-widest mb-6 flex items-center gap-2">
                <span class="w-1.5 h-4 bg-blue-500 rounded-full"></span> Monthly Movement
            </h3>
            <div class="h-[250px] md:h-[300px] w-full"><canvas id="monthlyBarChart"></canvas></div>
        </div>

        <div class="container-flat p-4 md:p-6 shadow-sm">
            <h3 class="text-[10px] md:text-xs font-black-custom text-slate-900 uppercase tracking-widest mb-6 flex items-center gap-2">
                <span class="w-1.5 h-4 bg-green-500 rounded-full"></span> Stock Health
            </h3>
            <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="relative w-full max-w-[180px] md:max-w-[220px]">
                    <canvas id="stockPieChart"></canvas>
                </div>
                <div class="grid grid-cols-3 md:grid-cols-1 gap-2 w-full md:w-auto">
                    <div class="p-2 md:p-3 rounded-lg bg-green-50 border-l-4 border-green-500">
                        <p class="text-[8px] font-bold text-green-700 uppercase">Safe</p>
                        <p class="text-sm md:text-lg font-black-custom text-green-900">850</p>
                    </div>
                    <div class="p-2 md:p-3 rounded-lg bg-yellow-50 border-l-4 border-yellow-500">
                        <p class="text-[8px] font-bold text-yellow-700 uppercase">Warning</p>
                        <p class="text-sm md:text-lg font-black-custom text-yellow-900">12</p>
                    </div>
                    <div class="p-2 md:p-3 rounded-lg bg-red-50 border-l-4 border-red-500">
                        <p class="text-[8px] font-bold text-red-700 uppercase">Zero</p>
                        <p class="text-sm md:text-lg font-black-custom text-red-900">3</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    function updateClock() {
        const now = new Date();
        document.getElementById('realtime-clock').textContent = now.toLocaleTimeString('en-GB');
    }
    setInterval(updateClock, 1000); updateClock();

    Chart.defaults.animation.duration = 2000;
    Chart.defaults.font.family = "'Inter', sans-serif";

    document.addEventListener('DOMContentLoaded', function() {
        // Line Chart
        new Chart(document.getElementById('activityChart'), {
            type: 'line',
            data: {
                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                datasets: [
                    { label: 'Out', data: [12, 19, 3, 5, 2, 3, 9], borderColor: '#ef4444', borderWidth: 3, tension: 0.4, fill: true, backgroundColor: 'rgba(239, 68, 68, 0.05)', pointRadius: 0 },
                    { label: 'In', data: [10, 15, 8, 12, 7, 10, 14], borderColor: '#22c55e', borderWidth: 3, tension: 0.4, fill: true, backgroundColor: 'rgba(34, 197, 94, 0.05)', pointRadius: 0 }
                ]
            },
            options: { maintainAspectRatio: false, plugins: { legend: { display: false } } }
        });

        // Bar Chart
        new Chart(document.getElementById('monthlyBarChart'), {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{ label: 'Total', data: [1100, 1240, 980, 1300, 1150, 1240], backgroundColor: '#2563eb', borderRadius: 6 }]
            },
            options: { maintainAspectRatio: false, plugins: { legend: { display: false } } }
        });

        // Doughnut Chart
        new Chart(document.getElementById('stockPieChart'), {
            type: 'doughnut',
            data: {
                labels: ['Safe', 'Warning', 'Zero'],
                datasets: [{ data: [850, 12, 3], backgroundColor: ['#22c55e', '#f59e0b', '#ef4444'], borderWidth: 0 }]
            },
            options: { maintainAspectRatio: false, cutout: '70%', plugins: { legend: { display: false } } }
        });
    });
</script>
@endsection