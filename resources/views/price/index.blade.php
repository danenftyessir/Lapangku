@extends('layouts.app')

@section('title', 'Pricing - ' . config('app.name'))

@push('styles')
<style>
    /* smooth scroll behavior */
    html {
        scroll-behavior: smooth;
    }

    /* pricing cards */
    .pricing-card {
        background: white;
        border: 2px solid #e5e7eb;
        border-radius: 1rem;
        padding: 2.5rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        height: 100%;
        display: flex;
        flex-direction: column;
        position: relative;
        overflow: hidden;
    }

    .pricing-card:hover {
        border-color: #3B82F6;
        box-shadow: 0 10px 30px rgba(59, 130, 246, 0.2);
        transform: translateY(-5px);
    }

    .pricing-card.popular {
        border-color: #3B82F6;
        border-width: 3px;
        box-shadow: 0 10px 30px rgba(59, 130, 246, 0.15);
    }

    .pricing-card.popular::before {
        content: 'PALING POPULER';
        position: absolute;
        top: 20px;
        right: -35px;
        background: linear-gradient(135deg, #3B82F6, #2563EB);
        color: white;
        padding: 5px 45px;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.5px;
        transform: rotate(45deg);
        box-shadow: 0 4px 10px rgba(59, 130, 246, 0.3);
    }

    /* price styling */
    .price {
        font-size: 3rem;
        font-weight: 900;
        color: #1F2937;
        line-height: 1;
    }

    .price-period {
        font-size: 1rem;
        color: #6B7280;
        font-weight: 500;
    }

    /* features list */
    .feature-item {
        display: flex;
        align-items: flex-start;
        padding: 0.75rem 0;
    }

    .feature-icon {
        flex-shrink: 0;
        width: 20px;
        height: 20px;
        margin-right: 12px;
        margin-top: 2px;
    }

    /* buttons */
    .btn-primary {
        background: linear-gradient(135deg, #3B82F6, #2563EB);
        color: white;
        border: none;
        padding: 1rem 2rem;
        border-radius: 0.5rem;
        font-weight: 700;
        font-size: 1rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        width: 100%;
        text-align: center;
        display: inline-block;
        text-decoration: none;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #2563EB, #1D4ED8);
        box-shadow: 0 10px 25px rgba(59, 130, 246, 0.4);
        transform: translateY(-2px);
    }

    .btn-outline {
        background: white;
        color: #3B82F6;
        border: 2px solid #3B82F6;
        padding: 1rem 2rem;
        border-radius: 0.5rem;
        font-weight: 700;
        font-size: 1rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        width: 100%;
        text-align: center;
        display: inline-block;
        text-decoration: none;
    }

    .btn-outline:hover {
        background: #3B82F6;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(59, 130, 246, 0.3);
    }

    /* benefit cards */
    .benefit-card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 1rem;
        padding: 2rem;
        text-align: center;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .benefit-card:hover {
        border-color: #3B82F6;
        box-shadow: 0 8px 20px rgba(59, 130, 246, 0.15);
        transform: translateY(-5px);
    }

    .benefit-icon {
        width: 64px;
        height: 64px;
        background: linear-gradient(135deg, #3B82F6, #2563EB);
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.5rem;
    }

    /* animations */
    .fade-in-up {
        opacity: 0;
        animation: fadeInUp 0.6s cubic-bezier(0.4, 0, 0.2, 1) forwards;
    }

    .pricing-card:nth-child(1) {
        animation-delay: 0.1s;
    }

    .pricing-card:nth-child(2) {
        animation-delay: 0.2s;
    }

    .pricing-card:nth-child(3) {
        animation-delay: 0.3s;
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

    /* section header */
    .section-header {
        opacity: 0;
        animation: fadeInUp 0.8s cubic-bezier(0.4, 0, 0.2, 1) 0.2s forwards;
    }

    /* comparison table styling */
    .comparison-badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .badge-free {
        background: #DBEAFE;
        color: #1E40AF;
    }

    .badge-pro {
        background: linear-gradient(135deg, #3B82F6, #2563EB);
        color: white;
    }

    .badge-enterprise {
        background: #F3E8FF;
        color: #6B21A8;
    }

    /* accessibility */
    @media (prefers-reduced-motion: reduce) {
        *,
        *::before,
        *::after {
            animation-duration: 0.01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: 0.01ms !important;
            scroll-behavior: auto !important;
        }

        .pricing-card:hover,
        .benefit-card:hover,
        .btn-primary:hover,
        .btn-outline:hover {
            transform: none;
        }
    }

    /* responsive */
    @media (max-width: 768px) {
        .price {
            font-size: 2.5rem;
        }

        .pricing-card {
            margin-bottom: 2rem;
        }
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-white">

    {{-- hero section --}}
    <section class="relative h-screen min-h-[600px] overflow-hidden">
        {{-- background image --}}
        <div class="absolute inset-0">
            <img src="{{ asset('price.jpg') }}"
                 alt="Pricing Lapangku"
                 class="w-full h-full object-cover">
            {{-- overlay gradient --}}
            <div class="absolute inset-0 bg-gradient-to-b from-black/40 via-black/50 to-black/70"></div>
        </div>

        {{-- content --}}
        <div class="relative h-full">
            <div class="container mx-auto px-6 h-full flex items-end pb-20">
                <div class="max-w-4xl">
                    <h1 class="text-6xl md:text-7xl lg:text-8xl font-black leading-tight tracking-tight drop-shadow-2xl mb-4" style="font-family: 'Montserrat', 'Poppins', 'Space Grotesk', sans-serif; font-weight: 900; color: #FFFFFF; text-shadow: 0 4px 20px rgba(0, 0, 0, 0.8);">
                        Pricing
                    </h1>
                    <p class="text-xl md:text-2xl font-medium drop-shadow-lg" style="color: #FFFFFF;">
                        Pilih Paket Terbaik untuk Kebutuhan Institusi Anda
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- pricing section --}}
    <section class="py-24" style="background-color: #ffffff; background-image: radial-gradient(at 15% 15%, rgba(99, 102, 241, 0.15) 0px, transparent 50%), radial-gradient(at 85% 20%, rgba(236, 72, 153, 0.12) 0px, transparent 50%), radial-gradient(at 25% 75%, rgba(59, 130, 246, 0.15) 0px, transparent 50%), radial-gradient(at 75% 85%, rgba(168, 85, 247, 0.12) 0px, transparent 50%), radial-gradient(at 50% 50%, rgba(147, 51, 234, 0.1) 0px, transparent 50%);">
        <div class="container mx-auto px-6">

            {{-- section header --}}
            <div class="text-center mb-16 section-header">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">Paket Kerja Sama</h2>
                <div class="w-20 h-1 bg-blue-600 mx-auto mb-6"></div>
                <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                    Kami menawarkan berbagai paket yang fleksibel untuk memenuhi kebutuhan institusi Anda, dari yang baru memulai hingga enterprise dengan volume tinggi.
                </p>
            </div>

            {{-- pricing cards --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-7xl mx-auto mb-20">
                @foreach($pricingPlans as $plan)
                <div class="pricing-card fade-in-up {{ $plan['isPopular'] ? 'popular' : '' }}">

                    {{-- header --}}
                    <div class="mb-6">
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $plan['name'] }}</h3>
                        <p class="text-gray-600 text-sm leading-relaxed">{{ $plan['description'] }}</p>
                    </div>

                    {{-- price --}}
                    <div class="mb-8">
                        <div class="price">{{ $plan['price'] }}</div>
                        <div class="price-period">{{ $plan['period'] }}</div>
                    </div>

                    {{-- features --}}
                    <div class="flex-grow mb-8">
                        <ul class="space-y-2">
                            @foreach($plan['features'] as $feature)
                            <li class="feature-item">
                                <svg class="feature-icon text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span class="text-gray-700 text-sm">{{ $feature }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>

                    {{-- button --}}
                    <a href="{{ route('contact') }}" class="{{ $plan['buttonClass'] }}">
                        {{ $plan['buttonText'] }}
                    </a>
                </div>
                @endforeach
            </div>

            {{-- comparison note --}}
            <div class="text-center max-w-3xl mx-auto mb-16">
                <div class="bg-blue-50 border-l-4 border-blue-600 p-6 rounded-r-lg">
                    <div class="flex items-start">
                        <svg class="w-6 h-6 text-blue-600 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div class="text-left">
                            <p class="text-blue-900 font-semibold mb-2">Tidak yakin paket mana yang tepat?</p>
                            <p class="text-blue-800 text-sm">Tim kami siap membantu Anda memilih paket yang sesuai dengan kebutuhan dan anggaran institusi. Hubungi kami untuk konsultasi gratis!</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- additional benefits section --}}
    <section class="py-24 bg-gray-50">
        <div class="container mx-auto px-6">

            {{-- section header --}}
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">Keuntungan Bergabung dengan Lapangku</h2>
                <div class="w-20 h-1 bg-blue-600 mx-auto mb-6"></div>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Semua paket mendapatkan akses ke fitur-fitur unggulan kami yang dirancang untuk memaksimalkan dampak program KKN.
                </p>
            </div>

            {{-- benefits grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 max-w-7xl mx-auto">
                @foreach($additionalBenefits as $benefit)
                <div class="benefit-card">
                    <div class="benefit-icon">
                        @if($benefit['icon'] === 'shield-check')
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        @elseif($benefit['icon'] === 'users')
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        @elseif($benefit['icon'] === 'chart-bar')
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        @elseif($benefit['icon'] === 'book-open')
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                        @endif
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">{{ $benefit['title'] }}</h3>
                    <p class="text-gray-600 text-sm leading-relaxed">{{ $benefit['description'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>
</div>
@endsection
