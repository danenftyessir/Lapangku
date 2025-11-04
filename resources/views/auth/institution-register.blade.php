{{-- resources/views/auth/institution-register.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Daftar Sebagai Instansi - KKN-GO</title>
    
    @vite(['resources/css/app.css'])
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth-institution.css') }}">
    
    {{-- tambahkan Alpine.js CDN untuk membuat dropdown dinamis bekerja --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
    
    <style>
        /* background image dengan opacity yang lebih tinggi */
        .register-container.institution-register {
            position: relative;
            min-height: 100vh;
        }
        
        .register-container.institution-register::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: url('{{ asset('institution-register-background.jpeg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            opacity: 0.35;
            z-index: 0;
            pointer-events: none;
        }
        
        .register-container.institution-register > * {
            position: relative;
            z-index: 1;
        }
        
        /* MODERN STEP INDICATOR - Minimalist Glass Effect */
        .step-indicator {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
            border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 1.5rem 1.5rem;
            border-radius: 1rem 1rem 0 0;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
        }
        
        /* Modern Step Numbers */
        .step-number-wrapper {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            border: 2px solid rgba(156, 163, 175, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            backdrop-filter: blur(10px);
        }
        
        .step.active .step-number-wrapper {
            background: linear-gradient(135deg, #10b981 0%, #14b8a6 100%);
            border-color: transparent;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
            transform: scale(1.1);
        }
        
        .step.completed .step-number-wrapper {
            background: #10b981;
            border-color: transparent;
        }
        
        .step-number {
            color: #6B7280;
            font-weight: 600;
            font-size: 0.875rem;
        }
        
        .step.active .step-number,
        .step.completed .step-number {
            color: white;
        }
        
        /* Modern Step Labels */
        .step-label {
            color: #6B7280 !important;
            font-weight: 500;
            font-size: 0.75rem;
            margin-top: 0.5rem;
            transition: all 0.3s ease;
        }
        
        .step.active .step-label {
            color: #10b981 !important;
            font-weight: 600;
        }
        
        /* Step Track Line - PERBAIKAN: Presisi pas dengan lingkaran step */
        .step-track {
            position: absolute;
            top: 1.25rem; /* 2.5rem (height circle) / 2 = 1.25rem */
            left: calc(25% + 1.25rem);
            right: calc(25% + 1.25rem);
            height: 2px;
            background: rgba(156, 163, 175, 0.2);
            z-index: 0;
        }
        
        /* Container step indicator */
        .step-indicator > div:last-child {
            position: relative;
        }
        
        /* Individual step positioning */
        .step {
            position: relative;
            z-index: 1;
        }
        
        /* GLASS MORPHISM CARD */
        .register-card {
            background: rgba(255, 255, 255, 0.25) !important;
            backdrop-filter: blur(30px) saturate(180%);
            -webkit-backdrop-filter: blur(30px) saturate(180%);
            border: 1px solid rgba(255, 255, 255, 0.4);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15) !important;
        }
        
        /* Form content background - opacity lebih rendah */
        .register-card .p-8 {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(10px);
            border-radius: 0 0 1rem 1rem;
        }
        
        /* step content title */
        .step-content h2 {
            color: #1F2937;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }
        
        .step-content p {
            color: #4B5563;
        }
        
        /* styling tombol navigasi */
        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 2rem;
            background: linear-gradient(135deg, #10b981 0%, #14b8a6 100%);
            color: white;
            font-weight: 600;
            font-size: 0.9375rem;
            border-radius: 0.5rem;
            border: none;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }
        
        .btn-primary:hover:not(:disabled) {
            background: linear-gradient(135deg, #059669 0%, #0d9488 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
        }
        
        .btn-primary:active:not(:disabled) {
            transform: translateY(0);
        }
        
        .btn-primary:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
        
        .btn-secondary {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 2rem;
            background: white;
            color: #6B7280;
            font-weight: 600;
            font-size: 0.9375rem;
            border-radius: 0.5rem;
            border: 2px solid #E5E7EB;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .btn-secondary:hover {
            background: #F9FAFB;
            border-color: #10b981;
            color: #10b981;
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50">
    <nav class="fixed top-0 left-0 right-0 bg-white border-b border-gray-200 z-50">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <a href="{{ route('home') }}" class="inline-flex items-center text-gray-700 hover:text-gray-900 transition-colors font-semibold">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span class="font-medium">Back</span>
            </a>
            
            <div class="flex items-center space-x-6">
                <a href="{{ route('contact') }}" class="text-gray-600 hover:text-gray-900 font-medium transition-colors">Contact</a>
                <a href="{{ route('about') }}" class="text-gray-600 hover:text-gray-900 font-medium transition-colors">About</a>
            </div>
        </div>
    </nav>

    <div class="register-container institution-register" style="padding-top: 5rem;">
        <div class="relative z-10 flex items-center justify-center min-h-screen py-12 px-4">
            <div class="w-full max-w-4xl">
                {{-- logo & header --}}
                <div class="text-center mb-8">
                    <img src="{{ asset('kkn-go-logo.png') }}" alt="KKN-GO Logo" class="h-24 mx-auto mb-6">
                    <h1 class="text-4xl font-bold text-gray-900 mb-3">Daftar Sebagai Instansi</h1>
                    <p class="text-gray-600 mb-4">Mulai Posting Masalah Dan Temukan Mahasiswa KKN Terbaik</p>
                    <div class="inline-flex items-center gap-2 text-sm">
                        <span class="text-gray-600">Sudah Punya Akun?</span>
                        <a href="{{ route('login') }}" class="text-green-600 hover:text-green-700 font-semibold transition-colors">Masuk Di Sini</a>
                    </div>
                </div>

                {{-- main form card --}}
                <div class="register-card fade-in-up">
                    {{-- PERBAIKAN 2: step indicator - STRUKTUR KONSISTEN DENGAN STUDENT --}}
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-8 pb-6 border-b border-gray-100">
                        <div class="step-indicator-container">
                            <div class="step-item" id="step1-item">
                                <div class="step-circle active" id="step1-circle">
                                    <span class="step-number">1</span>
                                </div>
                                <span class="step-label">Data Instansi</span>
                            </div>
                            
                            <div class="step-connector" id="connector1"></div>
                            
                            <div class="step-item" id="step2-item">
                                <div class="step-circle inactive" id="step2-circle">
                                    <span class="step-number">2</span>
                                </div>
                                <span class="step-label">Lokasi</span>
                            </div>
                            
                            <div class="step-connector" id="connector2"></div>
                            
                            <div class="step-item" id="step3-item">
                                <div class="step-circle inactive" id="step3-circle">
                                    <span class="step-number">3</span>
                                </div>
                                <span class="step-label">Penanggung Jawab</span>
                            </div>
                            
                            <div class="step-connector" id="connector3"></div>
                            
                            <div class="step-item" id="step4-item">
                                <div class="step-circle inactive" id="step4-circle">
                                    <span class="step-number">4</span>
                                </div>
                                <span class="step-label">Akun & Verifikasi</span>
                            </div>
                        </div>
                    </div>

                    {{-- form content --}}
                    <form method="POST" 
                          action="{{ route('register.institution.submit') }}" 
                          enctype="multipart/form-data" 
                          id="institutionRegisterForm"
                          class="p-8"
                          x-data="institutionForm()">
                        @csrf

                        {{-- STEP 1: Data Instansi --}}
                        <div id="step1-content" class="step-content">
                            <div class="mb-8">
                                <h2 class="text-2xl font-bold text-gray-800 mb-2">Data Instansi</h2>
                                <p class="text-gray-600">Informasi Lengkap Tentang Instansi Anda</p>
                            </div>

                            <div class="space-y-6">
                                {{-- nama instansi --}}
                                <div>
                                    <label for="institution_name" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Nama Instansi <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" 
                                           id="institution_name" 
                                           name="institution_name" 
                                           value="{{ old('institution_name') }}"
                                           placeholder="Contoh: Desa Sukamaju"
                                           class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition @error('institution_name') border-red-500 @enderror"
                                           required>
                                    @error('institution_name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- jenis instansi --}}
                                <div>
                                    <label for="institution_type" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Jenis Instansi <span class="text-red-500">*</span>
                                    </label>
                                    <select id="institution_type" 
                                            name="institution_type" 
                                            class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition @error('institution_type') border-red-500 @enderror"
                                            required>
                                        <option value="">Pilih Jenis Instansi</option>
                                        <option value="pemerintah_desa" {{ old('institution_type') == 'pemerintah_desa' ? 'selected' : '' }}>Pemerintah Desa</option>
                                        <option value="dinas" {{ old('institution_type') == 'dinas' ? 'selected' : '' }}>Dinas</option>
                                        <option value="ngo" {{ old('institution_type') == 'ngo' ? 'selected' : '' }}>NGO</option>
                                        <option value="puskesmas" {{ old('institution_type') == 'puskesmas' ? 'selected' : '' }}>Puskesmas</option>
                                        <option value="sekolah" {{ old('institution_type') == 'sekolah' ? 'selected' : '' }}>Sekolah</option>
                                        <option value="perguruan_tinggi" {{ old('institution_type') == 'perguruan_tinggi' ? 'selected' : '' }}>Perguruan Tinggi</option>
                                        <option value="lainnya" {{ old('institution_type') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                                    </select>
                                    @error('institution_type')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- email resmi --}}
                                <div>
                                    <label for="official_email" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Email Resmi Instansi <span class="text-red-500">*</span>
                                    </label>
                                    <input type="email" 
                                           id="official_email" 
                                           name="official_email" 
                                           value="{{ old('official_email') }}"
                                           placeholder="Contoh: info@desasukamaju.go.id"
                                           class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition @error('official_email') border-red-500 @enderror"
                                           required>
                                    @error('official_email')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="flex justify-end mt-8">
                                <button type="button" onclick="nextStep(2)" class="btn-primary">
                                    Selanjutnya
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        {{-- STEP 2: Lokasi --}}
                        <div id="step2-content" class="step-content" style="display: none;">
                            <div class="mb-8">
                                <h2 class="text-2xl font-bold text-gray-800 mb-2">Lokasi</h2>
                                <p class="text-gray-600">Informasi Lokasi Instansi Anda</p>
                            </div>

                            <div class="space-y-6">
                                {{-- alamat lengkap --}}
                                <div>
                                    <label for="address" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Alamat Lengkap <span class="text-red-500">*</span>
                                    </label>
                                    <textarea id="address" 
                                              name="address" 
                                              rows="3"
                                              placeholder="Contoh: Jl. Raya Sukamaju No. 123, RT 02/RW 05"
                                              class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition @error('address') border-red-500 @enderror"
                                              required>{{ old('address') }}</textarea>
                                    @error('address')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- provinsi --}}
                                <div>
                                    <label for="province_id" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Provinsi <span class="text-red-500">*</span>
                                    </label>
                                    
                                    <select id="province_id" 
                                            name="province_id"
                                            x-model="selectedProvince"
                                            @change="loadRegencies()"
                                            class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition @error('province_id') border-red-500 @enderror"
                                            required>
                                        <option value="">Pilih Provinsi</option>
                                        @foreach($provinces as $province)
                                            <option value="{{ $province->id }}" {{ old('province_id') == $province->id ? 'selected' : '' }}>
                                                {{ $province->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('province_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- kabupaten/kota --}}
                                <div>
                                    <label for="regency_id" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Kabupaten/Kota <span class="text-red-500">*</span>
                                    </label>
                                    <select id="regency_id" 
                                            name="regency_id"
                                            x-model="selectedRegency"
                                            class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition @error('regency_id') border-red-500 @enderror"
                                            required
                                            :disabled="!selectedProvince">
                                        <option value="">Pilih Kabupaten/Kota</option>
                                        <template x-for="regency in regencies" :key="regency.id">
                                            <option :value="regency.id" x-text="regency.name"></option>
                                        </template>
                                    </select>
                                    @error('regency_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="flex justify-between mt-8">
                                <button type="button" onclick="prevStep(1)" class="btn-secondary">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                    </svg>
                                    Kembali
                                </button>
                                <button type="button" onclick="nextStep(3)" class="btn-primary">
                                    Selanjutnya
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        {{-- STEP 3: Penanggung Jawab --}}
                        <div id="step3-content" class="step-content" style="display: none;">
                            <div class="mb-8">
                                <h2 class="text-2xl font-bold text-gray-800 mb-2">Penanggung Jawab</h2>
                                <p class="text-gray-600">Informasi Kontak Dan Dokumen Pendukung</p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="pic_name" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Nama PIC <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="pic_name" name="pic_name" value="{{ old('pic_name') }}" placeholder="Contoh: Budi Santoso" class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg" required>
                                    @error('pic_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="pic_position" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Jabatan PIC <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="pic_position" name="pic_position" value="{{ old('pic_position') }}" placeholder="Contoh: Sekretaris Desa" class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg" required>
                                    @error('pic_position')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="phone_number" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Nomor Telepon <span class="text-red-500">*</span>
                                    </label>
                                    <input type="tel" id="phone_number" name="phone_number" value="{{ old('phone_number') }}" placeholder="Contoh: 081234567890" class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg" required>
                                    @error('phone_number')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="logo" class="block text-sm font-semibold text-gray-700 mb-2">Logo Instansi (Opsional)</label>
                                    <input type="file" id="logo" name="logo" accept="image/*" class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg">
                                </div>
                                <div>
                                    <label for="verification_document" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Dokumen Verifikasi <span class="text-red-500">*</span>
                                    </label>
                                    <input type="file" id="verification_document" name="verification_document" accept="application/pdf" class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg" required>
                                </div>
                                <div>
                                    <label for="website" class="block text-sm font-semibold text-gray-700 mb-2">Website (Opsional)</label>
                                    <input type="url" id="website" name="website" value="{{ old('website') }}" placeholder="https://www.contoh.go.id" class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg">
                                </div>
                                <div class="md:col-span-2">
                                    <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi Instansi (Opsional)</label>
                                    <textarea id="description" name="description" rows="4" placeholder="Ceritakan Tentang Instansi Anda..." class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg">{{ old('description') }}</textarea>
                                </div>
                            </div>

                            <div class="flex justify-between mt-8">
                                <button type="button" onclick="prevStep(2)" class="btn-secondary">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                    </svg>
                                    Kembali
                                </button>
                                <button type="button" onclick="nextStep(4)" class="btn-primary">
                                    Selanjutnya
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        {{-- STEP 4: Akun & Verifikasi --}}
                        <div id="step4-content" class="step-content" style="display: none;">
                            <div class="mb-8">
                                <h2 class="text-2xl font-bold text-gray-800 mb-2">Akun & Verifikasi</h2>
                                <p class="text-gray-600">Buat Username Dan Password Untuk Akun Anda</p>
                            </div>

                            <div class="space-y-6">
                                <div>
                                    <label for="username" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Username <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="username" name="username" value="{{ old('username') }}" placeholder="Contoh: desasukamaju" class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg" required>
                                    @error('username')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                                </div>
                                
                                {{-- peringatan password requirement --}}
                                <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                                    <div class="flex items-start gap-3">
                                        <svg class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                        </svg>
                                        <div class="flex-1">
                                            <p class="text-sm font-semibold text-amber-800 mb-2">Syarat Password:</p>
                                            <ul class="text-sm text-amber-700 space-y-1 list-disc list-inside">
                                                <li>Minimal 8 karakter</li>
                                                <li>Mengandung huruf besar (A-Z)</li>
                                                <li>Mengandung huruf kecil (a-z)</li>
                                                <li>Mengandung simbol (@, #, $, !, %, *, ?, &, _)</li>
                                            </ul>
                                            <p class="text-sm text-amber-700 mt-3">
                                                <span class="font-semibold">Contoh password yang valid:</span> 
                                                <code class="bg-amber-100 px-2 py-1 rounded text-amber-900 font-mono">Desa2024!</code> atau 
                                                <code class="bg-amber-100 px-2 py-1 rounded text-amber-900 font-mono">Sukamaju#2024</code>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div>
                                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Password <span class="text-red-500">*</span>
                                    </label>
                                    <input type="password" id="password" name="password" placeholder="Minimal 8 Karakter" class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg" required>
                                    @error('password')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Konfirmasi Password <span class="text-red-500">*</span>
                                    </label>
                                    <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Ketik Ulang Password" class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg" required>
                                </div>
                            </div>

                            <div class="flex justify-between mt-8">
                                <button type="button" onclick="prevStep(3)" class="btn-secondary">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                    </svg>
                                    Kembali
                                </button>
                                <button type="submit" class="btn-primary">
                                    Daftar Sekarang
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- loading overlay --}}
    <div id="loadingOverlay" style="display: none;" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-8 flex flex-col items-center">
            <div class="animate-spin rounded-full h-16 w-16 border-t-4 border-green-500 mb-4"></div>
            <p class="text-gray-700 font-semibold">Mendaftarkan Akun Anda...</p>
        </div>
    </div>

    <script>
    // current step tracker
    let currentStep = 1;

    // validasi per step sebelum lanjut
    async function nextStep(step) {
        const loadingOverlay = document.getElementById('loadingOverlay');
        
        // validasi step sekarang sebelum lanjut
        const formData = new FormData(document.getElementById('institutionRegisterForm'));
        formData.append('step', currentStep);
        
        loadingOverlay.style.display = 'flex';

        try {
            const response = await fetch('/api/public/validate/institution/step', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            const data = await response.json();

            if (!response.ok) {
                // tampilkan error
                if (data.errors) {
                    let errorMessages = [];
                    Object.keys(data.errors).forEach(key => {
                        errorMessages.push(...data.errors[key]);
                    });
                    alert('Mohon Lengkapi Data Berikut:\n\n' + errorMessages.join('\n'));
                } else {
                    alert('Terjadi Kesalahan Saat Validasi. Silakan Coba Lagi.');
                }
                loadingOverlay.style.display = 'none';
                return;
            }

            // validasi berhasil, pindah ke step selanjutnya
            showStep(step);
            currentStep = step;
            window.scrollTo({ top: 0, behavior: 'smooth' });
            loadingOverlay.style.display = 'none';

        } catch (error) {
            console.error('Validation error:', error);
            alert('Tidak Dapat Terhubung Ke Server. Periksa Koneksi Internet Anda.');
            loadingOverlay.style.display = 'none';
        }
    }

    function prevStep(step) {
        showStep(step);
        currentStep = step;
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    // PERBAIKAN: showStep untuk handle 4 steps dengan step-circle
    function showStep(step) {
        // hide semua step content
        for (let i = 1; i <= 4; i++) {
            const content = document.getElementById(`step${i}-content`);
            if (content) content.style.display = 'none';
        }

        // tampilkan step saat ini
        const currentContent = document.getElementById(`step${step}-content`);
        if (currentContent) currentContent.style.display = 'block';

        // update step circle indicators
        for (let i = 1; i <= 4; i++) {
            const circle = document.getElementById(`step${i}-circle`);
            if (!circle) continue;
            
            circle.classList.remove('active', 'completed', 'inactive');
            
            if (i < step) {
                circle.classList.add('completed');
            } else if (i === step) {
                circle.classList.add('active');
            } else {
                circle.classList.add('inactive');
            }
            
            // update connector
            if (i < 4) {
                const connector = document.getElementById(`connector${i}`);
                if (connector) {
                    if (i < step) {
                        connector.classList.add('completed');
                    } else {
                        connector.classList.remove('completed');
                    }
                }
            }
        }
    }

    // Alpine.js untuk dynamic province-regency dropdown
    function institutionForm() {
        return {
            selectedProvince: '{{ old("province_id") }}',
            selectedRegency: '{{ old("regency_id") }}',
            regencies: @json($regencies),
            
            async loadRegencies() {
                if (!this.selectedProvince) {
                    this.regencies = [];
                    this.selectedRegency = '';
                    return;
                }

                try {
                    const response = await fetch(`/api/public/regencies/${this.selectedProvince}`);
                    if (response.ok) {
                        this.regencies = await response.json();
                    }
                } catch (error) {
                    console.error('Failed to load regencies:', error);
                    alert('Gagal Memuat Data Kabupaten/Kota. Silakan Coba Lagi.');
                }
            }
        }
    }

    document.getElementById('institutionRegisterForm')?.addEventListener('submit', async function(e) {
        e.preventDefault(); // batalkan submit bawaan

        const loadingOverlay = document.getElementById('loadingOverlay');
        loadingOverlay.style.display = 'flex';
        
        const formData = new FormData(this);
        
        try {
            const response = await fetch("{{ route('register.institution.submit') }}", {
                method: 'POST',
                headers: { 
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: formData
            });

            const data = await response.json();

            // jika backend mengembalikan error validasi saat submit akhir
            if (response.status === 422) {
                // cari tahu di langkah mana error pertama terjadi
                const errorFields = Object.keys(data.errors);
                const step1Fields = ['institution_name', 'institution_type', 'official_email'];
                const step2Fields = ['address', 'province_id', 'regency_id'];
                const step3Fields = ['pic_name', 'pic_position', 'phone_number', 'logo', 'verification_document', 'website', 'description'];
                const step4Fields = ['username', 'password', 'password_confirmation'];

                let errorStep = 4;
                if (errorFields.some(field => step1Fields.includes(field))) {
                    errorStep = 1;
                } else if (errorFields.some(field => step2Fields.includes(field))) {
                    errorStep = 2;
                } else if (errorFields.some(field => step3Fields.includes(field))) {
                    errorStep = 3;
                }
                
                // pindah ke step yang error dan tampilkan pesan
                if (currentStep !== errorStep) {
                    showStep(errorStep);
                    currentStep = errorStep;
                }

                // tampilkan error di bawah setiap input
                Object.keys(data.errors).forEach(field => {
                    const inputEl = document.getElementById(field);
                    const errorMsg = data.errors[field][0];
                    
                    if (inputEl) {
                        // tambah border merah
                        inputEl.classList.add('border-red-500');
                        
                        // cari atau buat elemen error message
                        let errorEl = inputEl.parentElement.querySelector('.error-message');
                        if (!errorEl) {
                            errorEl = document.createElement('p');
                            errorEl.className = 'mt-1 text-sm text-red-600 error-message';
                            inputEl.parentElement.appendChild(errorEl);
                        }
                        errorEl.textContent = errorMsg;
                    }
                });

                loadingOverlay.style.display = 'none';
                window.scrollTo({ top: 0, behavior: 'smooth' });
                return;
            }

            // jika sukses (status 200 dan success = true), redirect
            if (response.ok && data.success && data.redirect_url) {
                // redirect ke dashboard institution
                window.location.href = data.redirect_url;
                // jangan sembunyikan loading karena halaman akan redirect
            } else {
                // handle error lainnya
                alert(data.message || 'Terjadi kesalahan saat pendaftaran.');
                loadingOverlay.style.display = 'none';
            }

        } catch(error) {
            console.error('Submit error:', error);
            alert('Terjadi Kesalahan Saat Mengirimkan Formulir. Periksa Koneksi Internet Anda Dan Coba Lagi.');
            loadingOverlay.style.display = 'none';
        }
    });

    // handle error dari server saat page load (tetap pertahankan)
    @if($errors->any())
        const errorFields = @json($errors->keys());
        const step1Fields = ['institution_name', 'institution_type', 'official_email'];
        const step2Fields = ['address', 'province_id', 'regency_id'];
        const step3Fields = ['pic_name', 'pic_position', 'phone_number', 'logo', 'verification_document', 'website', 'description'];
        const step4Fields = ['username', 'password', 'password_confirmation'];
        
        let errorStep = 1;
        if (errorFields.some(field => step4Fields.includes(field))) {
            errorStep = 4;
        } else if (errorFields.some(field => step3Fields.includes(field))) {
            errorStep = 3;
        } else if (errorFields.some(field => step2Fields.includes(field))) {
            errorStep = 2;
        }
        
        showStep(errorStep);
        currentStep = errorStep;
    @endif
    </script>
</body>
</html>