<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="mb-10 text-center">
        <h2 class="text-2xl md:text-3xl font-black text-slate-900 tracking-tighter uppercase italic">
            Sparepart<span class="text-blue-600 not-italic">System</span>
        </h2>
        <div class="h-1 w-10 bg-blue-600 mx-auto mt-2 rounded-full"></div>
        <p class="text-[10px] md:text-xs font-bold text-slate-400 mt-4 tracking-[0.2em] uppercase">Secure Login Portal</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <label for="nik" class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2 ml-1">NIK</label>
            <input id="nik" class="block w-full px-4 py-3.5 md:py-4 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 focus:bg-white transition-all text-slate-900 font-medium placeholder-slate-300" 
                   type="text" name="nik" :value="old('nik')" required autofocus placeholder="Masukkan NIK" />
            <x-input-error :messages="$errors->get('nik')" class="mt-2 text-xs" />
        </div>

        <div>
            <label for="password" class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2 ml-1">Password</label>
            <input id="password" class="block w-full px-4 py-3.5 md:py-4 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-600/20 focus:border-blue-600 focus:bg-white transition-all text-slate-900 font-medium placeholder-slate-300" 
                   type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs" />
        </div>

        <div class="flex items-center justify-between px-1">
            <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                <input id="remember_me" type="checkbox" class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500 transition-all" name="remember">
                <span class="ms-2 text-xs font-medium text-slate-500 group-hover:text-slate-700">{{ __('Ingat Saya') }}</span>
            </label>
            
            @if (Route::has('password.request'))
                <a class="text-xs font-bold text-blue-600 hover:text-blue-800 transition-colors" href="{{ route('password.request') }}">
                    {{ __('Lupa Sandi?') }}
                </a>
            @endif
        </div>

        <div class="pt-4">
            <button type="submit" class="w-full bg-slate-900 hover:bg-blue-600 text-white font-extrabold py-4 px-4 rounded-xl shadow-lg shadow-slate-200 tracking-widest transition-all duration-300 transform active:scale-[0.98]">
                LOGIN SYSTEM
            </button>
        </div>
    </form>
</x-guest-layout>