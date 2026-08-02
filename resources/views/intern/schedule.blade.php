@extends('layouts.intern')

@section('title', 'Jadwal Magang')

@section('content')
<!-- Filter/Header Bar -->
<div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">{{ $date->translatedFormat('F Y') }}</h2>
        <p class="text-sm text-gray-500 mt-1">Jadwal kehadiran siswa magang bulan ini</p>
    </div>
    <div class="flex items-center gap-3">
        <a href="{{ route('intern.schedule', ['month' => $date->copy()->subMonth()->month, 'year' => $date->copy()->subMonth()->year]) }}" class="p-2 rounded-xl border border-gray-200 bg-white text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition-colors shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        </a>
        <a href="{{ route('intern.schedule') }}" class="px-4 py-2 rounded-xl border border-gray-200 bg-white text-gray-700 font-semibold text-sm hover:bg-gray-50 transition-colors shadow-sm">
            Bulan Ini
        </a>
        <a href="{{ route('intern.schedule', ['month' => $date->copy()->addMonth()->month, 'year' => $date->copy()->addMonth()->year]) }}" class="p-2 rounded-xl border border-gray-200 bg-white text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition-colors shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        </a>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-orange-50 text-orange-500 flex items-center justify-center">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
        </div>
        <div>
            <div class="text-2xl font-bold text-gray-800">{{ $mySchedulesThisWeek }} <span class="text-sm font-medium text-gray-400">kali</span></div>
            <div class="text-sm font-medium text-gray-500">Jadwal Anda Minggu Ini</div>
        </div>
    </div>
    
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-500 flex items-center justify-center">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
        </div>
        <div>
            <div class="text-2xl font-bold text-gray-800">{{ $mySchedulesThisMonth }} <span class="text-sm font-medium text-gray-400">kali</span></div>
            <div class="text-sm font-medium text-gray-500">Total Jadwal di Bulan Ini</div>
        </div>
    </div>
</div>

<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
    <!-- Kalender Grid -->
    <div class="p-4 md:p-6">
        <!-- Nama Hari -->
        <div class="grid grid-cols-7 gap-2 md:gap-4 mb-4">
            @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $dayName)
                <div class="text-center">
                    <span class="hidden md:inline font-bold text-xs uppercase tracking-wider text-gray-400">{{ $dayName }}</span>
                    <span class="md:hidden font-bold text-xs uppercase tracking-wider text-gray-400">{{ substr($dayName, 0, 3) }}</span>
                </div>
            @endforeach
        </div>

        <!-- Tanggal -->
        <div class="grid grid-cols-7 gap-2 md:gap-4">
            {{-- Padding untuk hari sebelum tanggal 1 --}}
            @php
                $emptyCells = $firstDayOfWeek - 1; 
            @endphp
            @for ($i = 0; $i < $emptyCells; $i++)
                <div class="min-h-[100px] md:min-h-[140px] bg-gray-50/50 rounded-2xl border border-gray-100 border-dashed opacity-50"></div>
            @endfor

            {{-- Sel-sel tanggal --}}
            @for ($day = 1; $day <= $daysInMonth; $day++)
                @php
                    $currentDateStr = $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '-' . str_pad($day, 2, '0', STR_PAD_LEFT);
                    $isToday = $currentDateStr == \Carbon\Carbon::today()->format('Y-m-d');
                    $daySchedules = $schedulesByDate->get($currentDateStr, collect());
                @endphp
                
                <div class="min-h-[100px] md:min-h-[140px] p-2 md:p-3 rounded-2xl border {{ $isToday ? 'border-orange-300 bg-gradient-to-b from-orange-50/50 to-white shadow-sm ring-1 ring-orange-100' : 'border-gray-100 hover:border-gray-300 hover:shadow-md transition-all duration-300 bg-white' }} flex flex-col group relative">
                    <!-- Tanggal Header -->
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-bold w-8 h-8 flex items-center justify-center rounded-xl {{ $isToday ? 'text-white bg-gradient-to-tr from-orange-500 to-orange-400 shadow-md shadow-orange-500/20' : 'text-gray-600 group-hover:bg-gray-100' }}">
                            {{ $day }}
                        </span>
                        @if($daySchedules->count() > 0)
                            <span class="text-[10px] font-bold bg-gray-100 text-gray-500 px-2 py-1 rounded-lg group-hover:bg-orange-100 group-hover:text-orange-600 transition-colors">
                                {{ $daySchedules->count() }}
                            </span>
                        @endif
                    </div>

                    <!-- Daftar Siswa Masuk -->
                    <div class="flex-1 overflow-y-auto custom-scrollbar flex flex-wrap gap-1.5 items-start content-start">
                        @foreach($daySchedules as $schedule)
                            @php
                                $isMe = $schedule->user_id == auth()->id();
                            @endphp
                            <div class="flex items-center gap-1.5 px-2 py-1 rounded-full {{ $isMe ? 'bg-orange-100/80 border border-orange-200/50 ring-1 ring-orange-500/10' : 'bg-gray-50 border border-gray-100 hover:bg-gray-100' }} transition-colors" title="{{ $schedule->user->name }}">
                                <!-- Avatar Mini -->
                                <div class="w-4 h-4 md:w-5 md:h-5 rounded-full flex-shrink-0 bg-white flex items-center justify-center text-[9px] font-bold text-gray-600 overflow-hidden shadow-sm border border-gray-100">
                                    @if($schedule->user->avatar)
                                        <img src="{{ Storage::url($schedule->user->avatar) }}" alt="{{ $schedule->user->name }}" class="w-full h-full object-cover">
                                    @else
                                        {{ substr($schedule->user->name, 0, 1) }}
                                    @endif
                                </div>
                                <!-- Nama -->
                                <span class="text-[9px] md:text-[10px] font-semibold truncate {{ $isMe ? 'text-orange-700' : 'text-gray-600' }}">
                                    {{ $schedule->user->nickname ? $schedule->user->nickname : explode(' ', trim($schedule->user->name))[0] }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endfor
        </div>
    </div>
</div>

<style>
    /* Styling scrollbar kecil untuk dalam tanggal */
    .custom-scrollbar::-webkit-scrollbar {
        width: 3px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background-color: #e2e8f0;
        border-radius: 10px;
    }
    .group:hover .custom-scrollbar::-webkit-scrollbar-thumb {
        background-color: #cbd5e1;
    }
</style>
@endsection
