<x-guest-layout>
    <div class="w-full">
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-slate-900">Welcome Back</h2>
            <p class="text-slate-500 text-sm">Please sign in to continue</p>
        </div>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">NIK</label>
                <input type="text" name="nik" :value="old('nik')" required autofocus 
                    class="w-full px-4 py-3 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition" 
                    placeholder="Masukkan NIK" />
                <x-input-error :messages="$errors->get('nik')" class="mt-1 text-xs" />
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Password</label>
                <input type="password" name="password" required 
                    class="w-full px-4 py-3 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition" 
                    placeholder="••••••••" />
                <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs" />
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center text-sm text-slate-600">
                    <input type="checkbox" name="remember" class="rounded border-slate-300 text-blue-600 shadow-sm focus:ring-blue-500">
                    <span class="ml-2">Remember me</span>
                </label>
                
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-sm text-blue-600 hover:underline">Forgot?</a>
                @endif
            </div>

            <button type="submit" class="w-full py-3 bg-slate-900 text-white font-bold rounded-lg hover:bg-slate-800 transition">
                LOGIN
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-slate-500">
            Don't have an account? <a href="{{ route('register') }}" class="text-blue-600 font-bold hover:underline">Register</a>
        </p>
    </div>
</x-guest-layout>