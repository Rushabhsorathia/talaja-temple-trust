<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // One row per front-end page (home, about, history, trustees, facilities,
        // contact, temple_info, live_darshan, …). Slug is the routing key
        // (matches public route name). Stores SEO + page-level toggle.
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();      // home, about, history, ...
            $table->string('title');
            $table->string('title_gu')->nullable();
            $table->string('route_name')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_image')->nullable();
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });

        // Every editable region on a page is a section row. `type` drives the
        // form schema and the renderer. `data` is a structured JSON payload
        // specific to the section type (text, richtext, list of cards, etc.)
        // so admins can change copy, swap images and reorder without code.
        Schema::create('page_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->constrained()->cascadeOnDelete();
            $table->string('section_key');        // e.g. "hero", "values", "timeline"
            $table->string('type');               // hero_slider | richtext | stats | cards | timeline | values | cta | contact_block | timings | festivals
            $table->string('title')->nullable();
            $table->string('subtitle')->nullable();
            $table->longText('content')->nullable();
            $table->json('data')->nullable();     // structured payload (cards, items, links…)
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['page_id', 'section_key']);
            $table->index(['page_id', 'sort_order']);
        });

        // Grouped site-wide settings (header, footer, social, branding, contact,
        // seo, scripts). One resource in the admin instead of a key/value dump.
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('group');              // branding | header | footer | social | contact | seo | scripts
            $table->string('key')->unique();
            $table->longText('value')->nullable();
            $table->string('type')->default('text'); // text | textarea | boolean | json | image
            $table->timestamps();

            $table->index('group');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_settings');
        Schema::dropIfExists('page_sections');
        Schema::dropIfExists('pages');
    }
};