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
                
                <div class="min-h-[100px] md:min-h-[140px] p-2 md:p-3 rounded-2xl border {{ $holiday ? 'border-red-200 bg-red-50' : ($isToday ? 'border-orange-300 bg-gradient-to-b from-orange-50/50 to-white shadow-sm ring-1 ring-orange-100' : 'border-gray-100 hover:border-gray-300 hover:shadow-md transition-all duration-300 bg-white') }} flex flex-col group relative">
                    <!-- Tanggal Header -->
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-bold w-8 h-8 flex items-center justify-center rounded-xl {{ $holiday ? 'text-red-600 bg-red-100' : ($isToday ? 'text-white bg-gradient-to-tr from-orange-500 to-orange-400 shadow-md shadow-orange-500/20' : 'text-gray-600 group-hover:bg-gray-100') }}">
                            {{ $day }}
                        </span>
                        @if(!$holiday && $daySchedules->count() > 0)
                            <span class="text-[10px] font-bold bg-gray-100 text-gray-500 px-2 py-1 rounded-lg group-hover:bg-orange-100 group-hover:text-orange-600 transition-colors">
                                {{ $daySchedules->count() }}
                            </span>
                        @endif
                    </div>

                    @if($holiday)
                        <div class="flex-1 flex flex-col items-center justify-center text-center opacity-80">
                            <svg class="w-6 h-6 text-red-400 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                            <span class="text-xs font-bold text-red-600 uppercase tracking-wide">LIBUR</span>
                            <span class="text-[10px] font-medium text-red-500 leading-tight mt-0.5">{{ $holiday->name }}</span>
                        </div>
                    @else
                        <!-- Daftar Siswa Masuk -->
                        <div class="flex-1 overflow-y-auto custom-scrollbar flex flex-wrap gap-1.5 items-start content-start">
                            @foreach($daySchedules as $schedule)
                                @php
                                    $isMe = $schedule->user_id == auth()->id();
                                    $attKey = $currentDateStr . '_' . $schedule->user_id;
                                    $att = $attendances->get($attKey);
                                    
                                    $bgColor = 'bg-gray-50';
                                    $borderColor = 'border-gray-100';
                                    $textColor = 'text-gray-600';
                                    $hoverColor = 'hover:bg-gray-100';
                                    $titleStatus = 'Belum Absen';

                                    if ($att) {
                                        if ($att->status == 'present') {
                                            $bgColor = 'bg-green-50'; $borderColor = 'border-green-200'; $textColor = 'text-green-700'; $hoverColor = 'hover:bg-green-100'; $titleStatus = 'Hadir';
                                        } elseif ($att->status == 'late') {
                                            $bgColor = 'bg-orange-50'; $borderColor = 'border-orange-200'; $textColor = 'text-orange-700'; $hoverColor = 'hover:bg-orange-100'; $titleStatus = 'Terlambat';
                                        } elseif ($att->status == 'sakit' || $att->status == 'sick') {
                                            $bgColor = 'bg-red-50'; $borderColor = 'border-red-200'; $textColor = 'text-red-700'; $hoverColor = 'hover:bg-red-100'; $titleStatus = 'Sakit';
                                        } elseif ($att->status == 'izin' || $att->status == 'permit') {
                                            $bgColor = 'bg-blue-50'; $borderColor = 'border-blue-200'; $textColor = 'text-blue-700'; $hoverColor = 'hover:bg-blue-100'; $titleStatus = 'Izin';
                                        } elseif ($att->status == 'alpha') {
                                            $bgColor = 'bg-gray-200'; $borderColor = 'border-gray-300'; $textColor = 'text-gray-800'; $hoverColor = 'hover:bg-gray-300'; $titleStatus = 'Alpha';
                                        }
                                    } elseif (\Carbon\Carbon::parse($currentDateStr)->isBefore(\Carbon\Carbon::today())) {
                                        $bgColor = 'bg-gray-200'; $borderColor = 'border-gray-300'; $textColor = 'text-gray-800'; $hoverColor = 'hover:bg-gray-300'; $titleStatus = 'Alpha (Tidak Hadir)';
                                    }
                                    
                                    // Jika ini adalah jadwal pengguna sendiri, kita tetap pertahankan ring/border yang mencolok namun pakai warna status
                                    if ($isMe) {
                                        $borderColor = 'border-gray-500 ring-1 ring-gray-400';
                                        if ($att) {
                                            if ($att->status == 'present') { $borderColor = 'border-green-400 ring-1 ring-green-300'; }
                                            elseif ($att->status == 'late') { $borderColor = 'border-orange-400 ring-1 ring-orange-300'; }
                                            elseif ($att->status == 'sakit' || $att->status == 'sick') { $borderColor = 'border-red-400 ring-1 ring-red-300'; }
                                            elseif ($att->status == 'izin' || $att->status == 'permit') { $borderColor = 'border-blue-400 ring-1 ring-blue-300'; }
                                            elseif ($att->status == 'alpha') { $borderColor = 'border-gray-400 ring-1 ring-gray-300'; }
                                        }
                                    }
                                @endphp
                                <div class="flex items-center gap-1.5 px-2 py-1 rounded-full {{ $bgColor }} border {{ $borderColor }} {{ $hoverColor }} transition-colors" title="{{ $schedule->user->name }} ({{ $titleStatus }})">
                                    <!-- Avatar Mini -->
                                    <div class="w-4 h-4 md:w-5 md:h-5 rounded-full flex-shrink-0 bg-white flex items-center justify-center text-[9px] font-bold text-gray-600 overflow-hidden shadow-sm border border-gray-100">
                                        @if($schedule->user->avatar)
                                            <img src="{{ Storage::url($schedule->user->avatar) }}" alt="{{ $schedule->user->name }}" class="w-full h-full object-cover">
                                        @else
                                            {{ substr($schedule->user->name, 0, 1) }}
                                        @endif
                                    </div>
                                    <!-- Nama -->
                                    <span class="text-[9px] md:text-[10px] font-semibold truncate {{ $textColor }}">
                                        {{ $schedule->user->nickname ? $schedule->user->nickname : explode(' ', trim($schedule->user->name))[0] }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @endif
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
