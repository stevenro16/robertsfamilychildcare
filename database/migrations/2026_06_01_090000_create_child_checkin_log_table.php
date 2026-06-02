<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ChildCheckinLog', function (Blueprint $table) {
            $table->string('id', 32)->primary();
            $table->string('childId', 32)->index();
            $table->enum('action', ['CHECKIN', 'CHECKOUT']);
            $table->string('employeeId', 32)->nullable()->index();
            $table->timestamp('occurredAt')->useCurrent();
            $table->string('note', 500)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ChildCheckinLog');
    }
};
