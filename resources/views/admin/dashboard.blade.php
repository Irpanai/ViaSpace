@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight mb-2">Halo, {{ auth()->user()->name }}! 👋</h1>
    <p class="text-gray-500">Ringkasan aktivitas magang hari ini, {{ now()->translatedFormat('d F Y') }}.</p>
</div>

<!-- Top Interns Leaderboard -->
@if($topInterns->isNotEmpty())
<div class="mb-8">
    <div class="flex items-center gap-3 mb-4">
        <div class="w-10 h-10 bg-yellow-100 text-yellow-600 rounded-xl flex items-center justify-center shadow-inner">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
        </div>
        <h2 class="text-xl font-bold text-gray-800 tracking-tight">Top Interns of the Month</h2>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        @foreach($topInterns as $index => $topIntern)
        <div class="glass-panel rounded-2xl p-4 flex flex-col items-center text-center relative overflow-hidden group hover:shadow-lg transition-all {{ $index === 0 ? 'border-2 border-yellow-400/50 bg-gradient-to-b from-yellow-50/50 to-white' : '' }}">
            @if($index === 0)
                <div class="absolute -top-3 -right-3 w-10 h-10 bg-yellow-400 rotate-45"></div>
                <svg class="absolute top-1 right-1 w-4 h-4 text-white z-10" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
            @endif
            
            <div class="w-14 h-14 rounded-full bg-gray-100 flex items-center justify-center font-bold text-xl text-gray-600 mb-2 border-2 border-white shadow-sm ring-2 {{ $index === 0 ? 'ring-yellow-400' : 'ring-gray-100 group-hover:ring-orange-300' }} transition-all">
                {{ substr($topIntern->name, 0, 1) }}
            </div>
            <div class="font-bold text-gray-800 text-sm w-full truncate">{{ $topIntern->name }}</div>
            <div class="text-xs font-semibold {{ $index === 0 ? 'text-yellow-600' : 'text-orange-500' }} mt-1 bg-white/60 px-2 py-0.5 rounded-full shadow-sm">{{ $topIntern->monthly_points }} Poin</div>
        </div>
        @endforeach
    </div>
</div>
@endif

<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
    <!-- Stat 1 -->
    <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm relative overflow-hidden group">
        <div class="absolute -right-6 -top-6 w-24 h-24 bg-green-50 rounded-full group-hover:scale-150 transition-transform duration-500 ease-in-out"></div>
        <div class="relative z-10 flex items-start justify-between">
            <div>
                <p class="text-sm font-semibold text-gray-500 mb-1">Hadir Hari Ini</p>
                <h3 class="text-4xl font-extrabold text-gray-900 mb-2">{{ $presentToday }} <span class="text-lg font-medium text-gray-400">siswa</span></h3>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-green-100 text-green-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>
    </div>

    <!-- Stat 2 -->
    <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm relative overflow-hidden group">
        <div class="absolute -right-6 -top-6 w-24 h-24 bg-blue-50 rounded-full group-hover:scale-150 transition-transform duration-500 ease-in-out"></div>
        <div class="relative z-10 flex items-start justify-between">
            <div>
                <p class="text-sm font-semibold text-gray-500 mb-1">Jadwal Piket Hari Ini</p>
                <h3 class="text-4xl font-extrabold text-gray-900 mb-2">{{ $scheduledToday }} <span class="text-lg font-medium text-gray-400">siswa</span></h3>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-blue-100 text-blue-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
        </div>
    </div>

    <!-- Stat 3 -->
    <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm relative overflow-hidden group">
        <div class="absolute -right-6 -top-6 w-24 h-24 bg-orange-50 rounded-full group-hover:scale-150 transition-transform duration-500 ease-in-out"></div>
        <div class="relative z-10 flex items-start justify-between">
            <div>
                <p class="text-sm font-semibold text-gray-500 mb-1">Total Siswa Terdaftar</p>
                @php $totalInterns = \App\Models\User::where('role', 'intern')->count(); @endphp
                <h3 class="text-4xl font-extrabold text-gray-900 mb-2">{{ $totalInterns }} <span class="text-lg font-medium text-gray-400">orang</span></h3>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-orange-100 text-orange-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
        </div>
    </div>
</div>

<!-- Reminders Section -->
@if($missingCheckIn->count() > 0 || $missingCheckOut->count() > 0)
<div class="mb-8">
    <div class="flex items-center gap-3 mb-4">
        <div class="w-8 h-8 rounded-full bg-red-100 text-red-600 flex items-center justify-center font-bold">!</div>
        <h3 class="text-lg font-semibold text-gray-800">Perhatian: Siswa Belum Lengkap Absen Hari Ini</h3>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @if($missingCheckIn->count() > 0)
        <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm">
            <h4 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-red-500"></span>
                Belum Check-In ({{ $missingCheckIn->count() }})
            </h4>
            <div class="space-y-3">
                @foreach($missingCheckIn as $intern)
                    @php
                        $waPhone = $intern->phone_number ? preg_replace('/^0/', '62', $intern->phone_number) : '';
                        $waText = urlencode("Halo {$intern->name}, kamu belum melakukan absen Check-In hari ini (" . now()->translatedFormat('d F Y') . "). Segera lakukan absen di aplikasi ViaSpace ya! Terima kasih.");
                    @endphp
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl border border-gray-100">
                        <div>
                            <p class="font-semibold text-sm text-gray-800">{{ $intern->name }}</p>
                            <p class="text-xs text-gray-500">{{ $intern->phone_number ?: 'Nomor HP belum diisi' }}</p>
                        </div>
                        @if($waPhone)
                        <a href="https://wa.me/{{ $waPhone }}?text={{ $waText }}" target="_blank" class="flex items-center gap-1.5 px-3 py-1.5 bg-green-500 hover:bg-green-600 text-white rounded-lg text-xs font-semibold transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                            Reminder
                        </a>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        @if($missingCheckOut->count() > 0)
        <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm">
            <h4 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-orange-500"></span>
                Belum Check-Out ({{ $missingCheckOut->count() }})
            </h4>
            <div class="space-y-3">
                @foreach($missingCheckOut as $intern)
                    @php
                        $waPhone = $intern->phone_number ? preg_replace('/^0/', '62', $intern->phone_number) : '';
                        $waText = urlencode("Halo {$intern->name}, kamu belum melakukan absen Check-Out hari ini (" . now()->translatedFormat('d F Y') . "). Segera lakukan absen pulang dan isi logbook di aplikasi ViaSpace ya! Terima kasih.");
                    @endphp
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl border border-gray-100">
                        <div>
                            <p class="font-semibold text-sm text-gray-800">{{ $intern->name }}</p>
                            <p class="text-xs text-gray-500">{{ $intern->phone_number ?: 'Nomor HP belum diisi' }}</p>
                        </div>
                        @if($waPhone)
                        <a href="https://wa.me/{{ $waPhone }}?text={{ $waText }}" target="_blank" class="flex items-center gap-1.5 px-3 py-1.5 bg-green-500 hover:bg-green-600 text-white rounded-lg text-xs font-semibold transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                            Reminder
                        </a>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endif

<!-- Quick Actions -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <a href="{{ route('admin.calendar') }}" class="flex items-center p-6 bg-gradient-to-r from-orange-500 to-orange-600 rounded-3xl text-white hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
        <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mr-6 backdrop-blur-sm">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
        </div>
        <div>
            <h3 class="text-xl font-bold mb-1">Lihat Kalender Magang</h3>
            <p class="text-orange-100 text-sm">Pantau jadwal seluruh siswa bulan ini</p>
        </div>
    </a>
    
    <a href="{{ route('admin.interns.index') }}" class="flex items-center p-6 bg-gradient-to-r from-gray-800 to-gray-900 rounded-3xl text-white hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
        <div class="w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center mr-6 backdrop-blur-sm">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
        </div>
        <div>
            <h3 class="text-xl font-bold mb-1">Kelola Data Siswa</h3>
            <p class="text-gray-400 text-sm">Lihat detail dan daftar seluruh pemagang</p>
        </div>
    </a>
</div>
@endsection
