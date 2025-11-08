{{-- resources/views/student/browse-problems/components/map-view.blade.php --}}
{{-- component untuk menampilkan map view dengan leaflet --}}

<div x-data="mapView()" class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    {{-- map container --}}
    <div id="problems-map" class="w-full h-[600px]"></div>
    
    {{-- map controls overlay --}}
    <div class="absolute top-4 left-4 z-[1000] space-y-2">
        {{-- zoom controls sudah ada di leaflet --}}
        
        {{-- filter toggle --}}
        <button @click="showFilters = !showFilters"
                class="bg-white px-4 py-2 rounded-lg shadow-lg border border-gray-200 hover:bg-gray-50 transition-colors flex items-center space-x-2">
            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
            </svg>
            <span class="text-sm font-medium text-gray-700">Filter</span>
        </button>
        
        {{-- filter panel --}}
        <div x-show="showFilters" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="bg-white rounded-lg shadow-lg border border-gray-200 p-4 w-64"
             style="display: none;">
            <h3 class="font-semibold text-gray-900 mb-3">Filter Peta</h3>
            
            <div class="space-y-3">
                {{-- difficulty filter --}}
                <div>
                    <label class="text-xs font-medium text-gray-700 mb-1 block">Tingkat Kesulitan</label>
                    <select @change="filterMarkers()" x-model="filters.difficulty" class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Semua</option>
                        <option value="beginner">Beginner</option>
                        <option value="intermediate">Intermediate</option>
                        <option value="advanced">Advanced</option>
                    </select>
                </div>
                
                {{-- urgent filter --}}
                <div>
                    <label class="flex items-center">
                        <input type="checkbox" @change="filterMarkers()" x-model="filters.urgentOnly" class="rounded text-blue-600 focus:ring-2 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">Hanya Mendesak</span>
                    </label>
                </div>
                
                {{-- featured filter --}}
                <div>
                    <label class="flex items-center">
                        <input type="checkbox" @change="filterMarkers()" x-model="filters.featuredOnly" class="rounded text-blue-600 focus:ring-2 focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-700">Hanya Unggulan</span>
                    </label>
                </div>
                
                {{-- reset button --}}
                <button @click="resetMapFilters()" class="w-full px-3 py-2 bg-gray-100 text-gray-700 text-sm rounded-lg hover:bg-gray-200 transition-colors">
                    Reset Filter
                </button>
            </div>
        </div>
    </div>
    
    {{-- legend --}}
    <div class="absolute bottom-4 right-4 z-[1000] bg-white rounded-lg shadow-lg border border-gray-200 p-3">
        <h4 class="text-xs font-semibold text-gray-900 mb-2">Legenda</h4>
        <div class="space-y-1">
            <div class="flex items-center space-x-2">
                <div class="w-3 h-3 rounded-full bg-blue-500"></div>
                <span class="text-xs text-gray-600">Normal</span>
            </div>
            <div class="flex items-center space-x-2">
                <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                <span class="text-xs text-gray-600">Unggulan</span>
            </div>
            <div class="flex items-center space-x-2">
                <div class="w-3 h-3 rounded-full bg-red-500"></div>
                <span class="text-xs text-gray-600">Mendesak</span>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css" />

<script>
function mapView() {
    return {
        map: null,
        markers: [],
        markerCluster: null,
        showFilters: false,
        filters: {
            difficulty: '',
            urgentOnly: false,
            featuredOnly: false
        },
        problems: @json($problems),
        
        init() {
            this.initializeMap();
            this.loadMarkers();
            
            // listen untuk map view activated event
            window.addEventListener('mapViewActivated', () => {
                if (this.map) {
                    setTimeout(() => {
                        this.map.invalidateSize();
                    }, 100);
                }
            });
        },
        
        initializeMap() {
            // inisialisasi map
            this.map = L.map('problems-map', {
                center: [-2.5, 118], // koordinat tengah Indonesia
                zoom: 5,
                zoomControl: true,
                scrollWheelZoom: true
            });
            
            // tambahkan tile layer
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors',
                maxZoom: 19
            }).addTo(this.map);
            
            // inisialisasi marker cluster
            this.markerCluster = L.markerClusterGroup({
                maxClusterRadius: 50,
                spiderfyOnMaxZoom: true,
                showCoverageOnHover: false,
                zoomToBoundsOnClick: true
            });
            
            this.map.addLayer(this.markerCluster);
        },
        
        loadMarkers() {
            // bersihkan markers existing
            this.markerCluster.clearLayers();
            this.markers = [];
            
            // tambahkan marker untuk setiap problem
            this.problems.forEach(problem => {
                const marker = this.createMarker(problem);
                if (marker) {
                    this.markers.push({ marker, problem });
                    this.markerCluster.addLayer(marker);
                }
            });
            
            // fit bounds jika ada markers
            if (this.markers.length > 0) {
                const group = new L.featureGroup(this.markers.map(m => m.marker));
                this.map.fitBounds(group.getBounds().pad(0.1));
            }
        },
        
        createMarker(problem) {
            // TODO: gunakan koordinat real dari database
            // sementara gunakan koordinat random di Indonesia
            const lat = problem.latitude || this.getRandomLatIndonesia();
            const lng = problem.longitude || this.getRandomLngIndonesia();
            
            // tentukan warna marker berdasarkan status
            const markerColor = this.getMarkerColor(problem);
            
            // buat custom icon
            const icon = L.divIcon({
                className: 'custom-marker',
                html: `
                    <div class="relative">
                        <div class="w-8 h-8 rounded-full ${markerColor} border-2 border-white shadow-lg flex items-center justify-center transform hover:scale-110 transition-transform cursor-pointer">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        ${problem.is_urgent ? '<div class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full border border-white animate-pulse"></div>' : ''}
                    </div>
                `,
                iconSize: [32, 32],
                iconAnchor: [16, 32],
                popupAnchor: [0, -32]
            });
            
            const marker = L.marker([lat, lng], { icon });
            
            // tambahkan popup
            const popupContent = this.createPopupContent(problem);
            marker.bindPopup(popupContent, {
                maxWidth: 300,
                className: 'custom-popup'
            });
            
            // event handlers
            marker.on('click', () => {
                this.onMarkerClick(problem);
            });
            
            return marker;
        },
        
        createPopupContent(problem) {
            const daysLeft = this.calculateDaysLeft(problem.application_deadline);
            
            return `
                <div class="p-2">
                    <h3 class="font-bold text-gray-900 mb-2 text-sm">${problem.title}</h3>
                    <div class="space-y-1 text-xs text-gray-600 mb-3">
                        <div class="flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            ${problem.institution.name}
                        </div>
                        <div class="flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            </svg>
                            ${problem.regency.name}, ${problem.province.name}
                        </div>
                        <div class="flex items-center ${daysLeft <= 7 ? 'text-red-600 font-semibold' : ''}">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            ${daysLeft} hari lagi
                        </div>
                    </div>
                    <a href="/student/problems/${problem.id}" 
                       class="block w-full px-3 py-2 bg-blue-600 text-white text-xs font-semibold text-center rounded-lg hover:bg-blue-700 transition-colors">
                        Lihat Detail
                    </a>
                </div>
            `;
        },
        
        getMarkerColor(problem) {
            if (problem.is_urgent) return 'bg-red-500';
            if (problem.is_featured) return 'bg-yellow-500';
            return 'bg-blue-500';
        },
        
        onMarkerClick(problem) {
            // optional: tambahkan analytics atau tracking
            console.log('Marker clicked:', problem.title);
        },
        
        filterMarkers() {
            this.markerCluster.clearLayers();
            
            const filteredMarkers = this.markers.filter(({ problem }) => {
                // filter by difficulty
                if (this.filters.difficulty && problem.difficulty_level !== this.filters.difficulty) {
                    return false;
                }
                
                // filter urgent only
                if (this.filters.urgentOnly && !problem.is_urgent) {
                    return false;
                }
                
                // filter featured only
                if (this.filters.featuredOnly && !problem.is_featured) {
                    return false;
                }
                
                return true;
            });
            
            filteredMarkers.forEach(({ marker }) => {
                this.markerCluster.addLayer(marker);
            });
            
            // fit bounds ke filtered markers
            if (filteredMarkers.length > 0) {
                const group = new L.featureGroup(filteredMarkers.map(m => m.marker));
                this.map.fitBounds(group.getBounds().pad(0.1));
            }
        },
        
        resetMapFilters() {
            this.filters = {
                difficulty: '',
                urgentOnly: false,
                featuredOnly: false
            };
            this.filterMarkers();
        },
        
        calculateDaysLeft(deadline) {
            const now = new Date();
            const deadlineDate = new Date(deadline);
            const diffTime = deadlineDate - now;
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            return Math.max(0, diffDays);
        },
        
        // helper untuk generate koordinat random di Indonesia
        // TODO: ganti dengan koordinat real dari database
        getRandomLatIndonesia() {
            // Indonesia latitude range: -11 to 6
            return -11 + Math.random() * 17;
        },
        
        getRandomLngIndonesia() {
            // Indonesia longitude range: 95 to 141
            return 95 + Math.random() * 46;
        }
    };
}
</script>

<style>
/* custom marker styles */
.custom-marker {
    background: transparent;
    border: none;
}

/* custom popup styles */
.custom-popup .leaflet-popup-content-wrapper {
    border-radius: 0.75rem;
    padding: 0;
}

.custom-popup .leaflet-popup-content {
    margin: 0;
    width: 100% !important;
}

.custom-popup .leaflet-popup-tip {
    background: white;
}
</style>
@endpush