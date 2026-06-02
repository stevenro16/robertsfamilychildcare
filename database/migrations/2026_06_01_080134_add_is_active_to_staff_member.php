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
            if (!Schema::hasColumn('StaffMember', 'isActive')) {
                $table->boolean('isActive')->default(true)->after('sortOrder');
            }
        });
    }

    public function down(): void
    {
        Schema::table('StaffMember', function (Blueprint $table) {
            $table->dropColumn('isActive');
        });
    }
};
