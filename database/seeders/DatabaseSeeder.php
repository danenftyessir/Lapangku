<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * database seeder utama
 * menjalankan semua seeder dalam urutan yang benar
 * 
 * jalankan: php artisan db:seed
 * atau: php artisan migrate:fresh --seed
 */
class DatabaseSeeder extends Seeder
{
    /**
     * seed database aplikasi
     */
    public function run(): void
    {
        $this->command->info('==========================================');
        $this->command->info('   SEEDING DATABASE KKN-GO');
        $this->command->info('==========================================');
        $this->command->newLine();

        // 1. seed data master (provinsi & kabupaten) terlebih dahulu
        $this->call([
            ProvincesRegenciesSeeder::class,
        ]);

        // 2. seed data dummy (users, students, institutions, universities)
        $this->call([
            DummyDataSeeder::class,
        ]);

        // 3. seed problems
        $this->call([
            ProblemsSeeder::class,
        ]);

        // 4. seed problem images (menggunakan gambar yang sudah disiapkan)
        $this->call([
            ProblemImagesSeeder::class,
        ]);

        // 5. seed applications
        $this->call([
            ApplicationsSeeder::class,
        ]);

        // 6. seed projects
        $this->call([
            ProjectsSeeder::class,
        ]);

        // 7. seed documents
        $this->call([
            DocumentsSeeder::class,
        ]);

        // 8. seed notifications (BARU)
        $this->call([
            NotificationsSeeder::class,
        ]);

        $this->command->newLine();
        $this->command->info('==========================================');
        $this->command->info('   SEEDING SELESAI!');
        $this->command->info('==========================================');
        $this->command->newLine();
        
        // tampilkan statistik
        $this->showStatistics();
    }

    /**
     * tampilkan statistik data yang telah di-seed
     */
    private function showStatistics(): void
    {
        $stats = [
            'Users' => \App\Models\User::count(),
            'Students' => \App\Models\Student::count(),
            'Institutions' => \App\Models\Institution::count(),
            'Provinces' => \App\Models\Province::count(),
            'Regencies' => \App\Models\Regency::count(),
            'Universities' => \App\Models\University::count(),
            'Problems' => \App\Models\Problem::count(),
            'Problem Images' => \App\Models\ProblemImage::count(),
            'Applications' => \App\Models\Application::count(),
            'Projects' => \App\Models\Project::count(),
            'Documents' => \App\Models\Document::count(),
            'Notifications' => \App\Models\Notification::count(),
        ];

        $this->command->table(
            ['Entity', 'Count'],
            collect($stats)->map(fn($count, $name) => [$name, $count])->values()
        );
    }
}