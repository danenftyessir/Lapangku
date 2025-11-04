<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

/**
 * controller untuk knowledge repository dengan sistem filter yang telah diperbaiki
 * mahasiswa bisa browse dan download dokumen hasil proyek
 */
class KnowledgeRepositoryController extends Controller
{
    /**
     * tampilkan halaman repository dengan filter dan statistik
     */
    public function index(Request $request)
    {
        // query base
        $query = Document::with(['uploader.student', 'province', 'regency'])
            ->where('is_public', true)
            ->where('status', 'approved');

        // filter 1: search keyword - case insensitive dengan ILIKE (PostgreSQL)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'ILIKE', "%{$search}%")
                  ->orWhere('description', 'ILIKE', "%{$search}%")
                  ->orWhere('author_name', 'ILIKE', "%{$search}%")
                  ->orWhere('tags', 'ILIKE', "%{$search}%");
            });
        }

        // ✅ FILTER SDG CATEGORIES - FIXED untuk handle double-encoded JSON
        // gunakan raw SQL dengan JSONB cast
        if ($request->filled('category')) {
            $categories = $request->category;
            
            // pastikan input adalah array
            if (!is_array($categories)) {
                $categories = [$categories];
            }
            
            // convert semua ke integer
            $categories = array_map('intval', array_filter($categories));
            
            if (!empty($categories)) {
                // gunakan raw SQL dengan JSONB cast
                $query->where(function($q) use ($categories) {
                    foreach ($categories as $cat) {
                        $q->orWhereRaw("categories::jsonb @> ?::jsonb", [json_encode([$cat])]);
                    }
                });
            }
        }

        // filter 3: province
        if ($request->filled('province_id')) {
            $query->where('province_id', $request->province_id);
        }

        // filter 4: regency
        if ($request->filled('regency_id')) {
            $query->where('regency_id', $request->regency_id);
        }

        // filter 5: year
        if ($request->filled('year')) {
            $query->whereYear('created_at', $request->year);
        }

        // filter 6: file type
        if ($request->filled('file_type')) {
            $query->where('file_type', $request->file_type);
        }

        // sorting
        $sortBy = $request->get('sort', 'latest');
        switch ($sortBy) {
            case 'popular':
                $query->orderBy('download_count', 'desc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'most_viewed':
                $query->orderBy('view_count', 'desc');
                break;
            case 'title':
                $query->orderBy('title', 'asc');
                break;
            case 'latest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        // hitung total sebelum pagination
        $totalDocuments = (clone $query)->count();

        // pagination
        $documents = $query->paginate(12)->withQueryString();

        // data untuk dropdown
        $provinces = Province::orderBy('name')->get(['id', 'name']);
        
        // featured documents untuk highlight
        $featuredDocuments = Document::where('is_featured', true)
            ->where('is_public', true)
            ->where('status', 'approved')
            ->with(['uploader.student', 'province'])
            ->limit(3)
            ->get();

        // statistik untuk dashboard
        $stats = [
            'total_documents' => Document::where('is_public', true)
                ->where('status', 'approved')
                ->count(),
            'total_downloads' => Document::where('is_public', true)
                ->where('status', 'approved')
                ->sum('download_count'),
            'total_provinces' => Document::where('is_public', true)
                ->where('status', 'approved')
                ->distinct('province_id')
                ->count('province_id'),
            'total_views' => Document::where('is_public', true)
                ->where('status', 'approved')
                ->sum('view_count'),
            'total_institutions' => Document::where('is_public', true)
                ->where('status', 'approved')
                ->distinct('institution_name')
                ->count('institution_name'),
        ];

        // daftar tahun untuk filter
        $years = Document::where('is_public', true)
            ->where('status', 'approved')
            ->selectRaw('DISTINCT EXTRACT(YEAR FROM created_at) as year')
            ->orderBy('year', 'desc')
            ->pluck('year');

        return view('student.repository.index', compact(
            'documents',
            'provinces',
            'featuredDocuments',
            'stats',
            'years',
            'totalDocuments'
        ));
    }

    /**
     * tampilkan detail dokumen
     */
    public function show($id)
    {
        $document = Document::with(['uploader.student', 'province', 'regency'])
            ->where('is_public', true)
            ->where('status', 'approved')
            ->findOrFail($id);

        // increment view count
        $document->incrementViews();

        // dokumen terkait berdasarkan kategori atau lokasi
        $relatedDocuments = Document::where('id', '!=', $document->id)
            ->where('is_public', true)
            ->where('status', 'approved')
            ->where(function($query) use ($document) {
                // dokumen dari province yang sama
                $query->where('province_id', $document->province_id);
                
                // ATAU dokumen dengan kategori yang overlap
                $categories = $document->categories;
                
                if (is_string($categories)) {
                    $categories = json_decode($categories, true) ?? [];
                }
                
                if (is_array($categories) && count($categories) > 0) {
                    $query->orWhere(function($q) use ($categories) {
                        foreach ($categories as $cat) {
                            $q->orWhereJsonContains('categories', $cat);
                        }
                    });
                }
            })
            ->with(['uploader.student', 'province'])
            ->limit(4)
            ->get();

        return view('student.repository.show', compact('document', 'relatedDocuments'));
    }

    /**
     * download dokumen dan increment counter
     */
    public function download($id)
    {
        $document = Document::where('is_public', true)
            ->where('status', 'approved')
            ->findOrFail($id);

        // increment download count
        $document->incrementDownloads();

        // generate URL dari supabase
        $url = document_url($document->file_path);

        // redirect ke URL file
        return redirect($url);
    }

    /**
     * API endpoint: get regencies by province
     */
    public function getRegencies(Request $request)
    {
        $provinceId = $request->province_id;
        
        if (!$provinceId) {
            return response()->json([]);
        }

        $regencies = \App\Models\Regency::where('province_id', $provinceId)
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($regencies);
    }
}