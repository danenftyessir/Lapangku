<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Services\SupabaseService;

class UserSeeder extends Seeder
{
    protected $supabase;

    public function __construct()
    {
        $this->supabase = new SupabaseService();
    }

    /**
     * Run the database seeds.
     *
     * Create users untuk:
     * 1. Company users (user_type = 'company')
     * 2. Student/Talent users (user_type = 'student')
     * 3. Institution users (user_type = 'institution')
     */
    public function run(): void
    {
        $this->command->info('🌱 Seeding users...');

        // ============================================
        // COMPANY USERS (50 users)
        // ============================================
        $companyUsers = $this->generateCompanyUsers();
        $insertedCompany = 0;
        $skippedCompany = 0;
        foreach ($companyUsers as $index => $user) {
            try {
                // Check if user already exists
                $existingUser = $this->supabase->select('users', ['id'], ['email' => $user['email']]);
                if (!empty($existingUser)) {
                    $skippedCompany++;
                    continue;
                }

                $this->supabase->insert('users', $user);
                $insertedCompany++;

                // Flush connection every 10 inserts to avoid prepared statement limit
                if (($index + 1) % 10 == 0) {
                    \DB::reconnect('pgsql');
                }
            } catch (\Exception $e) {
                $this->command->error("❌ Failed to insert user: {$user['email']} - " . $e->getMessage());
            }
        }
        if ($skippedCompany > 0) {
            $this->command->warn("⚠️  Skipped $skippedCompany existing company users");
        }
        $this->command->info("✅ Company users seeded: $insertedCompany new users");

        // ============================================
        // STUDENT/TALENT USERS (200 users)
        // ============================================
        $studentUsers = $this->generateStudentUsers();
        $insertedStudent = 0;
        $skippedStudent = 0;
        foreach ($studentUsers as $index => $user) {
            try {
                // Check if user already exists (check both email and username)
                $existingByEmail = $this->supabase->select('users', ['id'], ['email' => $user['email']]);
                $existingByUsername = $this->supabase->select('users', ['id'], ['username' => $user['username']]);

                if (!empty($existingByEmail) || !empty($existingByUsername)) {
                    $skippedStudent++;
                    continue;
                }

                $this->supabase->insert('users', $user);
                $insertedStudent++;

                // Flush connection every 10 inserts
                if (($index + 1) % 10 == 0) {
                    \DB::reconnect('pgsql');
                    if ($insertedStudent > 0 && $insertedStudent % 50 == 0) {
                        $this->command->info("   ... inserted $insertedStudent students");
                    }
                }
            } catch (\Exception $e) {
                $this->command->error("❌ Failed to insert student: {$user['email']} - " . $e->getMessage());
            }
        }
        if ($skippedStudent > 0) {
            $this->command->warn("⚠️  Skipped $skippedStudent existing student users");
        }
        $this->command->info("✅ Student users seeded: $insertedStudent new users");

        // ============================================
        // INSTITUTION USERS (20 users)
        // ============================================
        $institutionUsers = $this->generateInstitutionUsers();
        $insertedInstitution = 0;
        $skippedInstitution = 0;
        foreach ($institutionUsers as $index => $user) {
            try {
                // Check if user already exists
                $existingUser = $this->supabase->select('users', ['id'], ['email' => $user['email']]);
                if (!empty($existingUser)) {
                    $skippedInstitution++;
                    continue;
                }

                $this->supabase->insert('users', $user);
                $insertedInstitution++;
            } catch (\Exception $e) {
                $this->command->error("❌ Failed to insert institution: {$user['email']} - " . $e->getMessage());
            }
        }
        if ($skippedInstitution > 0) {
            $this->command->warn("⚠️  Skipped $skippedInstitution existing institution users");
        }
        $this->command->info("✅ Institution users seeded: $insertedInstitution new users");

        \DB::reconnect('pgsql'); // Final reconnect
        $totalInserted = $insertedCompany + $insertedStudent + $insertedInstitution;
        $totalSkipped = $skippedCompany + $skippedStudent + $skippedInstitution;
        $this->command->info("🎉 Total users: $totalInserted new, $totalSkipped skipped");
    }

    /**
     * Generate 50 company users
     */
    private function generateCompanyUsers(): array
    {
        $users = [];
        $companyNames = [
            'Wahana Visi Indonesia', 'UNICEF Indonesia', 'Plan International', 'Save the Children',
            'Yayasan Dharma Bhakti Astra', 'Tanoto Foundation', 'Dompet Dhuafa', 'Rumah Zakat',
            'Kopernik', 'Anak Bangsa Bisa', 'Waste4Change', 'Lumbung Desa',
            'Kitabisa', 'Gojek', 'Tokopedia', 'Bukalapak', 'Shopee Indonesia',
            'WWF Indonesia', 'Greenpeace Indonesia', 'Lindungi Hutan', 'Divers Clean Action',
            'Indonesia Mengajar', 'Gerakan Guru Belajar', 'Zenius Education', 'Ruangguru',
            'Halodoc', 'Alodokter', 'Good Doctor', 'Kementerian Kesehatan RI',
            'Tanihub', 'TaniGroup', 'Sayurbox', 'eFishery',
            'Pemda DKI Jakarta', 'Pemda Jawa Barat', 'Pemda Jawa Tengah', 'Pemda Jawa Timur',
            'Kementerian Desa PDTT', 'Kementerian Sosial RI', 'BAPPENAS', 'BPS',
            'Telkom Indonesia', 'Indosat Ooredoo', 'XL Axiata', 'Smartfren',
            'Bank Mandiri', 'BRI', 'BNI', 'BTN', 'Danamon',
        ];

        for ($i = 0; $i < 50; $i++) {
            $companyName = $companyNames[$i];
            $slug = str_replace(' ', '', strtolower($companyName));

            $users[] = [
                'name' => $companyName . ' - HR Admin',
                'username' => 'hr_' . $slug . '_' . $i,
                'email' => 'hr@' . $slug . '.com',
                'password' => Hash::make('password123'),
                'user_type' => 'company',
                'email_verified_at' => now()->toDateTimeString(),
                'created_at' => now()->subDays(rand(1, 365))->toDateTimeString(),
                'updated_at' => now()->toDateTimeString(),
            ];
        }

        return $users;
    }

    /**
     * Generate 200 student/talent users
     */
    private function generateStudentUsers(): array
    {
        $users = [];
        $firstNames = [
            'Andi', 'Budi', 'Citra', 'Dewi', 'Eko', 'Fitri', 'Gita', 'Hadi', 'Indah', 'Joko',
            'Kartika', 'Lina', 'Made', 'Nur', 'Oka', 'Putri', 'Rini', 'Sari', 'Tono', 'Umar',
            'Vina', 'Wati', 'Yoga', 'Zaki', 'Ayu', 'Bagas', 'Candra', 'Dian', 'Fajar', 'Gilang',
        ];

        $lastNames = [
            'Pratama', 'Saputra', 'Wijaya', 'Kusuma', 'Hartono', 'Santoso', 'Kurniawan', 'Setiawan',
            'Permana', 'Wibowo', 'Nugroho', 'Rahman', 'Hidayat', 'Suharto', 'Hakim', 'Firmansyah',
            'Ramadhan', 'Maulana', 'Putra', 'Utomo', 'Gunawan', 'Irawan', 'Mahendra', 'Supardi',
        ];

        $universities = [
            'ITB', 'UI', 'UGM', 'ITS', 'IPB', 'UNDIP', 'UNAIR', 'UNPAD', 'UNS', 'UB',
            'UNHAS', 'USU', 'UNSRI', 'UNAND', 'UNUD', 'UNESA', 'UNJ', 'UPI', 'UNY', 'UIN Jakarta',
        ];

        for ($i = 0; $i < 200; $i++) {
            $firstName = $firstNames[array_rand($firstNames)];
            $lastName = $lastNames[array_rand($lastNames)];
            $fullName = $firstName . ' ' . $lastName;
            $email = strtolower($firstName . '.' . $lastName . rand(1, 999) . '@student.ac.id');
            $university = $universities[array_rand($universities)];

            $username = strtolower($firstName . $lastName . rand(100, 999));

            $users[] = [
                'name' => $fullName,
                'username' => $username,
                'email' => $email,
                'password' => Hash::make('password123'),
                'user_type' => 'student',
                'email_verified_at' => now()->toDateTimeString(),
                // Note: 'university' field removed - not in users table schema
                'created_at' => now()->subDays(rand(1, 730))->toDateTimeString(),
                'updated_at' => now()->toDateTimeString(),
            ];
        }

        return $users;
    }

    /**
     * Generate 20 institution users (pemerintah/desa)
     */
    private function generateInstitutionUsers(): array
    {
        $users = [];
        $institutions = [
            ['name' => 'Desa Karanganyar', 'email' => 'admin@desa-karanganyar.id'],
            ['name' => 'Desa Cijambe', 'email' => 'admin@desa-cijambe.id'],
            ['name' => 'Kelurahan Cigadung', 'email' => 'admin@kelurahan-cigadung.id'],
            ['name' => 'Kelurahan Antapani', 'email' => 'admin@kelurahan-antapani.id'],
            ['name' => 'Puskesmas Cibiru', 'email' => 'admin@puskesmas-cibiru.go.id'],
            ['name' => 'Puskesmas Ujungberung', 'email' => 'admin@puskesmas-ujungberung.go.id'],
            ['name' => 'SDN 1 Bandung', 'email' => 'admin@sdn1-bandung.sch.id'],
            ['name' => 'SMAN 3 Bandung', 'email' => 'admin@sman3-bandung.sch.id'],
            ['name' => 'Kecamatan Coblong', 'email' => 'admin@kec-coblong.go.id'],
            ['name' => 'Kecamatan Cibeunying', 'email' => 'admin@kec-cibeunying.go.id'],
            ['name' => 'Dinas Sosial Kota Bandung', 'email' => 'admin@dinsos-bandung.go.id'],
            ['name' => 'Dinas Pendidikan Kota Bandung', 'email' => 'admin@disdik-bandung.go.id'],
            ['name' => 'BPBD Kota Bandung', 'email' => 'admin@bpbd-bandung.go.id'],
            ['name' => 'Diskominfo Kota Bandung', 'email' => 'admin@diskominfo-bandung.go.id'],
            ['name' => 'Perpustakaan Daerah Jabar', 'email' => 'admin@perpusda-jabar.go.id'],
            ['name' => 'Balai Latihan Kerja Bandung', 'email' => 'admin@blk-bandung.go.id'],
            ['name' => 'Rumah Sakit Umum Daerah', 'email' => 'admin@rsud-bandung.go.id'],
            ['name' => 'Yayasan Sosial Mandiri', 'email' => 'admin@ys-mandiri.org'],
            ['name' => 'Karang Taruna Kota Bandung', 'email' => 'admin@karangtaruna-bandung.org'],
            ['name' => 'LSM Peduli Lingkungan', 'email' => 'admin@lsm-pedulilingkungan.org'],
        ];

        foreach ($institutions as $index => $institution) {
            $username = 'inst_' . str_replace([' ', '.', '-'], '', strtolower($institution['name'])) . '_' . $index;

            $users[] = [
                'name' => $institution['name'] . ' - Administrator',
                'username' => $username,
                'email' => $institution['email'],
                'password' => Hash::make('password123'),
                'user_type' => 'institution',
                'email_verified_at' => now()->toDateTimeString(),
                'created_at' => now()->subDays(rand(1, 365))->toDateTimeString(),
                'updated_at' => now()->toDateTimeString(),
            ];
        }

        return $users;
    }
}
