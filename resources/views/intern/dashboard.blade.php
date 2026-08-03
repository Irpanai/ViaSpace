@extends('layouts.intern')
@section('title', 'Beranda')

@section('content')

<!-- Statistics Cards -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
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

@if(!$hasSchedule)
    <!-- State: No Schedule / Weekend -->
    <div class="glass-panel rounded-3xl shadow-xl p-10 text-center mt-10">
        <div class="w-24 h-24 bg-gradient-to-br from-orange-100 to-orange-50 text-orange-500 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner">
            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
        </div>
        <h2 class="text-3xl font-bold text-gray-800 mb-3 tracking-tight">Selamat Istirahat!</h2>
        <p class="text-gray-500 max-w-md mx-auto">Anda tidak memiliki jadwal magang untuk hari ini. Silakan nikmati waktu luang Anda atau periksa kembali jadwal di waktu yang akan datang.</p>
    </div>
@else
    <!-- State: Has Schedule -->
    <div class="glass-panel rounded-3xl shadow-xl overflow-hidden">
        <div class="p-8 border-b border-gray-100 bg-white/50">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Presensi Harian</h2>
                    <p class="text-gray-500 mt-1">{{ \Carbon\Carbon::parse($today)->translatedFormat('l, d F Y') }}</p>
                </div>
                <div class="px-4 py-2 bg-orange-50 text-orange-700 rounded-xl font-semibold border border-orange-100 shadow-sm">
                    Shift: {{ $startTime }} - {{ $endTime }}
                </div>
            </div>
        </div>

        <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-8">
            
            <!-- Presensi & Izin Section -->
            <div class="space-y-6">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold">1</div>
                        <h3 class="text-lg font-semibold text-gray-800">Kehadiran Hari Ini</h3>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100 flex flex-col justify-center">
                    @if($attendance && in_array($attendance->status, ['izin', 'sakit']))
                        <div class="text-center py-4 mb-4">
                            <div class="w-16 h-16 bg-blue-100 text-blue-500 rounded-full flex items-center justify-center mx-auto mb-3">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <p class="text-gray-800 font-medium">Anda sedang {{ ucfirst($attendance->status) }} hari ini.</p>
                            @if($attendance->leave_reason)
                                <p class="text-sm text-gray-500 mt-2 italic">"{{ $attendance->leave_reason }}"</p>
                            @endif
                        </div>
                    @elseif($attendance && $attendance->check_in_time)
                        <div class="text-center py-4 mb-4">
                            @if($attendance->status === 'late')
                                <div class="w-16 h-16 bg-orange-100 text-orange-500 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <p class="text-gray-800 font-medium">Terlambat Check-In pada</p>
                            @else
                                <div class="w-16 h-16 bg-green-100 text-green-500 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <p class="text-gray-800 font-medium">Berhasil Check-In pada</p>
                            @endif
                            <p class="text-2xl font-bold text-gray-900 mt-1">{{ \Carbon\Carbon::parse($attendance->check_in_time)->format('H:i') }}</p>
                        </div>
                        
                        <div class="text-sm text-gray-500 text-center mb-2">Riwayat Lokasi Presensi:</div>
                        <!-- Peta Leaflet akan muncul di sini -->
                        <div id="checkInMap" class="w-full h-64 rounded-xl border-2 border-gray-200 z-0 relative" data-lat="{{ $attendance->check_in_lat }}" data-lng="{{ $attendance->check_in_lng }}">
                            <div class="absolute inset-0 flex items-center justify-center bg-gray-100/80 z-10" id="mapLoader">
                                <svg class="animate-spin h-8 w-8 text-orange-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            </div>
                        </div>
                        @if($attendance->photo_path)
                            <div class="mt-4">
                                <div class="text-sm text-gray-500 text-center mb-2">Foto Bukti Presensi:</div>
                                <img src="{{ Storage::url($attendance->photo_path) }}" alt="Foto Presensi" class="w-full h-48 object-cover rounded-xl shadow-sm border border-gray-200">
                            </div>
                        @endif
                    @else
                        <!-- Form Selection -->
                        <div class="flex gap-2 mb-6 bg-gray-200/50 p-1 rounded-xl">
                            <button type="button" id="tabHadir" onclick="switchTab('hadir')" class="flex-1 py-2 text-sm font-semibold rounded-lg bg-white text-gray-800 shadow-sm transition-all">Hadir</button>
                            <button type="button" id="tabIzin" onclick="switchTab('izin')" class="flex-1 py-2 text-sm font-semibold rounded-lg text-gray-500 hover:text-gray-700 transition-all">Izin / Sakit</button>
                        </div>

                        <!-- Form Check-In -->
                        <form action="{{ route('intern.checkin') }}" method="POST" id="checkInForm" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="lat" id="lat">
                            <input type="hidden" name="lng" id="lng">
                            
                            <div class="mb-4 text-sm text-gray-500 text-center">
                                Pastikan Anda berada di dalam area kantor (kotak hijau) sebelum Check-In.
                            </div>

                            <!-- Peta Leaflet akan muncul di sini -->
                            <div id="checkInMap" class="w-full h-64 rounded-xl border-2 border-gray-200 mb-4 z-0 relative">
                                <div class="absolute inset-0 flex items-center justify-center bg-gray-100/80 z-10" id="mapLoader">
                                    <svg class="animate-spin h-8 w-8 text-orange-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Ambil Foto / Selfie Bukti Hadir (Wajib)</label>
                                <input type="file" name="photo" id="photo" accept="image/*" capture="user" required class="w-full border border-gray-200 rounded-xl shadow-sm bg-white focus:ring-orange-500 focus:border-orange-500 p-2 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-600 hover:file:bg-orange-100 transition-colors">
                            </div>

                            <button type="button" onclick="getLocationAndSubmit()" id="checkInBtn" class="w-full bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-medium py-4 px-4 rounded-xl transition-all duration-300 flex items-center justify-center gap-2 shadow-lg shadow-orange-500/30 transform hover:-translate-y-0.5">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.242-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                Ambil Lokasi & Check-In
                            </button>
                        </form>

                        <!-- Form Izin -->
                        <form action="{{ route('intern.leave') }}" method="POST" id="leaveForm" enctype="multipart/form-data" class="hidden">
                            @csrf
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Tipe</label>
                                    <select name="leave_type" required class="w-full border border-gray-200 rounded-xl shadow-sm p-3 bg-white focus:ring-orange-500 focus:border-orange-500 transition-colors">
                                        <option value="">Pilih Izin/Sakit...</option>
                                        <option value="sakit">Sakit (Butuh Surat Dokter)</option>
                                        <option value="izin">Izin Kategori Lain</option>
                                    </select>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Keterangan / Alasan</label>
                                    <textarea name="leave_reason" rows="3" required placeholder="Jelaskan alasan izin secara detail..." class="w-full border border-gray-200 rounded-xl shadow-sm focus:bg-white focus:border-orange-500 focus:ring-orange-500 p-3 bg-gray-50 transition-colors"></textarea>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Surat Bukti (Wajib)</label>
                                    <input type="file" name="leave_proof" accept="image/*,.pdf" required class="w-full border border-gray-200 rounded-xl shadow-sm bg-white focus:ring-orange-500 focus:border-orange-500 p-2 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-gray-50 file:text-gray-600 hover:file:bg-gray-100 transition-colors">
                                </div>

                                <button type="submit" class="w-full mt-4 bg-gray-900 hover:bg-black text-white font-medium py-3 px-4 rounded-xl transition-all duration-300 flex items-center justify-center gap-2 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                                    Kirim Pengajuan
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Check-Out & Logbook Section -->
            <div class="space-y-6">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-8 h-8 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center font-bold">2</div>
                    <h3 class="text-lg font-semibold text-gray-800">Check-Out & Logbook</h3>
                </div>

                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100 flex flex-col justify-center">
                    @if($attendance && $attendance->check_out_time)
                        <div class="text-center py-4 mb-4">
                            <div class="w-16 h-16 bg-green-100 text-green-500 rounded-full flex items-center justify-center mx-auto mb-3">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <p class="text-gray-800 font-medium">Berhasil Check-Out pada</p>
                            <p class="text-2xl font-bold text-gray-900 mt-1">{{ \Carbon\Carbon::parse($attendance->check_out_time)->format('H:i') }}</p>
                        </div>
                        
                        <div class="text-sm text-gray-500 text-center mb-2">Riwayat Lokasi Presensi Pulang:</div>
                        <!-- Peta Leaflet akan muncul di sini -->
                        <div id="checkOutMap" class="w-full h-64 rounded-xl border-2 border-gray-200 z-0 relative" data-lat="{{ $attendance->check_out_lat }}" data-lng="{{ $attendance->check_out_lng }}">
                            <div class="absolute inset-0 flex items-center justify-center bg-gray-100/80 z-10" id="mapLoaderOut">
                                <svg class="animate-spin h-8 w-8 text-orange-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            </div>
                        </div>
                        @if($attendance->check_out_photo_path)
                            <div class="mt-4">
                                <div class="text-sm text-gray-500 text-center mb-2">Foto Bukti Pulang:</div>
                                <img src="{{ Storage::url($attendance->check_out_photo_path) }}" alt="Foto Presensi Pulang" class="w-full h-48 object-cover rounded-xl shadow-sm border border-gray-200">
                            </div>
                        @endif
                    @else
                        <form action="{{ route('intern.checkout') }}" method="POST" id="checkOutForm" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="lat_out" id="lat_out">
                            <input type="hidden" name="lng_out" id="lng_out">
                            
                            <div class="mb-4 text-sm text-gray-500 text-center">
                                Pastikan Anda berada di dalam area kantor (kotak hijau) sebelum Check-Out.
                            </div>

                            <!-- Peta Leaflet akan muncul di sini -->
                            <div id="checkOutMap" class="w-full h-64 rounded-xl border-2 border-gray-200 mb-4 z-0 relative">
                                <div class="absolute inset-0 flex items-center justify-center bg-gray-100/80 z-10" id="mapLoaderOut">
                                    <svg class="animate-spin h-8 w-8 text-orange-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Kategori Tugas</label>
                                    <select name="category" required class="w-full border border-gray-200 rounded-xl shadow-sm focus:bg-white focus:border-orange-500 focus:ring-orange-500 p-3 bg-gray-50 transition-colors">
                                        <option value="">Pilih Kategori...</option>
                                        <option value="Development">Development (Frontend/Backend)</option>
                                        <option value="Design">UI/UX Design</option>
                                        <option value="Research">Research & Analysis</option>
                                        <option value="Testing">QA & Testing</option>
                                        <option value="Other">Lainnya</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                                        Deskripsi Pekerjaan 
                                        <span id="charCount" class="text-xs text-gray-400 font-normal float-right mt-1">0 / 50 min</span>
                                    </label>
                                    <textarea name="description" id="logbookDesc" rows="3" required placeholder="Ceritakan apa saja yang kamu kerjakan hari ini..." class="w-full border border-gray-200 rounded-xl shadow-sm focus:bg-white focus:border-orange-500 focus:ring-orange-500 p-3 bg-gray-50 transition-colors"></textarea>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Link Hasil Kerja (Opsional)</label>
                                    <input type="text" name="link" placeholder="Tuliskan link atau keterangan lainnya..." class="w-full border border-gray-200 rounded-xl shadow-sm focus:bg-white focus:border-orange-500 focus:ring-orange-500 p-3 bg-gray-50 transition-colors">
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Foto Kegiatan / Logbook (Opsional, Maks. 2)</label>
                                    <input type="file" name="photos[]" multiple accept="image/*" class="w-full border border-gray-200 rounded-xl shadow-sm bg-white focus:ring-orange-500 focus:border-orange-500 p-2 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-gray-50 file:text-gray-600 hover:file:bg-gray-100 transition-colors">
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Selfie Bukti Pulang (Wajib)</label>
                                    <input type="file" name="photo_out" accept="image/*" capture="user" required class="w-full border border-gray-200 rounded-xl shadow-sm bg-white focus:ring-orange-500 focus:border-orange-500 p-2 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-600 hover:file:bg-orange-100 transition-colors">
                                </div>

                                <button type="button" onclick="getLocationAndCheckOut()" id="checkOutBtn" disabled class="w-full mt-4 bg-gray-300 text-gray-500 font-medium py-3 px-4 rounded-xl transition-all duration-300 flex items-center justify-center gap-2 cursor-not-allowed">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                    Submit Logbook & Check-Out
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endif

@endsection

@push('scripts')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    // Map Initialization if element exists
    let checkInMap = null;
    let userMarker = null;

    if (document.getElementById('checkInMap')) {
        const officeLat = {{ $officeLat }};
        const officeLng = {{ $officeLng }};
        const polygonData = {!! $officePolygon !!};

        checkInMap = L.map('checkInMap').setView([officeLat, officeLng], 17);
        
        // Use Satellite layer by default for better visibility
        L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            maxZoom: 20,
            maxNativeZoom: 18,
            attribution: 'Tiles &copy; Esri'
        }).addTo(checkInMap);

        // Draw Polygon
        if (polygonData && polygonData.length > 0) {
            L.polygon(polygonData, {
                color: '#22c55e',
                fillColor: '#22c55e',
                fillOpacity: 0.3,
                weight: 3
            }).addTo(checkInMap);
        }

        document.getElementById('mapLoader').classList.add('hidden');

        // Check if already checked in
        const mapEl = document.getElementById('checkInMap');
        const checkedLat = mapEl.getAttribute('data-lat');
        const checkedLng = mapEl.getAttribute('data-lng');

        if (checkedLat && checkedLng) {
            // Sudah check-in, tampilkan marker di lokasi check-in
            userMarker = L.marker([checkedLat, checkedLng]).addTo(checkInMap)
                .bindPopup('Lokasi Presensi Anda').openPopup();
            checkInMap.setView([checkedLat, checkedLng], 18);
        } else {
            // Belum check-in, tonton lokasi real-time
            if (navigator.geolocation) {
                navigator.geolocation.watchPosition(
                    function(position) {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;
                        
                        if (userMarker) {
                            userMarker.setLatLng([lat, lng]);
                        } else {
                            userMarker = L.marker([lat, lng]).addTo(checkInMap)
                                .bindPopup('Posisi Anda').openPopup();
                            checkInMap.setView([lat, lng], 18);
                        }
                        
                        const latInput = document.getElementById('lat');
                        const lngInput = document.getElementById('lng');
                        if (latInput) latInput.value = lat;
                        if (lngInput) lngInput.value = lng;
                    },
                    function(error) {
                        console.log('Error getting location:', error);
                    },
                    { enableHighAccuracy: true, maximumAge: 10000, timeout: 5000 }
                );
            }
        }
    }

    let checkOutMap = null;
    let userOutMarker = null;

    if (document.getElementById('checkOutMap')) {
        const officeLat = {{ $officeLat }};
        const officeLng = {{ $officeLng }};
        const polygonData = {!! $officePolygon !!};

        checkOutMap = L.map('checkOutMap').setView([officeLat, officeLng], 17);
        
        L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            maxZoom: 20,
            maxNativeZoom: 18,
            attribution: 'Tiles &copy; Esri'
        }).addTo(checkOutMap);

        if (polygonData && polygonData.length > 0) {
            L.polygon(polygonData, {
                color: '#22c55e',
                fillColor: '#22c55e',
                fillOpacity: 0.3,
                weight: 3
            }).addTo(checkOutMap);
        }

        document.getElementById('mapLoaderOut').classList.add('hidden');

        const mapOutEl = document.getElementById('checkOutMap');
        const checkedOutLat = mapOutEl.getAttribute('data-lat');
        const checkedOutLng = mapOutEl.getAttribute('data-lng');

        if (checkedOutLat && checkedOutLng) {
            userOutMarker = L.marker([checkedOutLat, checkedOutLng]).addTo(checkOutMap)
                .bindPopup('Lokasi Presensi Pulang').openPopup();
            checkOutMap.setView([checkedOutLat, checkedOutLng], 18);
        } else {
            if (navigator.geolocation) {
                navigator.geolocation.watchPosition(
                    function(position) {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;
                        
                        if (userOutMarker) {
                            userOutMarker.setLatLng([lat, lng]);
                        } else {
                            userOutMarker = L.marker([lat, lng]).addTo(checkOutMap)
                                .bindPopup('Posisi Anda Saat Ini').openPopup();
                            checkOutMap.setView([lat, lng], 18);
                        }
                        
                        const latOutInput = document.getElementById('lat_out');
                        const lngOutInput = document.getElementById('lng_out');
                        if (latOutInput) latOutInput.value = lat;
                        if (lngOutInput) lngOutInput.value = lng;
                    },
                    function(error) {
                        console.log('Error getting location for checkout:', error);
                    },
                    { enableHighAccuracy: true, maximumAge: 10000, timeout: 5000 }
                );
            }
        }
    }
    // Geolocation Script for Submit Button
    function getLocationAndSubmit() {
        const form = document.getElementById('checkInForm');
        
        // Trigger HTML5 Validation (khususnya untuk input type="file" required)
        if (!form.reportValidity()) {
            return; // Berhenti jika ada field yang invalid (misal: foto belum diisi)
        }

        const btn = document.getElementById('checkInBtn');
        const originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = `<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Memverifikasi & Check-In...`;

        const latInput = document.getElementById('lat').value;
        const lngInput = document.getElementById('lng').value;

        if (latInput && lngInput) {
            // Jika sudah dapat lokasi dari watcher, langsung submit
            form.submit();
        } else if (navigator.geolocation) {
            // Fallback jika watcher belum dapat lokasi
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    document.getElementById('lat').value = position.coords.latitude;
                    document.getElementById('lng').value = position.coords.longitude;
                    form.submit();
                },
                function(error) {
                    alert("Gagal mendapatkan lokasi. Pastikan izin lokasi aktif.");
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                }
            );
        } else {
            alert("Geolocation tidak didukung oleh browser ini.");
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
    }

    function getLocationAndCheckOut() {
        const form = document.getElementById('checkOutForm');
        
        if (!form.reportValidity()) {
            return;
        }

        const btn = document.getElementById('checkOutBtn');
        const originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = `<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Memverifikasi & Check-Out...`;

        const latOutInput = document.getElementById('lat_out').value;
        const lngOutInput = document.getElementById('lng_out').value;

        if (latOutInput && lngOutInput) {
            form.submit();
        } else if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    document.getElementById('lat_out').value = position.coords.latitude;
                    document.getElementById('lng_out').value = position.coords.longitude;
                    form.submit();
                },
                function(error) {
                    alert("Gagal mendapatkan lokasi. Pastikan izin lokasi aktif untuk melakukan Check-Out.");
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                },
                { enableHighAccuracy: true, maximumAge: 10000, timeout: 5000 }
            );
        } else {
            alert("Geolocation tidak didukung oleh browser ini.");
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
    }

    // Logbook Validation Script
    const descInput = document.getElementById('logbookDesc');
    const checkOutBtn = document.getElementById('checkOutBtn');
    const charCount = document.getElementById('charCount');

    if (descInput) {
        descInput.addEventListener('input', function() {
            const len = this.value.length;
            charCount.textContent = `${len} / 50 min`;
            
            if (len >= 50) {
                charCount.classList.remove('text-gray-400', 'text-red-500');
                charCount.classList.add('text-green-500');
                checkOutBtn.disabled = false;
                checkOutBtn.className = "w-full mt-4 bg-gray-900 hover:bg-black text-white font-medium py-3 px-4 rounded-xl transition-all duration-300 flex items-center justify-center gap-2 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5";
            } else {
                charCount.classList.remove('text-gray-400', 'text-green-500');
                charCount.classList.add('text-red-500');
                checkOutBtn.disabled = true;
                checkOutBtn.className = "w-full mt-4 bg-gray-300 text-gray-500 font-medium py-3 px-4 rounded-xl transition-all duration-300 flex items-center justify-center gap-2 cursor-not-allowed";
            }
        });
    }

    // Form Draft / Autosave Logic
    const categorySelect = document.querySelector('select[name="category"]');
    const linkInput = document.querySelector('input[name="link"]');
    const form = document.querySelector('form[enctype="multipart/form-data"]');
    const fileInput = document.getElementById('file-upload');

    // Setup IndexedDB for File Storage
    let db;
    const request = indexedDB.open('ViaSpaceDraftDB', 1);
    
    request.onupgradeneeded = function(event) {
        db = event.target.result;
        if (!db.objectStoreNames.contains('drafts')) {
            db.createObjectStore('drafts');
        }
    };
    
    request.onsuccess = function(event) {
        db = event.target.result;
        loadDraft();
    };

    function saveDraft() {
        if (categorySelect) localStorage.setItem('draft_category', categorySelect.value);
        if (descInput) localStorage.setItem('draft_description', descInput.value);
        if (linkInput) localStorage.setItem('draft_link', linkInput.value);
    }

    function savePhotosDraft() {
        if (!db || !fileInput) return;
        const transaction = db.transaction(['drafts'], 'readwrite');
        const store = transaction.objectStore('drafts');
        if (fileInput.files.length > 0) {
            // Convert FileList to Array to store in IndexedDB
            const filesArray = Array.from(fileInput.files);
            store.put(filesArray, 'logbook_photos');
        } else {
            store.delete('logbook_photos');
        }
    }

    function loadDraft() {
        if (categorySelect && localStorage.getItem('draft_category')) {
            categorySelect.value = localStorage.getItem('draft_category');
            if(categorySelect.tomselect) categorySelect.tomselect.sync();
        }
        if (linkInput && localStorage.getItem('draft_link')) {
            linkInput.value = localStorage.getItem('draft_link');
        }
        if (descInput && localStorage.getItem('draft_description')) {
            descInput.value = localStorage.getItem('draft_description');
            // Dispatch event after everything is loaded so saveDraft doesn't wipe out other fields
            descInput.dispatchEvent(new Event('input'));
        }
        
        // Load Photos from IndexedDB
        if (db && fileInput) {
            const transaction = db.transaction(['drafts'], 'readonly');
            const store = transaction.objectStore('drafts');
            const req = store.get('logbook_photos');
            req.onsuccess = function(e) {
                const filesArray = e.target.result;
                if (filesArray && filesArray.length > 0) {
                    try {
                        const dataTransfer = new DataTransfer();
                        filesArray.forEach(file => dataTransfer.items.add(file));
                        fileInput.files = dataTransfer.files;
                        updateFileNames(fileInput); // Trigger UI update
                    } catch (error) {
                        console.error('DataTransfer API not supported or error loading files:', error);
                    }
                }
            };
        }
    }

    // Bind save events
    if (categorySelect) categorySelect.addEventListener('change', saveDraft);
    if (descInput) descInput.addEventListener('input', saveDraft);
    if (linkInput) linkInput.addEventListener('input', saveDraft);
    if (fileInput) fileInput.addEventListener('change', savePhotosDraft);

    // Clear draft on submit
    if (form) {
        form.addEventListener('submit', function() {
            localStorage.removeItem('draft_category');
            localStorage.removeItem('draft_description');
            localStorage.removeItem('draft_link');
            if (db) {
                const transaction = db.transaction(['drafts'], 'readwrite');
                transaction.objectStore('drafts').delete('logbook_photos');
            }
        });
    }

    function updateFileNames(input) {
        const display = document.getElementById('file-names-display');
        const uploadText = document.getElementById('file-upload-text');
        display.innerHTML = '';
        
        if (input.files && input.files.length > 0) {
            // Cek jumlah file
            if (input.files.length > 2) {
                alert('Maksimal hanya 2 foto yang diperbolehkan!');
                input.value = ''; // Reset input
                display.classList.add('hidden');
                uploadText.textContent = 'Pilih Foto (Maks. 2)';
                return;
            }

            uploadText.textContent = input.files.length + ' Foto Terpilih';
            display.classList.remove('hidden');
            
            Array.from(input.files).forEach((file, index) => {
                const fileSize = (file.size / 1024 / 1024).toFixed(2); // in MB
                const fileHtml = `
                    <div class="flex items-center justify-between p-3 bg-white border border-gray-200 rounded-xl shadow-sm">
                        <div class="flex items-center gap-3 overflow-hidden">
                            <div class="w-10 h-10 rounded-lg bg-orange-50 text-orange-500 flex flex-shrink-0 items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <div class="truncate">
                                <p class="text-sm font-medium text-gray-800 truncate">${file.name}</p>
                                <p class="text-xs text-gray-500">${fileSize} MB</p>
                            </div>
                        </div>
                    </div>
                `;
                display.insertAdjacentHTML('beforeend', fileHtml);
            });
        } else {
            display.classList.add('hidden');
            uploadText.textContent = 'Pilih Foto (Maks. 2)';
        }
    }
    // Client-side Image Compression
    async function compressImage(file, maxSizeMB = 1) {
        return new Promise((resolve) => {
            const reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onload = function(event) {
                const img = new Image();
                img.src = event.target.result;
                img.onload = function() {
                    const canvas = document.createElement('canvas');
                    let width = img.width;
                    let height = img.height;
                    const max_size = 1280;

                    if (width > height) {
                        if (width > max_size) {
                            height *= max_size / width;
                            width = max_size;
                        }
                    } else {
                        if (height > max_size) {
                            width *= max_size / height;
                            height = max_size;
                        }
                    }

                    canvas.width = width;
                    canvas.height = height;
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(img, 0, 0, width, height);

                    canvas.toBlob((blob) => {
                        const compressedFile = new File([blob], file.name, {
                            type: 'image/jpeg',
                            lastModified: Date.now()
                        });
                        resolve(compressedFile);
                    }, 'image/jpeg', 0.7);
                }
            };
        });
    }

    // Attach compression to file inputs
    const photoInputs = document.querySelectorAll('input[type="file"][accept="image/*"]');
    photoInputs.forEach(input => {
        input.addEventListener('change', async function(e) {
            if (!this.files || this.files.length === 0) return;
            
            const dataTransfer = new DataTransfer();
            let hasLargeFile = false;
            
            for (let i = 0; i < this.files.length; i++) {
                const file = this.files[i];
                if (file.size > 1024 * 1024) { // Compress if larger than 1MB
                    hasLargeFile = true;
                    // Tampilkan loading state jika ada elemen yang sesuai (opsional)
                    const compressedFile = await compressImage(file);
                    dataTransfer.items.add(compressedFile);
                } else {
                    dataTransfer.items.add(file);
                }
            }
            
            if (hasLargeFile) {
                this.files = dataTransfer.files;
                if (typeof updateFileNames === 'function' && this.id === 'file-upload') {
                    updateFileNames(this);
                }
            }
        });
    });

    // Tab Switcher for Hadir / Izin
    function switchTab(tab) {
        const tabHadir = document.getElementById('tabHadir');
        const tabIzin = document.getElementById('tabIzin');
        const checkInForm = document.getElementById('checkInForm');
        const leaveForm = document.getElementById('leaveForm');

        if (tab === 'hadir') {
            tabHadir.className = "flex-1 py-2 text-sm font-semibold rounded-lg bg-white text-gray-800 shadow-sm transition-all";
            tabIzin.className = "flex-1 py-2 text-sm font-semibold rounded-lg text-gray-500 hover:text-gray-700 transition-all";
            checkInForm.classList.remove('hidden');
            leaveForm.classList.add('hidden');
        } else {
            tabIzin.className = "flex-1 py-2 text-sm font-semibold rounded-lg bg-white text-gray-800 shadow-sm transition-all";
            tabHadir.className = "flex-1 py-2 text-sm font-semibold rounded-lg text-gray-500 hover:text-gray-700 transition-all";
            leaveForm.classList.remove('hidden');
            checkInForm.classList.add('hidden');
        }
    }
</script>
@endpush
