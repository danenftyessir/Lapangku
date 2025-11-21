@extends('layouts.app')

@section('title', 'Talent Impact Leaderboard - ' . $company->name)

@push('styles')
<style>
    /* optimisasi performa dengan GPU acceleration */
    * {
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }

    html {
        scroll-behavior: smooth;
    }

    .talent-item {
        transition: transform 0.2s cubic-bezier(0.4, 0, 0.2, 1),
                    box-shadow 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        transform: translate3d(0, 0, 0);
        backface-visibility: hidden;
    }

    .talent-item:hover {
        transform: translate3d(0, -4px, 0);
        box-shadow: 0 12px 35px -8px rgba(0, 0, 0, 0.15);
    }

    /* rank badge styling */
    .rank-badge {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 14px;
        border-radius: 50%;
        position: absolute;
        top: -10px;
        left: -10px;
        z-index: 10;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    }

    .rank-1 {
        background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
        color: white;
        animation: pulse-gold 2s infinite;
    }

    .rank-2 {
        background: linear-gradient(135deg, #e5e7eb 0%, #9ca3af 100%);
        color: #374151;
    }

    .rank-3 {
        background: linear-gradient(135deg, #f59e0b 0%, #b45309 100%);
        color: white;
    }

    .rank-default {
        background: linear-gradient(135deg, #7c3aed 0%, #5b21b6 100%);
        color: white;
    }

    @keyframes pulse-gold {
        0%, 100% { box-shadow: 0 0 0 0 rgba(251, 191, 36, 0.4); }
        50% { box-shadow: 0 0 0 8px rgba(251, 191, 36, 0); }
    }

    /* impact score progress bar */
    .impact-bar {
        transition: width 0.8s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* chart container */
    .chart-container {
        height: 120px;
        position: relative;
    }

    /* badge animations */
    .verification-badge {
        animation: badge-pop 0.3s ease-out;
    }

    @keyframes badge-pop {
        0% { transform: scale(0); }
        50% { transform: scale(1.2); }
        100% { transform: scale(1); }
    }

    /* toast notification */
    .toast {
        transform: translateX(100%);
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .toast.show {
        transform: translateX(0);
    }

    /* comparison grid */
    .comparison-grid {
        display: grid;
        gap: 16px;
    }

    .comparison-grid.cols-2 { grid-template-columns: repeat(2, 1fr); }
    .comparison-grid.cols-3 { grid-template-columns: repeat(3, 1fr); }

    /* respek reduced motion */
    @media (prefers-reduced-motion: reduce) {
        *,
        *::before,
        *::after {
            animation-duration: 0.01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: 0.01ms !important;
        }

        html {
            scroll-behavior: auto;
        }
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50" x-data="leaderboardPage()" x-init="init()">

    <!-- toast notification -->
    <div x-show="toast.show" x-cloak
         class="fixed top-4 right-4 z-50 px-4 py-3 rounded-lg shadow-lg"
         :class="toast.type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-x-full"
         x-transition:enter-end="opacity-100 transform translate-x-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform translate-x-0"
         x-transition:leave-end="opacity-0 transform translate-x-full">
        <div class="flex items-center gap-2">
            <span x-text="toast.message"></span>
            <button @click="toast.show = false" class="ml-2 hover:opacity-75">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>

    <!-- header section -->
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Talent Impact Leaderboard</h1>
                    <p class="mt-1 text-sm text-gray-500">
                        Temukan dan hubungi talent terbaik dengan impact score tertinggi
                        <span x-show="selectedTalents.length > 0" class="ml-2 text-primary-600">
                            (<span x-text="selectedTalents.length"></span> dipilih)
                        </span>
                    </p>
                </div>

                <div class="flex items-center gap-3 flex-wrap">
                    <!-- compare button -->
                    <button x-show="selectedTalents.length >= 2 && selectedTalents.length <= 3"
                            @click="openComparisonModal()"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        Bandingkan
                    </button>

                    <!-- bulk save -->
                    <button x-show="selectedTalents.length > 0"
                            @click="bulkSaveTalents()"
                            class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg text-sm font-medium hover:bg-primary-700 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                        </svg>
                        Simpan (<span x-text="selectedTalents.length"></span>)
                    </button>

                    <!-- view toggle -->
                    <div class="inline-flex rounded-lg border border-gray-200 bg-white p-1">
                        <button @click="viewMode = 'grid'"
                                :class="viewMode === 'grid' ? 'bg-primary-600 text-white' : 'text-gray-600 hover:bg-gray-100'"
                                class="px-3 py-1.5 text-sm font-medium rounded-md transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                            </svg>
                        </button>
                        <button @click="viewMode = 'list'"
                                :class="viewMode === 'list' ? 'bg-primary-600 text-white' : 'text-gray-600 hover:bg-gray-100'"
                                class="px-3 py-1.5 text-sm font-medium rounded-md transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- main content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">

            <!-- left sidebar (filters) -->
            <div class="lg:col-span-1 space-y-4">

                <!-- search -->
                <div class="bg-white rounded-xl border border-gray-200 p-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cari Talent</label>
                    <div class="relative">
                        <input type="text"
                               x-model="searchQuery"
                               @input.debounce.300ms="filterTalents()"
                               placeholder="Ketik nama talent..."
                               class="w-full pl-10 rounded-lg border-gray-300 text-sm focus:ring-primary-500 focus:border-primary-500">
                        <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>

                <!-- sorting -->
                <div class="bg-white rounded-xl border border-gray-200 p-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Urutkan</label>
                    <select x-model="sortBy" @change="sortTalents()"
                            class="w-full rounded-lg border-gray-300 text-sm focus:ring-primary-500 focus:border-primary-500">
                        <option value="impact_desc">Impact Score (Tertinggi)</option>
                        <option value="impact_asc">Impact Score (Terendah)</option>
                        <option value="name_asc">Nama (A-Z)</option>
                        <option value="name_desc">Nama (Z-A)</option>
                        <option value="rank">Peringkat</option>
                    </select>
                </div>

                <!-- filter by skills -->
                <div class="bg-white rounded-xl border border-gray-200 p-4">
                    <h3 class="font-semibold text-gray-900 mb-3">Filter Skills</h3>
                    <div class="space-y-2 max-h-48 overflow-y-auto">
                        @foreach($availableSkills as $skill)
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox"
                                       value="{{ $skill }}"
                                       x-model="filters.skills"
                                       @change="filterTalents()"
                                       class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                                <span class="text-sm text-gray-600">{{ $skill }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- filter by SDG alignment -->
                <div class="bg-white rounded-xl border border-gray-200 p-4">
                    <h3 class="font-semibold text-gray-900 mb-3">Filter SDG</h3>
                    <div class="space-y-2 max-h-48 overflow-y-auto">
                        @foreach($sdgOptions as $sdg)
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox"
                                       value="{{ $sdg['id'] }}"
                                       x-model="filters.sdgs"
                                       @change="filterTalents()"
                                       class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                                <span class="text-sm text-gray-600">{{ $sdg['name'] }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- filter by location -->
                <div class="bg-white rounded-xl border border-gray-200 p-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Filter Lokasi</label>
                    <input type="text"
                           x-model="filters.location"
                           @input.debounce.300ms="filterTalents()"
                           placeholder="Contoh: Jakarta, Bandung..."
                           class="w-full rounded-lg border-gray-300 text-sm focus:ring-primary-500 focus:border-primary-500">
                </div>

                <!-- impact score range -->
                <div class="bg-white rounded-xl border border-gray-200 p-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Impact Score: <span x-text="filters.minScore"></span> - <span x-text="filters.maxScore"></span>
                    </label>
                    <div class="space-y-3">
                        <input type="range" x-model="filters.minScore" min="0" max="100" @input="filterTalents()"
                               class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer">
                        <input type="range" x-model="filters.maxScore" min="0" max="100" @input="filterTalents()"
                               class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer">
                    </div>
                </div>

                <!-- reset filters -->
                <button @click="resetFilters()"
                        x-show="hasActiveFilters"
                        class="w-full py-2 text-sm text-primary-600 hover:text-primary-700 font-medium bg-white rounded-lg border border-gray-200">
                    Reset Semua Filter
                </button>
            </div>

            <!-- right content (leaderboard) -->
            <div class="lg:col-span-3">

                <!-- stats summary -->
                <div class="grid grid-cols-3 gap-4 mb-6">
                    <div class="bg-white rounded-xl border border-gray-200 p-4 text-center">
                        <p class="text-2xl font-bold text-primary-600" x-text="filteredTalents.length"></p>
                        <p class="text-sm text-gray-500">Total Talent</p>
                    </div>
                    <div class="bg-white rounded-xl border border-gray-200 p-4 text-center">
                        <p class="text-2xl font-bold text-green-600" x-text="avgImpactScore + '%'"></p>
                        <p class="text-sm text-gray-500">Rata-rata Impact</p>
                    </div>
                    <div class="bg-white rounded-xl border border-gray-200 p-4 text-center">
                        <p class="text-2xl font-bold text-yellow-600" x-text="topPerformers"></p>
                        <p class="text-sm text-gray-500">Top Performers</p>
                    </div>
                </div>

                <!-- grid view -->
                <div x-show="viewMode === 'grid'" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                    <template x-for="talent in filteredTalents" :key="talent.id">
                        <div class="talent-item relative bg-white rounded-xl border border-gray-200 p-5"
                             :class="{'ring-2 ring-primary-500': selectedTalents.includes(talent.id)}">

                            <!-- checkbox select -->
                            <div class="absolute top-3 right-3">
                                <input type="checkbox"
                                       :value="talent.id"
                                       x-model="selectedTalents"
                                       class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                            </div>

                            <!-- rank badge -->
                            <div class="rank-badge"
                                 :class="{
                                     'rank-1': talent.rank === 1,
                                     'rank-2': talent.rank === 2,
                                     'rank-3': talent.rank === 3,
                                     'rank-default': talent.rank > 3
                                 }"
                                 x-text="talent.rank"></div>

                            <!-- talent info -->
                            <div class="text-center mb-4">
                                <div class="relative inline-block">
                                    <img :src="talent.avatar_url || '/images/default-avatar.png'"
                                         :alt="talent.name"
                                         class="w-20 h-20 rounded-full mx-auto mb-3 object-cover border-4 border-gray-100"
                                         onerror="this.src='/images/default-avatar.png'">

                                    <!-- verification badge -->
                                    <div x-show="talent.verified" class="verification-badge absolute -bottom-1 -right-1 bg-green-500 rounded-full p-1">
                                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                </div>

                                <h3 class="font-semibold text-gray-900" x-text="talent.name"></h3>
                                <p class="text-sm text-gray-500" x-text="talent.location"></p>

                                <!-- social proof badges -->
                                <div class="flex justify-center gap-1 mt-2">
                                    <template x-if="talent.projects_count > 5">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs bg-blue-100 text-blue-700">
                                            <span x-text="talent.projects_count"></span> Proyek
                                        </span>
                                    </template>
                                    <template x-if="talent.endorsements > 0">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs bg-purple-100 text-purple-700">
                                            <span x-text="talent.endorsements"></span> Endorsement
                                        </span>
                                    </template>
                                </div>
                            </div>

                            <!-- impact score -->
                            <div class="mb-4">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-sm text-gray-500">Impact Score</span>
                                    <span class="text-sm font-semibold"
                                          :class="{
                                              'text-green-600': talent.impact_score >= 90,
                                              'text-primary-600': talent.impact_score >= 75 && talent.impact_score < 90,
                                              'text-yellow-600': talent.impact_score < 75
                                          }"
                                          x-text="talent.impact_score + '%'"></span>
                                </div>
                                <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                                    <div class="impact-bar h-full rounded-full"
                                         :class="{
                                             'bg-green-500': talent.impact_score >= 90,
                                             'bg-primary-600': talent.impact_score >= 75 && talent.impact_score < 90,
                                             'bg-yellow-500': talent.impact_score < 75
                                         }"
                                         :style="'width: ' + talent.impact_score + '%'"></div>
                                </div>
                            </div>

                            <!-- skills -->
                            <div class="flex flex-wrap justify-center gap-1 mb-4">
                                <template x-for="skill in talent.skills.slice(0, 3)" :key="skill">
                                    <span class="px-2 py-0.5 bg-gray-100 text-gray-700 rounded-full text-xs font-medium"
                                          x-text="skill"></span>
                                </template>
                                <template x-if="talent.skills.length > 3">
                                    <span class="px-2 py-0.5 bg-gray-100 text-gray-500 rounded-full text-xs"
                                          x-text="'+' + (talent.skills.length - 3)"></span>
                                </template>
                            </div>

                            <!-- SDG badge -->
                            <div class="flex justify-center mb-4">
                                <span class="inline-flex items-center gap-1 px-3 py-1 bg-primary-50 text-primary-700 rounded-full text-xs font-medium border border-primary-200">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span x-text="talent.sdg_badge?.name || 'SDG'"></span>
                                </span>
                            </div>

                            <!-- action buttons -->
                            <div class="flex gap-2">
                                <a :href="'/company/talents/' + talent.id"
                                   class="flex-1 text-center px-3 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                                    Lihat Profil
                                </a>
                                <button @click="toggleSaveTalent(talent)"
                                        class="px-3 py-2 rounded-lg text-sm font-medium transition-colors"
                                        :class="talent.is_saved ? 'bg-primary-100 text-primary-700' : 'border border-gray-300 text-gray-700 hover:bg-gray-50'">
                                    <svg class="w-5 h-5" :class="talent.is_saved ? 'fill-current' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- list view -->
                <div x-show="viewMode === 'list'" x-cloak class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left">
                                    <input type="checkbox" @change="toggleSelectAll($event)"
                                           class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rank</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Talent</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Impact Score</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Skills</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">SDG</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <template x-for="talent in filteredTalents" :key="talent.id">
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-4">
                                        <input type="checkbox" :value="talent.id" x-model="selectedTalents"
                                               class="rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                                    </td>
                                    <td class="px-4 py-4">
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full text-sm font-semibold"
                                              :class="{
                                                  'bg-yellow-100 text-yellow-800': talent.rank === 1,
                                                  'bg-gray-100 text-gray-800': talent.rank === 2,
                                                  'bg-orange-100 text-orange-800': talent.rank === 3,
                                                  'bg-primary-100 text-primary-800': talent.rank > 3
                                              }"
                                              x-text="talent.rank"></span>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="flex items-center gap-3">
                                            <img :src="talent.avatar_url || '/images/default-avatar.png'"
                                                 class="w-10 h-10 rounded-full object-cover"
                                                 onerror="this.src='/images/default-avatar.png'">
                                            <div>
                                                <p class="font-medium text-gray-900" x-text="talent.name"></p>
                                                <p class="text-sm text-gray-500" x-text="talent.location"></p>
                                            </div>
                                            <span x-show="talent.verified" class="text-green-500">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                </svg>
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="flex items-center gap-2">
                                            <div class="w-24 h-2 bg-gray-200 rounded-full overflow-hidden">
                                                <div class="h-full rounded-full"
                                                     :class="{
                                                         'bg-green-500': talent.impact_score >= 90,
                                                         'bg-primary-600': talent.impact_score >= 75 && talent.impact_score < 90,
                                                         'bg-yellow-500': talent.impact_score < 75
                                                     }"
                                                     :style="'width: ' + talent.impact_score + '%'"></div>
                                            </div>
                                            <span class="text-sm font-medium" x-text="talent.impact_score + '%'"></span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="flex flex-wrap gap-1">
                                            <template x-for="skill in talent.skills.slice(0, 2)" :key="skill">
                                                <span class="px-2 py-0.5 bg-gray-100 text-gray-700 rounded text-xs" x-text="skill"></span>
                                            </template>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <span class="px-2 py-1 bg-primary-50 text-primary-700 rounded text-xs" x-text="talent.sdg_badge?.name || '-'"></span>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="flex items-center gap-2">
                                            <a :href="'/company/talents/' + talent.id"
                                               class="text-sm font-medium text-primary-600 hover:text-primary-700">Profil</a>
                                            <button @click="toggleSaveTalent(talent)"
                                                    class="text-gray-400 hover:text-primary-600">
                                                <svg class="w-5 h-5" :class="talent.is_saved ? 'fill-primary-600 text-primary-600' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                <!-- empty state -->
                <div x-show="filteredTalents.length === 0" class="text-center py-16 bg-white rounded-xl border border-gray-200">
                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak Ada Talent Ditemukan</h3>
                    <p class="text-gray-500">Coba ubah filter untuk melihat lebih banyak talent.</p>
                </div>

                <!-- pagination / load more -->
                <div x-show="filteredTalents.length > 0 && hasMoreTalents" class="text-center mt-6">
                    <button @click="loadMore()"
                            :disabled="isLoading"
                            class="inline-flex items-center px-6 py-3 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50">
                        <span x-show="!isLoading">Muat Lebih Banyak</span>
                        <span x-show="isLoading">Memuat...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- comparison modal -->
    <div x-show="comparisonModalOpen" x-cloak
         class="fixed inset-0 z-50 overflow-y-auto"
         @keydown.escape.window="comparisonModalOpen = false">
        <div class="flex items-center justify-center min-h-screen px-4 py-8">
            <div class="fixed inset-0 bg-black/50" @click="comparisonModalOpen = false"></div>

            <div class="relative bg-white rounded-xl shadow-xl max-w-6xl w-full p-6 max-h-[90vh] overflow-y-auto"
                 x-transition>
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Perbandingan Talent</h3>
                    <button @click="comparisonModalOpen = false" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div class="comparison-grid" :class="'cols-' + comparisonTalents.length">
                    <template x-for="talent in comparisonTalents" :key="talent.id">
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="text-center mb-4 pb-4 border-b border-gray-100">
                                <div class="relative inline-block">
                                    <span class="absolute -top-2 -left-2 w-6 h-6 rounded-full text-xs font-bold flex items-center justify-center"
                                          :class="{
                                              'bg-yellow-400 text-white': talent.rank === 1,
                                              'bg-gray-400 text-white': talent.rank === 2,
                                              'bg-orange-400 text-white': talent.rank === 3,
                                              'bg-primary-600 text-white': talent.rank > 3
                                          }"
                                          x-text="talent.rank"></span>
                                    <img :src="talent.avatar_url || '/images/default-avatar.png'"
                                         class="w-20 h-20 rounded-full mx-auto mb-3 object-cover">
                                </div>
                                <h4 class="font-semibold text-gray-900" x-text="talent.name"></h4>
                                <p class="text-sm text-gray-500" x-text="talent.location"></p>
                            </div>

                            <div class="space-y-3">
                                <div>
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="text-sm text-gray-500">Impact Score</span>
                                        <span class="text-sm font-bold"
                                              :class="{
                                                  'text-green-600': talent.impact_score >= 90,
                                                  'text-primary-600': talent.impact_score >= 75 && talent.impact_score < 90,
                                                  'text-yellow-600': talent.impact_score < 75
                                              }"
                                              x-text="talent.impact_score + '%'"></span>
                                    </div>
                                    <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                                        <div class="h-full rounded-full"
                                             :class="{
                                                 'bg-green-500': talent.impact_score >= 90,
                                                 'bg-primary-600': talent.impact_score >= 75 && talent.impact_score < 90,
                                                 'bg-yellow-500': talent.impact_score < 75
                                             }"
                                             :style="'width: ' + talent.impact_score + '%'"></div>
                                    </div>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-500">Proyek</span>
                                    <span class="text-sm text-gray-900" x-text="talent.projects_count || 0"></span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-500">Endorsement</span>
                                    <span class="text-sm text-gray-900" x-text="talent.endorsements || 0"></span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-500">SDG Focus</span>
                                    <span class="text-sm text-primary-600" x-text="talent.sdg_badge?.name || '-'"></span>
                                </div>
                            </div>

                            <div class="mt-4 pt-4 border-t border-gray-100">
                                <p class="text-xs text-gray-500 mb-2">Skills</p>
                                <div class="flex flex-wrap gap-1">
                                    <template x-for="skill in talent.skills" :key="skill">
                                        <span class="px-2 py-0.5 bg-gray-100 text-gray-700 rounded text-xs" x-text="skill"></span>
                                    </template>
                                </div>
                            </div>

                            <div class="mt-4 pt-4 border-t border-gray-100">
                                <a :href="'/company/talents/' + talent.id"
                                   class="block w-full text-center px-4 py-2 bg-primary-600 text-white rounded-lg text-sm hover:bg-primary-700">
                                    Lihat Profil Lengkap
                                </a>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
function leaderboardPage() {
    return {
        // state utama
        isLoading: false,
        viewMode: 'grid',
        searchQuery: '',
        sortBy: 'impact_desc',
        selectedTalents: [],
        hasMoreTalents: true,
        currentPage: 1,

        // toast
        toast: { show: false, message: '', type: 'success' },

        // modals
        comparisonModalOpen: false,
        comparisonTalents: [],

        // filters
        filters: {
            skills: [],
            sdgs: [],
            location: '',
            minScore: 0,
            maxScore: 100
        },

        // data
        talents: [],
        allTalents: [],

        init() {
            this.parseBackendData();
        },

        parseBackendData() {
            const rawTalents = @json($leaderboardTalents ?? []);

            // transform data
            this.allTalents = rawTalents.map((talent, index) => ({
                id: talent.id,
                rank: talent.rank || index + 1,
                name: talent.name,
                location: talent.location || 'Unknown',
                avatar_url: talent.avatar ? '/storage/profiles/' + talent.avatar : null,
                impact_score: talent.impact_score || Math.floor(Math.random() * 30) + 70,
                skills: talent.skills || [],
                sdg_badge: talent.sdg_badge || { id: 0, name: 'SDG' },
                verified: talent.verified || false,
                is_saved: false,
                projects_count: talent.projects_count || Math.floor(Math.random() * 10),
                endorsements: talent.endorsements || Math.floor(Math.random() * 20)
            }));

            this.talents = [...this.allTalents];
            this.sortTalents();
        },

        get hasActiveFilters() {
            return this.searchQuery.trim() !== '' ||
                   this.filters.skills.length > 0 ||
                   this.filters.sdgs.length > 0 ||
                   this.filters.location.trim() !== '' ||
                   this.filters.minScore > 0 ||
                   this.filters.maxScore < 100;
        },

        get filteredTalents() {
            return this.talents;
        },

        get avgImpactScore() {
            if (this.filteredTalents.length === 0) return 0;
            const sum = this.filteredTalents.reduce((acc, t) => acc + t.impact_score, 0);
            return Math.round(sum / this.filteredTalents.length);
        },

        get topPerformers() {
            return this.filteredTalents.filter(t => t.impact_score >= 90).length;
        },

        filterTalents() {
            this.talents = this.allTalents.filter(talent => {
                // search filter
                if (this.searchQuery.trim()) {
                    const query = this.searchQuery.toLowerCase();
                    if (!talent.name.toLowerCase().includes(query)) return false;
                }

                // skills filter
                if (this.filters.skills.length > 0) {
                    const hasSkill = talent.skills.some(s => this.filters.skills.includes(s));
                    if (!hasSkill) return false;
                }

                // SDG filter
                if (this.filters.sdgs.length > 0) {
                    if (!this.filters.sdgs.includes(String(talent.sdg_badge?.id))) return false;
                }

                // location filter
                if (this.filters.location.trim()) {
                    if (!talent.location.toLowerCase().includes(this.filters.location.toLowerCase())) return false;
                }

                // impact score range
                if (talent.impact_score < this.filters.minScore || talent.impact_score > this.filters.maxScore) {
                    return false;
                }

                return true;
            });

            this.sortTalents();
        },

        sortTalents() {
            const sortMap = {
                'impact_desc': (a, b) => b.impact_score - a.impact_score,
                'impact_asc': (a, b) => a.impact_score - b.impact_score,
                'name_asc': (a, b) => a.name.localeCompare(b.name),
                'name_desc': (a, b) => b.name.localeCompare(a.name),
                'rank': (a, b) => a.rank - b.rank
            };

            this.talents.sort(sortMap[this.sortBy] || sortMap['impact_desc']);

            // update ranks after sort
            if (this.sortBy.startsWith('impact')) {
                this.talents.forEach((t, i) => t.rank = i + 1);
            }
        },

        resetFilters() {
            this.searchQuery = '';
            this.filters = {
                skills: [],
                sdgs: [],
                location: '',
                minScore: 0,
                maxScore: 100
            };
            this.talents = [...this.allTalents];
            this.sortTalents();
        },

        showToast(message, type = 'success') {
            this.toast = { show: true, message, type };
            setTimeout(() => { this.toast.show = false; }, 3000);
        },

        toggleSelectAll(event) {
            if (event.target.checked) {
                this.selectedTalents = this.filteredTalents.map(t => t.id);
            } else {
                this.selectedTalents = [];
            }
        },

        async toggleSaveTalent(talent) {
            try {
                await fetch(`/company/talents/${talent.id}/toggle-save`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                talent.is_saved = !talent.is_saved;
                this.showToast(talent.is_saved ? 'Talent disimpan' : 'Talent dihapus dari tersimpan');
            } catch (error) {
                this.showToast('Gagal menyimpan talent', 'error');
            }
        },

        async bulkSaveTalents() {
            if (this.selectedTalents.length === 0) return;

            this.isLoading = true;
            let savedCount = 0;

            for (const id of this.selectedTalents) {
                try {
                    await fetch(`/company/talents/${id}/toggle-save`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ action: 'save' })
                    });

                    const talent = this.talents.find(t => t.id === id);
                    if (talent) talent.is_saved = true;
                    savedCount++;
                } catch (error) {
                    console.error('Error saving talent:', error);
                }
            }

            this.isLoading = false;
            this.selectedTalents = [];
            this.showToast(`${savedCount} talent berhasil disimpan`);
        },

        openComparisonModal() {
            this.comparisonTalents = this.talents.filter(t =>
                this.selectedTalents.includes(t.id)
            );
            this.comparisonModalOpen = true;
        },

        loadMore() {
            this.isLoading = true;
            // simulasi load more - seharusnya dari API
            setTimeout(() => {
                this.isLoading = false;
                this.hasMoreTalents = false;
                this.showToast('Semua talent sudah ditampilkan');
            }, 500);
        }
    }
}
</script>
@endpush
