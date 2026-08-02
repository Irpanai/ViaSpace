<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <meta property="og:title" content="ViaSpace - PT Via Digital Indonesia">
    <meta property="og:description" content="Portal Presensi dan Logbook Magang">
    <meta property="og:url" content="https://viaspace.viadigital.id">
    <meta property="og:image" content="https://viaspace.viadigital.id/logo.png">

    <title>Masuk - ViaSpace</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .glass-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }
        .mesh-bg {
            background-color: #f9fafb;
            background-image: radial-gradient(at 100% 0%, hsla(28,100%,74%,0.5) 0px, transparent 50%),
                              radial-gradient(at 0% 100%, hsla(349,100%,88%,0.5) 0px, transparent 50%);
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-gray-900 antialiased mesh-bg min-h-screen flex items-center justify-center p-6 selection:bg-orange-500 selection:text-white">
    
    <div class="w-full max-w-[1000px] flex flex-col md:flex-row bg-white/50 backdrop-blur-xl rounded-[2rem] shadow-2xl shadow-orange-500/10 border border-white overflow-hidden relative">
        
        <!-- Left Side: Branding / Abstract -->
        <div class="hidden md:flex flex-col justify-between w-1/2 p-12 bg-gradient-to-br from-orange-500 to-orange-600 text-white relative overflow-hidden">
            <!-- Background Ornaments -->
            <div class="absolute top-0 right-0 translate-x-1/3 -translate-y-1/3 w-64 h-64 bg-white/10 rounded-full blur-2xl"></div>
            <div class="absolute bottom-0 left-0 -translate-x-1/3 translate-y-1/3 w-80 h-80 bg-orange-400/30 rounded-full blur-3xl"></div>
            
            <div class="relative z-10">
                <!-- Logo Image -->
                <div class="flex items-center gap-3 mb-8">
                    <img src="{{ asset('logo-via.png') }}" alt="Via Logo" class="w-auto h-10 drop-shadow-md bg-white rounded-xl p-1">
                    <span class="font-extrabold text-2xl tracking-tight text-white">ViaSpace</span>
                </div>
                <h1 class="text-4xl font-bold leading-tight mb-4">Portal Logbook & Presensi Magang</h1>
                <p class="text-orange-100 text-lg leading-relaxed">Kelola aktivitas magang Anda dengan mudah, rapi, dan terpantau secara real-time dari mana saja.</p>
            </div>
            
            <div class="relative z-10 flex items-center gap-4 mt-12">
                <div class="flex -space-x-4">
                    <div class="w-10 h-10 rounded-full border-2 border-orange-500 bg-gray-200"></div>
                    <div class="w-10 h-10 rounded-full border-2 border-orange-500 bg-gray-300"></div>
                    <div class="w-10 h-10 rounded-full border-2 border-orange-500 bg-gray-400"></div>
                </div>
                <div class="text-sm font-medium text-orange-100">
                    Dipercaya oleh <span class="font-bold text-white">1,000+</span> siswa magang
                </div>
            </div>
        </div>

        <!-- Right Side: Auth Form -->
        <div class="w-full md:w-1/2 p-8 md:p-14 flex flex-col justify-center bg-white relative z-10">
            <!-- Mobile Logo -->
            <div class="md:hidden flex items-center justify-center gap-2 mb-8">
                <img src="{{ asset('logo-via.png') }}" alt="Via Logo" class="w-auto h-8">
                <span class="font-bold text-xl tracking-tight text-gray-900">Via<span class="text-orange-600">Space</span></span>
            </div>

            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Selamat Datang Kembali 👋</h2>
                <p class="text-gray-500">Silakan masukkan detail akun Anda untuk melanjutkan.</p>
            </div>

            {{ $slot }}

        </div>
    </div>
    
</body>
</html>
