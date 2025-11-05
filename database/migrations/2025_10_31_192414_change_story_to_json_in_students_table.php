<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if column 'stories' already exists
        if (!Schema::hasColumn('students', 'stories')) {
            Schema::table('students', function (Blueprint $table) {
                // rename story to stories and change to JSON
                if (Schema::hasColumn('students', 'story')) {
                    $table->renameColumn('story', 'stories');
                }
            });
        }

        Schema::table('students', function (Blueprint $table) {
            // change stories to JSON type if it exists
            if (Schema::hasColumn('students', 'stories')) {
                $table->json('stories')->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // change stories back to text
            $table->text('stories')->nullable()->change();
        });

        Schema::table('students', function (Blueprint $table) {
            // rename stories back to story
            $table->renameColumn('stories', 'story');
        });
    }
};
