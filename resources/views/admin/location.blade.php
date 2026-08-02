@extends('layouts.admin')
@section('title', 'Pengaturan Titik Geolokasi')

@section('content')
<div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Geolokasi Kantor (Polygon)</h2>
        <p class="text-gray-500 text-sm">Tentukan pusat lokasi dan gambar area presensi secara bebas (bentuk kotak/bebas).</p>
    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
    <!-- Map Section -->
    <div class="xl:col-span-2 bg-white rounded-3xl shadow-sm border border-gray-100 p-2 overflow-hidden flex flex-col h-[500px]">
        <div id="map" class="w-full h-full rounded-2xl z-0"></div>
    </div>

    <!-- Form Section -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 flex flex-col justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-800 mb-6 flex items-center gap-2">
                <div class="w-10 h-10 rounded-xl bg-orange-50 text-orange-500 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.242-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                </div>
                Koordinat & Area Presensi
            </h3>
            
            <form action="{{ route('admin.location.update') }}" method="POST" id="locationForm">
                @csrf
                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Titik Pusat (Latitude)</label>
                        <input type="text" name="office_lat" id="lat" value="{{ $lat }}" required readonly class="w-full border-gray-200 rounded-xl shadow-sm p-3 bg-gray-100 font-mono text-sm text-gray-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Titik Pusat (Longitude)</label>
                        <input type="text" name="office_lng" id="lng" value="{{ $lng }}" required readonly class="w-full border-gray-200 rounded-xl shadow-sm p-3 bg-gray-100 font-mono text-sm text-gray-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Data Area Polygon (JSON)</label>
                        <textarea name="office_polygon" id="polygon_data" rows="3" required readonly class="w-full border-gray-200 rounded-xl shadow-sm p-3 bg-gray-100 font-mono text-[10px] text-gray-500">{{ $polygon }}</textarea>
                    </div>
                </div>
            </form>
        </div>

        <div class="mt-8 space-y-3">
            <button type="button" onclick="document.getElementById('locationForm').submit()" class="w-full bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-bold py-3.5 px-4 rounded-xl transition duration-200 shadow-lg shadow-orange-500/30 transform hover:-translate-y-0.5">
                Simpan Konfigurasi Peta
            </button>
        </div>
    </div>
</div>

<!-- Informasi / Panduan -->
<div class="mt-6 bg-blue-50/50 rounded-3xl border border-blue-100 p-6 flex items-start gap-4">
    <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-500 flex items-center justify-center flex-shrink-0">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
    </div>
    <div>
        <h3 class="text-sm font-bold text-blue-900 mb-1">Panduan Menggambar Area (Polygon)</h3>
        <p class="text-sm text-blue-800 leading-relaxed">Gunakan tombol <strong>ikon segi-lima</strong> di sebelah kiri peta untuk mulai menggambar area batas kantor Anda. Klik di setiap sudut area, lalu klik titik awal kembali untuk menutup bidang. Anda bisa mengedit bentuk yang sudah ada dengan menekan <strong>ikon pensil</strong>. Area oranye adalah area di mana absensi siswa dianggap valid.</p>
    </div>
</div>

@push('scripts')
<!-- Leaflet CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<!-- Leaflet Draw CSS & JS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Initial Center Values
        let latInput = document.getElementById('lat');
        let lngInput = document.getElementById('lng');
        let polygonInput = document.getElementById('polygon_data');
        
        let currentLat = parseFloat(latInput.value) || -3.277524;
        let currentLng = parseFloat(lngInput.value) || 114.600035;

        // Parse saved polygon data
        let savedPolygon = [];
        try {
            let parsed = JSON.parse(polygonInput.value);
            if (Array.isArray(parsed) && parsed.length > 0) {
                savedPolygon = parsed;
            }
        } catch (e) {
            console.error('Invalid polygon data');
        }

        // Initialize Map
        let map = L.map('map').setView([currentLat, currentLng], 18);

        // Map Tiles (OpenStreetMap & Satellite)
        let osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 22,
            maxNativeZoom: 19,
            attribution: '© OpenStreetMap'
        });
        
        let satelliteLayer = L.tileLayer('http://mt0.google.com/vt/lyrs=y&hl=en&x={x}&y={y}&z={z}', {
            maxZoom: 22,
            maxNativeZoom: 19,
            attribution: '© Google Maps'
        });

        // Set default layer
        osmLayer.addTo(map);

        // Add Layer Control
        L.control.layers({
            "Peta Jalan (OSM)": osmLayer,
            "Satelit (Esri)": satelliteLayer
        }).addTo(map);

        // FeatureGroup is to store editable layers
        let drawnItems = new L.FeatureGroup();
        map.addLayer(drawnItems);

        // Draw saved polygon if exists
        if (savedPolygon.length > 0) {
            let polygon = L.polygon(savedPolygon, {
                color: '#f97316',
                fillColor: '#fdba74',
                fillOpacity: 0.3,
                weight: 2
            });
            drawnItems.addLayer(polygon);
            map.fitBounds(polygon.getBounds());
        }

        // Setup Leaflet Draw Control
        let drawControl = new L.Control.Draw({
            draw: {
                polyline: false,
                circle: false,
                circlemarker: false,
                marker: false,
                rectangle: true, // Allow rectangle
                polygon: { // Allow polygon
                    allowIntersection: false,
                    showArea: true,
                    shapeOptions: {
                        color: '#f97316',
                        fillColor: '#fdba74',
                        fillOpacity: 0.3,
                        weight: 2
                    }
                }
            },
            edit: {
                featureGroup: drawnItems,
                remove: true
            }
        });
        map.addControl(drawControl);

        // Event for when a new polygon/rectangle is drawn
        map.on(L.Draw.Event.CREATED, function (e) {
            let type = e.layerType,
                layer = e.layer;

            // Clear previous drawings (only allow 1 geofence)
            drawnItems.clearLayers();
            
            // Set style
            layer.setStyle({
                color: '#f97316',
                fillColor: '#fdba74',
                fillOpacity: 0.3,
                weight: 2
            });

            drawnItems.addLayer(layer);
            updatePolygonData();
        });

        // Event for when polygons are edited
        map.on(L.Draw.Event.EDITED, function (e) {
            updatePolygonData();
        });

        // Event for when polygons are deleted
        map.on(L.Draw.Event.DELETED, function (e) {
            polygonInput.value = '[]';
        });

        // Function to extract coordinates and update input
        function updatePolygonData() {
            let data = [];
            let center = null;
            
            drawnItems.eachLayer(function (layer) {
                if (layer instanceof L.Polygon) {
                    let latlngs = layer.getLatLngs()[0]; // get outer ring
                    
                    latlngs.forEach(function(latlng) {
                        data.push([latlng.lat, latlng.lng]);
                    });
                    
                    center = layer.getBounds().getCenter();
                }
            });

            if (data.length > 0) {
                polygonInput.value = JSON.stringify(data);
                if (center) {
                    latInput.value = center.lat.toFixed(6);
                    lngInput.value = center.lng.toFixed(6);
                }
            }
        }
    });
</script>
@endpush
@endsection
