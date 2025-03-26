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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('title')->default('Danaei CMS');
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->string('robots_index')->default('index, follow');
            $table->string('logo_url')->nullable();
            $table->text('company_description')->nullable();
            $table->string('support_email')->nullable();
            $table->string('facebook_url')->nullable();
            $table->string('twitter_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('telagram_url')->nullable();
            $table->enum('site_status', ['active', 'maintenance'])->default('active');
            $table->string('company_address')->nullable();
            $table->integer('pagination_limit')->default(10);
            $table->string('default_language')->default('en');
            $table->string('support_hours')->nullable();
            $table->string('background_image')->nullable();
            $table->string('base_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
