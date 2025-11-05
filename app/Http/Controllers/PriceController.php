<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PriceController extends Controller
{
    /**
     * tampilkan halaman pricing
     */
    public function index()
    {
        // paket pricing untuk kerja sama institusi dengan Lapangku
        $pricingPlans = [
            [
                'name' => 'Paket Basic',
                'price' => 'Gratis',
                'period' => 'Selamanya',
                'description' => 'Cocok untuk institusi kecil yang baru memulai program KKN.',
                'features' => [
                    'Publikasi hingga 5 masalah per tahun',
                    'Akses ke marketplace masalah',
                    'Review dan validasi hasil KKN',
                    'Portfolio mahasiswa terverifikasi',
                    'Dukungan email',
                    'Dashboard institusi dasar',
                ],
                'isPopular' => false,
                'buttonText' => 'Mulai Gratis',
                'buttonClass' => 'btn-outline',
            ],
            [
                'name' => 'Paket Professional',
                'price' => 'Rp 2.500.000',
                'period' => 'Per Tahun',
                'description' => 'Untuk institusi menengah dengan program KKN aktif dan berkelanjutan.',
                'features' => [
                    'Publikasi masalah UNLIMITED',
                    'Prioritas dalam marketplace',
                    'Advanced analytics dashboard',
                    'Knowledge repository dedicated',
                    'Review & rating mahasiswa',
                    'Dukungan prioritas (email & WhatsApp)',
                    'Pelatihan penggunaan platform',
                    'Custom branding profile',
                ],
                'isPopular' => true,
                'buttonText' => 'Pilih Paket',
                'buttonClass' => 'btn-primary',
            ],
            [
                'name' => 'Paket Enterprise',
                'price' => 'Custom',
                'period' => 'Konsultasi',
                'description' => 'Solusi lengkap untuk institusi besar dengan kebutuhan khusus.',
                'features' => [
                    'Semua fitur Professional',
                    'API Integration untuk sistem internal',
                    'Dedicated account manager',
                    'Custom feature development',
                    'Advanced reporting & analytics',
                    'Multi-branch management',
                    'Dukungan 24/7 prioritas',
                    'On-site training & workshop',
                    'White-label solution (optional)',
                ],
                'isPopular' => false,
                'buttonText' => 'Hubungi Kami',
                'buttonClass' => 'btn-outline',
            ],
        ];

        // additional benefits untuk semua paket
        $additionalBenefits = [
            [
                'icon' => 'shield-check',
                'title' => 'Data Aman & Terverifikasi',
                'description' => 'Semua data terenkripsi dan tersimpan dengan standar keamanan tinggi.',
            ],
            [
                'icon' => 'users',
                'title' => 'Akses ke Ribuan Mahasiswa',
                'description' => 'Hubungkan institusi Anda dengan mahasiswa dari seluruh Indonesia.',
            ],
            [
                'icon' => 'chart-bar',
                'title' => 'Analitik Mendalam',
                'description' => 'Pantau progress dan dampak program KKN secara real-time.',
            ],
            [
                'icon' => 'book-open',
                'title' => 'Knowledge Repository',
                'description' => 'Akses ke database hasil KKN untuk pembelajaran berkelanjutan.',
            ],
        ];

        return view('price.index', compact('pricingPlans', 'additionalBenefits'));
    }
}
