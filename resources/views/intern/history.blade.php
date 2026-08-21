@extends('layouts.intern')
@section('title', 'Riwayat Presensi')

@section('content')

<!-- Statistics Cards -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-green-50 text-green-500 flex items-center justify-center">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <div>
            <div class="text-2xl font-bold text-gray-800">{{ $totalHadir }}</div>
            <div class="text-sm font-medium text-gray-500">Total Hadir</div>
        </div>
    </div>
    
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-500 flex items-center justify-center">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
        </div>
        <div>
            <div class="text-2xl font-bold text-gray-800">{{ $totalIzin }}</div>
            <div class="text-sm font-medium text-gray-500">Total Izin</div>
        </div>
    </div>
    
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-red-50 text-red-500 flex items-center justify-center">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <div>
            <div class="text-2xl font-bold text-gray-800">{{ $totalSakit }}</div>
            <div class="text-sm font-medium text-gray-500">Total Sakit</div>
        </div>
    </div>
</div>

<div class="glass-panel rounded-3xl shadow-xl overflow-hidden mt-6">
    <div class="p-6 md:p-8 border-b border-gray-100 bg-white/50 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-xl font-bold text-gray-800 tracking-tight">Rekam Jejak Logbook</h2>
            <p class="text-gray-500 mt-1 text-sm">Daftar riwayat kehadiran dan laporan logbook harian Anda selama masa magang.</p>
        </div>
        <div class="flex items-center gap-3 w-full md:w-auto">
            <a href="{{ route('intern.history.export.excel', request()->query()) }}" class="flex-1 md:flex-none flex items-center justify-center gap-2 px-4 py-2 bg-green-50 text-green-700 hover:bg-green-100 rounded-xl font-semibold border border-green-200 transition-colors shadow-sm text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Export Excel
            </a>
            <a href="{{ route('intern.history.export.pdf', request()->query()) }}" class="flex-1 md:flex-none flex items-center justify-center gap-2 px-4 py-2 bg-red-50 text-red-700 hover:bg-red-100 rounded-xl font-semibold border border-red-200 transition-colors shadow-sm text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Export PDF
            </a>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="px-6 py-4 bg-gray-50/50 border-b border-gray-100">
        <form action="{{ route('intern.history') }}" method="GET" class="flex flex-col md:flex-row items-center gap-4">
            <select name="filter_type" id="filter_type" class="w-full md:w-auto px-4 py-2 rounded-xl border border-gray-200 shadow-sm focus:bg-white focus:border-orange-500 focus:ring-orange-500 text-sm bg-gray-50" onchange="toggleCustomDates()">
                <option value="all" {{ request('filter_type') == 'all' ? 'selected' : '' }}>Semua Data</option>
                <option value="this_week" {{ request('filter_type') == 'this_week' ? 'selected' : '' }}>Minggu Ini</option>
                <option value="this_month" {{ request('filter_type') == 'this_month' ? 'selected' : '' }}>Bulan Ini</option>
                <option value="custom" {{ request('filter_type') == 'custom' ? 'selected' : '' }}>Pilih Kustom</option>
            </select>
            
            <div id="custom_dates" class="flex items-center gap-2 w-full md:w-auto {{ request('filter_type') == 'custom' ? '' : 'hidden' }}">
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full md:w-auto px-4 py-2 rounded-xl border border-gray-200 shadow-sm focus:bg-white focus:border-orange-500 focus:ring-orange-500 text-sm bg-gray-50" title="Dari Tanggal">
                <span class="text-gray-400 font-medium">-</span>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full md:w-auto px-4 py-2 rounded-xl border border-gray-200 shadow-sm focus:bg-white focus:border-orange-500 focus:ring-orange-500 text-sm bg-gray-50" title="Sampai Tanggal">
            </div>
            
            <button type="submit" class="w-full md:w-auto px-6 py-2 bg-gray-800 text-white rounded-xl text-sm font-semibold hover:bg-gray-700 transition-colors shadow-sm">
                Terapkan
            </button>
            @if(request()->has('filter_type') && request('filter_type') != 'all')
                <a href="{{ route('intern.history') }}" class="text-sm text-orange-600 hover:text-orange-700 font-medium whitespace-nowrap">Reset Filter</a>
            @endif
        </form>
    </div>

    <div class="p-0">
        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-widest border-b border-gray-100">
                        <th class="p-5 font-semibold">Tanggal</th>
                        <th class="p-5 font-semibold">Check-In</th>
                        <th class="p-5 font-semibold">Jam Keluar</th>
                        <th class="p-5 font-semibold">Logbook / Pekerjaan</th>
                        <th class="p-5 font-semibold text-center">Status</th>
                        <th class="p-5 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($attendances as $att)
                        <tr class="hover:bg-orange-50/30 transition-colors group">
                            <td class="p-5 whitespace-nowrap">
                                <div class="font-medium text-gray-800">{{ \Carbon\Carbon::parse($att->date)->format('d M Y') }}</div>
                                <div class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($att->date)->translatedFormat('l') }}</div>
                            </td>
                            <td class="p-5 whitespace-nowrap">
                                @if($att->check_in_time)
                                    <div class="inline-flex items-center gap-1.5 text-green-600 bg-green-50 px-2.5 py-1 rounded-lg text-sm font-medium border border-green-100">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        {{ \Carbon\Carbon::parse($att->check_in_time)->format('H:i') }}
                                    </div>
                                @else
                                    <span class="text-gray-300">-</span>
                                @endif
                            </td>
                            <td class="p-5 whitespace-nowrap">
                                @if($att->check_out_time)
                                    <div class="inline-flex items-center gap-1.5 text-orange-600 bg-orange-50 px-2.5 py-1 rounded-lg text-sm font-medium border border-orange-100">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        {{ \Carbon\Carbon::parse($att->check_out_time)->format('H:i') }}
                                    </div>
                                @else
                                    <span class="text-gray-300">-</span>
                                @endif
                            </td>
                            <td class="p-5">
                                @if($att->logbook)
                                    <div class="flex items-start gap-3">
                                        @if($att->logbook->photo_path)
                                            <a href="javascript:void(0)" onclick="showImageModal('{{ Storage::url($att->logbook->photo_path) }}')" class="flex-shrink-0 relative group/img block overflow-hidden rounded-lg">
                                                <img src="{{ Storage::url($att->logbook->photo_path) }}" alt="Bukti" class="w-12 h-12 object-cover border border-gray-200 transition duration-300 group-hover/img:scale-110">
                                                <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover/img:opacity-100 transition duration-300">
                                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                                </div>
                                            </a>
                                        @endif
                                        <div>
                                            <div class="text-sm font-bold text-gray-800">{{ $att->logbook->category }}</div>
                                            <div class="text-xs text-gray-500 line-clamp-2 mt-0.5" title="{{ $att->logbook->description }}">{{ $att->logbook->description }}</div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-gray-400 text-sm italic">Belum mengisi logbook</span>
                                @endif
                            </td>
                            <td class="p-5 whitespace-nowrap text-center">
                                @if($att->status == 'present')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Hadir
                                    </span>
                                @elseif($att->status == 'sick')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Sakit
                                    </span>
                                @elseif($att->status == 'permit')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> Izin
                                    </span>
                                @elseif($att->status == 'late')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-orange-100 text-orange-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span> Terlambat
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-gray-500"></span> Alpa
                                    </span>
                                @endif
                            </td>
                            <td class="p-5 whitespace-nowrap text-center">
                                <button onclick="showDetailModal({{ json_encode([
                                    'date' => \Carbon\Carbon::parse($att->date)->translatedFormat('l, d F Y'),
                                    'check_in' => $att->check_in_time ? \Carbon\Carbon::parse($att->check_in_time)->format('H:i') : '-',
                                    'check_out' => $att->check_out_time ? \Carbon\Carbon::parse($att->check_out_time)->format('H:i') : '-',
                                    'status' => $att->status,
                                    'logbook' => $att->logbook,
                                    'photo_path' => $att->photo_path ? Storage::url($att->photo_path) : null,
                                    'check_out_photo_path' => $att->check_out_photo_path ? Storage::url($att->check_out_photo_path) : null
                                ]) }})" class="inline-flex items-center gap-2 px-3 py-1.5 bg-orange-50 text-orange-600 rounded-lg hover:bg-orange-100 transition-colors text-sm font-medium border border-orange-100">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    Detail
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-10 text-center text-gray-500">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                Belum ada riwayat presensi.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="p-5 border-t border-gray-100">
            {{ $attendances->links() }}
        </div>
    </div>
</div>

<!-- Modal Detail Logbook -->
<div id="detailModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4" onclick="closeDetailModal()">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-hidden flex flex-col transform transition-all" onclick="event.stopPropagation()">
        <div class="p-6 md:p-8 border-b border-gray-100 flex justify-between items-center bg-gradient-to-r from-gray-50 to-white">
            <div>
                <h3 class="text-2xl font-bold text-gray-900 tracking-tight" id="modalTitle">Detail Rekam Jejak</h3>
                <p class="text-sm text-gray-500 mt-1">Ringkasan aktivitas absensi dan laporan pekerjaan.</p>
            </div>
            <button onclick="closeDetailModal()" class="w-10 h-10 rounded-full bg-gray-100 text-gray-500 hover:bg-gray-200 hover:text-gray-700 flex items-center justify-center transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <div class="p-6 md:p-8 overflow-y-auto flex-1 custom-scrollbar bg-gray-50/30">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                    <div class="text-xs text-gray-400 font-bold uppercase tracking-widest mb-1.5">Tanggal</div>
                    <div class="font-bold text-gray-900 text-lg" id="modalDate">-</div>
                </div>
                <div class="bg-gradient-to-br from-green-50 to-green-100/50 p-5 rounded-2xl shadow-sm border border-green-100">
                    <div class="text-xs text-green-600 font-bold uppercase tracking-widest mb-1.5">Check-In</div>
                    <div class="font-bold text-green-700 text-lg" id="modalCheckIn">-</div>
                </div>
                <div class="bg-gradient-to-br from-orange-50 to-orange-100/50 p-5 rounded-2xl shadow-sm border border-orange-100">
                    <div class="text-xs text-orange-600 font-bold uppercase tracking-widest mb-1.5">Check-Out</div>
                    <div class="font-bold text-orange-700 text-lg" id="modalCheckOut">-</div>
                </div>
                <div class="bg-gradient-to-br from-blue-50 to-blue-100/50 p-5 rounded-2xl shadow-sm border border-blue-100">
                    <div class="text-xs text-blue-600 font-bold uppercase tracking-widest mb-1.5">Status</div>
                    <div class="font-bold text-blue-700 text-lg" id="modalStatus">-</div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Bagian Kiri: Foto Presensi -->
                <div class="space-y-6">
                    <h4 class="text-base font-bold text-gray-900 border-b border-gray-200 pb-3 flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path></svg>
                        Bukti Kehadiran
                    </h4>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div id="modalPhotoContainer" class="hidden flex-col items-center">
                            <span class="text-xs font-semibold text-gray-500 mb-2">Selfie Datang</span>
                            <img id="modalCheckInPhoto" src="" alt="Foto Check-In" class="w-full h-48 object-cover rounded-2xl border-2 border-gray-100 shadow-sm">
                        </div>
                        <div id="modalPhotoOutContainer" class="hidden flex-col items-center">
                            <span class="text-xs font-semibold text-gray-500 mb-2">Selfie Pulang</span>
                            <img id="modalCheckOutPhoto" src="" alt="Foto Check-Out" class="w-full h-48 object-cover rounded-2xl border-2 border-gray-100 shadow-sm">
                        </div>
                    </div>
                </div>

                <!-- Bagian Kanan: Logbook -->
                <div>
                    <h4 class="text-base font-bold text-gray-900 border-b border-gray-200 pb-3 flex items-center gap-2 mb-6">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Laporan Logbook
                    </h4>
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100" id="modalLogbookContent">
                        <!-- Content injected via JS -->
                    </div>
                </div>
            </div>
        </div>
        
        <div class="p-6 border-t border-gray-100 bg-gray-50 flex justify-end">
            <button onclick="closeDetailModal()" class="px-8 py-3 bg-gray-900 text-white font-semibold rounded-xl hover:bg-black transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5">Tutup Jendela</button>
        </div>
    </div>
</div>

<!-- Modal Image -->
<div id="imageModal" class="fixed inset-0 bg-black/90 backdrop-blur-sm hidden z-[100] flex items-center justify-center p-4" onclick="closeImageModal()">
    <button type="button" class="absolute top-4 right-4 md:top-6 md:right-6 w-10 h-10 md:w-12 md:h-12 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-colors backdrop-blur-md" onclick="closeImageModal()">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
    </button>
    <img id="imageModalContent" src="" class="max-w-full max-h-[90vh] object-contain rounded-xl shadow-2xl" onclick="event.stopPropagation()">
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    .custom-scrollbar:hover::-webkit-scrollbar-thumb { background: #94a3b8; }
</style>

<script>
    function toggleCustomDates() {
        const type = document.getElementById('filter_type').value;
        const customDates = document.getElementById('custom_dates');
        if (type === 'custom') {
            customDates.classList.remove('hidden');
        } else {
            customDates.classList.add('hidden');
        }
    }

    function showDetailModal(data) {
        document.getElementById('modalDate').textContent = data.date;
        document.getElementById('modalCheckIn').textContent = data.check_in;
        document.getElementById('modalCheckOut').textContent = data.check_out;
        
        let statusBadge = '';
        if(data.status === 'present') statusBadge = 'Hadir';
        else if(data.status === 'late') statusBadge = 'Terlambat';
        else if(data.status === 'sick') statusBadge = 'Sakit';
        else if(data.status === 'permit') statusBadge = 'Izin';
        else statusBadge = 'Alpa';
        document.getElementById('modalStatus').textContent = statusBadge;

        // Handle Photo
        if (data.photo_path) {
            document.getElementById('modalCheckInPhoto').src = data.photo_path;
            document.getElementById('modalPhotoContainer').classList.remove('hidden');
            document.getElementById('modalPhotoContainer').classList.add('flex');
        } else {
            document.getElementById('modalPhotoContainer').classList.remove('flex');
            document.getElementById('modalPhotoContainer').classList.add('hidden');
        }

        if (data.check_out_photo_path) {
            document.getElementById('modalCheckOutPhoto').src = data.check_out_photo_path;
            document.getElementById('modalPhotoOutContainer').classList.remove('hidden');
            document.getElementById('modalPhotoOutContainer').classList.add('flex');
        } else {
            document.getElementById('modalPhotoOutContainer').classList.remove('flex');
            document.getElementById('modalPhotoOutContainer').classList.add('hidden');
        }

        // Handle Logbook
        let logbookHtml = '';
        if (data.logbook) {
            logbookHtml += `
                <div class="mb-4">
                    <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Kategori Pekerjaan</div>
                    <div class="text-gray-800 font-medium">${data.logbook.category}</div>
                </div>
                <div class="mb-4">
                    <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Deskripsi</div>
                    <div class="text-gray-700 leading-relaxed whitespace-pre-wrap break-words">${data.logbook.description}</div>
                </div>
            `;
            
            if (data.logbook.link) {
                logbookHtml += `
                <div class="mb-4">
                    <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Tautan / Bukti Link</div>
                    <a href="${data.logbook.link}" target="_blank" class="text-orange-600 hover:text-orange-700 underline break-all">${data.logbook.link}</a>
                </div>`;
            }

            // Photos from Logbook
            let photosHtml = '';
            if (data.logbook.photo_path) {
                photosHtml += `<a href="javascript:void(0)" onclick="showImageModal('/storage/${data.logbook.photo_path.replace('public/', '')}')"><img src="/storage/${data.logbook.photo_path.replace('public/', '')}" class="w-full h-32 object-cover rounded-lg border border-gray-200 hover:opacity-80 transition"></a>`;
            }
            if (data.logbook.photo_path_2) {
                photosHtml += `<a href="javascript:void(0)" onclick="showImageModal('/storage/${data.logbook.photo_path_2.replace('public/', '')}')"><img src="/storage/${data.logbook.photo_path_2.replace('public/', '')}" class="w-full h-32 object-cover rounded-lg border border-gray-200 hover:opacity-80 transition"></a>`;
            }

            if (photosHtml) {
                logbookHtml += `
                <div>
                    <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Foto / Screenshot Logbook</div>
                    <div class="grid grid-cols-2 gap-3">${photosHtml}</div>
                </div>`;
            }
        } else {
            logbookHtml = `<div class="text-center py-6 text-gray-400 italic">Tidak ada catatan logbook untuk hari ini.</div>`;
        }
        
        document.getElementById('modalLogbookContent').innerHTML = logbookHtml;
        document.getElementById('detailModal').classList.remove('hidden');
    }

    function showImageModal(src) {
        document.getElementById('imageModalContent').src = src;
        document.getElementById('imageModal').classList.remove('hidden');
    }

    function closeImageModal() {
        document.getElementById('imageModal').classList.add('hidden');
    }

    function closeDetailModal() {
        document.getElementById('detailModal').classList.add('hidden');
    }

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            if (!document.getElementById('imageModal').classList.contains('hidden')) {
                closeImageModal();
            } else if (!document.getElementById('detailModal').classList.contains('hidden')) {
                closeDetailModal();
            }
        }
    });
</script>
@endsection
