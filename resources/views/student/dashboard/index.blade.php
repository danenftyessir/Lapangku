@extends('layouts.app')

@section('title', 'Dashboard Mahasiswa - KKN-Go')

@push('styles')
<style>
    /* hero section dengan background image fixed */
    .hero-dashboard-background {
        position: relative;
        background-image: url('/dashboard-student2.jpeg');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        min-height: 450px;
    }

    .hero-dashboard-background::before {
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
    .stats-card-dashboard {
        background: rgba(255, 255, 255, 0.20);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        will-change: transform;
    }

    .stats-card-dashboard:hover {
        background: rgba(255, 255, 255, 0.30);
        transform: translate3d(0, -4px, 0);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3);
    }

    .text-shadow-strong {
        text-shadow:
            0 2px 4px rgba(0, 0, 0, 0.4),
            0 4px 8px rgba(0, 0, 0, 0.3),
            0 1px 2px rgba(0, 0, 0, 0.5);
    }

    .dashboard-fade-in {
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
{{-- hero section dengan background image --}}
<div class="hero-dashboard-background text-white py-16 md:py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="dashboard-fade-in">
            <h1 class="text-4xl md:text-5xl font-bold mb-4 text-shadow-strong">
                Selamat Datang, {{ Auth::user()->name }}!
            </h1>
            <p class="text-xl md:text-2xl text-white text-shadow-strong max-w-3xl">
                Mari berkontribusi untuk pembangunan berkelanjutan melalui KKN!
            </p>
        </div>

        {{-- stats cards dengan glassmorphism --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 md:gap-6 mt-10 dashboard-fade-in" style="animation-delay: 0.2s;">
            <div class="stats-card-dashboard rounded-xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-4xl md:text-5xl font-bold text-white text-shadow-strong">
                            {{ Auth::user()->student->applications()->count() }}
                        </div>
                        <div class="text-white text-shadow-strong mt-2">
                            Total Aplikasi
                        </div>
                    </div>
                    <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="stats-card-dashboard rounded-xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-4xl md:text-5xl font-bold text-white text-shadow-strong">
                            {{ Auth::user()->student->projects()->where('status', 'in_progress')->count() }}
                        </div>
                        <div class="text-white text-shadow-strong mt-2">
                            Proyek Aktif
                        </div>
                    </div>
                    <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="stats-card-dashboard rounded-xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-4xl md:text-5xl font-bold text-white text-shadow-strong">
                            {{ Auth::user()->student->projects()->where('status', 'completed')->count() }}
                        </div>
                        <div class="text-white text-shadow-strong mt-2">
                            Proyek Selesai
                        </div>
                    </div>
                    <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="stats-card-dashboard rounded-xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-4xl md:text-5xl font-bold text-white text-shadow-strong">
                            {{ Auth::user()->student->friendsCount() }}
                        </div>
                        <div class="text-white text-shadow-strong mt-2">
                            Koneksi
                        </div>
                    </div>
                    <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- main dashboard content --}}
<div class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-6 py-8">
        <div class="flex flex-col lg:flex-row gap-6">
            
            {{-- sidebar kiri --}}
            <aside class="lg:w-1/4">
                {{-- profile card --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden mb-6">
                    <div class="relative h-20 bg-gradient-to-r from-blue-500 to-indigo-600">
                        <img src="{{ asset('dashboard-student3.jpg') }}" 
                             alt="Cover" 
                             class="w-full h-full object-cover opacity-50">
                    </div>
                    <div class="relative px-4 pb-4">
                        <div class="flex justify-center -mt-12 mb-3">
                            <img src="{{ Auth::user()->profile_photo_url }}"
                                 alt="{{ Auth::user()->name }}"
                                 class="avatar-circle avatar-lg border-4 border-white shadow-lg">
                        </div>
                        <h3 class="text-center font-bold text-gray-900 text-lg">
                            {{ Auth::user()->name }}
                        </h3>
                        <p class="text-center text-sm text-gray-600 mb-1">
                            {{ Auth::user()->student->major }}
                        </p>
                        <p class="text-center text-xs text-gray-500">
                            {{ Auth::user()->student->university->name }}
                        </p>
                    </div>
                </div>

                {{-- navigation menu --}}
                <nav class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                    <h3 class="font-semibold text-gray-900 mb-3 text-sm">Menu Navigasi</h3>
                    <div class="space-y-1">
                        {{-- dashboard --}}
                        <a href="{{ route('student.dashboard') }}" 
                           class="flex items-center px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-colors {{ request()->routeIs('student.dashboard') ? 'bg-blue-50 text-blue-600 font-medium' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            <span>Dashboard</span>
                        </a>

                        {{-- browse problems --}}
                        <a href="{{ route('student.browse-problems.index') }}"
                           class="flex items-center px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-colors {{ request()->routeIs('student.browse-problems*') ? 'bg-blue-50 text-blue-600 font-medium' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <span>Cari Proyek</span>
                        </a>

                        {{-- friends/network - NEW --}}
                        <a href="{{ route('student.friends.index') }}" 
                           class="flex items-center px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-colors {{ request()->routeIs('student.friends.*') ? 'bg-blue-50 text-blue-600 font-medium' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            <span>Jaringan</span>
                            @if(Auth::user()->student->pendingFriendRequests()->count() > 0)
                            <span class="ml-auto bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">
                                {{ Auth::user()->student->pendingFriendRequests()->count() }}
                            </span>
                            @endif
                        </a>

                        {{-- my applications --}}
                        <a href="{{ route('student.applications.index') }}" 
                           class="flex items-center px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-colors {{ request()->routeIs('student.applications.*') ? 'bg-blue-50 text-blue-600 font-medium' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span>Aplikasi Saya</span>
                        </a>

                        {{-- my projects --}}
                        <a href="{{ route('student.projects.index') }}" 
                           class="flex items-center px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-colors {{ request()->routeIs('student.projects.*') ? 'bg-blue-50 text-blue-600 font-medium' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <span>Proyek Saya</span>
                        </a>

                        {{-- wishlist --}}
                        <a href="{{ route('student.wishlist.index') }}"
                           class="flex items-center px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-colors {{ request()->routeIs('student.wishlist.*') ? 'bg-blue-50 text-blue-600 font-medium' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                            <span>Wishlist</span>
                        </a>

                        {{-- repository --}}
                        <a href="{{ route('student.repository.index') }}" 
                           class="flex items-center px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-colors {{ request()->routeIs('student.repository.*') ? 'bg-blue-50 text-blue-600 font-medium' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                            <span>Repository</span>
                        </a>

                        {{-- profile --}}
                        <a href="{{ route('student.profile.index') }}" 
                           class="flex items-center px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-colors {{ request()->routeIs('student.profile.*') ? 'bg-blue-50 text-blue-600 font-medium' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <span>Profil Saya</span>
                        </a>
                    </div>
                </nav>
            </aside>

            {{-- main content area --}}
            <main class="lg:w-3/4 space-y-6">

                {{-- network widget - NEW --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="border-b border-gray-200 px-6 py-4 flex justify-between items-center">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            <h2 class="text-lg font-bold text-gray-900">Jaringan Saya</h2>
                        </div>
                        <a href="{{ route('student.friends.index') }}" 
                           class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                            Lihat Semua
                        </a>
                    </div>

                    <div class="p-6">
                        {{-- network stats --}}
                        <div class="grid grid-cols-3 gap-4 mb-6">
                            <div class="text-center p-4 bg-blue-50 rounded-lg">
                                <div class="text-2xl font-bold text-blue-600">
                                    {{ Auth::user()->student->friendsCount() }}
                                </div>
                                <div class="text-xs text-gray-600 mt-1">Koneksi</div>
                            </div>
                            <div class="text-center p-4 bg-yellow-50 rounded-lg">
                                <div class="text-2xl font-bold text-yellow-600">
                                    {{ Auth::user()->student->pendingFriendRequests()->count() }}
                                </div>
                                <div class="text-xs text-gray-600 mt-1">Permintaan</div>
                            </div>
                            <div class="text-center p-4 bg-purple-50 rounded-lg">
                                <div class="text-2xl font-bold text-purple-600">
                                    {{ Auth::user()->student->suggestedFriends(10)->count() }}
                                </div>
                                <div class="text-xs text-gray-600 mt-1">Saran</div>
                            </div>
                        </div>

                        {{-- pending requests preview --}}
                        @php
                            $pendingRequests = Auth::user()->student->pendingFriendRequests()->take(3);
                        @endphp
                        
                        @if($pendingRequests->count() > 0)
                        <div class="mb-6">
                            <h3 class="font-semibold text-gray-900 mb-3 text-sm">Permintaan Pertemanan</h3>
                            <div class="space-y-2">
                                @foreach($pendingRequests as $request)
                                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                                    <img src="{{ $request->requester->profile_photo_url }}"
                                         alt="{{ $request->requester->user->name }}"
                                         class="avatar-circle avatar-sm">
                                    <div class="flex-1 min-w-0">
                                        <p class="font-medium text-gray-900 text-sm truncate">
                                            {{ $request->requester->user->name }}
                                        </p>
                                        <p class="text-xs text-gray-600 truncate">
                                            {{ $request->requester->major }} • {{ $request->requester->university->name }}
                                        </p>
                                    </div>
                                    <div class="flex gap-1">
                                        <form method="POST" action="{{ route('student.friends.accept', $request->id) }}">
                                            @csrf
                                            <button type="submit" 
                                                    class="px-3 py-1 bg-blue-600 text-white text-xs font-medium rounded hover:bg-blue-700"
                                                    title="Terima">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('student.friends.reject', $request->id) }}">
                                            @csrf
                                            <button type="submit" 
                                                    class="px-3 py-1 bg-gray-200 text-gray-700 text-xs font-medium rounded hover:bg-gray-300"
                                                    title="Tolak">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @if(Auth::user()->student->pendingFriendRequests()->count() > 3)
                            <a href="{{ route('student.friends.index') }}" 
                               class="block text-center text-sm text-blue-600 hover:text-blue-700 font-medium mt-3">
                                Lihat {{ Auth::user()->student->pendingFriendRequests()->count() - 3 }} permintaan lainnya
                            </a>
                            @endif
                        </div>
                        @endif

                        {{-- suggestions preview --}}
                        @php
                            $suggestions = Auth::user()->student->suggestedFriends(4);
                        @endphp
                        
                        @if($suggestions->count() > 0)
                        <div>
                            <h3 class="font-semibold text-gray-900 mb-3 text-sm">Rekomendasi Koneksi</h3>
                            <div class="grid grid-cols-2 gap-3">
                                @foreach($suggestions as $suggestion)
                                <div class="border border-gray-200 rounded-lg p-3 hover:shadow-md transition-shadow">
                                    <div class="flex items-start gap-2 mb-2">
                                        <img src="{{ $suggestion->profile_photo_url }}"
                                             alt="{{ $suggestion->user->name }}"
                                             class="avatar-circle avatar-sm">
                                        <div class="flex-1 min-w-0">
                                            <p class="font-medium text-gray-900 text-sm truncate">
                                                {{ $suggestion->user->name }}
                                            </p>
                                            <p class="text-xs text-gray-600 truncate">
                                                {{ $suggestion->major }} • {{ $suggestion->university->name }}
                                            </p>
                                        </div>
                                    </div>
                                    <form method="POST" action="{{ route('student.friends.send-request', $suggestion->id) }}">
                                        @csrf
                                        <button type="submit" 
                                                class="w-full px-3 py-1.5 bg-blue-600 text-white text-xs font-medium rounded hover:bg-blue-700 transition-colors">
                                            Hubungkan
                                        </button>
                                    </form>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        {{-- call to action jika belum ada network --}}
                        @if($suggestions->count() === 0 && $pendingRequests->count() === 0 && Auth::user()->student->friendsCount() === 0)
                        <div class="text-center py-8">
                            <div class="inline-flex items-center justify-center w-12 h-12 bg-blue-100 rounded-full mb-3">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </div>
                            <h3 class="text-sm font-semibold text-gray-900 mb-1">
                                Mulai Bangun Jaringan
                            </h3>
                            <p class="text-xs text-gray-600 mb-4">
                                Terhubung dengan mahasiswa lain untuk berbagi pengalaman KKN
                            </p>
                            <a href="{{ route('student.friends.search') }}" 
                               class="inline-block px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                Cari Teman
                            </a>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- activity feed component - OPTIONAL --}}
                @php
                    // ambil aktivitas teman (implementasi bisa disesuaikan)
                    $activities = [];
                    // untuk saat ini kosongkan, nanti bisa diisi dari controller
                @endphp
                
                @if(count($activities) > 0)
                @include('components.activity-feed', ['activities' => $activities])
                @endif

                {{-- recent applications --}}
                @php
                    $recentApplications = Auth::user()->student->applications()
                        ->with(['problem.institution'])
                        ->latest()
                        ->take(5)
                        ->get();
                @endphp

                @if($recentApplications->count() > 0)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="border-b border-gray-200 px-6 py-4 flex justify-between items-center">
                        <h2 class="text-lg font-bold text-gray-900">Aplikasi Terbaru</h2>
                        <a href="{{ route('student.applications.index') }}" 
                           class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                            Lihat Semua
                        </a>
                    </div>
                    <div class="divide-y divide-gray-200">
                        @foreach($recentApplications as $application)
                        <div class="p-6 hover:bg-gray-50 transition-colors">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-900 mb-1">
                                        {{ $application->problem->title }}
                                    </h3>
                                    <p class="text-sm text-gray-600 mb-2">
                                        {{ $application->problem->institution->name }}
                                    </p>
                                    <div class="flex items-center gap-3 text-xs text-gray-500">
                                        <span>Diajukan {{ $application->created_at->diffForHumans() }}</span>
                                        <span class="px-2 py-1 rounded-full font-medium
                                            {{ $application->status === 'accepted' ? 'bg-green-100 text-green-700' : '' }}
                                            {{ $application->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                            {{ $application->status === 'rejected' ? 'bg-red-100 text-red-700' : '' }}
                                            {{ $application->status === 'under_review' ? 'bg-blue-100 text-blue-700' : '' }}">
                                            {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                                        </span>
                                    </div>
                                </div>
                                <a href="{{ route('student.applications.show', $application->id) }}" 
                                   class="px-4 py-2 text-sm font-medium text-blue-600 hover:text-blue-700 border border-blue-600 rounded-lg hover:bg-blue-50 transition-colors">
                                    Detail
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- active projects --}}
                @php
                    $activeProjects = Auth::user()->student->projects()
                        ->where('status', 'in_progress')
                        ->with(['problem.institution'])
                        ->latest()
                        ->take(3)
                        ->get();
                @endphp

                @if($activeProjects->count() > 0)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="border-b border-gray-200 px-6 py-4 flex justify-between items-center">
                        <h2 class="text-lg font-bold text-gray-900">Proyek Aktif</h2>
                        <a href="{{ route('student.projects.index') }}" 
                           class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                            Lihat Semua
                        </a>
                    </div>
                    <div class="divide-y divide-gray-200">
                        @foreach($activeProjects as $project)
                        <div class="p-6 hover:bg-gray-50 transition-colors">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-900 mb-1">
                                        {{ $project->problem->title }}
                                    </h3>
                                    <p class="text-sm text-gray-600 mb-3">
                                        {{ $project->problem->institution->name }}
                                    </p>
                                    <div class="w-full bg-gray-200 rounded-full h-2 mb-2">
                                        <div class="bg-blue-600 h-2 rounded-full" 
                                             style="width: {{ $project->progress ?? 0 }}%"></div>
                                    </div>
                                    <p class="text-xs text-gray-500">
                                        Progress: {{ $project->progress ?? 0 }}%
                                    </p>
                                </div>
                                <a href="{{ route('student.projects.show', $project->id) }}" 
                                   class="px-4 py-2 text-sm font-medium text-blue-600 hover:text-blue-700 border border-blue-600 rounded-lg hover:bg-blue-50 transition-colors">
                                    Detail
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

            </main>
        </div>
    </div>
</div>

@push('scripts')
<script>
// auto hide alerts
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    });
});
</script>
@endpush
@endsection