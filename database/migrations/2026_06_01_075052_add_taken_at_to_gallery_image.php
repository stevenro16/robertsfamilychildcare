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
        Schema::table('GalleryImage', function (Blueprint $table) {
            if (!Schema::hasColumn('GalleryImage', 'takenAt')) {
                $table->date('takenAt')->nullable()->after('caption');
            }
        });
    }

    public function down(): void
    {
        Schema::table('GalleryImage', function (Blueprint $table) {
            $table->dropColumn('takenAt');
        });
    }
};
