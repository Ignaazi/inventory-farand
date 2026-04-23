@extends('layouts.app')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="p-6 md:p-8">
    
    <div class="flex justify-between items-center mb-8 border-b border-slate-200 pb-6">
        <div>
            <h1 class="text-3xl font-black text-slate-900 tracking-tight uppercase">Production Monitor</h1>
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-widest mt-1">Real-time Station Intelligence</p>
        </div>
        <div class="text-right bg-white px-6 py-3 rounded-xl border border-slate-200 shadow-sm">
            <div id="clock" class="text-3xl font-mono font-bold text-slate-800">00:00:00</div>
            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">System Online</div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        @php 
            $cards = [
                ['title' => 'Needs Cleaning', 'val' => $stats['cleaning'], 'color' => 'text-red-600', 'border' => 'border-l-red-500'],
                ['title' => 'Pending Requests', 'val' => $stats['pending'], 'color' => 'text-amber-600', 'border' => 'border-l-amber-500'],
                ['title' => 'Today Inbound', 'val' => $stats['in_today'], 'color' => 'text-emerald-600', 'border' => 'border-l-emerald-500'],
                ['title' => 'Today Outbound', 'val' => $stats['out_today'], 'color' => 'text-blue-600', 'border' => 'border-l-blue-500'],
            ];
        @endphp
        
        @foreach($cards as $card)
        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm border-l-4 {{ $card['border'] }}">
            <h3 class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1">{{ $card['title'] }}</h3>
            <div class="text-4xl font-black {{ $card['color'] }}">{{ $card['val'] }}</div>
        </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <h2 class="font-bold text-xs uppercase tracking-widest text-slate-700">Recent Transactions</h2>
                <span class="flex items-center gap-2 text-[10px] text-emerald-600 font-bold uppercase">
                    <span class="relative flex h-2 w-2"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span><span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span></span>
                    Live Data
                </span>
            </div>
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-slate-500">
                    <tr>
                        <th class="px-6 py-3 text-[10px] uppercase font-bold">Time</th>
                        <th class="px-6 py-3 text-[10px] uppercase font-bold">Sparepart</th>
                        <th class="px-6 py-3 text-[10px] uppercase font-bold">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($recent as $tx)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 font-mono text-slate-600 text-sm">{{ $tx->created_at->format('H:i:s') }}</td>
                        <td class="px-6 py-4 font-bold text-slate-800 text-sm uppercase">{{ $tx->sparepart->part_name ?? 'N/A' }}</td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 bg-slate-100 rounded text-[10px] font-bold text-slate-600 uppercase">{{ $tx->type }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="px-6 py-10 text-center text-slate-400 text-xs">No recent data</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 flex flex-col">
            <h2 class="font-bold text-xs uppercase tracking-widest text-slate-700 mb-6">Activity Distribution</h2>
            <div class="flex-grow flex items-center justify-center">
                <canvas id="miniChart"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
    // Clock
    function updateClock() {
        document.getElementById('clock').innerText = new Date().toLocaleTimeString('id-ID');
    }
    setInterval(updateClock, 1000);
    
    // Auto-Refresh
    setInterval(() => { window.location.reload(); }, 30000);

    // Mini Chart
    new Chart(document.getElementById('miniChart'), {
        type: 'doughnut',
        data: {
            labels: ['Inbound', 'Outbound', 'Cleaning'],
            datasets: [{
                data: [{{ $stats['in_today'] + 1 }}, {{ $stats['out_today'] + 1 }}, {{ $stats['cleaning'] + 1 }}],
                backgroundColor: ['#10b981', '#2563eb', '#ef4444'], // Emerald, Blue, Red
                borderWidth: 0
            }]
        },
        options: { plugins: { legend: { position: 'bottom', labels: { color: '#64748b', font: { size: 10 } } } } }
    });
</script>
@endsection