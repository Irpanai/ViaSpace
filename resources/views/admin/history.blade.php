@extends('layouts.admin')
@section('title', 'Seluruh Riwayat Presensi')

@section('content')

<div class="glass-panel rounded-3xl shadow-xl overflow-hidden mt-6">
    <div class="p-6 md:p-8 border-b border-gray-100 bg-white/50 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-xl font-bold text-gray-800 tracking-tight">Rekam Jejak Logbook</h2>
            <p class="text-gray-500 mt-1 text-sm">Daftar riwayat kehadiran dan laporan logbook seluruh siswa magang.</p>
        </div>
        <button onclick="document.getElementById('manualLeaveModal').classList.remove('hidden')" class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-medium py-2 px-4 rounded-xl shadow-lg shadow-blue-500/30 transition-all flex items-center gap-2 text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Input Izin Manual
        </button>
    </div>

    <!-- Filter Form -->
    <div class="px-6 py-4 bg-gray-50/50 border-b border-gray-100">
        <form action="{{ route('admin.history') }}" method="GET" class="flex flex-col md:flex-row items-center gap-4">
            <select name="intern_id" class="w-full md:w-64 px-4 py-2 rounded-xl border border-gray-200 shadow-sm focus:bg-white focus:border-orange-500 focus:ring-orange-500 text-sm bg-gray-50">
                <option value="">Semua Siswa</option>
                @foreach($interns as $intern)
                    <option value="{{ $intern->id }}" {{ request('intern_id') == $intern->id ? 'selected' : '' }}>{{ $intern->name }}</option>
                @endforeach
            </select>
            
            <input type="month" name="month" value="{{ request('month', now()->format('Y-m')) }}" class="w-full md:w-auto px-4 py-2 rounded-xl border border-gray-200 shadow-sm focus:bg-white focus:border-orange-500 focus:ring-orange-500 text-sm bg-gray-50" title="Bulan">
            
            <button type="submit" class="w-full md:w-auto px-6 py-2 bg-gray-800 text-white rounded-xl text-sm font-semibold hover:bg-gray-700 transition-colors shadow-sm">
                Terapkan Filter
            </button>
            @if(request()->has('intern_id') || request()->has('month'))
                <a href="{{ route('admin.history') }}" class="text-sm text-orange-600 hover:text-orange-700 font-medium whitespace-nowrap">Reset Filter</a>
            @endif
        </form>
    </div>

    <div class="p-0">
        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-widest border-b border-gray-100">
                        <th class="p-5 font-semibold">Nama Siswa</th>
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
                                <div class="font-medium text-gray-800">{{ $att->user->name }}</div>
                                <div class="text-xs text-gray-500">{{ $att->user->nickname }}</div>
                            </td>
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
                                            <a href="{{ Storage::url($att->logbook->photo_path) }}" target="_blank" class="flex-shrink-0 relative group/img block overflow-hidden rounded-lg">
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
                                @elseif($att->status == 'sick' || $att->status == 'sakit')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Sakit
                                    </span>
                                @elseif($att->status == 'permit' || $att->status == 'izin')
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
                                    'check_out_photo_path' => $att->check_out_photo_path ? Storage::url($att->check_out_photo_path) : null,
                                    'leave_reason' => $att->leave_reason,
                                    'leave_proof_path' => $att->leave_proof_path ? Storage::url($att->leave_proof_path) : null
                                ]) }})" class="inline-flex items-center gap-2 px-3 py-1.5 bg-orange-50 text-orange-600 rounded-lg hover:bg-orange-100 transition-colors text-sm font-medium border border-orange-100">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    Detail
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center">
                                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </div>
                                <div class="text-gray-500 font-medium">Belum ada riwayat presensi.</div>
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
<div id="detailModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-hidden flex flex-col transform transition-all">
        <div class="p-6 md:p-8 border-b border-gray-100 flex justify-between items-center bg-gradient-to-r from-gray-50 to-white">
            <div>
                <h3 class="text-2xl font-bold text-gray-900 tracking-tight" id="modalTitle">Detail Rekam Jejak</h3>
                <p class="text-sm text-gray-500 mt-1">Ringkasan aktivitas absensi dan laporan pekerjaan.</p>
            </div>
            <button onclick="document.getElementById('detailModal').classList.add('hidden')" class="w-10 h-10 rounded-full bg-gray-100 text-gray-500 hover:bg-gray-200 hover:text-gray-700 flex items-center justify-center transition-colors">
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
            <button onclick="document.getElementById('detailModal').classList.add('hidden')" class="px-8 py-3 bg-gray-900 text-white font-semibold rounded-xl hover:bg-black transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5">Tutup Jendela</button>
        </div>
    </div>
</div>

<!-- Modal Input Izin Manual -->
<div id="manualLeaveModal" class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4 overflow-hidden transform transition-all">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h3 class="text-xl font-bold text-gray-800">Input Izin / Sakit Manual</h3>
            <button onclick="document.getElementById('manualLeaveModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <div class="p-6">
            <form action="{{ route('history.leave') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Siswa</label>
                        <select name="user_id" required class="w-full border border-gray-200 rounded-xl shadow-sm focus:bg-white focus:border-orange-500 focus:ring-orange-500 p-3 bg-gray-50 transition-colors">
                            <option value="">Pilih Siswa...</option>
                            @foreach($interns as $intern)
                                <option value="{{ $intern->id }}">{{ $intern->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Tanggal</label>
                        <input type="date" name="date" required value="{{ now()->format('Y-m-d') }}" class="w-full border border-gray-200 rounded-xl shadow-sm focus:bg-white focus:border-orange-500 focus:ring-orange-500 p-3 bg-gray-50 transition-colors">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Tipe Izin</label>
                        <select name="status" required class="w-full border border-gray-200 rounded-xl shadow-sm focus:bg-white focus:border-orange-500 focus:ring-orange-500 p-3 bg-gray-50 transition-colors">
                            <option value="sakit">Sakit</option>
                            <option value="izin">Izin</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Keterangan / Alasan</label>
                        <textarea name="leave_reason" rows="3" required placeholder="Contoh: Mengikuti acara keluarga, atau surat menyusul..." class="w-full border border-gray-200 rounded-xl shadow-sm focus:bg-white focus:border-orange-500 focus:ring-orange-500 p-3 bg-gray-50 transition-colors"></textarea>
                    </div>
                </div>

                <div class="mt-8 flex justify-end gap-3">
                    <button type="button" onclick="document.getElementById('manualLeaveModal').classList.add('hidden')" class="px-5 py-2.5 text-gray-600 font-medium hover:bg-gray-100 rounded-xl transition-colors">Batal</button>
                    <button type="submit" class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-medium py-2.5 px-6 rounded-xl shadow-md transition-all transform hover:-translate-y-0.5">
                        Simpan Absensi
                    </button>
                </div>
            </form>
        </div>
    </div>
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
        else if(data.status === 'sick' || data.status === 'sakit') statusBadge = 'Sakit';
        else if(data.status === 'permit' || data.status === 'izin') statusBadge = 'Izin';
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

        // Handle Logbook / Leave Proof
        let logbookHtml = '';
        if (data.status === 'izin' || data.status === 'sakit' || data.status === 'sick' || data.status === 'permit') {
            logbookHtml += `
                <div class="mb-4">
                    <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Alasan ${statusBadge}</div>
                    <div class="text-gray-700 leading-relaxed whitespace-pre-wrap break-words">${data.leave_reason || '-'}</div>
                </div>
            `;
            if (data.leave_proof_path) {
                logbookHtml += `
                <div>
                    <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Surat Bukti</div>
                    <a href="${data.leave_proof_path}" target="_blank" class="block w-full">
                        <img src="${data.leave_proof_path}" class="w-full h-48 object-cover rounded-lg border border-gray-200 hover:opacity-80 transition" alt="Surat Bukti">
                    </a>
                </div>`;
            }
        } else if (data.logbook) {
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
                photosHtml += `<a href="/storage/${data.logbook.photo_path.replace('public/', '')}" target="_blank"><img src="/storage/${data.logbook.photo_path.replace('public/', '')}" class="w-full h-32 object-cover rounded-lg border border-gray-200 hover:opacity-80 transition"></a>`;
            }
            if (data.logbook.photo_path_2) {
                photosHtml += `<a href="/storage/${data.logbook.photo_path_2.replace('public/', '')}" target="_blank"><img src="/storage/${data.logbook.photo_path_2.replace('public/', '')}" class="w-full h-32 object-cover rounded-lg border border-gray-200 hover:opacity-80 transition"></a>`;
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
</script>
@endsection
