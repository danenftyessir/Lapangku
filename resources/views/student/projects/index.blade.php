{{-- resources/views/student/projects/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Proyek Saya')

@push('styles')
<style>
    /* hero section dengan background image */
    .hero-projects-background {
        position: relative;
        background-image: url('/projects-student.jpeg');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        min-height: 400px;
    }
    
    .hero-projects-background::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(
            135deg, 
            rgba(37, 99, 235, 0.50) 0%,
            rgba(59, 130, 246, 0.45) 35%,
            rgba(16, 185, 129, 0.45) 65%,
            rgba(5, 150, 105, 0.50) 100%
        );
        backdrop-filter: blur(1px);
    }
    
    /* stats cards dengan glassmorphism */
    .stats-card-projects {
        background: rgba(255, 255, 255, 0.20);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        will-change: transform;
    }
    
    .stats-card-projects:hover {
        background: rgba(255, 255, 255, 0.30);
        transform: translate3d(0, -4px, 0);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3);
    }
    
    .stats-card-projects svg {
        filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.3));
    }
    
    .stats-card-projects .backdrop-blur-sm {
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
    }
    
    /* text shadow untuk hero */
    .text-shadow-projects {
        text-shadow: 
            0 2px 4px rgba(0, 0, 0, 0.4),
            0 4px 8px rgba(0, 0, 0, 0.3),
            0 1px 2px rgba(0, 0, 0, 0.5);
    }
    
    /* fade in animation */
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
    
    .fade-in-up {
        animation: fadeInUp 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        opacity: 0;
        animation-fill-mode: forwards;
    }
    
    /* project card animations */
    .project-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        will-change: transform, box-shadow;
    }
    
    .project-card:hover {
        transform: translate3d(0, -8px, 0);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.15);
    }
    
    /* smooth scrolling */
    html {
        scroll-behavior: smooth;
    }
    
    /* GPU acceleration untuk performa */
    .stats-card-projects,
    .project-card {
        transform: translateZ(0);
        backface-visibility: hidden;
        perspective: 1000px;
    }
    
    /* accessibility - prefers reduced motion */
    @media (prefers-reduced-motion: reduce) {
        *,
        *::before,
        *::after {
            animation-duration: 0.01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: 0.01ms !important;
            scroll-behavior: auto !important;
        }
        
        .stats-card-projects:hover,
        .project-card:hover {
            transform: none;
        }
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50">
    
    {{-- hero section dengan background image --}}
    <div class="hero-projects-background text-white py-16 md:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="fade-in-up">
                <h1 class="text-4xl md:text-5xl font-bold mb-4 text-shadow-projects">
                    Proyek Saya
                </h1>
                <p class="text-xl md:text-2xl text-white text-shadow-projects max-w-3xl">
                    Kelola dan pantau progress proyek KKN Anda
                </p>
            </div>
            
            {{-- statistics cards --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 md:gap-6 mt-10 fade-in-up" style="animation-delay: 0.2s;">
                {{-- total projects --}}
                <div class="stats-card-projects rounded-xl p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-white opacity-90 mb-1">Total Proyek</p>
                            <p class="text-4xl md:text-5xl font-bold text-white text-shadow-projects">{{ $stats['total'] }}</p>
                        </div>
                        <div class="w-14 h-14 md:w-16 md:h-16 bg-white/20 bg-opacity-30 rounded-xl flex items-center justify-center backdrop-blur-sm shadow-lg">
                            <svg class="w-8 h-8 md:w-9 md:h-9 text-white drop-shadow-lg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- active projects --}}
                <div class="stats-card-projects rounded-xl p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-white opacity-90 mb-1">Proyek Aktif</p>
                            <p class="text-4xl md:text-5xl font-bold text-white text-shadow-projects">{{ $stats['active'] }}</p>
                        </div>
                        <div class="w-14 h-14 md:w-16 md:h-16 bg-white/20 bg-opacity-30 rounded-xl flex items-center justify-center backdrop-blur-sm shadow-lg">
                            <svg class="w-8 h-8 md:w-9 md:h-9 text-white drop-shadow-lg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- completed projects --}}
                <div class="stats-card-projects rounded-xl p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-white opacity-90 mb-1">Proyek Selesai</p>
                            <p class="text-4xl md:text-5xl font-bold text-white text-shadow-projects">{{ $stats['completed'] }}</p>
                        </div>
                        <div class="w-14 h-14 md:w-16 md:h-16 bg-white/20 bg-opacity-30 rounded-xl flex items-center justify-center backdrop-blur-sm shadow-lg">
                            <svg class="w-8 h-8 md:w-9 md:h-9 text-white drop-shadow-lg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- total reports --}}
                <div class="stats-card-projects rounded-xl p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-white opacity-90 mb-1">Total Laporan</p>
                            <p class="text-4xl md:text-5xl font-bold text-white text-shadow-projects">{{ $stats['total_reports'] }}</p>
                        </div>
                        <div class="w-14 h-14 md:w-16 md:h-16 bg-white/20 bg-opacity-30 rounded-xl flex items-center justify-center backdrop-blur-sm shadow-lg">
                            <svg class="w-8 h-8 md:w-9 md:h-9 text-white drop-shadow-lg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- main content area --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        {{-- filter section --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8 fade-in-up" style="animation-delay: 0.3s;">
            <form method="GET" action="{{ route('student.projects.index') }}" class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <select name="status" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                        <option value="on_hold" {{ request('status') == 'on_hold' ? 'selected' : '' }}>Ditunda</option>
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" 
                            class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all font-semibold shadow-sm hover:shadow-md">
                        Filter
                    </button>
                    <a href="{{ route('student.projects.index') }}" 
                       class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-all font-semibold shadow-sm">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        {{-- projects list --}}
        @if($projects->isEmpty())
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center fade-in-up" style="animation-delay: 0.4s;">
                <svg class="w-20 h-20 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Belum Ada Proyek</h3>
                <p class="text-gray-600 mb-6">Anda belum memiliki proyek yang sedang berjalan</p>
                <a href="{{ route('student.browse-problems.index') }}" 
                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-green-600 text-white rounded-lg hover:shadow-lg transition-all font-semibold">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Cari Proyek Baru
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($projects as $index => $project)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden project-card fade-in-up" 
                     style="animation-delay: {{ 0.4 + ($index * 0.1) }}s;">
                    
                    {{-- status badge --}}
                    <div class="p-6 pb-0">
                        <div class="flex items-center gap-2 mb-4">
                            @if($project->status === 'active')
                                <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full">Aktif</span>
                            @elseif($project->status === 'completed')
                                <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-semibold rounded-full">Selesai</span>
                            @elseif($project->status === 'on_hold')
                                <span class="px-3 py-1 bg-yellow-100 text-yellow-700 text-xs font-semibold rounded-full">Ditunda</span>
                            @else
                                <span class="px-3 py-1 bg-gray-100 text-gray-700 text-xs font-semibold rounded-full">{{ ucfirst($project->status) }}</span>
                            @endif
                            
                            @if($project->is_overdue && $project->status === 'active')
                                <span class="px-3 py-1 bg-red-100 text-red-700 text-xs font-semibold rounded-full">Overdue</span>
                            @endif
                        </div>
                    </div>
                    
                    {{-- content --}}
                    <div class="p-6 pt-0">
                        <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2">{{ $project->title }}</h3>
                        
                        {{-- institution --}}
                        <div class="flex items-center text-sm text-gray-600 mb-4">
                            <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            <span class="truncate">{{ $project->institution->name }}</span>
                        </div>

                        {{-- progress bar --}}
                        <div class="mb-4">
                            <div class="flex justify-between text-sm text-gray-600 mb-2">
                                <span>Progress</span>
                                <span class="font-semibold">{{ $project->progress_percentage }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                                <div class="bg-gradient-to-r from-blue-600 to-green-600 h-2 rounded-full transition-all duration-500" 
                                     style="width: {{ $project->progress_percentage }}%"></div>
                            </div>
                        </div>

                        {{-- timeline --}}
                        <div class="flex items-center justify-between text-sm text-gray-600 mb-4">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span>{{ $project->start_date->format('d M Y') }}</span>
                            </div>
                            <span class="text-gray-400">→</span>
                            <div>{{ $project->end_date->format('d M Y') }}</div>
                        </div>

                        {{-- action button --}}
                        <a href="{{ route('student.projects.show', $project->id) }}" 
                           class="block w-full text-center px-4 py-2 bg-gradient-to-r from-blue-600 to-green-600 text-white rounded-lg hover:shadow-lg transition-all font-semibold">
                            Lihat Detail
                        </a>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- pagination --}}
            <div class="mt-8 fade-in-up" style="animation-delay: 0.6s;">
                {{ $projects->links() }}
            </div>
        @endif

    </div>
</div>
@endsection