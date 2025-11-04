<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectMilestone;
use App\Models\ProjectReport;
use App\Services\ProjectService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * controller untuk mengelola proyek mahasiswa
 * 
 * path: app/Http/Controllers/Student/MyProjectsController.php
 */
class MyProjectsController extends Controller
{
    protected $projectService;

    public function __construct(ProjectService $projectService)
    {
        $this->projectService = $projectService;
    }

    /**
     * tampilkan halaman my projects
     */
    public function index(Request $request)
    {
        $student = Auth::user()->student;
        
        $query = Project::where('student_id', $student->id)
                       ->with(['problem', 'institution', 'milestones']);

        // filter by status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->active();
            } elseif ($request->status === 'completed') {
                $query->completed();
            } else {
                $query->where('status', $request->status);
            }
        }

        $projects = $query->latest()->paginate(6);

        // statistik
        $stats = $this->projectService->getStudentStats($student->id);

        return view('student.projects.index', compact('projects', 'stats'));
    }

    /**
     * tampilkan detail proyek
     */
    public function show($id)
    {
        $student = Auth::user()->student;
        
        $project = Project::where('id', $id)
                         ->where('student_id', $student->id)
                         ->with([
                             'problem',
                             'institution',
                             'milestones',
                             'reports' => function($query) {
                                 $query->latest()->limit(5);
                             }
                         ])
                         ->firstOrFail();

        return view('student.projects.show', compact('project'));
    }

    /**
     * update milestone progress
     */
    public function updateMilestone(Request $request, $milestoneId)
    {
        $request->validate([
            'progress_percentage' => 'required|integer|min:0|max:100',
            'notes' => 'nullable|string',
        ]);

        try {
            $milestone = $this->projectService->updateMilestoneProgress(
                $milestoneId,
                $request->progress_percentage,
                $request->notes
            );

            return response()->json([
                'success' => true,
                'message' => 'Progress milestone berhasil diupdate',
                'milestone' => $milestone,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal update milestone: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * tampilkan form create report
     */
    public function createReport($projectId)
    {
        $student = Auth::user()->student;
        
        $project = Project::where('id', $projectId)
                         ->where('student_id', $student->id)
                         ->firstOrFail();

        return view('student.projects.create-report', compact('project'));
    }

    /**
     * submit progress report
     */
    public function storeReport(Request $request, $projectId)
    {
        $student = Auth::user()->student;
        
        $project = Project::where('id', $projectId)
                         ->where('student_id', $student->id)
                         ->firstOrFail();

        $request->validate([
            'type' => 'required|in:weekly,monthly',
            'title' => 'required|string|max:255',
            'summary' => 'required|string',
            'activities' => 'required|string',
            'challenges' => 'nullable|string',
            'next_plans' => 'nullable|string',
            'period_start' => 'required|date',
            'period_end' => 'required|date|after:period_start',
            'document' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        try {
            $data = $request->only([
                'type', 'title', 'summary', 'activities', 
                'challenges', 'next_plans', 'period_start', 'period_end'
            ]);
            
            $data['project_id'] = $project->id;
            $data['student_id'] = $student->id;

            $report = $this->projectService->submitReport(
                $project->id,
                $data,
                $request->file('document'),
                $request->file('photos')
            );

            return redirect()
                ->route('student.projects.show', $project->id)
                ->with('success', 'Laporan progress berhasil dikirim');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal mengirim laporan: ' . $e->getMessage());
        }
    }

    /**
     * tampilkan form final report
     */
    public function createFinalReport($projectId)
    {
        $student = Auth::user()->student;
        
        $project = Project::where('id', $projectId)
                         ->where('student_id', $student->id)
                         ->active()
                         ->firstOrFail();

        return view('student.projects.create-final-report', compact('project'));
    }

    /**
     * submit final report
     */
    public function storeFinalReport(Request $request, $projectId)
    {
        $student = Auth::user()->student;
        
        $project = Project::where('id', $projectId)
                         ->where('student_id', $student->id)
                         ->active()
                         ->firstOrFail();

        $request->validate([
            'summary' => 'required|string',
            'activities' => 'required|string',
            'final_report' => 'required|file|mimes:pdf|max:20480',
            'beneficiaries' => 'nullable|integer|min:0',
            'activities_count' => 'nullable|integer|min:0',
        ]);

        try {
            $data = [
                'summary' => $request->summary,
                'activities' => $request->activities,
                'impact_metrics' => [
                    'beneficiaries' => $request->beneficiaries ?? 0,
                    'activities' => $request->activities_count ?? 0,
                ],
            ];

            $this->projectService->submitFinalReport(
                $project->id,
                $data,
                $request->file('final_report')
            );

            // complete project
            $this->projectService->completeProject($project->id);

            return redirect()
                ->route('student.projects.show', $project->id)
                ->with('success', 'Laporan akhir berhasil dikirim. Proyek telah selesai!');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal mengirim laporan akhir: ' . $e->getMessage());
        }
    }

    /**
     * download report document
     */
    public function downloadReport($reportId)
    {
        $student = Auth::user()->student;
        
        $report = ProjectReport::where('id', $reportId)
                              ->where('student_id', $student->id)
                              ->firstOrFail();

        if (!$report->document_path) {
            abort(404, 'Dokumen tidak ditemukan');
        }

        return response()->download(
            storage_path('app/public/' . $report->document_path)
        );
    }
}