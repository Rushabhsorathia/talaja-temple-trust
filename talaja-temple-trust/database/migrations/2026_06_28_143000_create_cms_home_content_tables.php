<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Proper CMS tables for home-page content. Each item is its own record
 * (editable via Filament: image upload, enable/disable, reorder) instead
 * of a JSON blob in settings.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('home_slides', function (Blueprint $table) {
            $table->id();
            $table->string('image_path')->nullable();
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->string('tag')->nullable();
            $table->string('button_label')->nullable();
            $table->string('button_href')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('home_services', function (Blueprint $table) {
            $table->id();
            $table->string('icon')->default('heart');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('href')->nullable();
            $table->string('badge')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('home_stats', function (Blueprint $table) {
            $table->id();
            $table->string('value');
            $table->string('label');
            $table->string('icon')->default('users');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('facilities', function (Blueprint $table) {
            $table->id();
            $table->string('icon')->default('bed');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('image_path')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('facilities');
        Schema::dropIfExists('home_stats');
        Schema::dropIfExists('home_services');
        Schema::dropIfExists('home_slides');
    }
};
