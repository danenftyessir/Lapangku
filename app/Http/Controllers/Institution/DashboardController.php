<?php

namespace App\Http\Controllers\Institution;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Problem;
use App\Models\Application;
use App\Models\Project;
use App\Models\Review;
use App\Services\AnalyticsService;
use App\Services\ReviewService;

/**
 * controller untuk dashboard instansi
 */
class DashboardController extends Controller
{
    protected $analyticsService;
    protected $reviewService;

    public function __construct(AnalyticsService $analyticsService, ReviewService $reviewService)
    {
        $this->analyticsService = $analyticsService;
        $this->reviewService = $reviewService;
    }

    /**
     * tampilkan dashboard instansi
     */
    public function index()
    {
        $institution = auth()->user()->institution;

        // statistik dashboard menggunakan analytics service
        $stats = $this->analyticsService->getInstitutionAnalytics($institution->id);

        // tambahkan monthly growth untuk applications
        $stats['applications']['monthly_growth'] = $this->calculateMonthlyGrowth($institution->id);

        // recent applications dengan prioritas pending
        $recentApplications = Application::with(['student.user', 'student.university', 'problem'])
                                        ->whereHas('problem', function($q) use ($institution) {
                                            $q->where('institution_id', $institution->id);
                                        })
                                        ->where(function($query) {
                                            $query->where('status', 'pending')
                                                  ->orWhere('status', 'under_review');
                                        })
                                        ->latest()
                                        ->limit(5)
                                        ->get();

        // TODO: AI talent recommendations - implementasi scoring algorithm dengan Claude/Cohere untuk ranking kandidat berdasarkan skills, experience, dan problem fit
        // AI recommendations: kandidat terbaik berdasarkan acceptance probability
        $aiRecommendations = $this->getAIRecommendations($institution->id, 5);

        // time series data untuk applications over time chart
        $timeSeriesData = $this->prepareTimeSeriesChartData($institution->id, 6);

        // jobs by category data untuk bar chart
        $jobsCategoryData = $this->getJobsCategoryData($institution->id);

        return view('institution.dashboard.index', compact(
            'stats',
            'recentApplications',
            'aiRecommendations',
            'timeSeriesData',
            'jobsCategoryData',
            'institution'
        ));
    }

    /**
     * hitung monthly growth untuk applications
     */
    private function calculateMonthlyGrowth($institutionId)
    {
        $currentMonth = Application::whereHas('problem', function($q) use ($institutionId) {
            $q->where('institution_id', $institutionId);
        })
        ->whereMonth('created_at', now()->month)
        ->whereYear('created_at', now()->year)
        ->count();

        $lastMonth = Application::whereHas('problem', function($q) use ($institutionId) {
            $q->where('institution_id', $institutionId);
        })
        ->whereMonth('created_at', now()->subMonth()->month)
        ->whereYear('created_at', now()->subMonth()->year)
        ->count();

        if ($lastMonth == 0) {
            return $currentMonth > 0 ? 100 : 0;
        }

        return round((($currentMonth - $lastMonth) / $lastMonth) * 100, 1);
    }

    /**
     * get AI recommendations berdasarkan scoring
     * TODO: implementasi AI scoring dengan Claude/Cohere API
     */
    private function getAIRecommendations($institutionId, $limit = 5)
    {
        // untuk saat ini, ambil aplikasi dengan status under_review dan accepted
        // dengan prioritas berdasarkan created_at terbaru
        // TODO: replace dengan AI scoring algorithm yang analyze:
        // - student skills match dengan job requirements
        // - student experience dan portfolio quality
        // - student university reputation
        // - student GPA dan academic performance
        return Application::with(['student.user', 'student.university', 'problem'])
                        ->whereHas('problem', function($q) use ($institutionId) {
                            $q->where('institution_id', $institutionId);
                        })
                        ->whereIn('status', ['under_review', 'accepted'])
                        ->latest()
                        ->limit($limit)
                        ->get();
    }

    /**
     * prepare time series data untuk chart
     */
    private function prepareTimeSeriesChartData($institutionId, $months = 6)
    {
        $labels = [];
        $newData = [];
        $reviewingData = [];
        $shortlistedData = [];

        // generate data untuk 6 bulan terakhir
        for ($i = $months - 1; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $labels[] = $date->format('M');

            // hitung aplikasi baru (pending)
            $newCount = Application::whereHas('problem', function($q) use ($institutionId) {
                $q->where('institution_id', $institutionId);
            })
            ->where('status', 'pending')
            ->whereMonth('created_at', $date->month)
            ->whereYear('created_at', $date->year)
            ->count();

            // hitung aplikasi dalam review
            $reviewingCount = Application::whereHas('problem', function($q) use ($institutionId) {
                $q->where('institution_id', $institutionId);
            })
            ->where('status', 'under_review')
            ->whereMonth('created_at', $date->month)
            ->whereYear('created_at', $date->year)
            ->count();

            // hitung aplikasi yang di-shortlist (accepted)
            $shortlistedCount = Application::whereHas('problem', function($q) use ($institutionId) {
                $q->where('institution_id', $institutionId);
            })
            ->where('status', 'accepted')
            ->whereMonth('created_at', $date->month)
            ->whereYear('created_at', $date->year)
            ->count();

            $newData[] = $newCount;
            $reviewingData[] = $reviewingCount;
            $shortlistedData[] = $shortlistedCount;
        }

        return [
            'labels' => $labels,
            'new' => $newData,
            'reviewing' => $reviewingData,
            'shortlisted' => $shortlistedData,
        ];
    }
    
/**
     * get jobs by category data untuk bar chart
     * PERBAIKAN: Menggunakan Collection processing untuk menghindari error Group By JSON di Postgres
     */
    private function getJobsCategoryData($institutionId)
    {
        // 1. Ambil hanya kolom sdg_categories dari database (lebih ringan)
        $categories = Problem::where('institution_id', $institutionId)
                        ->whereNotNull('sdg_categories')
                        ->pluck('sdg_categories');

        // 2. Proses menggunakan Collection Laravel
        $stats = $categories->flatten() // Menggabungkan semua array (misal: [[1,2], [3]] menjadi [1, 2, 3])
            ->countBy(function ($sdgId) {
                // Ubah angka ID menjadi Nama Label SDG
                // Kita gunakan helper sdg_label() yang sudah ada di aplikasi Anda
                return function_exists('sdg_label') ? sdg_label($sdgId) : "SDG $sdgId";
            })
            ->sortDesc() // Urutkan dari jumlah terbanyak
            ->take(5);   // Ambil 5 besar

        // 3. Jika data kosong, return default
        if ($stats->isEmpty()) {
            return [
                'labels' => ['No Data'],
                'values' => [0],
            ];
        }

        // 4. Return format yang sesuai untuk Chart.js
        return [
            'labels' => $stats->keys()->toArray(),
            'values' => $stats->values()->toArray(),
        ];
    }

    /**
     * get chart data untuk dashboard (AJAX)
     */
    public function getChartData(Request $request)
    {
        $institution = auth()->user()->institution;
        $days = $request->input('days', 30);

        $timeSeriesData = $this->analyticsService->getTimeSeriesData($institution->id, $days);

        return response()->json([
            'success' => true,
            'data' => $timeSeriesData
        ]);
    }

    /**
     * get sdg distribution data (AJAX)
     */
    public function getSdgDistribution()
    {
        $institution = auth()->user()->institution;

        $sdgData = $this->analyticsService->getProblemsBySdgCategory($institution->id);

        return response()->json([
            'success' => true,
            'data' => $sdgData
        ]);
    }

    /**
     * export dashboard report
     */
    public function exportReport(Request $request)
    {
        $institution = auth()->user()->institution;
        $format = $request->input('format', 'json');

        $report = $this->analyticsService->exportFullReport($institution->id, $format);

        if ($format === 'json') {
            return response()->json([
                'success' => true,
                'data' => $report
            ]);
        }

        // untuk format lain seperti PDF atau Excel, bisa ditambahkan nanti
        return response()->download($report);
    }
}