<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('institutions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // PERBAIKAN: ubah nama kolom agar konsisten
            $table->string('name');  // sebelumnya: institution_name
            $table->string('type');  // sebelumnya: institution_type
            
            $table->text('address');
            $table->foreignId('province_id')->constrained()->onDelete('cascade');
            $table->foreignId('regency_id')->constrained()->onDelete('cascade');
            
            // PERBAIKAN: ubah nama kolom agar konsisten
            $table->string('email');  // sebelumnya: official_email
            $table->string('phone', 20);  // sebelumnya: phone_number
            
            $table->string('logo_path')->nullable();
            $table->string('pic_name'); // person in charge
            $table->string('pic_position');
            $table->string('verification_document_path')->nullable();
            $table->string('website')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
            
            $table->index('user_id');
            $table->index('province_id');
            $table->index('regency_id');
            $table->index('is_verified');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('institutions');
    }
};