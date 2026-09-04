@extends('layouts.intern')
@section('title', 'Pengajuan Izin & Sakit')

@section('content')

@if(session('success'))
<div class="mb-6 bg-green-50 text-green-700 p-4 rounded-2xl border border-green-200 flex items-center gap-3 shadow-sm">
    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
    <span class="font-medium">{{ session('success') }}</span>
</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Form Pengajuan -->
    <div class="lg:col-span-1">
        <div class="glass-panel rounded-3xl shadow-xl overflow-hidden sticky top-24">
            <div class="p-6 md:p-8 border-b border-gray-100 bg-white/50">
                <h2 class="text-xl font-bold text-gray-800 tracking-tight">Ajukan Izin</h2>
                <p class="text-gray-500 mt-1 text-sm">Formulir pengajuan izin atau sakit.</p>
            </div>
            <div class="p-6 md:p-8 bg-gray-50/30">
                <form action="{{ route('intern.leave.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                    @csrf
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tipe Pengajuan</label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="cursor-pointer">
                                <input type="radio" name="type" value="sick" class="peer sr-only" required>
                                <div class="px-4 py-3 rounded-xl border border-gray-200 text-center peer-checked:bg-red-50 peer-checked:border-red-500 peer-checked:text-red-700 transition-all text-sm font-medium hover:bg-gray-50 text-gray-600">Sakit</div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="type" value="permission" class="peer sr-only">
                                <div class="px-4 py-3 rounded-xl border border-gray-200 text-center peer-checked:bg-blue-50 peer-checked:border-blue-500 peer-checked:text-blue-700 transition-all text-sm font-medium hover:bg-gray-50 text-gray-600">Izin Lainnya</div>
                            </label>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Dari Tanggal</label>
                            <input type="date" name="start_date" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Sampai Tanggal</label>
                            <input type="date" name="end_date" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 text-sm">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Alasan Detail</label>
                        <textarea name="reason" rows="3" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 text-sm resize-none" placeholder="Tuliskan alasan izin/sakit dengan jelas..."></textarea>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Lampiran <span class="text-xs text-gray-400 font-normal">(Opsional/Wajib jika sakit)</span></label>
                        <input type="file" name="proof" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100 border border-gray-200 rounded-xl bg-white">
                        <p class="text-xs text-gray-400 mt-1.5">Format: JPG/PNG, maks 2MB (Surat Dokter/dll)</p>
                    </div>
                    
                    <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white rounded-xl font-bold text-sm shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-orange-500/50">
                        Kirim Pengajuan
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Riwayat Pengajuan -->
    <div class="lg:col-span-2">
        <div class="glass-panel rounded-3xl shadow-xl overflow-hidden h-full">
            <div class="p-6 md:p-8 border-b border-gray-100 bg-white/50">
                <h2 class="text-xl font-bold text-gray-800 tracking-tight">Riwayat Pengajuan</h2>
                <p class="text-gray-500 mt-1 text-sm">Status dan riwayat izin yang pernah Anda ajukan.</p>
            </div>
            <div class="p-0">
                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-widest border-b border-gray-100">
                                <th class="p-5 font-semibold">Tanggal Diajukan</th>
                                <th class="p-5 font-semibold">Tipe & Rentang</th>
                                <th class="p-5 font-semibold">Alasan</th>
                                <th class="p-5 font-semibold text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($leaves as $leave)
                                <tr class="hover:bg-orange-50/30 transition-colors">
                                    <td class="p-5 whitespace-nowrap">
                                        <div class="font-medium text-gray-800">{{ $leave->created_at->format('d M Y') }}</div>
                                        <div class="text-xs text-gray-400">{{ $leave->created_at->format('H:i') }}</div>
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
                                        @if($leave->admin_notes)
                                            <div class="mt-1.5 p-2 bg-gray-50 rounded-lg text-xs border border-gray-100 border-l-2 border-l-gray-400 text-gray-500">
                                                <span class="font-semibold text-gray-700">Catatan Admin:</span> {{ $leave->admin_notes }}
                                            </div>
                                        @endif
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
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="p-10 text-center">
                                        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3">
                                            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        </div>
                                        <div class="text-gray-500 font-medium">Belum ada riwayat pengajuan.</div>
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
    </div>
</div>

@endsection
