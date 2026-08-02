<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta property="og:title" content="ViaSpace - PT Via Digital Indonesia">
    <meta property="og:description" content="Portal Presensi dan Logbook Magang">
    <meta property="og:url" content="https://viaspace.viadigital.id">
    <meta property="og:image" content="https://viaspace.viadigital.id/logo.png">
    <title>ViaSpace - @yield('title', 'Intern Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        
        /* Tom Select Tailwind Overrides */
        .ts-wrapper {
            padding: 0 !important;
            border: none !important;
            background: transparent !important;
            box-shadow: none !important;
        }
        .ts-wrapper .ts-control {
            border-radius: 0.75rem !important;
            padding: 0.75rem 1rem !important;
            border-color: #e5e7eb !important;
            background-color: #f9fafb !important;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
            font-family: 'Inter', sans-serif !important;
            font-size: 1rem !important;
            color: #1f2937 !important; /* gray-800 */
            line-height: 1.5 !important;
        }
        .ts-wrapper .ts-control input {
            font-family: 'Inter', sans-serif !important;
            font-size: 1rem !important;
            color: #1f2937 !important;
            line-height: 1.5 !important;
            padding: 0 !important;
            margin: 0 !important;
            border: none !important;
            box-shadow: none !important;
            background: transparent !important;
        }
        .ts-wrapper.focus .ts-control {
            border-color: #f97316 !important;
            box-shadow: 0 0 0 1px #f97316 !important;
            background-color: #ffffff !important;
        }
        .ts-dropdown {
            border-radius: 0.75rem !important;
            border-color: #e5e7eb !important;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06) !important;
            font-family: 'Inter', sans-serif !important;
            font-size: 1rem !important;
            color: #1f2937 !important;
        }
        .ts-dropdown .option.active {
            background-color: #fff7ed !important; /* orange-50 */
            color: #ea580c !important; /* orange-600 */
        }
        
        .glass-panel {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        /* Sidebar Transitions */
        .sidebar-transition {
            transition: width 0.3s ease;
        }
        .sidebar-collapsed {
            width: 5rem !important; /* 80px */
        }
        .sidebar-collapsed .sidebar-text,
        .sidebar-collapsed .sidebar-header-text,
        .sidebar-collapsed .menu-group-title {
            display: none;
        }
        .sidebar-collapsed .sidebar-icon-container {
            justify-content: center;
        }
    </style>
</head>
<body class="bg-gray-50 h-screen text-gray-800 flex overflow-hidden">

    <!-- Sidebar (Dark Mode synced with Admin) -->
    <aside id="mainSidebar" class="sidebar-transition w-72 bg-gradient-to-b from-gray-900 to-gray-800 shadow-2xl flex-shrink-0 hidden md:flex flex-col h-screen sticky top-0 z-10 text-white">
        <div class="h-20 flex items-center px-6 border-b border-gray-700/50 sidebar-icon-container">
            <div class="flex items-center gap-3">
                <!-- Via Logo Image -->
                <img src="{{ asset('logo-via.png') }}" alt="Via Logo" class="w-auto h-8">
                <h1 class="text-2xl font-bold text-white tracking-tight sidebar-header-text">ViaSpace</h1>
            </div>
        </div>
        
        <div class="p-4 flex-1 overflow-y-auto custom-scrollbar">
            <div class="menu-group-title text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-3 px-2 mt-2">Menu Utama</div>
            <nav class="space-y-1.5">
                <a href="{{ route('intern.dashboard') }}" class="flex items-center gap-3 px-3 py-3 rounded-xl transition-all duration-300 {{ request()->routeIs('intern.dashboard') ? 'bg-orange-500/10 text-orange-400 font-medium border border-orange-500/20' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }} sidebar-icon-container" title="Beranda">
                    <svg class="w-6 h-6 flex-shrink-0 {{ request()->routeIs('intern.dashboard') ? 'text-orange-400' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    <span class="sidebar-text whitespace-nowrap">Beranda</span>
                </a>
                <a href="{{ route('intern.history') }}" class="flex items-center gap-3 px-3 py-3 rounded-xl transition-all duration-300 {{ request()->routeIs('intern.history') ? 'bg-orange-500/10 text-orange-400 font-medium border border-orange-500/20' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }} sidebar-icon-container" title="Riwayat Presensi">
                    <svg class="w-6 h-6 flex-shrink-0 {{ request()->routeIs('intern.history') ? 'text-orange-400' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    <span class="sidebar-text whitespace-nowrap">Riwayat Presensi</span>
                </a>
                <a href="{{ route('intern.schedule') }}" class="flex items-center gap-3 px-3 py-3 rounded-xl transition-all duration-300 {{ request()->routeIs('intern.schedule') ? 'bg-orange-500/10 text-orange-400 font-medium border border-orange-500/20' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }} sidebar-icon-container" title="Jadwal Magang">
                    <svg class="w-6 h-6 flex-shrink-0 {{ request()->routeIs('intern.schedule') ? 'text-orange-400' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <span class="sidebar-text whitespace-nowrap">Jadwal Magang</span>
                </a>
            </nav>

            <div class="menu-group-title text-[11px] font-bold text-gray-400 uppercase tracking-widest mt-10 mb-3 px-2">Akun</div>
            <nav class="space-y-1.5">
                <a href="{{ route('intern.profile') }}" class="flex items-center gap-3 px-3 py-3 rounded-xl transition-all duration-300 {{ request()->routeIs('intern.profile') ? 'bg-orange-500/10 text-orange-400 font-medium border border-orange-500/20' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }} sidebar-icon-container" title="Profil Saya">
                    <svg class="w-6 h-6 flex-shrink-0 {{ request()->routeIs('intern.profile') ? 'text-orange-400' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    <span class="sidebar-text whitespace-nowrap">Profil Saya</span>
                </a>
            </nav>
        </div>
        
        <div class="p-4 border-t border-gray-700/50 bg-gray-900/50 flex items-center justify-between">
            <!-- User Avatar & Profile -->
            <div class="flex items-center gap-3 sidebar-icon-container">
                <div class="w-10 h-10 flex-shrink-0 rounded-xl bg-gradient-to-tr from-orange-500 to-orange-400 border border-orange-300/30 flex items-center justify-center font-bold text-white shadow-sm overflow-hidden" title="{{ auth()->user()->name }}">
                    @if(auth()->user()->avatar)
                        <img src="{{ Storage::url(auth()->user()->avatar) }}" alt="Avatar" class="w-full h-full object-cover">
                    @else
                        {{ substr(auth()->user()->name, 0, 1) }}
                    @endif
                </div>
                <div class="sidebar-text overflow-hidden">
                    <div class="text-sm font-bold text-white truncate">{{ auth()->user()->name }}</div>
                    <div class="text-xs text-orange-400 font-medium truncate">Intern Portal</div>
                </div>
            </div>

            <!-- Logout Icon -->
            <form method="POST" action="{{ route('logout') }}" class="m-0">
                @csrf
                <button type="submit" class="p-2.5 rounded-xl text-gray-400 hover:bg-red-500/10 hover:text-red-400 transition-colors" title="Log Out">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col min-w-0 bg-gray-50/30 transition-all duration-300 w-full overflow-hidden">
        <!-- Page Header -->
        <header class="glass-panel sticky top-0 z-20 shadow-sm hidden md:block">
            <div class="h-20 px-8 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <button id="sidebarToggle" class="p-2 rounded-xl text-gray-500 hover:bg-gray-100 transition-colors focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>
                    </button>
                    <h2 class="text-2xl font-bold text-gray-800 tracking-tight truncate">@yield('title', 'Beranda')</h2>
                </div>
                <div class="flex items-center gap-5">
                    <!-- Live Clock -->
                    <div class="hidden lg:flex items-center gap-3 bg-white/60 px-4 py-2 rounded-xl border border-gray-200 shadow-sm text-gray-800">
                        <div class="w-8 h-8 rounded-lg bg-orange-100 text-orange-500 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-gray-500" id="live-clock-date">Memuat...</span>
                            <span class="text-sm font-bold leading-none text-gray-800" id="live-clock-time">00:00:00</span>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        
        <!-- Mobile Header -->
        <header class="md:hidden glass-panel h-16 flex items-center justify-between px-4 sticky top-0 z-20 shadow-sm w-full">
            <h1 class="text-xl font-bold text-orange-600 tracking-tight">ViaSpace</h1>
            <!-- Menu mobile placeholder -->
            <button class="text-gray-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
            </button>
        </header>

        <main class="p-4 md:p-8 flex-1 overflow-y-auto w-full">
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-2xl shadow-sm flex items-center gap-3">
                    <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <div class="font-medium text-sm">{{ session('success') }}</div>
                </div>
            @endif
            @if(session('error'))
                <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-2xl shadow-sm flex items-center gap-3">
                    <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <div class="font-medium text-sm">{{ session('error') }}</div>
                </div>
            @endif
            @if($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-2xl shadow-sm">
                    <ul class="list-disc list-inside space-y-1 text-sm">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Sidebar Collapse Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('mainSidebar');
            const toggleBtn = document.getElementById('sidebarToggle');
            
            // Check local storage for preference
            if(localStorage.getItem('sidebarCollapsed') === 'true') {
                sidebar.classList.add('sidebar-collapsed');
            }

            if (toggleBtn) {
                toggleBtn.addEventListener('click', function() {
                    sidebar.classList.toggle('sidebar-collapsed');
                    localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('sidebar-collapsed'));
                });
            }

            // Initialize Tom Select on all select elements
            document.querySelectorAll('select').forEach(function(el) {
                new TomSelect(el, {
                    create: false,
                    controlInput: '<input>',
                });
            });

            // Live Clock Logic
            function updateClock() {
                const now = new Date();
                const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
                
                const day = days[now.getDay()];
                const date = now.getDate().toString().padStart(2, '0');
                const month = months[now.getMonth()];
                const year = now.getFullYear();
                
                const hours = now.getHours().toString().padStart(2, '0');
                const minutes = now.getMinutes().toString().padStart(2, '0');
                const seconds = now.getSeconds().toString().padStart(2, '0');
                
                const dateEl = document.getElementById('live-clock-date');
                const timeEl = document.getElementById('live-clock-time');
                
                if (dateEl && timeEl) {
                    dateEl.textContent = `${day}, ${date} ${month} ${year}`;
                    timeEl.textContent = `${hours}:${minutes}:${seconds}`;
                }
            }
            setInterval(updateClock, 1000);
            updateClock();
        });
    </script>
    @stack('scripts')
</body>
</html>
