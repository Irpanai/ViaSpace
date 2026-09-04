@extends('layouts.intern')
@section('title', 'Rapor Penilaian')

@section('content')

<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Rapor Magang</h2>
    <p class="text-gray-500 mt-1 text-sm">Evaluasi bulanan kinerja Anda selama program magang.</p>
</div>

<div class="grid grid-cols-1 gap-6">
    @forelse($grades as $grade)
        <div class="glass-panel rounded-3xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-xl transition-all">
            <div class="flex flex-col md:flex-row border-b border-gray-100 bg-white/50">
                <div class="p-6 md:w-1/3 flex items-center justify-between md:border-r border-gray-100">
                    <div>
                        <div class="text-xs font-bold text-orange-500 uppercase tracking-widest mb-1">Bulan Penilaian</div>
                        <h3 class="text-2xl font-bold text-gray-900">{{ \Carbon\Carbon::parse($grade->year . '-' . $grade->month . '-01')->translatedFormat('F Y') }}</h3>
                    </div>
                    <div class="w-16 h-16 rounded-full flex items-center justify-center font-bold text-xl {{ $grade->average_score >= 80 ? 'bg-green-100 text-green-700' : ($grade->average_score >= 60 ? 'bg-orange-100 text-orange-700' : 'bg-red-100 text-red-700') }} shadow-inner">
                        {{ $grade->average_score }}
                    </div>
                </div>
                
                <div class="p-6 md:w-2/3 grid grid-cols-3 gap-4">
                    <div class="text-center p-3 bg-gray-50/50 rounded-2xl">
                        <div class="text-gray-400 text-xs font-semibold uppercase tracking-wider mb-2">Disiplin</div>
                        <div class="text-2xl font-bold text-gray-800">{{ $grade->discipline_score }}</div>
                    </div>
                    <div class="text-center p-3 bg-gray-50/50 rounded-2xl">
                        <div class="text-gray-400 text-xs font-semibold uppercase tracking-wider mb-2">Kerjasama</div>
                        <div class="text-2xl font-bold text-gray-800">{{ $grade->teamwork_score }}</div>
                    </div>
                    <div class="text-center p-3 bg-gray-50/50 rounded-2xl">
                        <div class="text-gray-400 text-xs font-semibold uppercase tracking-wider mb-2">Keahlian</div>
                        <div class="text-2xl font-bold text-gray-800">{{ $grade->skill_score }}</div>
                    </div>
                </div>
            </div>
            
            @if($grade->notes)
            <div class="p-6 bg-orange-50/30">
                <div class="flex gap-3">
                    <svg class="w-5 h-5 text-orange-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path></svg>
                    <div>
                        <h4 class="text-xs font-bold text-gray-700 uppercase tracking-widest mb-1">Catatan Evaluasi</h4>
                        <p class="text-sm text-gray-600 italic">"{{ $grade->notes }}"</p>
                    </div>
                </div>
            </div>
            @endif
        </div>
    @empty
        <div class="glass-panel p-10 rounded-3xl text-center shadow-md">
            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <h3 class="text-lg font-bold text-gray-800 mb-1">Belum Ada Rapor</h3>
            <p class="text-gray-500 text-sm">Nilai magang Anda akan muncul di sini setelah Admin melakukan evaluasi bulanan.</p>
        </div>
    @endempty
</div>

@endsection
