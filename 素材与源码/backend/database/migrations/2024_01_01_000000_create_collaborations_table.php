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
        Schema::create('collaborations', function (Blueprint $table) {
            $table->id();
            $table->string('resource_type'); // activity, project, lesson, courseware
            $table->unsignedBigInteger('resource_id');
            $table->unsignedBigInteger('owner_id');
            $table->unsignedBigInteger('collaborator_id');
            $table->string('permission'); // view, edit, admin
            $table->string('status')->default('pending'); // pending, accepted, rejected
            $table->json('notes')->nullable();
            $table->timestamps();

            // 外键约束
            $table->foreign('owner_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('collaborator_id')->references('id')->on('users')->onDelete('cascade');

            // 索引
            $table->index(['resource_type', 'resource_id']);
            $table->index('owner_id');
            $table->index('collaborator_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collaborations');
    }
}; 