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
        Schema::create('collaboration_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('collaboration_id');
            $table->unsignedBigInteger('user_id');
            $table->string('action');
            $table->json('details')->nullable();
            $table->json('changes')->nullable();
            $table->timestamps();

            // 外键约束
            $table->foreign('collaboration_id')
                ->references('id')
                ->on('collaborations')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            // 索引
            $table->index('collaboration_id');
            $table->index('user_id');
            $table->index('action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collaboration_histories');
    }
}; 