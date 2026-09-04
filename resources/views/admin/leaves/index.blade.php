@extends('layouts.admin')
@section('title', 'Persetujuan Izin & Sakit')

@section('content')

@if(session('success'))
<div class="mb-6 bg-green-50 text-green-700 p-4 rounded-2xl border border-green-200 flex items-center gap-3 shadow-sm">
    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
    <span class="font-medium">{{ session('success') }}</span>
</div>
@endif

<div class="glass-panel rounded-3xl shadow-xl overflow-hidden mt-6">
    <div class="p-6 md:p-8 border-b border-gray-100 bg-white/50 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-xl font-bold text-gray-800 tracking-tight">Daftar Pengajuan</h2>
            <p class="text-gray-500 mt-1 text-sm">Kelola persetujuan izin dan sakit seluruh siswa magang.</p>
        </div>
        
        <form action="{{ route('admin.leaves.index') }}" method="GET" class="w-full md:w-auto flex items-center gap-2">
            <select name="status" class="w-full md:w-auto px-4 py-2 rounded-xl border border-gray-200 shadow-sm focus:bg-white focus:border-orange-500 focus:ring-orange-500 text-sm bg-gray-50" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
            </select>
        </form>
    </div>

    <div class="p-0">
        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-widest border-b border-gray-100">
                        <th class="p-5 font-semibold">Nama Siswa</th>
                        <th class="p-5 font-semibold">Tipe & Tanggal</th>
                        <th class="p-5 font-semibold">Alasan</th>
                        <th class="p-5 font-semibold text-center">Status</th>
                        <th class="p-5 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($leaves as $leave)
                        <tr class="hover:bg-orange-50/30 transition-colors">
                            <td class="p-5 whitespace-nowrap">
                                <div class="font-medium text-gray-800">{{ $leave->user->name }}</div>
                                <div class="text-xs text-gray-400">Diajukan: {{ $leave->created_at->format('d M Y') }}</div>
                            </td>
                            <td class="p-5">
                                <div class="flex items-center gap-2 mb-1">
                                    @if($leave->type == 'sick')
                                        <span class="px-2 py-0.5 bg-red-100 text-red-600 rounded text-[10px] font-bold uppercase tracking-wider">Sakit</span>
                                    @else
                                        <span class="px-2 py-0.5 bg-blue-100 text-blue-600 rounded text-[10px] font-bold uppercase tracking-wider">Izin</span>
                                    @endif
                                    @if($leave->proof_path)
                                        <a href="{{ Storage::url($leave->proof_path) }}" target="_blank" class="text-gray-400 hover:text-orange-500 transition-colors" title="Lihat Lampiran">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                        </a>
                                    @endif
                                </div>
                                <div class="text-sm font-semibold text-gray-700">
                                    @if($leave->start_date == $leave->end_date)
                                        {{ $leave->start_date->format('d M Y') }}
                                    @else
                                        {{ $leave->start_date->format('d M') }} - {{ $leave->end_date->format('d M Y') }}
                                    @endif
                                </div>
                            </td>
                            <td class="p-5">
                                <p class="text-sm text-gray-600 line-clamp-2" title="{{ $leave->reason }}">{{ $leave->reason }}</p>
                            </td>
                            <td class="p-5 whitespace-nowrap text-center">
                                @if($leave->status == 'pending')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600">
                                        <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> Menunggu
                                    </span>
                                @elseif($leave->status == 'approved')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Disetujui
                                    </span>
                                @elseif($leave->status == 'rejected')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Ditolak
                                    </span>
                                @endif
                            </td>
                            <td class="p-5 text-center">
                                <button type="button" onclick="openActionModal('{{ $leave->id }}', '{{ $leave->user->name }}')" class="px-4 py-2 bg-white border border-gray-200 text-gray-700 hover:bg-orange-50 hover:text-orange-600 rounded-xl text-sm font-semibold transition-colors shadow-sm">
                                    Tinjau
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-10 text-center">
                                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </div>
                                <div class="text-gray-500 font-medium">Belum ada data pengajuan.</div>
                            </td>
                        </tr>
                    @endempty
                </tbody>
            </table>
        </div>
        <div class="p-5 border-t border-gray-100">
            {{ $leaves->links() }}
        </div>
    </div>
</div>

<!-- Modal Tindakan -->
<div id="actionModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden flex flex-col transform transition-all">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50">
            <h3 class="text-xl font-bold text-gray-900">Tinjau Pengajuan</h3>
            <button onclick="document.getElementById('actionModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <form id="actionForm" method="POST" class="p-6 space-y-4">
            @csrf
            <p class="text-sm text-gray-600 mb-4">Berikan persetujuan untuk <span id="modalInternName" class="font-bold text-gray-900"></span>.</p>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tindakan</label>
                <select name="status" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 text-sm" required>
                    <option value="approved">Setujui Pengajuan</option>
                    <option value="rejected">Tolak Pengajuan</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Catatan Admin <span class="text-xs text-gray-400 font-normal">(Opsional)</span></label>
                <textarea name="admin_notes" rows="2" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 text-sm resize-none" placeholder="Berikan catatan bila perlu..."></textarea>
            </div>
            
            <div class="pt-2">
                <button type="submit" class="w-full py-3 bg-gray-900 hover:bg-gray-800 text-white rounded-xl font-bold text-sm transition-colors shadow-md">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openActionModal(id, name) {
        document.getElementById('modalInternName').innerText = name;
        document.getElementById('actionForm').action = '/admin/leaves/' + id;
        document.getElementById('actionModal').classList.remove('hidden');
    }
</script>

@endsection
