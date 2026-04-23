<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-gray-900">
    <div class="min-h-screen grid grid-cols-1 lg:grid-cols-2">
        
        <div class="hidden lg:flex relative items-center justify-center overflow-hidden bg-slate-900">
            <img src="{{ asset('img/loginpage.png') }}" 
                 class="absolute inset-0 w-full h-full object-cover opacity-60 scale-105 animate-slow-zoom" 
                 alt="Industrial Background">
            
            <div class="absolute inset-0 bg-gradient-to-tr from-blue-900/90 to-slate-900/40"></div>
            
            <div class="relative z-10 p-12 text-white">
                <h2 class="text-5xl font-extrabold tracking-tighter mb-4">Sparepart <br>Management.</h2>
                <p class="text-blue-200 text-lg">Platform monitoring sparepart untuk efisiensi produksi yang lebih baik.</p>
            </div>
        </div>

        <div class="flex items-center justify-center p-8 bg-slate-50">
            <div class="w-full max-w-md">
                {{ $slot }}
            </div>
        </div>
        
    </div>
</body>
</html>