<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Employee', function (Blueprint $table) {
            $table->char('id', 32)->primary();
            $table->string('name', 100);
            $table->string('email')->unique();
            $table->string('password');
            $table->string('role', 20)->default('STAFF');
            $table->boolean('isActive')->default(true);
            $table->boolean('mustChangePassword')->default(false);
            $table->rememberToken();
            $table->timestamp('createdAt')->useCurrent();
        });

        Schema::create('Contact', function (Blueprint $table) {
            $table->char('id', 32)->primary();
            $table->string('name', 200);
            $table->string('email', 200)->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('photoUrl')->nullable();
            $table->timestamp('createdAt')->useCurrent();
            $table->timestamp('updatedAt')->nullable();
        });

        Schema::create('ParentUser', function (Blueprint $table) {
            $table->char('id', 32)->primary();
            $table->char('contactId', 32)->nullable()->index();
            $table->string('username', 100)->unique();
            $table->string('password');
            $table->boolean('mustChangePassword')->default(true);
            $table->rememberToken();
            $table->timestamp('createdAt')->useCurrent();
            $table->timestamp('updatedAt')->nullable();
        });

        Schema::create('Inquiry', function (Blueprint $table) {
            $table->char('id', 32)->primary();
            $table->string('parentName', 255);
            $table->string('parentEmail', 255)->nullable();
            $table->string('parentPhone', 50)->nullable();
            $table->string('childName', 255)->nullable();
            $table->date('childDob')->nullable();
            $table->date('desiredStart')->nullable();
            $table->string('hearAbout', 255)->nullable();
            $table->string('programInterest', 255)->nullable();
            $table->text('message')->nullable();
            $table->string('status', 50)->default('NEW');
            $table->boolean('isSnoozed')->default(false);
            $table->timestamp('snoozeUntil')->nullable();
            $table->timestamp('createdAt')->useCurrent();
            $table->timestamp('updatedAt')->nullable();
        });

        Schema::create('Child', function (Blueprint $table) {
            $table->char('id', 32)->primary();
            $table->string('firstName', 100);
            $table->string('lastName', 100);
            $table->date('dateOfBirth')->nullable();
            $table->string('photoUrl')->nullable();
            $table->string('status', 50)->default('ACTIVE');
            $table->text('schedule')->nullable();
            $table->timestamp('checkedInAt')->nullable();
            $table->date('expectedStart')->nullable();
            $table->text('notes')->nullable();
            $table->char('inquiryId', 32)->nullable()->index();
            $table->timestamp('createdAt')->useCurrent();
            $table->timestamp('updatedAt')->nullable();
        });

        Schema::create('ChildNote', function (Blueprint $table) {
            $table->char('id', 32)->primary();
            $table->char('childId', 32)->index();
            $table->char('employeeId', 32)->nullable()->index();
            $table->text('content');
            $table->timestamp('createdAt')->useCurrent();
            $table->timestamp('updatedAt')->nullable();
        });

        Schema::create('ChildDocument', function (Blueprint $table) {
            $table->char('id', 32)->primary();
            $table->char('childId', 32)->index();
            $table->string('title')->nullable();
            $table->string('name');
            $table->string('fileUrl');
            $table->string('mimeType', 100)->nullable();
            $table->unsignedBigInteger('size')->nullable();
            $table->boolean('uploadedByParent')->default(false);
            $table->timestamp('uploadedAt')->useCurrent();
        });

        Schema::create('ChildContact', function (Blueprint $table) {
            $table->char('id', 32)->primary();
            $table->char('childId', 32)->index();
            $table->char('contactId', 32)->index();
            $table->string('relationship', 100);
            $table->boolean('isPrimary')->default(false);
            $table->boolean('addedByParent')->default(false);
            $table->timestamp('createdAt')->useCurrent();
        });

        Schema::create('InquiryNote', function (Blueprint $table) {
            $table->char('id', 32)->primary();
            $table->char('inquiryId', 32)->index();
            $table->char('employeeId', 32)->index();
            $table->text('content');
            $table->timestamp('createdAt')->useCurrent();
        });

        Schema::create('InquiryStatusHistory', function (Blueprint $table) {
            $table->char('id', 32)->primary();
            $table->char('inquiryId', 32)->index();
            $table->char('employeeId', 32)->index();
            $table->string('oldStatus', 50);
            $table->string('newStatus', 50);
            $table->timestamp('createdAt')->useCurrent();
        });

        Schema::create('Message', function (Blueprint $table) {
            $table->char('id', 32)->primary();
            $table->char('parentUserId', 32)->index();
            $table->string('senderRole', 20);
            $table->text('content');
            $table->timestamp('readAt')->nullable();
            $table->timestamp('createdAt')->useCurrent();
        });

        Schema::create('StaffMember', function (Blueprint $table) {
            $table->char('id', 32)->primary();
            $table->string('name', 100);
            $table->string('title', 100);
            $table->text('bio')->nullable();
            $table->string('photoUrl')->nullable();
            $table->unsignedInteger('sortOrder')->default(0);
            $table->boolean('isActive')->default(true);
            $table->date('startDate')->nullable();
            $table->unsignedSmallInteger('yearsExperience')->nullable();
            $table->timestamp('createdAt')->useCurrent();
        });

        Schema::create('StaffNote', function (Blueprint $table) {
            $table->char('id', 32)->primary();
            $table->char('staffMemberId', 32)->index();
            $table->char('employeeId', 32)->index();
            $table->text('content');
            $table->string('sentiment', 20)->default('NEUTRAL');
            $table->timestamp('createdAt')->useCurrent();
            $table->timestamp('updatedAt')->nullable();
        });

        Schema::create('GalleryImage', function (Blueprint $table) {
            $table->char('id', 32)->primary();
            $table->string('filename');
            $table->string('caption', 500)->nullable();
            $table->date('takenAt')->nullable();
            $table->unsignedInteger('sortOrder')->default(0);
            $table->text('data')->nullable();
            $table->timestamp('createdAt')->useCurrent();
        });

        Schema::create('TestimonialLink', function (Blueprint $table) {
            $table->char('id', 32)->primary();
            $table->char('createdById', 32)->index();
            $table->string('token')->unique();
            $table->string('parentName', 200)->nullable();
            $table->timestamp('usedAt')->nullable();
            $table->timestamp('createdAt')->useCurrent();
        });

        Schema::create('Testimonial', function (Blueprint $table) {
            $table->char('id', 32)->primary();
            $table->char('linkId', 32)->index();
            $table->string('parentName', 200);
            $table->text('content');
            $table->unsignedTinyInteger('rating')->nullable();
            $table->string('status', 20)->default('PENDING');
            $table->boolean('isActive')->default(false);
            $table->timestamp('createdAt')->useCurrent();
            $table->timestamp('reviewedAt')->nullable();
        });

        Schema::create('SiteContent', function (Blueprint $table) {
            $table->char('id', 32)->primary();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamp('updatedAt')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('SiteContent');
        Schema::dropIfExists('Testimonial');
        Schema::dropIfExists('TestimonialLink');
        Schema::dropIfExists('GalleryImage');
        Schema::dropIfExists('StaffNote');
        Schema::dropIfExists('StaffMember');
        Schema::dropIfExists('Message');
        Schema::dropIfExists('InquiryStatusHistory');
        Schema::dropIfExists('InquiryNote');
        Schema::dropIfExists('ChildContact');
        Schema::dropIfExists('ChildDocument');
        Schema::dropIfExists('ChildNote');
        Schema::dropIfExists('Child');
        Schema::dropIfExists('Inquiry');
        Schema::dropIfExists('ParentUser');
        Schema::dropIfExists('Contact');
        Schema::dropIfExists('Employee');
    }
};
