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
        Schema::create('gemini_lite_roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('description')->nullable();
            $table->integer('daily_request_limit');
            $table->integer('monthly_request_limit');
            $table->integer('daily_token_limit');
            $table->integer('monthly_token_limit');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('gemini_lite_role_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('role_id')->constrained('gemini_lite_roles')->onDelete('cascade');
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        Schema::create('gemini_lite_usage', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->boolean('can_make_requests')->default(true);
            $table->timestamp('current_day_tracking_start')->useCurrent();
            $table->timestamp('current_month_tracking_start')->useCurrent();
            $table->integer('completed_requests_today')->default(0);
            $table->integer('completed_requests_this_month')->default(0);
            $table->integer('consumed_tokens_today')->default(0);
            $table->integer('consumed_tokens_this_month')->default(0);
            $table->timestamp('last_request_completion_time')->nullable();
            $table->timestamps();
        });

        Schema::create('gemini_lite_request_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('request_type');
            $table->integer('consumed_tokens');
            $table->boolean('request_successful');
            $table->json('request_data')->nullable();
            $table->json('response_data')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gemini_lite_request_logs');
        Schema::dropIfExists('gemini_lite_usage');
        Schema::dropIfExists('gemini_lite_role_assignments');
        Schema::dropIfExists('gemini_lite_roles');
    }
};
