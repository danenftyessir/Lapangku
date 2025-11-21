@extends('layouts.app')

@section('title', 'Talent Impact Leaderboard - ' . $company->name)

@push('styles')
<style>
    /* optimisasi performa dengan GPU acceleration */
    .talent-item {
        transition: transform 0.2s cubic-bezier(0.4, 0, 0.2, 1),
                    box-shadow 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        transform: translateZ(0);
        backface-visibility: hidden;
    }

    .talent-item:hover {
        transform: translateY(-3px) translateZ(0);
        box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.1);
    }

    /* rank badge styling */
    .rank-badge {
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 14px;
        border-radius: 50%;
        position: absolute;
        top: -8px;
        left: -8px;
        z-index: 10;
    }

    .rank-1 { background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%); color: white; }
    .rank-2 { background: linear-gradient(135deg, #9ca3af 0%, #6b7280 100%); color: white; }
    .rank-3 { background: linear-gradient(135deg, #d97706 0%, #b45309 100%); color: white; }
    .rank-default { background-color: #7c3aed; color: white; }

    /* impact score progress bar */
    .impact-bar {
        transition: width 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* sidebar filter animations */
    .filter-section {
        transition: max-height 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* hero section styling */
    .hero-section {
        background: linear-gradient(135deg, rgba(0, 0, 0, 0.5) 0%, rgba(0, 0, 0, 0.7) 100%);
    }

    /* skill tags */
    .skill-tag {
        transition: background-color 0.15s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .skill-tag:hover {
        background-color: #e5e7eb;
    }

    /* SDG badge */
    .sdg-badge {
        transition: transform 0.15s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .sdg-badge:hover {
        transform: scale(1.02);
    }

    /* checkbox styling */
    .filter-checkbox {
        transition: all 0.15s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* respek reduced motion untuk aksesibilitas */
    @media (prefers-reduced-motion: reduce) {
        .talent-item,
        .impact-bar,
        .filter-section,
        .skill-tag,
        .sdg-badge {
            transition: none;
        }
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50" x-data="leaderboardPage()">

    <!-- hero section -->
    <div class="relative h-56 overflow-hidden">
        <img src="{{ asset('images/team-collaboration.jpg') }}"
             alt="Talent Leaderboard"
             class="absolute inset-0 w-full h-full object-cover"
             onerror="this.src='{{ asset('images/hero-bg.jpg') }}'">
        <div class="hero-section absolute inset-0 flex flex-col items-center justify-center text-white px-4">
            <h1 class="text-3xl md:text-4xl font-bold mb-3 text-center">Talent Impact Leaderboard</h1>
            <p class="text-center text-white/90 max-w-2xl">
                Discover and engage with top-performing talents driving global change.
            </p>
        </div>
    </div>

    <!-- main content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

            <!-- left sidebar (filters) -->
            <div class="lg:col-span-1 space-y-6">

                <!-- filter by skills -->
                <div class="bg-white rounded-xl border border-gray-200 p-5">
                    <h3 class="font-semibold text-gray-900 mb-4">Filter By Skills</h3>

                    <div x-data="{ expanded: false }">
                        <button @click="expanded = !expanded"
                                class="flex items-center justify-between w-full text-sm text-gray-600 mb-3">
                            <span>Select Skills</span>
                            <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': expanded }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div x-show="expanded" x-collapse class="space-y-2">
                            @foreach($availableSkills as $skill)
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox"
                                           value="{{ $skill }}"
                                           x-model="filters.skills"
                                           class="filter-checkbox rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                                    <span class="text-sm text-gray-600">{{ $skill }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- filter by SDG alignment -->
                <div class="bg-white rounded-xl border border-gray-200 p-5">
                    <h3 class="font-semibold text-gray-900 mb-4">Filter By SDG Alignment</h3>

                    <div x-data="{ expanded: false }">
                        <button @click="expanded = !expanded"
                                class="flex items-center justify-between w-full text-sm text-gray-600 mb-3">
                            <span>Select SDGs</span>
                            <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': expanded }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div x-show="expanded" x-collapse class="space-y-2 max-h-48 overflow-y-auto">
                            @foreach($sdgOptions as $sdg)
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox"
                                           value="{{ $sdg['id'] }}"
                                           x-model="filters.sdgs"
                                           class="filter-checkbox rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                                    <span class="text-sm text-gray-600">{{ $sdg['name'] }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- filter by location -->
                <div class="bg-white rounded-xl border border-gray-200 p-5">
                    <h3 class="font-semibold text-gray-900 mb-4">Filter By Location</h3>
                    <input type="text"
                           x-model="filters.location"
                           placeholder="e.g., San Francisco, Re"
                           class="w-full rounded-lg border-gray-300 text-sm">
                </div>

                <!-- impact breakdown -->
                <div class="bg-white rounded-xl border border-gray-200 p-5">
                    <h3 class="font-semibold text-gray-900 mb-4">Impact Breakdown</h3>
                    <div class="space-y-4">
                        @foreach($impactBreakdown as $metric)
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-sm text-gray-600">{{ $metric['name'] }}</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $metric['value'] }}%</span>
                                </div>
                                <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                                    <div class="impact-bar h-full bg-primary-600 rounded-full"
                                         style="width: {{ $metric['value'] }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- reset filters -->
                <button @click="resetFilters()"
                        x-show="hasActiveFilters"
                        class="w-full py-2 text-sm text-primary-600 hover:text-primary-700 font-medium">
                    Reset All Filters
                </button>
            </div>

            <!-- right content (leaderboard) -->
            <div class="lg:col-span-3">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Top Talents</h2>

                <!-- talents grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                    <template x-for="talent in filteredTalents" :key="talent.id">
                        <div class="talent-item relative bg-white rounded-xl border border-gray-200 p-5">

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
                                <img :src="'/storage/profiles/' + talent.avatar"
                                     :alt="talent.name"
                                     class="w-20 h-20 rounded-full mx-auto mb-3 object-cover border-4 border-gray-100"
                                     onerror="this.src='/images/default-avatar.png'">
                                <h3 class="font-semibold text-gray-900" x-text="talent.name"></h3>
                                <p class="text-sm text-gray-500" x-text="talent.location"></p>
                            </div>

                            <!-- impact score -->
                            <div class="mb-4">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-sm text-gray-500">Impact Score:</span>
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
                            <div class="flex flex-wrap justify-center gap-2 mb-4">
                                <template x-for="skill in talent.skills" :key="skill">
                                    <span class="skill-tag px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-medium"
                                          x-text="skill"></span>
                                </template>
                            </div>

                            <!-- SDG badge -->
                            <div class="flex justify-center">
                                <span class="sdg-badge inline-flex items-center gap-1 px-3 py-1 bg-primary-50 text-primary-700 rounded-full text-xs font-medium border border-primary-200">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span x-text="talent.sdg_badge.name"></span>
                                </span>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- empty state -->
                <div x-show="filteredTalents.length === 0" class="text-center py-16">
                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak Ada Talent Ditemukan</h3>
                    <p class="text-gray-500">Coba ubah filter untuk melihat lebih banyak talent.</p>
                </div>

                <!-- load more -->
                <div x-show="filteredTalents.length > 0" class="text-center mt-8">
                    <button @click="loadMore()"
                            class="inline-flex items-center px-6 py-3 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-150">
                        Load More Talents
                    </button>
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
        filters: {
            skills: [],
            sdgs: [],
            location: ''
        },

        // TO DO: ambil data dari database via API
        talents: @json($leaderboardTalents),

        get hasActiveFilters() {
            return this.filters.skills.length > 0 ||
                   this.filters.sdgs.length > 0 ||
                   this.filters.location.trim() !== '';
        },

        get filteredTalents() {
            return this.talents.filter(talent => {
                // filter by skills
                if (this.filters.skills.length > 0) {
                    const hasMatchingSkill = talent.skills.some(skill =>
                        this.filters.skills.includes(skill)
                    );
                    if (!hasMatchingSkill) return false;
                }

                // filter by SDGs
                if (this.filters.sdgs.length > 0) {
                    if (!this.filters.sdgs.includes(String(talent.sdg_badge.id))) return false;
                }

                // filter by location
                if (this.filters.location.trim()) {
                    const locationMatch = talent.location.toLowerCase()
                        .includes(this.filters.location.toLowerCase());
                    if (!locationMatch) return false;
                }

                return true;
            });
        },

        resetFilters() {
            this.filters = {
                skills: [],
                sdgs: [],
                location: ''
            };
        },

        // TO DO: implementasi load more dengan pagination dari server
        loadMore() {
            // TO DO: panggil API untuk load more talents
            alert('Fitur Load More akan segera tersedia!');
        }
    }
}
</script>
@endpush
