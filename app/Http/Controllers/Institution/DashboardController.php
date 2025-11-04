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

        // recent problems
        $recentProblems = Problem::where('institution_id', $institution->id)
                                ->with(['province', 'regency'])
                                ->latest()
                                ->limit(5)
                                ->get();

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

        // active projects dengan progress
        $activeProjects = Project::with(['student.user', 'student.university', 'problem'])
                                ->where('institution_id', $institution->id)
                                ->where('status', 'active')
                                ->orderBy('progress_percentage', 'asc')
                                ->limit(5)
                                ->get();

        // projects yang perlu direview (completed tapi belum ada rating)
        $pendingReviews = $this->reviewService->getPendingReviews($institution->id, 5);

        // top problems (most applications)
        $topProblems = $this->analyticsService->getTopProblems($institution->id, 5);

        // time series data untuk chart (30 hari terakhir)
        $timeSeriesData = $this->analyticsService->getTimeSeriesData($institution->id, 30);

        // application funnel untuk conversion metrics
        $applicationFunnel = $this->analyticsService->getApplicationFunnel($institution->id);

        // recent reviews yang diberikan
        $recentReviews = $this->reviewService->getRecentInstitutionReviews($institution->id, 3);

        // urgent items
        $urgentItems = [
            'pending_applications' => $stats['applications']['pending'],
            'pending_reviews' => $pendingReviews->count(),
            'overdue_milestones' => Project::where('institution_id', $institution->id)
                ->whereHas('milestones', function($q) {
                    $q->where('target_date', '<', now())
                    ->where('status', '!=', 'completed');
                })->count(),
        ];

        return view('institution.dashboard.index', compact(
            'stats',
            'recentProblems',
            'recentApplications',
            'activeProjects',
            'pendingReviews',
            'topProblems',
            'timeSeriesData',
            'applicationFunnel',
            'recentReviews',
            'urgentItems',
            'institution'
        ));
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