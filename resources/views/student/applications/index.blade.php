{{-- resources/views/student/applications/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Aplikasi Saya')

@push('styles')
{{-- Import Google Font - Space Grotesk for Hero --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@600;700&display=swap" rel="stylesheet">

<style>
    /* Hero section style mirip dashboard */
    .marketplace-hero-applications {
        position: relative;
        background-image:
            linear-gradient(135deg, rgba(99, 102, 241, 0.35) 0%, rgba(129, 140, 248, 0.30) 50%, rgba(156, 163, 175, 0.25) 100%),
            url('/application-student.jpg');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        min-height: 480px;
    }

    .hero-title-applications {
        font-family: 'Space Grotesk', sans-serif;
        font-weight: 700;
        letter-spacing: -0.02em;
    }

    .text-shadow-strong {
        text-shadow:
            0 2px 4px rgba(0, 0, 0, 0.4),
            0 4px 8px rgba(0, 0, 0, 0.3);
    }

    .application-fade-in {
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

    /* Background style seperti dashboard */
    .gradient-mesh-bg {
        background-color: #ffffff;
        background-image:
            radial-gradient(at 15% 15%, rgba(99, 102, 241, 0.08) 0px, transparent 50%),
            radial-gradient(at 85% 20%, rgba(236, 72, 153, 0.08) 0px, transparent 50%),
            radial-gradient(at 25% 75%, rgba(59, 130, 246, 0.08) 0px, transparent 50%),
            radial-gradient(at 75% 85%, rgba(168, 85, 247, 0.08) 0px, transparent 50%);
    }

    /* Vertical bar chart styling */
    .bar-chart-container {
        display: flex !important;
        align-items: flex-end !important;
        justify-content: space-around !important;
        height: 350px !important;
        width: 100% !important;
        gap: 2rem !important;
        padding: 3rem 1rem 1rem 1rem !important;
        background: linear-gradient(to top, rgba(0, 0, 0, 0.03) 0%, transparent 100%) !important;
        border-radius: 1rem !important;
        position: relative !important;
    }

    .bar-item {
        flex: 1 !important;
        display: flex !important;
        flex-direction: column !important;
        align-items: center !important;
        justify-content: flex-end !important;
        gap: 1rem !important;
        max-width: 150px !important;
        height: 100% !important;
    }

    .bar-column {
        width: 100% !important;
        max-width: 100px !important;
        border-radius: 0.5rem 0.5rem 0 0 !important;
        transition: all 0.3s ease !important;
        position: relative !important;
        box-shadow: 0 -4px 6px -1px rgba(0, 0, 0, 0.1) !important;
    }

    .bar-column:hover {
        transform: translateY(-4px) !important;
        box-shadow: 0 -8px 12px -2px rgba(0, 0, 0, 0.15) !important;
    }

    .bar-column-green {
        background: linear-gradient(to top, #22c55e, #10b981) !important;
    }

    .bar-column-blue {
        background: linear-gradient(to top, #3b82f6, #2563eb) !important;
    }

    .bar-column-yellow {
        background: linear-gradient(to top, #eab308, #ca8a04) !important;
    }

    .bar-column-red {
        background: linear-gradient(to top, #ef4444, #dc2626) !important;
    }

    .bar-value {
        position: absolute !important;
        top: -2.5rem !important;
        left: 50% !important;
        transform: translateX(-50%) !important;
        font-weight: 700 !important;
        font-size: 1.5rem !important;
        white-space: nowrap !important;
    }

    .bar-label {
        text-align: center !important;
        font-weight: 600 !important;
        font-size: 0.9rem !important;
        line-height: 1.4 !important;
        margin-top: 0.5rem !important;
    }
</style>
@endpush

@section('content')
<div class="min-h-screen gradient-mesh-bg">

    {{-- marketplace-style hero section mirip dashboard --}}
    <section class="marketplace-hero-applications text-white relative flex items-center justify-center">
        <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 relative z-10 w-full py-12">
            <div class="max-w-4xl mx-auto text-center">
                <div class="application-fade-in">
                    {{-- Judul dan deskripsi --}}
                    <h1 class="hero-title-applications text-4xl md:text-6xl font-bold mb-6 text-white leading-tight" style="color: white !important;">
                        Aplikasi Saya
                    </h1>

                    {{-- primary CTA --}}
                    <div class="flex flex-col sm:flex-row gap-4 justify-center mb-6">
                        <a href="{{ route('student.browse-problems.index') }}"
                           class="inline-flex items-center justify-center px-8 py-3 bg-indigo-600 text-white font-bold rounded-full hover:bg-indigo-700 transition-all duration-300 shadow-xl hover:shadow-2xl hover:scale-105">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <span>Cari Proyek KKN</span>
                        </a>
                        <a href="{{ route('student.dashboard') }}"
                           class="inline-flex items-center justify-center px-8 py-3 bg-white/20 backdrop-blur-sm text-white font-bold rounded-full hover:bg-white/30 transition-all duration-300 border-2 border-white hover:scale-105">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            <span>Kembali ke Dashboard</span>
                        </a>
                    </div>

                    <p class="text-lg md:text-xl leading-relaxed max-w-2xl mx-auto font-medium" style="color: #ffffff !important; text-shadow: 0 2px 8px rgba(0, 0, 0, 0.5), 0 4px 12px rgba(0, 0, 0, 0.4);">
                        Kelola dan pantau status aplikasi proyek KKN Anda
                    </p>
                </div>
            </div>
        </div>

        {{-- straight divider --}}
        <div class="absolute bottom-0 left-0 right-0 h-1 bg-white"></div>
    </section>

    {{-- main content --}}
    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 py-8">

        {{-- Statistics Chart Section --}}
        @php
            $accepted = $stats['accepted'] ?? 0;
            $reviewed = $stats['reviewed'] ?? 0;
            $pending = $stats['pending'] ?? 0;
            $rejected = $stats['rejected'] ?? 0;
            $maxValue = max($accepted, $reviewed, $pending, $rejected, 1);
        @endphp

        <div class="mb-12">
            <div class="mb-6">
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Statistik Aplikasi</h2>
                <p class="text-gray-600">Ringkasan status aplikasi proyek KKN Anda</p>
            </div>

            <div class="bg-white rounded-3xl p-8 shadow-lg">
                {{-- Vertical Bar Chart --}}
                <div class="bar-chart-container">
                    {{-- Bar Diterima --}}
                    <div class="bar-item">
                        @php
                            $acceptedHeight = $accepted > 0 ? max((($accepted / max($maxValue, 1)) * 100), 8) : 8;
                        @endphp
                        <div class="bar-column bar-column-green"
                             style="height: {{ $acceptedHeight }}%;">
                            <div class="bar-value text-green-600">{{ $accepted }}</div>
                        </div>
                        <div class="bar-label text-gray-700">
                            <div class="font-bold text-base">Diterima</div>
                        </div>
                    </div>

                    {{-- Bar Direview --}}
                    <div class="bar-item">
                        @php
                            $reviewedHeight = $reviewed > 0 ? max((($reviewed / max($maxValue, 1)) * 100), 8) : 8;
                        @endphp
                        <div class="bar-column bar-column-blue"
                             style="height: {{ $reviewedHeight }}%;">
                            <div class="bar-value text-blue-600">{{ $reviewed }}</div>
                        </div>
                        <div class="bar-label text-gray-700">
                            <div class="font-bold text-base">Direview</div>
                        </div>
                    </div>

                    {{-- Bar Pending --}}
                    <div class="bar-item">
                        @php
                            $pendingHeight = $pending > 0 ? max((($pending / max($maxValue, 1)) * 100), 8) : 8;
                        @endphp
                        <div class="bar-column bar-column-yellow"
                             style="height: {{ $pendingHeight }}%;">
                            <div class="bar-value text-yellow-600">{{ $pending }}</div>
                        </div>
                        <div class="bar-label text-gray-700">
                            <div class="font-bold text-base">Pending</div>
                        </div>
                    </div>

                    {{-- Bar Ditolak --}}
                    <div class="bar-item">
                        @php
                            $rejectedHeight = $rejected > 0 ? max((($rejected / max($maxValue, 1)) * 100), 8) : 8;
                        @endphp
                        <div class="bar-column bar-column-red"
                             style="height: {{ $rejectedHeight }}%;">
                            <div class="bar-value text-red-600">{{ $rejected }}</div>
                        </div>
                        <div class="bar-label text-gray-700">
                            <div class="font-bold text-base">Ditolak</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- search dan filter --}}
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6 border border-gray-100">
            <form method="GET" action="{{ route('student.applications.index') }}" class="flex flex-wrap gap-4">
                {{-- search --}}
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Cari berdasarkan judul proyek atau instansi..." 
                       class="flex-1 min-w-[300px] px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                
                {{-- status filter --}}
                <select name="status" class="px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="reviewed" {{ request('status') == 'reviewed' ? 'selected' : '' }}>Direview</option>
                    <option value="accepted" {{ request('status') == 'accepted' ? 'selected' : '' }}>Diterima</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                </select>
                
                {{-- sort --}}
                <select name="sort" class="px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Terlama</option>
                </select>
                
                {{-- submit button --}}
                <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                    Filter
                </button>
                
                {{-- reset --}}
                @if(request()->hasAny(['search', 'status', 'sort']))
                <a href="{{ route('student.applications.index') }}" 
                   class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors font-medium text-center">
                    Reset
                </a>
                @endif
            </form>
        </div>

        {{-- applications list --}}
        <div class="space-y-4">
            @forelse($applications as $application)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-all duration-300">
                <div class="flex items-start gap-4">
                    {{-- ✅ PERBAIKAN: problem image menggunakan coverImage accessor dan image_url --}}
                    @if($application->problem->coverImage)
                    <img src="{{ $application->problem->coverImage->image_url }}" 
                         alt="{{ $application->problem->title }}"
                         onerror="this.onerror=null; this.src='https://via.placeholder.com/96?text=No+Image';"
                         class="w-24 h-24 object-cover rounded-lg flex-shrink-0">
                    @else
                    <div class="w-24 h-24 bg-gradient-to-br from-blue-500 to-green-500 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    @endif
                    
                    {{-- application info --}}
                    <div class="flex-1">
                        <div class="flex items-start justify-between mb-2">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 mb-1">{{ $application->problem->title }}</h3>
                                <p class="text-gray-600 mb-1">{{ $application->problem->institution->name }}</p>
                                <p class="text-sm text-gray-500">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    {{ $application->problem->regency->name ?? $application->problem->location_regency }}
                                </p>
                            </div>
                            
                            {{-- status badge --}}
                            <span class="inline-flex px-4 py-2 text-sm font-semibold rounded-full
                                {{ $application->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $application->status === 'reviewed' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $application->status === 'accepted' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $application->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}">
                                {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                            </span>
                        </div>
                        
                        {{-- metadata --}}
                        <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600 mb-3">
                            <div class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span>Diajukan {{ $application->applied_at->format('d M Y') }}</span>
                            </div>
                            
                            @if($application->reviewed_at)
                            <div class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>Direview {{ $application->reviewed_at->format('d M Y') }}</span>
                            </div>
                            @endif
                        </div>
                        
                        {{-- feedback dari instansi --}}
                        @if($application->feedback && $application->status === 'accepted')
                        <div class="bg-green-50 border border-green-200 rounded-lg p-3 mb-3">
                            <p class="text-sm text-green-800 font-medium mb-1">Feedback Dari Instansi:</p>
                            <p class="text-sm text-green-700">{{ $application->feedback }}</p>
                        </div>
                        @elseif($application->feedback && $application->status === 'rejected')
                        <div class="bg-red-50 border border-red-200 rounded-lg p-3 mb-3">
                            <p class="text-sm text-red-800 font-medium mb-1">Alasan Penolakan:</p>
                            <p class="text-sm text-red-700">{{ $application->feedback }}</p>
                        </div>
                        @endif
                        
                        {{-- actions --}}
                        <div class="flex items-center gap-3">
                            <a href="{{ route('student.applications.show', $application->id) }}" 
                               class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Lihat Detail
                            </a>
                            
                                @if($application->status === 'pending')
                                <form action="{{ route('student.applications.withdraw', $application->id) }}" method="POST" 
                                    onsubmit="return confirm('Apakah Anda yakin ingin membatalkan aplikasi ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="inline-flex items-center gap-2 px-4 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors text-sm font-medium">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    Batalkan Aplikasi
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="bg-white rounded-xl shadow-sm p-12 text-center">
                <svg class="w-20 h-20 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Belum Ada Aplikasi</h3>
                <p class="text-gray-600 mb-6">Anda belum mengajukan aplikasi untuk proyek apapun</p>
                <a href="{{ route('student.browse-problems.index') }}" 
                   class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Jelajahi Proyek
                </a>
            </div>
            @endforelse
        </div>

        {{-- pagination --}}
        @if($applications->hasPages())
        <div class="mt-6">
            {{ $applications->links() }}
        </div>
        @endif
    </div>
</div>
@endsection