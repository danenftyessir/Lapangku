@extends('layouts.app')

@section('title', 'Tentang Kami - KKN-Go')

@section('content')
<div class="min-h-screen bg-white">
    
    {{-- hero section --}}
    <section class="relative h-screen min-h-[600px] overflow-hidden">
        {{-- background image --}}
        <div class="absolute inset-0">
            <img src="{{ asset('mahasiswa-about.jpeg') }}" 
                 alt="Tentang Kami KKN-Go" 
                 class="w-full h-full object-cover">
            {{-- overlay gradient - lebih gelap di bawah --}}
            <div class="absolute inset-0 bg-gradient-to-b from-black/30 via-black/40 to-black/70"></div>
        </div>
        
        {{-- content - text di kiri bawah --}}
        <div class="relative h-full">
            <div class="container mx-auto px-6 h-full flex items-end pb-20">
                <div class="max-w-4xl">
                    <h1 class="text-6xl md:text-7xl lg:text-8xl font-black text-white leading-tight tracking-tight">
                        Tentang Kami
                    </h1>
                </div>
            </div>
        </div>
    </section>

    {{-- layer 4: perkenalan kkn-go --}}
    <section class="py-24 bg-gray-50">
        <div class="container mx-auto px-6">
            {{-- header dengan logo --}}
            <div class="text-center mb-16">
                <div class="flex justify-center mb-8">
                    <img src="{{ asset('kkn-go-logo.png') }}" alt="Logo KKN-Go" class="h-24 w-auto">
                </div>
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6 leading-tight">
                    Perkenalkan, KKN-Go
                </h2>
                <div class="max-w-4xl mx-auto">
                    <p class="text-lg text-gray-700 leading-relaxed mb-6 text-justify">
                        Indonesia memiliki potensi intelektual yang luar biasa dengan <span class="font-bold text-blue-600">8,3 juta mahasiswa aktif</span> di 4.500 perguruan tinggi, dimana sekitar <span class="font-bold text-blue-600">520.000 mahasiswa melaksanakan KKN</span> setiap tahun menghasilkan lebih dari 100.000 laporan penelitian. Namun, data menunjukkan bahwa <span class="font-bold text-red-600">76% hasil penelitian mahasiswa hanya berakhir sebagai dokumen arsip</span> tanpa implementasi nyata, menciptakan pemborosan sumber daya senilai <span class="font-bold text-red-600">±Rp 1,2 triliun per tahun</span>.
                    </p>
                </div>
            </div>

            {{-- 3 masalah yang diselesaikan kkn-go --}}
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-10">
                    <h3 class="text-2xl md:text-3xl font-bold text-gray-900 mb-4">
                        Tiga Masalah Krusial Yang Kami Selesaikan
                    </h3>
                    <p class="text-lg text-gray-600">
                        KKN-Go hadir untuk mentransformasi ekosistem KKN Indonesia
                    </p>
                </div>

                <div class="grid md:grid-cols-3 gap-8">
                    {{-- solusi 1 --}}
                    <div class="bg-white rounded-lg p-8 shadow-lg hover:shadow-xl transition-shadow border-t-4 border-red-500">
                        <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mb-6">
                            <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Marketplace Masalah</h3>
                        <p class="text-gray-600 leading-relaxed mb-4">
                            Platform yang menghubungkan mahasiswa dengan masalah nyata dari pemerintah daerah, meningkatkan relevansi program hingga <span class="font-bold text-blue-600">75%</span>.
                        </p>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-600 italic">
                                "Mahasiswa dapat langsung fokus pada penyelesaian masalah tanpa menghabiskan waktu untuk identifikasi."
                            </p>
                        </div>
                    </div>

                    {{-- solusi 2 --}}
                    <div class="bg-white rounded-lg p-8 shadow-lg hover:shadow-xl transition-shadow border-t-4 border-yellow-500">
                        <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mb-6">
                            <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Impact Portfolio</h3>
                        <p class="text-gray-600 leading-relaxed mb-4">
                            Sistem validasi resmi dari pemerintah daerah yang menciptakan portofolio profesional terverifikasi untuk mahasiswa.
                        </p>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-600 italic">
                                "94% mahasiswa menyatakan portofolio tervalidasi sangat berharga dalam proses rekrutmen."
                            </p>
                        </div>
                    </div>

                    {{-- solusi 3 --}}
                    <div class="bg-white rounded-lg p-8 shadow-lg hover:shadow-xl transition-shadow border-t-4 border-blue-500">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-6">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Knowledge Repository</h3>
                        <p class="text-gray-600 leading-relaxed mb-4">
                            Perpustakaan digital nasional yang mengubah hasil KKN menjadi sumber pembelajaran kolektif yang dapat diakses seluruh masyarakat.
                        </p>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-600 italic">
                                "Potensi penghematan hingga Rp 540 miliar per tahun dengan mencegah duplikasi program."
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- layer 1: intro section - white background --}}
    <section class="py-24 bg-white">
        <div class="container mx-auto px-6">
            <div class="grid md:grid-cols-2 gap-16 items-start">
                {{-- left: big title --}}
                <div>
                    <h2 class="text-5xl md:text-6xl font-bold text-gray-900 leading-tight">
                        KKN-Go Dari Tahun Ke Tahun
                    </h2>
                </div>
                
                {{-- right: description --}}
                <div class="space-y-6">
                    <p class="text-lg text-gray-700 leading-relaxed">
                        KKN-Go berdiri sebagai katalisator dalam menciptakan ekosistem Kuliah Kerja Nyata 4.0 di Indonesia. Kami adalah laboratorium inovasi sekaligus wadah sinergi antara mahasiswa dan pemerintah daerah.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- layer 2: mission section - light gray background --}}
    <section class="py-24 bg-gray-100">
        <div class="container mx-auto px-6">
            <div class="grid md:grid-cols-2 gap-16 items-center">
                {{-- left: icon/visual element --}}
                <div class="flex items-center justify-center">
                    <div class="relative">
                        {{-- decorative circle --}}
                        <div class="w-64 h-64 md:w-80 md:h-80 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-2xl">
                            <svg class="w-32 h-32 md:w-40 md:h-40 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                        {{-- decorative dots --}}
                        <div class="absolute -top-4 -right-4 w-24 h-24 bg-yellow-400 rounded-full opacity-20"></div>
                        <div class="absolute -bottom-4 -left-4 w-32 h-32 bg-green-400 rounded-full opacity-20"></div>
                    </div>
                </div>
                
                {{-- right: text content --}}
                <div>
                    <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6 leading-tight">
                        Dari Mahasiswa
                    </h2>
                    <p class="text-lg text-gray-700 leading-relaxed mb-6">
                        Menyongsong usia ke-2 tahun, KKN-Go terus tumbuh dan bertransformasi. Dari pelabuhan kecil di masa lalu hingga platform digital masa kini, KKN-Go telah menghadapi beragam tantangan dan membuka banyak peluang.
                    </p>
                    <p class="text-lg text-gray-700 leading-relaxed">
                        Saatnya kita melangkah bersama untuk membentuk KKN-Go sebagai <span class="font-semibold italic">revolusi mahasiswa</span> yang lebih inklusif dan siap bersaing di pentas nasional.
                    </p>
                    <div class="mt-8">
                        <p class="text-gray-900 font-bold text-xl">Tim PHP Artisan Serve</p>
                        <p class="text-gray-600">Institut Teknologi Bandung</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- layer 3: impact section --}}
    <section class="py-24 bg-white">
        <div class="container mx-auto px-6">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                {{-- left: image --}}
                <div class="order-2 md:order-1">
                    <div class="relative">
                        <img src="{{ asset('handprints-about.jpeg') }}" 
                             alt="Aktivitas Mahasiswa KKN" 
                             class="w-full h-auto rounded-lg shadow-xl">
                    </div>
                </div>
                
                {{-- right: content with icon list --}}
                <div class="order-1 md:order-2">
                    <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-8 leading-tight">
                        Platform Terpadu Untuk Mahasiswa Dan Instansi
                    </h2>
                    <p class="text-lg text-gray-600 mb-10 leading-relaxed">
                        Selalu up-to-date dengan informasi dan data program KKN yang terintegrasi, aktual, serta transparan dari seluruh perguruan tinggi dan pemerintah daerah.
                    </p>
                    
                    {{-- icon list --}}
                    <div class="space-y-8">
                        {{-- item 1 --}}
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 rounded-full bg-blue-600 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <p class="text-gray-700 leading-relaxed">
                                    Pembaruan data setiap hari oleh mahasiswa dan instansi mitra
                                </p>
                            </div>
                        </div>
                        
                        {{-- item 2 --}}
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 rounded-full bg-blue-600 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <p class="text-gray-700 leading-relaxed">
                                    Dikelola oleh tim profesional dari perguruan tinggi terkemuka
                                </p>
                            </div>
                        </div>
                        
                        {{-- item 3 --}}
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 rounded-full bg-blue-600 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <p class="text-gray-700 leading-relaxed">
                                    Hasil sinergi perguruan tinggi dan pemerintah daerah untuk pembangunan berkelanjutan
                                </p>
                            </div>
                        </div>
                        
                        {{-- item 4 --}}
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 rounded-full bg-blue-600 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path>
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <p class="text-gray-700 leading-relaxed">
                                    Dikunjungi oleh lebih dari 520,000+ mahasiswa setiap tahun
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    {{-- cta link --}}
                    <div class="mt-10">
                        <a href="#" class="inline-flex items-center text-blue-600 font-semibold hover:text-blue-700 transition-colors group">
                            Kunjungi Website
                            <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- layer SDGs --}}
    <section class="py-24 bg-gray-100">
        <div class="container mx-auto px-6">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                {{-- left: content with SDGs --}}
                <div>
                    <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-8 leading-tight">
                        Mendukung Pencapaian Sustainable Development Goals
                    </h2>
                    <p class="text-lg text-gray-600 mb-10 leading-relaxed">
                        KKN-Go secara langsung berkontribusi pada pencapaian beberapa target SDGs melalui kolaborasi mahasiswa dan pemerintah daerah.
                    </p>
                    
                    {{-- sdgs list --}}
                    <div class="space-y-6">
                        {{-- sdg 4 --}}
                        <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow">
                            <div class="flex items-start gap-4">
                                <div class="flex-shrink-0">
                                    <div class="w-16 h-16 rounded-lg bg-red-500 flex items-center justify-center text-white font-bold text-2xl">
                                        4
                                    </div>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900 mb-2">Pendidikan Berkualitas</h3>
                                    <p class="text-gray-600 leading-relaxed">
                                        Menciptakan pengalaman belajar bermakna yang menghubungkan teori dengan praktik
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- sdg 11 --}}
                        <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow">
                            <div class="flex items-start gap-4">
                                <div class="flex-shrink-0">
                                    <div class="w-16 h-16 rounded-lg bg-orange-500 flex items-center justify-center text-white font-bold text-2xl">
                                        11
                                    </div>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900 mb-2">Kota Dan Komunitas Berkelanjutan</h3>
                                    <p class="text-gray-600 leading-relaxed">
                                        Mendukung pengembangan daerah berbasis data dan riset
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- sdg 16 --}}
                        <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow">
                            <div class="flex items-start gap-4">
                                <div class="flex-shrink-0">
                                    <div class="w-16 h-16 rounded-lg bg-blue-700 flex items-center justify-center text-white font-bold text-2xl">
                                        16
                                    </div>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900 mb-2">Institusi Yang Kuat</h3>
                                    <p class="text-gray-600 leading-relaxed">
                                        Memperkuat kapasitas pemerintahan lokal dalam pengambilan keputusan
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- sdg 17 --}}
                        <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow">
                            <div class="flex items-start gap-4">
                                <div class="flex-shrink-0">
                                    <div class="w-16 h-16 rounded-lg bg-blue-900 flex items-center justify-center text-white font-bold text-2xl">
                                        17
                                    </div>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900 mb-2">Kemitraan</h3>
                                    <p class="text-gray-600 leading-relaxed">
                                        Menciptakan jembatan kolaborasi antara akademisi, pemerintah, dan masyarakat
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- right: image --}}
                <div>
                    <div class="relative">
                        <img src="{{ asset('jaket-about.jpeg') }}" 
                             alt="SDGs KKN-Go" 
                             class="w-full h-auto rounded-lg shadow-xl">
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- how it works section --}}
    <section class="py-24 bg-gradient-to-br from-blue-50 to-white">
        <div class="container mx-auto px-6">
            <div class="max-w-7xl mx-auto">
                
                {{-- header --}}
                <h2 class="text-4xl md:text-5xl font-bold text-blue-700 mb-12 text-center">
                    How It Works
                </h2>

                {{-- tab switcher --}}
                <div class="flex justify-center mb-16">
                    <div class="inline-flex rounded-lg bg-gray-200 p-1">
                        <button onclick="switchTab('mahasiswa')" id="tabMahasiswa" class="tab-button active">
                            Untuk Mahasiswa
                        </button>
                        <button onclick="switchTab('institusi')" id="tabInstitusi" class="tab-button">
                            Untuk Institusi
                        </button>
                    </div>
                </div>

                {{-- navigation arrows --}}
                <div class="flex justify-end mb-8">
                    <div class="flex gap-3">
                        <button onclick="scrollTimelineLeft()" class="arrow-btn">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </button>
                        <button onclick="scrollTimelineRight()" class="arrow-btn">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- timeline container mahasiswa --}}
                <div id="contentMahasiswa" class="tab-content active">
                    <div class="relative overflow-x-auto pb-8" id="timelineMahasiswa">
                        <div class="flex min-w-max relative pt-12">
                            
                            {{-- garis horizontal --}}
                            <div class="absolute left-0 right-0 bg-blue-600" style="top: 90px; height: 2px;"></div>
                            
                            {{-- langkah 1 --}}
                            <div class="flex-shrink-0 px-4" style="width: 400px;">
                                <div class="flex flex-col items-start">
                                    <p class="text-sm text-gray-600 mb-4">Langkah 1</p>
                                    <div class="relative flex items-center justify-start w-full mb-6">
                                        <div class="w-3 h-3 bg-blue-600 rounded-full relative z-10"></div>
                                    </div>
                                    <div class="flex items-center gap-3 mb-3">
                                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        <h3 class="text-xl font-semibold text-gray-900">Daftar & Lengkapi Profil</h3>
                                    </div>
                                    <p class="text-sm text-gray-700 leading-relaxed">
                                        Buat akun Anda sebagai mahasiswa dalam hitungan menit. Lengkapi profil Anda dengan keahlian, riwayat pendidikan, dan portofolio awal untuk menarik perhatian institusi.
                                    </p>
                                </div>
                            </div>

                            {{-- langkah 2 --}}
                            <div class="flex-shrink-0 px-4" style="width: 400px;">
                                <div class="flex flex-col items-start">
                                    <p class="text-sm text-gray-600 mb-4">Langkah 2</p>
                                    <div class="relative flex items-center justify-start w-full mb-6">
                                        <div class="w-3 h-3 bg-blue-600 rounded-full relative z-10"></div>
                                    </div>
                                    <div class="flex items-center gap-3 mb-3">
                                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                        </svg>
                                        <h3 class="text-xl font-semibold text-gray-900">Jelajahi Masalah</h3>
                                    </div>
                                    <p class="text-sm text-gray-700 leading-relaxed">
                                        Temukan ratusan masalah nyata dari berbagai institusi. Gunakan filter berdasarkan lokasi, kategori keilmuan, atau jenis institusi untuk menemukan tantangan yang paling relevan.
                                    </p>
                                </div>
                            </div>

                            {{-- langkah 3 --}}
                            <div class="flex-shrink-0 px-4" style="width: 400px;">
                                <div class="flex flex-col items-start">
                                    <p class="text-sm text-gray-600 mb-4">Langkah 3</p>
                                    <div class="relative flex items-center justify-start w-full mb-6">
                                        <div class="w-3 h-3 bg-blue-600 rounded-full relative z-10"></div>
                                    </div>
                                    <div class="flex items-center gap-3 mb-3">
                                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                        </svg>
                                        <h3 class="text-xl font-semibold text-gray-900">Ajukan Proposal Solusi</h3>
                                    </div>
                                    <p class="text-sm text-gray-700 leading-relaxed">
                                        Tulis dan kirimkan proposal solusi Anda yang paling inovatif. Jelaskan ide, metodologi, dan estimasi waktu pengerjaan proyek secara rinci.
                                    </p>
                                </div>
                            </div>

                            {{-- langkah 4 --}}
                            <div class="flex-shrink-0 px-4" style="width: 400px;">
                                <div class="flex flex-col items-start">
                                    <p class="text-sm text-gray-600 mb-4">Langkah 4</p>
                                    <div class="relative flex items-center justify-start w-full mb-6">
                                        <div class="w-3 h-3 bg-blue-600 rounded-full relative z-10"></div>
                                    </div>
                                    <div class="flex items-center gap-3 mb-3">
                                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <h3 class="text-xl font-semibold text-gray-900">Tunggu Peninjauan</h3>
                                    </div>
                                    <p class="text-sm text-gray-700 leading-relaxed">
                                        Institusi akan meninjau proposal Anda. Anda akan menerima notifikasi apakah proposal Anda diterima, ditolak, atau membutuhkan revisi.
                                    </p>
                                </div>
                            </div>

                            {{-- langkah 5 --}}
                            <div class="flex-shrink-0 px-4" style="width: 400px;">
                                <div class="flex flex-col items-start">
                                    <p class="text-sm text-gray-600 mb-4">Langkah 5</p>
                                    <div class="relative flex items-center justify-start w-full mb-6">
                                        <div class="w-3 h-3 bg-blue-600 rounded-full relative z-10"></div>
                                    </div>
                                    <div class="flex items-center gap-3 mb-3">
                                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        <h3 class="text-xl font-semibold text-gray-900">Kerjakan Proyek & Lapor</h3>
                                    </div>
                                    <p class="text-sm text-gray-700 leading-relaxed">
                                        Setelah proposal disetujui, mulailah pengerjaan proyek. Laporkan kemajuan Anda secara berkala melalui dasbor proyek agar institusi dapat memantau perkembangan.
                                    </p>
                                </div>
                            </div>

                            {{-- langkah 6 --}}
                            <div class="flex-shrink-0 px-4" style="width: 400px;">
                                <div class="flex flex-col items-start">
                                    <p class="text-sm text-gray-600 mb-4">Langkah 6</p>
                                    <div class="relative flex items-center justify-start w-full mb-6">
                                        <div class="w-3 h-3 bg-blue-600 rounded-full relative z-10"></div>
                                    </div>
                                    <div class="flex items-center gap-3 mb-3">
                                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                        </svg>
                                        <h3 class="text-xl font-semibold text-gray-900">Dapatkan Pengakuan</h3>
                                    </div>
                                    <p class="text-sm text-gray-700 leading-relaxed">
                                        Selesaikan proyek dan dapatkan ulasan serta sertifikat digital. Proyek yang berhasil akan otomatis masuk ke portofolio online Anda, memperkuat reputasi profesional Anda.
                                    </p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                {{-- timeline container institusi --}}
                <div id="contentInstitusi" class="tab-content">
                    <div class="relative overflow-x-auto pb-8" id="timelineInstitusi">
                        <div class="flex min-w-max relative pt-12">
                            
                            {{-- garis horizontal --}}
                            <div class="absolute left-0 right-0 bg-blue-600" style="top: 90px; height: 2px;"></div>
                            
                            {{-- langkah 1 --}}
                            <div class="flex-shrink-0 px-4" style="width: 400px;">
                                <div class="flex flex-col items-start">
                                    <p class="text-sm text-gray-600 mb-4">Langkah 1</p>
                                    <div class="relative flex items-center justify-start w-full mb-6">
                                        <div class="w-3 h-3 bg-blue-600 rounded-full relative z-10"></div>
                                    </div>
                                    <div class="flex items-center gap-3 mb-3">
                                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                        </svg>
                                        <h3 class="text-xl font-semibold text-gray-900">Daftar & Verifikasi</h3>
                                    </div>
                                    <p class="text-sm text-gray-700 leading-relaxed">
                                        Daftarkan institusi Anda (pemerintah desa, UKM, NGO, dll.) dan lengkapi profil. Tim kami akan melakukan verifikasi untuk memastikan kredibilitas platform.
                                    </p>
                                </div>
                            </div>

                            {{-- langkah 2 --}}
                            <div class="flex-shrink-0 px-4" style="width: 400px;">
                                <div class="flex flex-col items-start">
                                    <p class="text-sm text-gray-600 mb-4">Langkah 2</p>
                                    <div class="relative flex items-center justify-start w-full mb-6">
                                        <div class="w-3 h-3 bg-blue-600 rounded-full relative z-10"></div>
                                    </div>
                                    <div class="flex items-center gap-3 mb-3">
                                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                                        </svg>
                                        <h3 class="text-xl font-semibold text-gray-900">Publikasikan Masalah</h3>
                                    </div>
                                    <p class="text-sm text-gray-700 leading-relaxed">
                                        Jabarkan masalah, tantangan, atau kebutuhan yang sedang dihadapi institusi Anda. Semakin detail Anda menjelaskannya, semakin relevan solusi yang akan Anda terima.
                                    </p>
                                </div>
                            </div>

                            {{-- langkah 3 --}}
                            <div class="flex-shrink-0 px-4" style="width: 400px;">
                                <div class="flex flex-col items-start">
                                    <p class="text-sm text-gray-600 mb-4">Langkah 3</p>
                                    <div class="relative flex items-center justify-start w-full mb-6">
                                        <div class="w-3 h-3 bg-blue-600 rounded-full relative z-10"></div>
                                    </div>
                                    <div class="flex items-center gap-3 mb-3">
                                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                        </svg>
                                        <h3 class="text-xl font-semibold text-gray-900">Tinjau Proposal Masuk</h3>
                                    </div>
                                    <p class="text-sm text-gray-700 leading-relaxed">
                                        Anda akan menerima beragam proposal solusi dari mahasiswa di seluruh Indonesia. Bandingkan setiap ide, kreativitas, dan kelayakan proposal yang diajukan.
                                    </p>
                                </div>
                            </div>

                            {{-- langkah 4 --}}
                            <div class="flex-shrink-0 px-4" style="width: 400px;">
                                <div class="flex flex-col items-start">
                                    <p class="text-sm text-gray-600 mb-4">Langkah 4</p>
                                    <div class="relative flex items-center justify-start w-full mb-6">
                                        <div class="w-3 h-3 bg-blue-600 rounded-full relative z-10"></div>
                                    </div>
                                    <div class="flex items-center gap-3 mb-3">
                                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                        <h3 class="text-xl font-semibold text-gray-900">Pilih Mahasiswa Terbaik</h3>
                                    </div>
                                    <p class="text-sm text-gray-700 leading-relaxed">
                                        Pilih mahasiswa atau tim dengan proposal terbaik. Anda dapat berkomunikasi langsung dengan mereka melalui platform untuk diskusi lebih lanjut sebelum membuat keputusan akhir.
                                    </p>
                                </div>
                            </div>

                            {{-- langkah 5 --}}
                            <div class="flex-shrink-0 px-4" style="width: 400px;">
                                <div class="flex flex-col items-start">
                                    <p class="text-sm text-gray-600 mb-4">Langkah 5</p>
                                    <div class="relative flex items-center justify-start w-full mb-6">
                                        <div class="w-3 h-3 bg-blue-600 rounded-full relative z-10"></div>
                                    </div>
                                    <div class="flex items-center gap-3 mb-3">
                                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                        </svg>
                                        <h3 class="text-xl font-semibold text-gray-900">Bimbing & Pantau Proyek</h3>
                                    </div>
                                    <p class="text-sm text-gray-700 leading-relaxed">
                                        Dampingi mahasiswa selama pengerjaan proyek. Pantau laporan kemajuan mereka melalui dasbor dan berikan masukan agar hasil akhir sesuai dengan ekspektasi.
                                    </p>
                                </div>
                            </div>

                            {{-- langkah 6 --}}
                            <div class="flex-shrink-0 px-4" style="width: 400px;">
                                <div class="flex flex-col items-start">
                                    <p class="text-sm text-gray-600 mb-4">Langkah 6</p>
                                    <div class="relative flex items-center justify-start w-full mb-6">
                                        <div class="w-3 h-3 bg-blue-600 rounded-full relative z-10"></div>
                                    </div>
                                    <div class="flex items-center gap-3 mb-3">
                                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/>
                                        </svg>
                                        <h3 class="text-xl font-semibold text-gray-900">Beri Ulasan & Terima Hasil</h3>
                                    </div>
                                    <p class="text-sm text-gray-700 leading-relaxed">
                                        Setelah proyek selesai, terima laporan akhir dan hasil kerja dari mahasiswa. Berikan ulasan yang membangun untuk membantu mereka di karir masa depan.
                                    </p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- CTA Final --}}
    <section class="py-20 bg-gradient-to-r from-blue-600 to-indigo-600">
        <div class="container mx-auto px-6">
            <div class="max-w-4xl mx-auto text-center">
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">
                    Siap Menjadi Bagian Dari Revolusi KKN?
                </h2>
                <p class="text-xl text-blue-100 mb-10">
                    Bergabunglah dengan ribuan mahasiswa dan institusi yang telah berkontribusi untuk pembangunan Indonesia
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('register.student') }}" class="inline-flex items-center justify-center px-8 py-4 bg-white text-blue-600 font-semibold rounded-lg hover:bg-gray-100 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Daftar Sebagai Mahasiswa
                    </a>
                    <a href="{{ route('register.institution') }}" class="inline-flex items-center justify-center px-8 py-4 bg-transparent text-white font-semibold rounded-lg border-2 border-white hover:bg-white hover:text-blue-600 transition-all transform hover:-translate-y-0.5">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        Daftar Sebagai Institusi
                    </a>
                </div>
            </div>
        </div>
    </section>

</div>

@push('styles')
<style>
    /* tab switcher */
    .tab-button {
        padding: 12px 32px;
        border-radius: 8px;
        background: transparent;
        color: #6b7280;
        font-weight: 600;
        font-size: 15px;
        border: none;
        cursor: pointer;
        transition: all 0.3s;
    }

    .tab-button.active {
        background: #2563eb;
        color: white;
    }

    /* tab content */
    .tab-content {
        display: none;
    }

    .tab-content.active {
        display: block;
    }

    /* arrow buttons */
    .arrow-btn {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        border: 1px solid #d1d5db;
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
    }

    .arrow-btn:hover {
        border-color: #2563eb;
        background: #eff6ff;
    }

    /* scrollbar */
    #timelineMahasiswa::-webkit-scrollbar,
    #timelineInstitusi::-webkit-scrollbar {
        height: 6px;
    }

    #timelineMahasiswa::-webkit-scrollbar-track,
    #timelineInstitusi::-webkit-scrollbar-track {
        background: #f3f4f6;
    }

    #timelineMahasiswa::-webkit-scrollbar-thumb,
    #timelineInstitusi::-webkit-scrollbar-thumb {
        background: #2563eb;
        border-radius: 3px;
    }
</style>
@endpush

@push('scripts')
<script>
    // switch tab function
    function switchTab(tab) {
        // update tab buttons
        document.querySelectorAll('.tab-button').forEach(btn => {
            btn.classList.remove('active');
        });
        document.getElementById('tab' + tab.charAt(0).toUpperCase() + tab.slice(1)).classList.add('active');
        
        // update content
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.remove('active');
        });
        document.getElementById('content' + tab.charAt(0).toUpperCase() + tab.slice(1)).classList.add('active');
    }

    // scroll functions
    function scrollTimelineLeft() {
        const activeTab = document.querySelector('.tab-content.active .overflow-x-auto');
        if (activeTab) {
            activeTab.scrollBy({ left: -400, behavior: 'smooth' });
        }
    }

    function scrollTimelineRight() {
        const activeTab = document.querySelector('.tab-content.active .overflow-x-auto');
        if (activeTab) {
            activeTab.scrollBy({ left: 400, behavior: 'smooth' });
        }
    }
</script>
@endpush
@endsection