<?php

namespace App\Services;

use App\Models\Institution;
use App\Models\VerificationDocument;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * AI Document Verification Service
 *
 * Menggunakan Claude AI untuk verifikasi dokumen institusi
 * Sesuai dengan Spesifikasi.md Feature 1
 */
class AIDocumentVerificationService
{
    protected $anthropicApiKey;
    protected $supabaseStorageService;

    public function __construct(SupabaseStorageService $supabaseStorageService)
    {
        $this->anthropicApiKey = config('services.anthropic.api_key');
        $this->supabaseStorageService = $supabaseStorageService;
    }

    /**
     * Verify institution documents using AI
     *
     * @param Institution $institution
     * @return array
     */
    public function verifyInstitutionDocuments(Institution $institution): array
    {
        try {
            // Get all documents for this institution
            $documents = $institution->verificationDocuments;

            if ($documents->isEmpty()) {
                throw new \Exception('No documents found for verification');
            }

            // Analyze each document
            $documentResults = [];
            foreach ($documents as $document) {
                $documentResults[] = $this->analyzeDocument($document);
            }

            // Calculate overall verification result
            $overallResult = $this->calculateOverallVerification($documentResults, $institution);

            // Update institution status
            $this->updateInstitutionStatus($institution, $overallResult);

            // Update each document with AI results
            foreach ($documentResults as $result) {
                $this->updateDocumentResult($result['document'], $result);
            }

            return [
                'verification_id' => $overallResult['verification_id'],
                'status' => $overallResult['status'],
                'score' => $overallResult['score'],
                'confidence' => $overallResult['confidence'],
                'reasoning' => $overallResult['reasoning'],
                'flags' => $overallResult['flags'],
                'processed_at' => now()->toIso8601String(),
            ];

        } catch (\Exception $e) {
            Log::error('AI Document Verification Error', [
                'institution_id' => $institution->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw $e;
        }
    }

    /**
     * Analyze single document using Claude AI
     *
     * @param VerificationDocument $document
     * @return array
     */
    protected function analyzeDocument(VerificationDocument $document): array
    {
        // Get document file URL
        $fileUrl = $document->file_url;

        // Prepare prompt based on document type
        $prompt = $this->buildAnalysisPrompt($document);

        // Call Claude API
        $response = $this->callClaudeAPI($prompt, $fileUrl, $document->mime_type);

        // Parse response
        $analysis = $this->parseClaudeResponse($response, $document->document_type);

        return [
            'document' => $document,
            'ai_status' => $analysis['status'],
            'ai_score' => $analysis['score'],
            'ai_confidence' => $analysis['confidence'],
            'ai_flags' => $analysis['flags'],
            'ai_extracted_data' => $analysis['extracted_data'],
            'ai_reasoning' => $analysis['reasoning'],
        ];
    }

    /**
     * Build analysis prompt for Claude based on document type
     *
     * @param VerificationDocument $document
     * @return string
     */
    protected function buildAnalysisPrompt(VerificationDocument $document): string
    {
        $institution = $document->institution;

        $basePrompt = "You are a document verification AI assistant specializing in Indonesian institutional documents.\n\n";
        $basePrompt .= "Institution Information:\n";
        $basePrompt .= "- Name: {$institution->name}\n";
        $basePrompt .= "- Type: {$institution->type}\n";
        $basePrompt .= "- Email: {$institution->email}\n";
        $basePrompt .= "- PIC Name: {$institution->pic_name}\n";
        $basePrompt .= "- PIC Position: {$institution->pic_position}\n\n";

        switch ($document->document_type) {
            case VerificationDocument::TYPE_OFFICIAL_LETTER:
                $basePrompt .= "Task: Analyze this official letter (Surat Pengantar/SK Institusi).\n\n";
                $basePrompt .= "Please verify:\n";
                $basePrompt .= "1. Document authenticity (letterhead, stamps, signatures)\n";
                $basePrompt .= "2. Information consistency with institution data\n";
                $basePrompt .= "3. Date validity and relevance\n";
                $basePrompt .= "4. Proper formatting and official structure\n";
                $basePrompt .= "5. Any signs of tampering or forgery\n\n";
                break;

            case VerificationDocument::TYPE_LOGO:
                $basePrompt .= "Task: Analyze this institution logo.\n\n";
                $basePrompt .= "Please verify:\n";
                $basePrompt .= "1. Image quality and professionalism\n";
                $basePrompt .= "2. Appropriate for official institution\n";
                $basePrompt .= "3. Not a copyrighted or well-known brand logo\n";
                $basePrompt .= "4. Resolution and format suitable\n";
                $basePrompt .= "5. Any suspicious elements\n\n";
                break;

            case VerificationDocument::TYPE_PIC_IDENTITY:
                $basePrompt .= "Task: Analyze this ID card (KTP) image.\n\n";
                $basePrompt .= "Please verify:\n";
                $basePrompt .= "1. Document appears to be authentic KTP\n";
                $basePrompt .= "2. Name matches PIC name: {$institution->pic_name}\n";
                $basePrompt .= "3. Photo quality and visibility\n";
                $basePrompt .= "4. NIK (ID number) is visible and follows format\n";
                $basePrompt .= "5. Any signs of digital manipulation or tampering\n";
                $basePrompt .= "6. Expiry date if visible\n\n";
                break;

            case VerificationDocument::TYPE_NPWP:
                $basePrompt .= "Task: Analyze this NPWP (Tax ID) document.\n\n";
                $basePrompt .= "Please verify:\n";
                $basePrompt .= "1. Document appears to be authentic NPWP\n";
                $basePrompt .= "2. Name matches institution name\n";
                $basePrompt .= "3. NPWP number is visible and follows format\n";
                $basePrompt .= "4. Document quality and authenticity\n";
                $basePrompt .= "5. Any signs of tampering\n\n";
                break;
        }

        $basePrompt .= "Provide your analysis in JSON format:\n";
        $basePrompt .= "{\n";
        $basePrompt .= "  \"score\": 0-100 (verification score),\n";
        $basePrompt .= "  \"confidence\": 0.0-1.0 (confidence level),\n";
        $basePrompt .= "  \"status\": \"approved\" | \"needs_review\" | \"rejected\",\n";
        $basePrompt .= "  \"flags\": [{\"type\": \"warning|error|info\", \"message\": \"description\", \"severity\": 1-10}],\n";
        $basePrompt .= "  \"extracted_data\": {extracted information from document},\n";
        $basePrompt .= "  \"reasoning\": \"detailed explanation of your decision\"\n";
        $basePrompt .= "}\n\n";
        $basePrompt .= "Focus on fraud detection. Be thorough but fair.";

        return $basePrompt;
    }

    /**
     * Call Claude API for document analysis
     *
     * @param string $prompt
     * @param string $fileUrl
     * @param string $mimeType
     * @return array
     */
    protected function callClaudeAPI(string $prompt, string $fileUrl, string $mimeType): array
    {
        try {
            // Get file content
            $fileContent = $this->getFileContent($fileUrl);

            // Prepare message content
            $content = [];

            // Add image if it's an image file
            if (str_starts_with($mimeType, 'image/')) {
                $content[] = [
                    'type' => 'image',
                    'source' => [
                        'type' => 'base64',
                        'media_type' => $mimeType,
                        'data' => base64_encode($fileContent),
                    ],
                ];
            }

            // Add text prompt
            $content[] = [
                'type' => 'text',
                'text' => $prompt,
            ];

            // Call Claude API
            $response = Http::withHeaders([
                'x-api-key' => $this->anthropicApiKey,
                'anthropic-version' => '2023-06-01',
                'content-type' => 'application/json',
            ])->timeout(60)->post('https://api.anthropic.com/v1/messages', [
                'model' => 'claude-3-haiku-20240307', // Using Haiku for cost optimization
                'max_tokens' => 2048,
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => $content,
                    ],
                ],
            ]);

            if (!$response->successful()) {
                throw new \Exception('Claude API Error: ' . $response->body());
            }

            $data = $response->json();

            return $data;

        } catch (\Exception $e) {
            Log::error('Claude API Call Error', [
                'error' => $e->getMessage(),
                'file_url' => $fileUrl,
            ]);

            throw $e;
        }
    }

    /**
     * Get file content from URL
     *
     * @param string $fileUrl
     * @return string
     */
    protected function getFileContent(string $fileUrl): string
    {
        try {
            $response = Http::timeout(30)->get($fileUrl);

            if (!$response->successful()) {
                throw new \Exception('Failed to download file from: ' . $fileUrl);
            }

            return $response->body();

        } catch (\Exception $e) {
            Log::error('File Download Error', [
                'error' => $e->getMessage(),
                'url' => $fileUrl,
            ]);

            throw $e;
        }
    }

    /**
     * Parse Claude API response
     *
     * @param array $response
     * @param string $documentType
     * @return array
     */
    protected function parseClaudeResponse(array $response, string $documentType): array
    {
        try {
            // Extract content from Claude response
            $content = $response['content'][0]['text'] ?? '';

            // Try to parse JSON from response
            $jsonStart = strpos($content, '{');
            $jsonEnd = strrpos($content, '}');

            if ($jsonStart !== false && $jsonEnd !== false) {
                $jsonString = substr($content, $jsonStart, $jsonEnd - $jsonStart + 1);
                $analysis = json_decode($jsonString, true);

                if (json_last_error() === JSON_ERROR_NONE) {
                    return [
                        'score' => $analysis['score'] ?? 50,
                        'confidence' => $analysis['confidence'] ?? 0.5,
                        'status' => $analysis['status'] ?? 'needs_review',
                        'flags' => $analysis['flags'] ?? [],
                        'extracted_data' => $analysis['extracted_data'] ?? [],
                        'reasoning' => $analysis['reasoning'] ?? $content,
                    ];
                }
            }

            // Fallback: return needs_review with original content
            return [
                'score' => 60,
                'confidence' => 0.6,
                'status' => 'needs_review',
                'flags' => [
                    [
                        'type' => 'info',
                        'message' => 'AI response could not be parsed properly',
                        'severity' => 5,
                    ],
                ],
                'extracted_data' => [],
                'reasoning' => $content,
            ];

        } catch (\Exception $e) {
            Log::error('Parse Claude Response Error', [
                'error' => $e->getMessage(),
                'response' => $response,
            ]);

            return [
                'score' => 50,
                'confidence' => 0.5,
                'status' => 'needs_review',
                'flags' => [
                    [
                        'type' => 'error',
                        'message' => 'Error parsing AI response',
                        'severity' => 7,
                    ],
                ],
                'extracted_data' => [],
                'reasoning' => 'Error occurred during analysis. Manual review required.',
            ];
        }
    }

    /**
     * Calculate overall verification from all documents
     *
     * @param array $documentResults
     * @param Institution $institution
     * @return array
     */
    protected function calculateOverallVerification(array $documentResults, Institution $institution): array
    {
        $totalScore = 0;
        $totalConfidence = 0;
        $allFlags = [];
        $hasRejected = false;
        $needsReview = false;

        foreach ($documentResults as $result) {
            $totalScore += $result['ai_score'];
            $totalConfidence += $result['ai_confidence'];
            $allFlags = array_merge($allFlags, $result['ai_flags']);

            if ($result['ai_status'] === 'rejected') {
                $hasRejected = true;
            }

            if ($result['ai_status'] === 'needs_review') {
                $needsReview = true;
            }
        }

        $avgScore = $totalScore / count($documentResults);
        $avgConfidence = $totalConfidence / count($documentResults);

        // Decision logic based on Spesifikasi.md
        if ($hasRejected || $avgScore < 60) {
            $status = 'rejected';
            $verificationStatus = Institution::STATUS_REJECTED;
            $reasoning = 'Document verification failed. Critical issues detected. ';
            $reasoning .= 'Score: ' . round($avgScore, 2) . '/100. ';
            $reasoning .= 'Please review flagged issues and resubmit with correct documents.';
        } elseif ($needsReview || $avgScore < 85 || $avgConfidence < 0.8) {
            $status = 'needs_review';
            $verificationStatus = Institution::STATUS_NEEDS_REVIEW;
            $reasoning = 'Document verification requires human review. ';
            $reasoning .= 'Score: ' . round($avgScore, 2) . '/100, Confidence: ' . round($avgConfidence, 2) . '. ';
            $reasoning .= 'Some warnings detected. Manual verification recommended.';
        } else {
            $status = 'approved';
            $verificationStatus = Institution::STATUS_PENDING_PAYMENT;
            $reasoning = 'All documents verified successfully. ';
            $reasoning .= 'Score: ' . round($avgScore, 2) . '/100, Confidence: ' . round($avgConfidence, 2) . '. ';
            $reasoning .= 'Institution can proceed to payment and subscription selection.';
        }

        return [
            'verification_id' => 'ver_' . Str::random(16),
            'status' => $verificationStatus,
            'ai_status' => $status,
            'score' => round($avgScore, 2),
            'confidence' => round($avgConfidence, 2),
            'flags' => $allFlags,
            'reasoning' => $reasoning,
        ];
    }

    /**
     * Update institution with verification results
     *
     * @param Institution $institution
     * @param array $result
     * @return void
     */
    protected function updateInstitutionStatus(Institution $institution, array $result): void
    {
        $institution->update([
            'verification_status' => $result['status'],
            'verification_score' => $result['score'],
            'verification_confidence' => $result['confidence'],
            'verified_at' => now(),
        ]);

        // Update legacy is_verified for backward compatibility
        if ($result['status'] === Institution::STATUS_PENDING_PAYMENT
            || $result['status'] === Institution::STATUS_ACTIVE) {
            $institution->update(['is_verified' => true]);
        }
    }

    /**
     * Update document with AI results
     *
     * @param VerificationDocument $document
     * @param array $result
     * @return void
     */
    protected function updateDocumentResult(VerificationDocument $document, array $result): void
    {
        $document->update([
            'ai_status' => $result['ai_status'],
            'ai_score' => $result['ai_score'],
            'ai_confidence' => $result['ai_confidence'],
            'ai_flags' => $result['ai_flags'],
            'ai_extracted_data' => $result['ai_extracted_data'],
            'ai_reasoning' => $result['ai_reasoning'],
            'ai_processed_at' => now(),
        ]);
    }
}
