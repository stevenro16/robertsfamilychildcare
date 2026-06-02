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
        Schema::table('StaffMember', function (Blueprint $table) {
            if (!Schema::hasColumn('StaffMember', 'startDate')) {
                $table->date('startDate')->nullable()->after('photoUrl');
            }
            if (!Schema::hasColumn('StaffMember', 'yearsExperience')) {
                $table->unsignedSmallInteger('yearsExperience')->nullable()->after('startDate');
            }
        });
    }

    public function down(): void
    {
        Schema::table('StaffMember', function (Blueprint $table) {
            $table->dropColumn(['startDate', 'yearsExperience']);
        });
    }
};
