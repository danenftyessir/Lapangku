<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Services\SupabaseService;

/**
 * REAL INDONESIAN COMPANIES SEEDER
 *
 * Seed 30 perusahaan nyata di Indonesia yang fokus pada:
 * - SDG & Community Development
 * - Field-based KKN Programs
 * - Social Innovation & Technology for Good
 */
class CompanySeederRealIndonesia extends Seeder
{
    protected $supabase;

    public function __construct()
    {
        $this->supabase = new SupabaseService();
    }

    public function run(): void
    {
        $this->command->info('🌱 Seeding 30 Real Indonesian Companies for KKN Programs...');

        $companies = [
            // NGO & NONPROFITS
            ['Wahana Visi Indonesia', 'Non-Profit/NGO', '201-500', 'Jakarta', 'https://www.wahanavisi.org', 'Organisasi kemanusiaan Kristen untuk kesejahteraan anak dan komunitas rentan', [1,2,3,4,6,16]],
            ['UNICEF Indonesia', 'International Organization', '51-200', 'Jakarta', 'https://www.unicef.org/indonesia', 'UN agency untuk hak anak: kesehatan, pendidikan, perlindungan', [1,2,3,4,5,6,10,16]],
            ['Plan International Indonesia', 'Non-Profit/NGO', '51-200', 'Jakarta', 'https://plan-international.or.id', 'Pemberdayaan anak perempuan dan kesetaraan gender', [1,4,5,8,10,16]],
            ['Tanoto Foundation', 'Foundation', '51-200', 'Jakarta', 'https://tanotofoundation.org', 'Yayasan filantropi untuk pendidikan berkualitas', [4,8,10]],
            ['Dompet Dhuafa', 'Islamic Philanthropy', '501-1000', 'Jakarta', 'https://www.dompetdhuafa.org', 'Lembaga filantropi Islam untuk pemberdayaan masyarakat', [1,2,3,4,8,10]],

            // TECH FOR GOOD
            ['Gojek (GoGive)', 'Technology', '1001-5000', 'Jakarta', 'https://www.gojek.com', 'Super app dengan program pemberdayaan mitra driver & UMKM', [1,8,9,11]],
            ['Tokopedia (Tokopedia Cares)', 'E-Commerce', '1001-5000', 'Jakarta', 'https://www.tokopedia.com', 'Marketplace dengan fokus digitalisasi UMKM', [1,4,8,9,10]],
            ['Bukalapak (BukaBantuan)', 'E-Commerce', '1001-5000', 'Jakarta', 'https://www.bukalapak.com', 'Platform e-commerce pemberdayaan warung & UMKM', [1,4,8,9]],

            // ENVIRONMENTAL
            ['WWF Indonesia', 'Environmental NGO', '51-200', 'Jakarta', 'https://www.wwf.id', 'Konservasi keanekaragaman hayati dan ekosistem', [13,14,15]],
            ['Lindungi Hutan', 'Social Enterprise', '11-50', 'Bali', 'https://lindungihutan.com', 'Crowdfunding untuk penanaman pohon dan konservasi', [13,15]],
            ['Waste4Change', 'Waste Management', '51-200', 'Jakarta', 'https://waste4change.com', 'Manajemen sampah terpadu dan berkelanjutan', [11,12,13]],

            // EDUCATION
            ['Indonesia Mengajar', 'Education NGO', '11-50', 'Jakarta', 'https://indonesiamengajar.org', 'Gerakan kirim lulusan terbaik mengajar di daerah terpencil', [4,10]],
            ['Zenius Education', 'EdTech', '201-500', 'Jakarta', 'https://www.zenius.net', 'Platform pembelajaran online gratis & berbayar', [4,10]],
            ['Ruangguru', 'EdTech', '501-1000', 'Jakarta', 'https://www.ruangguru.com', 'Platform bimbel online terbesar di Indonesia', [4,10]],

            // HEALTH
            ['Halodoc', 'HealthTech', '201-500', 'Jakarta', 'https://www.halodoc.com', 'Platform kesehatan digital: konsultasi dokter online', [3]],
            ['Kementerian Kesehatan RI', 'Government Health', '5000+', 'Jakarta', 'https://www.kemkes.go.id', 'Kementerian kesehatan: imunisasi, stunting, pelayanan', [2,3,6]],

            // AGRICULTURE
            ['TaniHub', 'AgriTech', '201-500', 'Jakarta', 'https://www.tanihub.com', 'Platform agrikultur menghubungkan petani dengan konsumen', [1,2,8,12]],
            ['eFishery', 'AgriTech/Aquaculture', '201-500', 'Bandung', 'https://efishery.com', 'Teknologi IoT untuk budidaya ikan otomatis', [2,9,14]],

            // GOVERNMENT
            ['Kementerian Desa PDTT', 'Government', '5000+', 'Jakarta', 'https://kemendesa.go.id', 'Pembangunan desa & pengentasan kemiskinan', [1,2,6,8,10,11]],
            ['Kementerian Sosial RI', 'Government', '5000+', 'Jakarta', 'https://kemensos.go.id', 'Bantuan sosial & pemberdayaan masyarakat', [1,2,3,10]],
            ['Pemerintah Provinsi DKI Jakarta', 'Government', '5000+', 'Jakarta', 'https://jakarta.go.id', 'Pemda DKI: smart city & pelayanan publik digital', [3,4,6,7,9,11,13]],
            ['Pemerintah Provinsi Jawa Barat', 'Government', '5000+', 'Bandung', 'https://jabarprov.go.id', 'Pemda Jabar: ekonomi kreatif & digitalisasi', [1,4,8,9,11]],

            // BANKING & FINTECH
            ['Bank Rakyat Indonesia', 'Banking', '5000+', 'Jakarta', 'https://bri.co.id', 'Bank pemberdayaan UMKM & mikro hingga pedesaan', [1,2,8,10]],
            ['Bank Mandiri (Mandiri Care)', 'Banking', '5000+', 'Jakarta', 'https://www.bankmandiri.co.id', 'Bank terbesar dengan CSR UMKM & pendidikan', [1,4,8]],

            // TELCO
            ['Telkom Indonesia', 'Telecommunications', '5000+', 'Bandung', 'https://www.telkom.co.id', 'Telco terbesar dengan program digital inclusion', [4,8,9,11]],

            // CROWDFUNDING
            ['Kitabisa.com', 'Crowdfunding', '51-200', 'Jakarta', 'https://kitabisa.com', 'Platform crowdfunding sosial terbesar di Indonesia', [1,3,4,10,16]],
            ['Lumbung Desa', 'Social Enterprise', '11-50', 'Jakarta', 'https://lumbungdesa.com', 'Pemberdayaan ekonomi desa & digitalisasi UMKM desa', [1,8,9,10]],

            // SOCIAL ENTERPRISE
            ['Kopernik', 'Social Enterprise', '11-50', 'Bali', 'https://kopernik.info', 'Menghubungkan teknologi sederhana dengan daerah terpencil', [1,7,9,13]],
            ['Anak Bangsa Bisa', 'Social Enterprise', '11-50', 'Jakarta', 'https://www.anakbangsabisa.org', 'Pengembangan pendidikan & kewirausahaan sosial', [4,8,10]],

            // FOUNDATIONS
            ['Rumah Zakat', 'Islamic Philanthropy', '201-500', 'Bandung', 'https://www.rumahzakat.org', 'Lembaga amil zakat: pendidikan, kesehatan, ekonomi, lingkungan', [1,2,3,4,8,13]],
        ];

        $companyCount = 0;
        foreach ($companies as $index => $companyData) {
            [$name, $industry, $size, $location, $website, $description, $sdgFocus] = $companyData;

            // Generate email from company name
            $slug = strtolower(str_replace([' ', '(', ')', '-', '.'], '', $name));
            $email = 'hr@' . $slug . '.com';

            // Find user by email (from UserSeeder)
            $users = $this->supabase->select('users', ['id'], ['email' => $email]);
            if ($users->isEmpty()) {
                $this->command->warn("⚠️  User not found for: $email - Skipping...");
                continue;
            }

            // Get first user from collection
            $firstUser = $users->first();
            if (!$firstUser || !isset($firstUser->id)) {
                $this->command->warn("⚠️  Invalid user data for: $email - Skipping...");
                continue;
            }
            $userId = $firstUser->id;

            // Check if company already exists for this user
            $existingCompany = $this->supabase->select('companies', ['id'], ['user_id' => $userId]);
            if (!empty($existingCompany)) {
                $this->command->warn("⚠️  Company already exists for: $name - Skipping...");
                continue;
            }

            // Insert company (match actual schema from migration)
            $company = [
                'user_id' => $userId,
                'name' => $name,
                'industry' => $industry,
                'employee_count' => $size, // Map company_size to employee_count
                'city' => $location, // Map location to city
                'address' => 'Jl. ' . $name . ' No. ' . rand(1, 100),
                'website' => $website,
                'description' => $description,
                'logo' => null, // Will be uploaded later
                'phone' => '+628' . rand(1000000000, 9999999999),
                'founded_year' => now()->subYears(rand(5, 30))->year,
                'verification_status' => 'verified',
                'verified_at' => now()->subMonths(rand(1, 12))->toDateTimeString(),
                'created_at' => now()->subYears(rand(1, 5))->toDateTimeString(),
                'updated_at' => now()->toDateTimeString(),
            ];

            $this->supabase->insert('companies', $company);
            $companyCount++;
            $this->command->info("✅ [$companyCount/30] $name");
        }

        $this->command->info('');
        $this->command->info('🎉 CompanySeederRealIndonesia completed!');
        $this->command->info("📊 Total companies seeded: $companyCount");
    }
}
