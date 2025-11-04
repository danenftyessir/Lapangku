<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * service untuk handle file operations dengan Supabase Storage
 * menggunakan Supabase REST API dengan fallback ke local storage
 * 
 * dokumentasi: https://supabase.com/docs/reference/javascript/storage-from-upload
 */
class SupabaseStorageService
{
    protected $projectId;
    protected $serviceKey;
    protected $bucketName;
    protected $baseUrl;
    protected $useSupabase;

    public function __construct()
    {
        $this->projectId = config('services.supabase.project_id');
        $this->serviceKey = config('services.supabase.service_key');
        $this->bucketName = config('services.supabase.bucket', 'kkngo-storage');
        $this->baseUrl = "https://{$this->projectId}.supabase.co/storage/v1";

        // cek apakah supabase dikonfigurasi dengan benar
        $this->useSupabase = !empty($this->projectId) && !empty($this->serviceKey);

        if (config('app.debug') && !$this->useSupabase) {
            Log::warning("⚠️ Supabase config tidak lengkap - akan menggunakan local storage!", [
                'project_id' => $this->projectId ? '✅' : '❌',
                'service_key' => $this->serviceKey ? '✅' : '❌',
                'bucket' => $this->bucketName,
            ]);
        }
    }

    /**
     * upload file ke supabase storage dengan fallback ke local
     * 
     * @param UploadedFile $file file yang akan diupload
     * @param string $path path tujuan di bucket (contoh: problems/file.jpg)
     * @return string|false path file yang berhasil diupload atau false jika gagal
     */
    public function uploadFile(UploadedFile $file, string $path)
    {
        // jika supabase tidak dikonfigurasi, gunakan local storage
        if (!$this->useSupabase) {
            return $this->uploadToLocal($file, $path);
        }

        try {
            // baca file content
            $fileContent = file_get_contents($file->getRealPath());
            $mimeType = $file->getMimeType();
            $fileSize = $file->getSize();

            Log::info("📤 Uploading to Supabase", [
                'path' => $path,
                'mime' => $mimeType,
                'size' => number_format($fileSize) . ' bytes',
                'bucket' => $this->bucketName,
            ]);

            // encode path untuk URL
            $encodedPath = $this->encodePath($path);

            // upload ke supabase menggunakan POST method
            $response = Http::timeout(30)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->serviceKey,
                    'Content-Type' => $mimeType,
                    'x-upsert' => 'true', // allow overwrite jika file sudah ada
                ])
                ->withBody($fileContent, $mimeType)
                ->post("{$this->baseUrl}/object/{$this->bucketName}/{$encodedPath}");

            // check response
            if ($response->successful()) {
                $publicUrl = $this->getPublicUrl($path);
                
                Log::info("✅ Upload SUCCESS to Supabase", [
                    'path' => $path,
                    'status' => $response->status(),
                    'public_url' => $publicUrl,
                ]);
                
                return $path; // return path yang akan disimpan di database
            }

            // jika gagal, log error dan coba local storage
            Log::error("❌ Upload FAILED to Supabase", [
                'path' => $path,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            
            // fallback ke local storage
            Log::info("⚠️ Falling back to local storage");
            return $this->uploadToLocal($file, $path);

        } catch (\Exception $e) {
            Log::error("❌ Upload EXCEPTION to Supabase", [
                'path' => $path,
                'error' => $e->getMessage(),
            ]);
            
            // fallback ke local storage
            Log::info("⚠️ Falling back to local storage");
            return $this->uploadToLocal($file, $path);
        }
    }

    /**
     * upload file ke local storage sebagai fallback
     * 
     * @param UploadedFile $file file yang akan diupload
     * @param string $path path tujuan
     * @return string|false path file atau false jika gagal
     */
    protected function uploadToLocal(UploadedFile $file, string $path)
    {
        try {
            Log::info("📁 Uploading to local storage", ['path' => $path]);
            
            // simpan ke storage/app/public/
            $storedPath = $file->storeAs(
                dirname($path), 
                basename($path), 
                'public'
            );
            
            if ($storedPath) {
                Log::info("✅ Upload SUCCESS to local storage", [
                    'path' => $storedPath,
                    'url' => Storage::disk('public')->url($storedPath)
                ]);
                return $storedPath;
            }
            
            return false;
            
        } catch (\Exception $e) {
            Log::error("❌ Upload FAILED to local storage", [
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * encode path untuk URL (handle spasi dan karakter khusus)
     */
    protected function encodePath(string $path): string
    {
        return implode('/', array_map('rawurlencode', explode('/', $path)));
    }

    /**
     * get public URL untuk file di Supabase
     *
     * @param string $path path file di bucket
     * @return string public URL
     */
    public function getPublicUrl(string $path): string
    {
        // validasi: jika path kosong, return placeholder
        if (empty($path)) {
            Log::debug("⚠️ getPublicUrl: Empty path provided");
            return $this->getPlaceholderAvatar();
        }

        if (!$this->useSupabase) {
            Log::debug("⚠️ getPublicUrl: Supabase not configured, falling back to local storage", [
                'path' => $path
            ]);

            // cek apakah path mengandung 'public/' prefix (local storage)
            if (str_starts_with($path, 'public/')) {
                return asset('storage/' . str_replace('public/', '', $path));
            }

            // path tanpa 'public/' kemungkinan dari Supabase yang belum ter-upload
            // return placeholder instead of broken link
            Log::warning("⚠️ getPublicUrl: Path mismatch - expected local storage format", [
                'path' => $path,
                'expected_format' => 'public/...',
            ]);

            return $this->getPlaceholderAvatar();
        }

        // hilangkan slash di awal jika ada
        $path = ltrim($path, '/');

        // encode path untuk URL
        $encodedPath = $this->encodePath($path);

        $url = "https://{$this->projectId}.supabase.co/storage/v1/object/public/{$this->bucketName}/{$encodedPath}";

        if (config('app.debug')) {
            Log::debug("✅ getPublicUrl: Generated Supabase URL", [
                'path' => $path,
                'bucket' => $this->bucketName,
                'url' => $url,
            ]);
        }

        return $url;
    }

    /**
     * get placeholder avatar URL
     *
     * @return string placeholder avatar URL
     */
    protected function getPlaceholderAvatar(): string
    {
        return 'https://ui-avatars.com/api/?name=User&size=200&background=6366F1&color=ffffff';
    }

    /**
     * cek apakah file exists di bucket
     * 
     * @param string $path path file
     * @return bool true jika exists, false jika tidak
     */
    public function exists(string $path): bool
    {
        if (!$this->useSupabase) {
            return Storage::disk('public')->exists($path);
        }

        try {
            $encodedPath = $this->encodePath($path);
            
            $response = Http::timeout(10)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->serviceKey,
                ])
                ->head("{$this->baseUrl}/object/{$this->bucketName}/{$encodedPath}");

            return $response->successful();

        } catch (\Exception $e) {
            Log::error("❌ Check file exists EXCEPTION", [
                'path' => $path,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * hapus file dari supabase storage
     * 
     * @param string $path path file yang akan dihapus
     * @return bool true jika berhasil, false jika gagal
     */
    public function delete(string $path): bool
    {
        if (!$this->useSupabase) {
            return Storage::disk('public')->delete($path);
        }

        try {
            Log::info("🗑️ Deleting file from Supabase", ['path' => $path]);

            $encodedPath = $this->encodePath($path);

            $response = Http::timeout(10)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->serviceKey,
                ])
                ->delete("{$this->baseUrl}/object/{$this->bucketName}/{$encodedPath}");

            if ($response->successful()) {
                Log::info("✅ Delete SUCCESS", ['path' => $path]);
                return true;
            }

            Log::error("❌ Delete FAILED", [
                'path' => $path,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            
            return false;

        } catch (\Exception $e) {
            Log::error("❌ Delete EXCEPTION", [
                'path' => $path,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * upload gambar problem ke supabase
     * 
     * @param UploadedFile $file file gambar
     * @param int $problemId ID problem
     * @param bool $isCover apakah ini cover image
     * @return string|false path file yang berhasil diupload atau false jika gagal
     */
    public function uploadProblemImage(UploadedFile $file, int $problemId, bool $isCover = false)
    {
        $extension = $file->getClientOriginalExtension();
        $prefix = $isCover ? 'cover' : 'img';
        $filename = "problem-{$problemId}-{$prefix}-" . time() . '.' . $extension;
        $path = 'problems/' . $filename;

        return $this->uploadFile($file, $path);
    }

    /**
     * upload foto profil mahasiswa ke supabase
     * 
     * @param UploadedFile $file file foto
     * @param int $studentId ID mahasiswa
     * @return string|false path file yang berhasil diupload atau false jika gagal
     */
    public function uploadProfilePhoto(UploadedFile $file, int $studentId)
    {
        $extension = $file->getClientOriginalExtension();
        $filename = "student-{$studentId}-profile-" . time() . '.' . $extension;
        $path = 'students/profiles/' . $filename;

        return $this->uploadFile($file, $path);
    }

    /**
     * upload logo institusi ke supabase
     * 
     * @param UploadedFile $file file logo
     * @param int $institutionId ID institusi
     * @return string|false path file yang berhasil diupload atau false jika gagal
     */
    public function uploadInstitutionLogo(UploadedFile $file, int $institutionId)
    {
        $extension = $file->getClientOriginalExtension();
        $filename = "institution-{$institutionId}-logo-" . time() . '.' . $extension;
        $path = 'institutions/logos/' . $filename;

        return $this->uploadFile($file, $path);
    }

    /**
     * upload verification document untuk institusi ke supabase
     *
     * @param UploadedFile $file file verification document
     * @param int $institutionId ID institusi
     * @return string|false path file yang berhasil diupload atau false jika gagal
     */
    public function uploadVerificationDocument(UploadedFile $file, int $institutionId)
    {
        $extension = $file->getClientOriginalExtension();
        $filename = "institution-{$institutionId}-verification-" . time() . '.' . $extension;
        $path = 'institutions/verifications/' . $filename;

        return $this->uploadFile($file, $path);
    }

    /**
     * upload document ke supabase
     *
     * @param UploadedFile $file file document
     * @param string $category kategori document (reports, certificates, dll)
     * @return string|false path file yang berhasil diupload atau false jika gagal
     */
    public function uploadDocument(UploadedFile $file, string $category = 'documents/reports')
    {
        $extension = $file->getClientOriginalExtension();
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $filename = $originalName . '-' . time() . '.' . $extension;
        $path = $category . '/' . $filename;

        return $this->uploadFile($file, $path);
    }

    /**
     * upload proposal application ke supabase
     * 
     * @param UploadedFile $file file proposal
     * @param int $applicationId ID aplikasi
     * @return string|false path file yang berhasil diupload atau false jika gagal
     */
    public function uploadProposal(UploadedFile $file, int $applicationId)
    {
        $extension = $file->getClientOriginalExtension();
        $filename = "application-{$applicationId}-proposal-" . time() . '.' . $extension;
        $path = 'proposals/' . $filename;

        return $this->uploadFile($file, $path);
    }

    /**
     * upload project report ke supabase
     * 
     * @param UploadedFile $file file report
     * @param int $projectId ID project
     * @param string $reportType tipe report (progress, final)
     * @return string|false path file yang berhasil diupload atau false jika gagal
     */
    public function uploadProjectReport(UploadedFile $file, int $projectId, string $reportType = 'progress')
    {
        $extension = $file->getClientOriginalExtension();
        $filename = "project-{$projectId}-{$reportType}-report-" . time() . '.' . $extension;
        $path = 'reports/' . $filename;

        return $this->uploadFile($file, $path);
    }
}