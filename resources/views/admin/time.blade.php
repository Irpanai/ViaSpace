@extends('layouts.admin')
@section('title', 'Pengaturan Jam Shift')

@section('content')
<div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Jam Shift Operasional</h2>
        <p class="text-gray-500 text-sm">Atur batas waktu check-in dan check-out untuk seluruh siswa magang.</p>
    </div>
</div>

<div class="max-w-2xl">
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 md:p-8">
        <h3 class="text-lg font-semibold text-gray-800 mb-6 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-orange-50 text-orange-500 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            Konfigurasi Jam Kerja
        </h3>
        
        <form action="{{ route('admin.time.update') }}" method="POST">
            @csrf
            <div class="space-y-6">
                <!-- Start Time -->
                <div class="group relative">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Jam Masuk (Start Time)</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400 group-focus-within:text-orange-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <input type="text" name="shift_start_time" value="{{ $startTime }}" required class="timepicker w-full pl-12 pr-4 py-3 border border-gray-200 rounded-xl shadow-sm bg-gray-50 focus:bg-white focus:border-orange-500 focus:ring-orange-500 font-mono text-lg transition-colors cursor-pointer" placeholder="Pilih Jam Masuk">
                    </div>
                    <p class="text-xs text-gray-500 mt-2 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Siswa tidak bisa melakukan Check-In sebelum jam ini.
                    </p>
                </div>

                <!-- End Time -->
                <div class="group relative">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Jam Pulang (End Time)</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400 group-focus-within:text-orange-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <input type="text" name="shift_end_time" value="{{ $endTime }}" required class="timepicker w-full pl-12 pr-4 py-3 border border-gray-200 rounded-xl shadow-sm bg-gray-50 focus:bg-white focus:border-orange-500 focus:ring-orange-500 font-mono text-lg transition-colors cursor-pointer" placeholder="Pilih Jam Pulang">
                    </div>
                    <p class="text-xs text-gray-500 mt-2 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Sistem Auto-Checkout akan memproses logbook kosong setelah batas jam ini.
                    </p>
                </div>

                <div class="pt-4 border-t border-gray-100">
                    <button type="submit" class="w-full bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-bold py-3.5 px-4 rounded-xl transition duration-300 shadow-lg shadow-orange-500/30 transform hover:-translate-y-0.5">
                        Simpan Pengaturan Jam
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        flatpickr(".timepicker", {
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            time_24hr: true
        });
    });
</script>
@endpush
@endsection
