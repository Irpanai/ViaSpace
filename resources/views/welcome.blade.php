<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ViaSpace - Platform Logbook & Presensi Modern</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        
        .glass-nav {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.5);
        }

        .hero-pattern {
            background-color: #ffffff;
            background-image: radial-gradient(at 0% 0%, hsla(28,100%,74%,1) 0, transparent 50%), radial-gradient(at 100% 0%, hsla(349,100%,88%,1) 0, transparent 50%);
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 antialiased overflow-x-hidden selection:bg-orange-500 selection:text-white">

    <!-- Navigation -->
    <nav class="fixed w-full z-50 glass-nav transition-all duration-300">
        <div class="max-w-7xl mx-auto px-6 md:px-12">
            <div class="flex items-center justify-between h-20">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center gap-2 cursor-pointer">
                    <!-- Via Logo Image -->
                    <img src="{{ asset('logo-via.png') }}" alt="Via Logo" class="w-auto h-8">
                    <span class="font-bold text-xl tracking-tight text-gray-900">Via<span class="text-orange-600">Space</span></span>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center gap-8 font-medium">
                    <a href="#fitur" class="text-gray-500 hover:text-orange-600 transition-colors">Fitur</a>
                    <a href="#tentang" class="text-gray-500 hover:text-orange-600 transition-colors">Tentang</a>
                    <div class="h-6 w-px bg-gray-300"></div>
                    
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ route('dashboard') }}" class="text-gray-900 hover:text-orange-600 transition-colors">Masuk Dashboard &rarr;</a>
                        @else
                            <a href="{{ route('login') }}" class="text-gray-900 hover:text-orange-600 transition-colors">Log in</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="px-5 py-2.5 rounded-full bg-gray-900 text-white hover:bg-orange-600 transition-colors shadow-lg shadow-gray-900/20">Mulai Sekarang</a>
                            @endif
                        @endauth
                    @endif
                </div>

                <!-- Mobile Menu Button -->
                <div class="md:hidden flex items-center">
                    @auth
                        <a href="{{ route('dashboard') }}" class="px-4 py-2 rounded-lg bg-orange-100 text-orange-600 text-sm font-bold">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 rounded-lg bg-orange-100 text-orange-600 text-sm font-bold">Log in</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative pt-32 pb-20 md:pt-48 md:pb-32 overflow-hidden hero-pattern">
        <div class="max-w-7xl mx-auto px-6 md:px-12 relative z-10">
            <div class="text-center max-w-4xl mx-auto">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-orange-50 border border-orange-100 text-orange-600 font-semibold text-sm mb-6 animate-fade-in-up">
                    <span class="relative flex h-2.5 w-2.5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-orange-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-orange-500"></span>
                    </span>
                    Platform Magang Digital #1
                </div>
                <h1 class="text-5xl md:text-7xl font-extrabold text-gray-900 tracking-tight leading-tight mb-8">
                    Kelola Magang Jadi Lebih <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-500 to-orange-400">Terstruktur & Modern</span>
                </h1>
                <p class="text-lg md:text-xl text-gray-600 mb-10 max-w-2xl mx-auto leading-relaxed">
                    ViaSpace adalah solusi terpadu untuk pencatatan presensi berakurat lokasi (GPS), pengisian logbook harian, dan pemantauan jadwal magang secara real-time.
                </p>
                
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="w-full sm:w-auto px-8 py-4 rounded-2xl bg-gradient-to-r from-orange-500 to-orange-600 text-white font-bold text-lg hover:from-orange-600 hover:to-orange-700 transition-all shadow-xl shadow-orange-500/30 transform hover:-translate-y-1">
                            Buka Dashboard Anda
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="w-full sm:w-auto px-8 py-4 rounded-2xl bg-gradient-to-r from-orange-500 to-orange-600 text-white font-bold text-lg hover:from-orange-600 hover:to-orange-700 transition-all shadow-xl shadow-orange-500/30 transform hover:-translate-y-1">
                            Masuk Sekarang
                        </a>
                    @endauth
                </div>
            </div>
        </div>
        
        <!-- Abstract Shapes -->
        <div class="absolute top-1/2 left-0 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-orange-300/20 rounded-full blur-3xl opacity-50 mix-blend-multiply pointer-events-none"></div>
        <div class="absolute top-0 right-0 translate-x-1/3 -translate-y-1/4 w-[800px] h-[800px] bg-yellow-200/20 rounded-full blur-3xl opacity-50 mix-blend-multiply pointer-events-none"></div>
    </section>

    <!-- Features Section -->
    <section id="fitur" class="py-24 bg-white relative">
        <div class="max-w-7xl mx-auto px-6 md:px-12">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Fitur Unggulan ViaSpace</h2>
                <p class="text-gray-500 max-w-2xl mx-auto">Kami merancang platform ini untuk menghilangkan kerumitan administrasi magang tradisional.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="feature-card bg-gray-50 rounded-3xl p-8 border border-gray-100 transition-all duration-300">
                    <div class="w-14 h-14 bg-orange-100 text-orange-600 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Presensi Geolocation</h3>
                    <p class="text-gray-600 leading-relaxed">Sistem absen akurat yang mendeteksi jarak lokasi Anda secara real-time. Memastikan kehadiran tepat pada tempatnya.</p>
                </div>

                <!-- Feature 2 -->
                <div class="feature-card bg-gray-50 rounded-3xl p-8 border border-gray-100 transition-all duration-300">
                    <div class="w-14 h-14 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Logbook Digital</h3>
                    <p class="text-gray-600 leading-relaxed">Catat aktivitas magang harian Anda lengkap dengan lampiran foto bukti kegiatan. Bebas repot kertas.</p>
                </div>

                <!-- Feature 3 -->
                <div class="feature-card bg-gray-50 rounded-3xl p-8 border border-gray-100 transition-all duration-300">
                    <div class="w-14 h-14 bg-purple-100 text-purple-600 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Kalender Jadwal Pintar</h3>
                    <p class="text-gray-600 leading-relaxed">Lihat siapa saja teman yang piket di hari yang sama dengan Anda melalui kalender visual interaktif yang cantik.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12 border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-6 md:px-12 flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-2">
                <img src="{{ asset('logo-via.png') }}" alt="Via Logo" class="w-auto h-6 opacity-50 grayscale">
                <span class="font-bold text-lg text-gray-400">ViaSpace</span>
            </div>
            <p class="text-gray-500 text-sm">&copy; {{ date('Y') }} ViaSpace. Hak Cipta Dilindungi.</p>
        </div>
    </footer>

</body>
</html>
