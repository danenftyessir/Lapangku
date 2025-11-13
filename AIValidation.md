# AI Validation System - Karsa Institution Registration

## Overview
Sistem validasi berbasis AI untuk registrasi instansi menggunakan Claude AI dan Cohere untuk memvalidasi dokumen dan data instansi secara otomatis sebelum review manual oleh admin.

---

## Architecture Diagram

```
┌─────────────────────────────────────────────────────────────────────┐
│                        USER REGISTRATION FLOW                        │
└─────────────────────────────────────────────────────────────────────┘

1. User mengisi form (4 steps)
   ↓
2. Submit → Quick Validation (Non-file fields only)
   ├─ Error → Redirect ke step yang error
   └─ Success → Simpan data + Upload files ke Supabase
       ↓
3. Toast Notification: "Ajuan registrasi berhasil! Cek email berkala"
   ↓
4. Dispatch AI Validation Job (Background Process)

┌─────────────────────────────────────────────────────────────────────┐
│                      AI VALIDATION PROCESS                           │
│                     (Background Queue Job)                           │
└─────────────────────────────────────────────────────────────────────┘

5. Job AI Validation dimulai:

   A. Document Verification (Claude AI)
      ├─ Verify PDF dokumen verifikasi
      │  ├─ Check legitimacy (surat resmi instansi)
      │  ├─ Extract metadata (tanggal, nama, jabatan)
      │  ├─ Validate format dan struktur
      │  └─ Score: 0-100
      │
      ├─ Verify KTP (Claude Vision)
      │  ├─ Check NIK format dan validitas
      │  ├─ Match nama dengan data registrasi
      │  ├─ Check image quality
      │  └─ Score: 0-100
      │
      └─ Verify NPWP (Claude Vision)
         ├─ Check NPWP format (15 digit)
         ├─ Match nama dengan data registrasi
         ├─ Check validity
         └─ Score: 0-100

   B. Logo Analysis (Cohere + Claude Vision)
      ├─ Check image quality dan resolution
      ├─ Verify logo adalah logo instansi (bukan random image)
      ├─ Detect offensive content
      └─ Score: 0-100

   C. Data Consistency Check (Cohere)
      ├─ Verify nama PIC match dengan KTP
      ├─ Verify alamat consistency
      ├─ Check email domain validity (official domain)
      ├─ Phone number format validation
      └─ Score: 0-100

   D. Institution Type Verification (Claude)
      ├─ Verify jenis instansi match dengan dokumen
      ├─ Cross-check dengan database existing
      ├─ Detect suspicious patterns
      └─ Score: 0-100

6. Calculate Total Score:
   Total = (DocScore × 0.35) + (KTPScore × 0.20) + (NPWPScore × 0.15) +
           (LogoScore × 0.10) + (DataScore × 0.15) + (TypeScore × 0.05)

7. Decision Tree:

   ├─ Score < 50: AUTO REJECT
   │  └─ Email: "Registrasi ditolak - Data tidak memenuhi standar"
   │
   ├─ Score 50-69: MANUAL REVIEW REQUIRED (Low Priority)
   │  └─ Status: "pending_manual_review_low"
   │
   ├─ Score 70-84: MANUAL REVIEW REQUIRED (Medium Priority)
   │  └─ Status: "pending_manual_review_medium"
   │
   └─ Score ≥ 85: MANUAL REVIEW REQUIRED (High Priority)
      └─ Status: "pending_manual_review_high"
      └─ Email: "Registrasi diterima - Menunggu verifikasi admin"

┌─────────────────────────────────────────────────────────────────────┐
│                        ADMIN DASHBOARD                               │
└─────────────────────────────────────────────────────────────────────┘

8. Admin Login → Institution Verification Page

   View: Table dengan columns:
   ├─ Institution Name
   ├─ Type
   ├─ Email
   ├─ AI Score (Badge: Red/Yellow/Green)
   ├─ Priority (High/Medium/Low)
   ├─ Registration Date
   ├─ Status
   └─ Actions (View Details / Approve / Reject)

   Sorting: Default by AI Score DESC (highest first)

9. Admin Review:

   A. View Details Page:
      ├─ Institution Data (all fields)
      ├─ AI Validation Report:
      │  ├─ Document Verification Result
      │  ├─ KTP Verification Result
      │  ├─ NPWP Verification Result
      │  ├─ Logo Analysis Result
      │  ├─ Data Consistency Result
      │  └─ Institution Type Verification Result
      ├─ Preview Files:
      │  ├─ Logo (image preview)
      │  ├─ Verification Document (PDF viewer)
      │  ├─ KTP (image preview)
      │  └─ NPWP (image preview)
      └─ Action Buttons:
         ├─ Approve
         ├─ Reject (with reason)
         └─ Request More Info

   B. Approval Actions:

      ├─ APPROVE:
      │  ├─ Update institution.is_verified = true
      │  ├─ Update institution.verification_status = 'verified'
      │  ├─ Update institution.verified_at = now()
      │  ├─ Update institution.verified_by = admin_id
      │  └─ Send Email: "Akun Anda Telah Diverifikasi"
      │
      └─ REJECT:
         ├─ Update institution.is_verified = false
         ├─ Update institution.verification_status = 'rejected'
         ├─ Store rejection reason
         ├─ Send Email: "Registrasi Ditolak - [Reason]"
         └─ Suggest resubmission

┌─────────────────────────────────────────────────────────────────────┐
│                      EMAIL NOTIFICATIONS                             │
└─────────────────────────────────────────────────────────────────────┘

Email Templates (Formal, No Emoji):

1. Initial Submission Email:
   Subject: "Registrasi Diterima - Dalam Proses Verifikasi"
   Content:
   - Terima kasih telah mendaftar
   - Data sedang divalidasi
   - Estimasi 1-3 hari kerja
   - Akan menerima update via email

2. AI Validation Passed Email:
   Subject: "Registrasi Lolos Validasi - Menunggu Persetujuan Admin"
   Content:
   - Validasi otomatis telah selesai
   - Skor validasi: [Score]
   - Status: Menunggu verifikasi manual admin
   - Estimasi 1-3 hari kerja

3. Admin Approved Email:
   Subject: "Selamat! Akun Karsa Anda Telah Diverifikasi"
   Content:
   - Akun telah disetujui
   - Dapat login menggunakan username/email
   - Link ke halaman login
   - Panduan memulai posting proyek

4. Admin Rejected Email:
   Subject: "Pemberitahuan Penolakan Registrasi"
   Content:
   - Registrasi tidak dapat disetujui
   - Alasan penolakan: [Reason]
   - Saran perbaikan
   - Link untuk registrasi ulang

5. Auto Rejected Email (Score < 50):
   Subject: "Registrasi Tidak Dapat Diproses"
   Content:
   - Data tidak memenuhi standar minimum
   - Mohon periksa kembali dokumen
   - Panduan dokumen yang benar
   - Link registrasi ulang

---

## Database Schema Changes

### institutions table - Add columns:
```sql
ALTER TABLE institutions ADD COLUMN verification_document_path VARCHAR(255);
ALTER TABLE institutions ADD COLUMN ktp_path VARCHAR(255);
ALTER TABLE institutions ADD COLUMN npwp_path VARCHAR(255);
ALTER TABLE institutions ADD COLUMN ai_validation_score DECIMAL(5,2) DEFAULT 0;
ALTER TABLE institutions ADD COLUMN ai_validation_report JSON;
ALTER TABLE institutions ADD COLUMN verification_status ENUM(
    'pending_ai_validation',
    'pending_manual_review_low',
    'pending_manual_review_medium',
    'pending_manual_review_high',
    'verified',
    'rejected',
    'auto_rejected'
) DEFAULT 'pending_ai_validation';
ALTER TABLE institutions ADD COLUMN rejection_reason TEXT NULL;
ALTER TABLE institutions ADD COLUMN verified_at TIMESTAMP NULL;
ALTER TABLE institutions ADD COLUMN verified_by INT NULL;
ALTER TABLE institutions ADD FOREIGN KEY (verified_by) REFERENCES users(id);
```

### Create new table: ai_validation_logs
```sql
CREATE TABLE ai_validation_logs (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    institution_id BIGINT NOT NULL,
    validation_type VARCHAR(50) NOT NULL, -- 'document', 'ktp', 'npwp', 'logo', 'data', 'type'
    score DECIMAL(5,2) NOT NULL,
    details JSON,
    api_used VARCHAR(50), -- 'claude', 'cohere'
    processing_time INT, -- milliseconds
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (institution_id) REFERENCES institutions(id) ON DELETE CASCADE
);
```

### Update users table:
```sql
ALTER TABLE users ADD COLUMN role ENUM('student', 'institution', 'admin') DEFAULT 'student';
```

---

## File Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/
│   │   │   ├── InstitutionVerificationController.php  [NEW]
│   │   │   └── DashboardController.php                [NEW]
│   │   └── Auth/
│   │       └── RegisterController.php                 [UPDATE]
│   ├── Middleware/
│   │   └── AdminMiddleware.php                        [NEW]
│   └── Requests/
│       └── InstitutionRegisterRequest.php             [UPDATE]
├── Jobs/
│   └── ValidateInstitutionWithAI.php                  [NEW]
├── Mail/
│   ├── InstitutionRegistrationReceived.php            [UPDATE]
│   ├── InstitutionAIValidationPassed.php              [NEW]
│   ├── InstitutionApproved.php                        [NEW]
│   ├── InstitutionRejected.php                        [NEW]
│   └── InstitutionAutoRejected.php                    [NEW]
├── Models/
│   ├── Institution.php                                [UPDATE]
│   └── AIValidationLog.php                            [NEW]
└── Services/
    ├── AI/
    │   ├── ClaudeService.php                          [NEW]
    │   ├── CohereService.php                          [NEW]
    │   └── InstitutionValidationService.php           [NEW]
    └── SupabaseStorageService.php                     [UPDATE]

resources/views/
├── admin/
│   ├── layout.blade.php                               [NEW]
│   ├── dashboard.blade.php                            [NEW]
│   └── institutions/
│       ├── index.blade.php                            [NEW]
│       ├── show.blade.php                             [NEW]
│       └── components/
│           ├── ai-score-badge.blade.php               [NEW]
│           └── validation-report.blade.php            [NEW]
├── auth/
│   └── institution-register.blade.php                 [UPDATE]
└── emails/
    ├── institution-registration-received.blade.php    [UPDATE]
    ├── institution-ai-validation-passed.blade.php     [NEW]
    ├── institution-approved.blade.php                 [NEW]
    ├── institution-rejected.blade.php                 [NEW]
    └── institution-auto-rejected.blade.php            [NEW]

routes/
├── web.php                                            [UPDATE]
└── api.php                                            [UPDATE]

database/
├── migrations/
│   ├── xxxx_add_ai_validation_to_institutions.php     [NEW]
│   ├── xxxx_create_ai_validation_logs_table.php       [NEW]
│   └── xxxx_add_role_to_users_table.php               [NEW]
└── seeders/
    └── AdminUserSeeder.php                            [NEW]
```

---

## Implementation Steps

### Phase 1: Database & Models (Priority: HIGH)
1. Create migrations for new columns and tables
2. Update Institution model with new fields and relationships
3. Create AIValidationLog model
4. Update User model with role field

### Phase 2: Form Updates (Priority: HIGH)
1. Update InstitutionRegisterRequest:
   - Add KTP validation rules
   - Add NPWP validation rules
   - Update verification_document to required
2. Update institution-register.blade.php:
   - Add KTP upload field in Step 3
   - Add NPWP upload field in Step 3
   - Update form styling for new fields

### Phase 3: Registration Flow Update (Priority: HIGH)
1. Update RegisterController:
   - Implement quick validation (non-file only)
   - Save data to database
   - Upload files to Supabase
   - Dispatch ValidateInstitutionWithAI job
   - Return success with toast message
2. Update toast message to be concise

### Phase 4: AI Services (Priority: HIGH)
1. Create ClaudeService:
   - Document verification method
   - KTP verification method (vision)
   - NPWP verification method (vision)
   - Institution type verification method
2. Create CohereService:
   - Logo analysis method
   - Data consistency check method
3. Create InstitutionValidationService:
   - Orchestrate all AI validations
   - Calculate total score
   - Make decision based on score
   - Update institution record

### Phase 5: Background Job (Priority: HIGH)
1. Create ValidateInstitutionWithAI Job:
   - Call InstitutionValidationService
   - Store validation results
   - Log to ai_validation_logs table
   - Send appropriate email based on result
   - Update institution status

### Phase 6: Email System (Priority: MEDIUM)
1. Update InstitutionRegistered email (make formal, no emoji)
2. Create InstitutionAIValidationPassed email
3. Create InstitutionApproved email
4. Create InstitutionRejected email
5. Create InstitutionAutoRejected email
6. Add Karsa logo to all email templates

### Phase 7: Admin System (Priority: MEDIUM)
1. Create AdminMiddleware
2. Update routes with admin middleware
3. Create admin layout template
4. Create admin dashboard
5. Create InstitutionVerificationController:
   - Index method (list with sorting)
   - Show method (detail view with AI report)
   - Approve method
   - Reject method
6. Create admin views:
   - institutions/index.blade.php (table view)
   - institutions/show.blade.php (detail view)
   - Components for AI score badge and validation report

### Phase 8: Admin User Setup (Priority: LOW)
1. Create AdminUserSeeder
2. Seed initial admin user
3. Document admin credentials

### Phase 9: Testing & Refinement (Priority: LOW)
1. Test complete registration flow
2. Test AI validation with various documents
3. Test admin approval/rejection flow
4. Test email delivery
5. Performance optimization for queue processing

---

## API Integration Details

### Claude AI API
**Endpoint**: `https://api.anthropic.com/v1/messages`

**Use Cases**:
1. **Document Verification**
   - Model: `claude-3-5-sonnet-20241022`
   - Input: PDF text extraction
   - Output: Legitimacy score + extracted metadata

2. **KTP Verification (Vision)**
   - Model: `claude-3-5-sonnet-20241022`
   - Input: Image (base64)
   - Output: NIK validation + name matching + quality score

3. **NPWP Verification (Vision)**
   - Model: `claude-3-5-sonnet-20241022`
   - Input: Image (base64)
   - Output: NPWP format validation + name matching

4. **Institution Type Verification**
   - Model: `claude-3-5-sonnet-20241022`
   - Input: All institution data + document text
   - Output: Type consistency score + suspicious pattern detection

**Cost Estimation**:
- ~$0.03 per institution validation (4 Claude calls)

### Cohere API
**Endpoint**: `https://api.cohere.ai/v1/classify`

**Use Cases**:
1. **Logo Analysis**
   - Model: `embed-english-v3.0` + custom classification
   - Input: Logo URL + image features
   - Output: Logo quality score + content appropriateness

2. **Data Consistency Check**
   - Model: `command-r-plus`
   - Input: All institution data fields
   - Output: Consistency score + anomaly detection

**Cost Estimation**:
- ~$0.01 per institution validation (2 Cohere calls)

**Total AI Cost per Institution**: ~$0.04

---

## Configuration

### .env additions:
```env
# AI Services
CLAUDE_API_KEY=your_claude_api_key
COHERE_API_KEY=your_cohere_api_key

# AI Validation Settings
AI_VALIDATION_MIN_SCORE=50
AI_VALIDATION_AUTO_APPROVE_SCORE=95
AI_VALIDATION_HIGH_PRIORITY_THRESHOLD=85
AI_VALIDATION_MEDIUM_PRIORITY_THRESHOLD=70

# Queue Settings
QUEUE_CONNECTION=database
```

### config/ai-validation.php:
```php
return [
    'claude' => [
        'api_key' => env('CLAUDE_API_KEY'),
        'model' => 'claude-3-5-sonnet-20241022',
        'max_tokens' => 4096,
    ],
    'cohere' => [
        'api_key' => env('COHERE_API_KEY'),
        'model' => 'command-r-plus',
    ],
    'scoring' => [
        'weights' => [
            'document' => 0.35,
            'ktp' => 0.20,
            'npwp' => 0.15,
            'logo' => 0.10,
            'data' => 0.15,
            'type' => 0.05,
        ],
        'thresholds' => [
            'auto_reject' => 50,
            'low_priority' => 70,
            'medium_priority' => 85,
            'auto_approve' => 95,
        ],
    ],
];
```

---

## Security Considerations

1. **File Upload Security**:
   - Validate file types strictly (PDF for documents, JPG/PNG for images)
   - Scan for malware before AI processing
   - Limit file size (PDF: 5MB, Images: 2MB)
   - Store in secure Supabase bucket with access control

2. **API Key Security**:
   - Store API keys in .env (never commit)
   - Use Laravel encryption for sensitive data
   - Implement rate limiting for AI API calls

3. **Admin Access**:
   - Implement proper role-based access control (RBAC)
   - Log all admin actions
   - Require strong authentication
   - Optional: 2FA for admin accounts

4. **Data Privacy**:
   - Encrypt sensitive documents in storage
   - Anonymize data in logs
   - Comply with data retention policies
   - Allow users to request data deletion

---

## Monitoring & Logging

1. **AI Validation Metrics**:
   - Track validation success/failure rates
   - Monitor API response times
   - Log API costs
   - Alert on high rejection rates

2. **Admin Activity Logs**:
   - Log all approvals/rejections
   - Track admin response times
   - Monitor workload distribution

3. **Email Delivery**:
   - Track email delivery rates
   - Monitor bounce rates
   - Log failed deliveries

---

## Future Enhancements

1. **Machine Learning**:
   - Train custom model based on admin decisions
   - Improve scoring algorithm over time
   - Reduce dependency on external APIs

2. **Advanced Features**:
   - Real-time status tracking for users
   - WhatsApp notifications
   - Document resubmission without new registration
   - Bulk admin actions

3. **Analytics Dashboard**:
   - Registration trends
   - AI accuracy metrics
   - Admin performance metrics
   - Rejection reason analytics

---

## Timeline Estimate

- **Phase 1-2**: 1 day (Database & Form Updates)
- **Phase 3**: 0.5 days (Registration Flow)
- **Phase 4-5**: 2 days (AI Services & Background Job)
- **Phase 6**: 1 day (Email System)
- **Phase 7**: 1.5 days (Admin System)
- **Phase 8**: 0.5 days (Admin Setup)
- **Phase 9**: 1 day (Testing)

**Total**: ~7.5 days for full implementation

---

## Success Criteria

✅ User can submit registration with KTP and NPWP
✅ AI validation completes within 5 minutes
✅ Admin receives sorted list by AI score
✅ Email notifications sent at each stage
✅ 95%+ AI validation accuracy (compared to admin decisions)
✅ < 3 days average approval time
✅ Zero security vulnerabilities
✅ Comprehensive logging and monitoring

---

## Notes

- AI validation runs asynchronously to avoid blocking user
- Admin has final decision authority (AI is recommendation only)
- System should handle API failures gracefully
- All emails should be professional and formal
- Mobile-responsive admin panel is essential

---

**Document Version**: 1.0
**Last Updated**: 2025-11-13
**Maintained By**: Development Team
