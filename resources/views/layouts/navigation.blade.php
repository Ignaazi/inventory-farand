<div x-show="sidebarOpen" 
     x-transition:enter="transition-opacity ease-linear duration-300" 
     x-transition:enter-start="opacity-0" 
     x-transition:enter-end="opacity-100" 
     x-transition:leave="transition-opacity ease-linear duration-300" 
     x-transition:leave-start="opacity-100" 
     x-transition:leave-end="opacity-0" 
     @click="sidebarOpen = false" 
     class="fixed inset-0 z-20 bg-black/50 lg:hidden" 
     x-cloak></div>

<aside 
    class="fixed inset-y-0 left-0 z-30 w-64 bg-slate-900 transition duration-300 transform lg:translate-x-0 lg:static lg:inset-0 flex-shrink-0 flex flex-col h-full text-white shadow-xl"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    x-cloak>
    
    <div class="h-16 flex items-center justify-between px-6 bg-slate-950 border-b border-slate-800">
        <div class="flex items-center space-x-2">
            <div class="p-1.5 bg-blue-600 rounded-lg">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path></svg>
            </div>
            <span class="text-lg font-bold tracking-tighter uppercase">Sparepart<span class="text-blue-500">Sys</span></span>
        </div>
        
        <button @click="sidebarOpen = false" class="lg:hidden text-slate-400 hover:text-white focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>

    <nav class="flex-1 p-4 space-y-1 overflow-y-auto" x-data="{ openRequest: {{ request()->routeIs('requests.*') ? 'true' : 'false' }}, openApproval: {{ request()->routeIs('approvals.*') ? 'true' : 'false' }} }">
        <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 rounded-lg text-sm font-medium transition-all group {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('dashboard') ? 'text-white' : 'text-slate-500 group-hover:text-blue-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
            Dashboard Activity
        </a>

        @if(Auth::user()->role == 'admin')
        <p class="px-4 mt-6 mb-2 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Management User</p>
        <a href="{{ route('users.index') }}" class="flex items-center px-4 py-3 rounded-lg text-sm font-medium transition-all group {{ request()->routeIs('users.*') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('users.*') ? 'text-white' : 'text-slate-500 group-hover:text-blue-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            User Management
        </a>

        <p class="px-4 mt-6 mb-2 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Inventory Data</p>
        <a href="{{ route('spareparts.index') }}" class="flex items-center px-4 py-3 rounded-lg text-sm font-medium transition-all group {{ request()->routeIs('spareparts.*') ? 'bg-blue-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('spareparts.*') ? 'text-white' : 'text-slate-500 group-hover:text-blue-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
            Stock Sparepart
        </a>
        @endif

        <p class="px-4 mt-6 mb-2 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Operation</p>
        
        <div>
            <button @click="openRequest = !openRequest" class="w-full flex items-center justify-between px-4 py-3 rounded-lg text-sm font-medium text-slate-400 hover:bg-slate-800 hover:text-white group transition-all">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3 text-slate-500 group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    Request Sparepart
                </div>
                <svg class="w-4 h-4 transition-transform duration-200" :class="openRequest ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>

            <div x-show="openRequest" x-cloak class="mt-1 ml-6 space-y-1 border-l border-slate-700">
                <a href="{{ route('requests.in') }}" class="flex items-center px-4 py-2 text-xs font-medium text-slate-400 hover:text-white hover:bg-slate-800 rounded-r-md transition-all group {{ request()->routeIs('requests.in') ? 'text-white bg-slate-800' : '' }}">
                    <svg class="w-4 h-4 mr-2 text-slate-600 group-hover:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                    In
                </a>
                <a href="{{ route('requests.out') }}" class="flex items-center px-4 py-2 text-xs font-medium text-slate-400 hover:text-white hover:bg-slate-800 rounded-r-md transition-all group {{ request()->routeIs('requests.out') ? 'text-white bg-slate-800' : '' }}">
                    <svg class="w-4 h-4 mr-2 text-slate-600 group-hover:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                    Out
                </a>
                <a href="{{ route('requests.history') }}" class="flex items-center px-4 py-2 text-xs font-medium text-slate-400 hover:text-white hover:bg-slate-800 rounded-r-md transition-all group {{ request()->routeIs('requests.history') ? 'text-white bg-slate-800' : '' }}">
                    <svg class="w-4 h-4 mr-2 text-slate-600 group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    History Request
                </a>
            </div>
        </div>

        <div>
            <button @click="openApproval = !openApproval" class="w-full flex items-center justify-between px-4 py-3 rounded-lg text-sm font-medium text-slate-400 hover:bg-slate-800 hover:text-white group transition-all">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3 text-slate-500 group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Approval Sparepart
                </div>
                <svg class="w-4 h-4 transition-transform duration-200" :class="openApproval ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>

            <div x-show="openApproval" x-cloak class="mt-1 ml-6 space-y-1 border-l border-slate-700">
                <a href="{{ route('approvals.in') }}" class="flex items-center px-4 py-2 text-xs font-medium text-slate-400 hover:text-white hover:bg-slate-800 rounded-r-md transition-all group {{ request()->routeIs('approvals.in') ? 'text-white bg-slate-800' : '' }}">
                    <svg class="w-4 h-4 mr-2 text-slate-600 group-hover:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                    In
                </a>
                <a href="{{ route('approvals.out') }}" class="flex items-center px-4 py-2 text-xs font-medium text-slate-400 hover:text-white hover:bg-slate-800 rounded-r-md transition-all group {{ request()->routeIs('approvals.out') ? 'text-white bg-slate-800' : '' }}">
                    <svg class="w-4 h-4 mr-2 text-slate-600 group-hover:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                    Out
                </a>
                <a href="{{ route('requests.history') }}" class="flex items-center px-4 py-2 text-xs font-medium text-slate-400 hover:text-white hover:bg-slate-800 rounded-r-md transition-all group">
                    <svg class="w-4 h-4 mr-2 text-slate-600 group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    History Request
                </a>
            </div>
        </div>

        <a href="{{ route('cleaning.index') }}" class="flex items-center px-4 py-3 rounded-lg text-sm font-medium text-slate-400 hover:bg-slate-800 hover:text-white group transition-all {{ request()->routeIs('cleaning.*') ? 'bg-blue-600 text-white' : '' }}">
            <svg class="w-5 h-5 mr-3 text-slate-500 group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            Cleaning Module
        </a>

        <p class="px-4 mt-6 mb-2 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Reports</p>
        <a href="{{ route('monitoring.index') }}" class="flex items-center px-4 py-3 rounded-lg text-sm font-medium text-slate-400 hover:bg-slate-800 hover:text-white group transition-all {{ request()->routeIs('monitoring.*') ? 'bg-blue-600 text-white' : '' }}">
            <svg class="w-5 h-5 mr-3 text-slate-500 group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
            Monitoring
        </a>
        <a href="{{ route('requests.history') }}" class="flex items-center px-4 py-3 rounded-lg text-sm font-medium text-slate-400 hover:bg-slate-800 hover:text-white group transition-all">
            <svg class="w-5 h-5 mr-3 text-slate-500 group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            History Sparepart
        </a>
    </nav>
</aside>