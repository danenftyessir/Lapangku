{{-- resources/views/student/browse-problems/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Browse Problems')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/browse-problems.css') }}">
<style>
    .hero-browse-background {
        position: relative;
        background-image: url('/dashboard-student.jpg');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
    }
    
    .hero-browse-background::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        /* gradient lebih transparan - opacity dikurangi dari 0.75-0.85 jadi 0.45-0.55 */
        background: linear-gradient(
            135deg, 
            rgba(37, 99, 235, 0.50) 0%,     /* biru lebih transparan */
            rgba(59, 130, 246, 0.45) 35%,   /* biru medium transparan */
            rgba(16, 185, 129, 0.45) 65%,   /* hijau medium transparan */
            rgba(5, 150, 105, 0.50) 100%    /* hijau transparan */
        );
        backdrop-filter: blur(1px);  /* blur dikurangi dari 2px ke 1px */
    }
    
    .stats-card-modern {
        background: rgba(255, 255, 255, 0.20);  /* sedikit lebih solid dari sebelumnya */
        backdrop-filter: blur(16px);             /* blur diperkuat untuk clarity */
        border: 1px solid rgba(255, 255, 255, 0.3);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .stats-card-modern:hover {
        background: rgba(255, 255, 255, 0.30);
        transform: translateY(-4px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3);
    }
    
    .text-shadow-strong {
        text-shadow: 
            0 2px 4px rgba(0, 0, 0, 0.4),
            0 4px 8px rgba(0, 0, 0, 0.3),
            0 1px 2px rgba(0, 0, 0, 0.5);  /* multiple layers untuk readability maksimal */
    }
    
    .browse-fade-in {
        animation: fadeInUp 0.8s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50">
    
    {{-- hero section dengan background --}}
    <div class="hero-browse-background text-white py-16 md:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="browse-fade-in">
                <h1 class="text-4xl md:text-5xl font-bold mb-4 text-shadow-strong">
                    Jelajahi Proyek KKN
                </h1>
                <p class="text-xl md:text-2xl text-white text-shadow-strong max-w-3xl">
                    Temukan proyek KKN yang sesuai dengan minat dan keahlian Anda
                </p>
            </div>
            
            {{-- stats cards --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6 mt-10 browse-fade-in" style="animation-delay: 0.2s;">
                <div class="stats-card-modern rounded-xl p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-4xl md:text-5xl font-bold text-white text-shadow-strong">
                                {{ $totalProblems ?? 0 }}
                            </div>
                            <div class="text-white text-shadow-strong mt-2">
                                Total Proyek
                            </div>
                        </div>
                        <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="stats-card-modern rounded-xl p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-4xl md:text-5xl font-bold text-white text-shadow-strong">
                                {{ $sdgCategories ?? 0 }}
                            </div>
                            <div class="text-white text-shadow-strong mt-2">
                                Kategori SDG
                            </div>
                        </div>
                        <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="stats-card-modern rounded-xl p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-4xl md:text-5xl font-bold text-white text-shadow-strong">
                                {{ $provinces->count() ?? 0 }}
                            </div>
                            <div class="text-white text-shadow-strong mt-2">
                                Provinsi Tersedia
                            </div>
                        </div>
                        <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- main content --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        {{-- filter toggle button (mobile) --}}
        <div class="mb-4 lg:hidden">
            <button id="toggle-filter" class="w-full bg-white px-4 py-3 rounded-lg shadow-sm border border-gray-200 flex items-center justify-between hover:bg-gray-50 transition-colors">
                <span class="text-gray-900 font-medium">Filter & Pencarian</span>
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
        </div>

        {{-- layout grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            
            {{-- sidebar filter --}}
            <aside class="lg:col-span-1">
                <div id="filter-section" class="hidden lg:block">
                    @include('student.browse-problems.components.filter-sidebar')
                </div>
            </aside>

            {{-- main problems area --}}
            <main class="lg:col-span-3">
                
                {{-- search bar --}}
                <div class="bg-white rounded-lg shadow-sm p-4 mb-6 border border-gray-200">
                    <form action="{{ route('student.browse-problems.index') }}" method="GET" class="flex gap-3">
                        @foreach(request()->except(['search', 'page']) as $key => $value)
                            @if(is_array($value))
                                {{-- jika value adalah array (contoh: sdg_categories), buat multiple hidden inputs --}}
                                @foreach($value as $item)
                                    <input type="hidden" name="{{ $key }}[]" value="{{ $item }}">
                                @endforeach
                            @else
                                {{-- jika value bukan array, buat single hidden input --}}
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endif
                        @endforeach
                        
                        <div class="flex-1">
                            <input type="text" 
                                   name="search" 
                                   value="{{ request('search') }}"
                                   placeholder="Cari proyek berdasarkan judul, atau deskripsi..."
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                            Cari
                        </button>
                    </form>
                </div>

                {{-- results count & view switcher --}}
                <div class="flex items-center justify-between mb-6">
                    <p class="text-gray-700">
                        Menampilkan <span class="font-semibold">{{ $problems->firstItem() ?? 0 }}</span> 
                        dari <span class="font-semibold">{{ $problems->total() }}</span> proyek
                    </p>
                    
                    {{-- view switcher buttons --}}
                    <div class="flex items-center gap-2">
                        <button id="grid-view-btn" 
                                onclick="switchView('grid')" 
                                class="p-2 border border-gray-300 rounded-lg hover:bg-gray-50 active-view bg-blue-50 border-blue-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                            </svg>
                        </button>
                        <button id="list-view-btn" 
                                onclick="switchView('list')" 
                                class="p-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                        <button id="map-view-btn" 
                                onclick="switchView('map')" 
                                class="p-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- problems display --}}
                <div id="grid-view" class="view-content">
                    @include('student.browse-problems.components.problems-grid')
                </div>

                <div id="list-view" class="view-content hidden">
                    @foreach($problems as $problem)
                        @include('student.browse-problems.components.problem-card-list', ['problem' => $problem])
                    @endforeach
                </div>

                <div id="map-view" class="view-content hidden">
                    @include('student.browse-problems.components.map-view')
                </div>

                {{-- pagination --}}
                <div class="mt-8">
                    {{ $problems->links() }}
                </div>
            </main>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/pages/browse-problems.js') }}"></script>
<script>
    // toggle filter section
    document.getElementById('toggle-filter').addEventListener('click', function() {
        const filterSection = document.getElementById('filter-section');
        filterSection.classList.toggle('hidden');
    });

    // view switcher
    function switchView(view) {
        document.querySelectorAll('.view-content').forEach(el => el.classList.add('hidden'));
        document.querySelectorAll('[id$="-view-btn"]').forEach(btn => {
            btn.classList.remove('active-view', 'bg-blue-50', 'border-blue-500');
        });
        document.getElementById(view + '-view').classList.remove('hidden');
        const activeBtn = document.getElementById(view + '-view-btn');
        activeBtn.classList.add('active-view', 'bg-blue-50', 'border-blue-500');
    }

    async function toggleWishlist(problemId, button) {
        button.disabled = true;
        const originalHTML = button.innerHTML;
        button.innerHTML = '<svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
        
        try {
            const response = await fetch(`/student/wishlist/${problemId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                button.innerHTML = originalHTML;
                button.setAttribute('data-wishlisted', data.saved ? 'true' : 'false');
                
                const svg = button.querySelector('svg');
                if (svg) {
                    if (data.saved) {
                        svg.setAttribute('fill', 'currentColor');
                        svg.classList.add('fill-red-500', 'text-red-500');
                        svg.classList.remove('text-gray-600');
                    } else {
                        svg.setAttribute('fill', 'none');
                        svg.classList.remove('fill-red-500', 'text-red-500');
                        svg.classList.add('text-gray-600');
                    }
                }
                
                button.style.transform = 'scale(1.2)';
                setTimeout(() => { button.style.transform = 'scale(1)'; }, 200);
                
                // notifikasi
                const notif = document.createElement('div');
                notif.className = 'fixed top-20 right-4 bg-green-50 border-l-4 border-green-500 text-green-800 px-4 py-3 rounded shadow-lg z-50';
                notif.innerHTML = `<div class="flex items-center"><svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg><span>${data.message || (data.saved ? 'Ditambahkan ke wishlist' : 'Dihapus dari wishlist')}</span></div>`;
                document.body.appendChild(notif);
                setTimeout(() => { notif.style.opacity='0'; notif.style.transition='all 0.3s'; setTimeout(() => notif.remove(), 300); }, 3000);
            }
        } catch (error) {
            console.error('Error:', error);
            button.innerHTML = originalHTML;
            alert('Terjadi kesalahan. Silakan coba lagi.');
        } finally {
            button.disabled = false;
        }
    }
</script>
@endpush