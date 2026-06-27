<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * M1-T5 — Core domain schema for Talaja Temple Trust portal.
 * Covers: temple profile, timings, donations, accommodation, finance,
 * feedback, communications, CMS, shop, live darshan & site settings.
 */
return new class extends Migration
{
    public function up(): void
    {
        // ---------------------------------------------------------------
        // Temple profile & info
        // ---------------------------------------------------------------
        Schema::create('temples', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->boolean('is_primary')->default(false);
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('map_embed')->nullable();
            $table->string('logo_path')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('temple_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('temple_id')->constrained()->cascadeOnDelete();
            $table->string('locale', 5);
            $table->longText('history')->nullable();
            $table->longText('about_trust')->nullable();
            $table->longText('trust_info')->nullable();
            $table->timestamps();

            $table->unique(['temple_id', 'locale']);
        });

        Schema::create('temple_timings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('temple_id')->constrained()->cascadeOnDelete();
            $table->string('type'); // darshan, aarti, pooja
            $table->string('title');
            $table->string('title_gu')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->string('day_of_week')->nullable();
            $table->decimal('fee', 10, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('festivals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('temple_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('title_gu')->nullable();
            $table->text('description')->nullable();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->string('image_path')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('trustees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('temple_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('designation');
            $table->string('designation_gu')->nullable();
            $table->longText('bio')->nullable();
            $table->longText('bio_gu')->nullable();
            $table->string('photo_path')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // ---------------------------------------------------------------
        // Donations
        // ---------------------------------------------------------------
        Schema::create('donation_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('name_gu')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_80g_eligible')->default(true);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->string('receipt_no')->unique();
            $table->foreignId('temple_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('donor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('donation_category_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 12, 2);
            $table->string('currency', 3)->default('INR');
            $table->enum('payment_mode', ['online', 'upi', 'cash', 'cheque', 'bank_transfer', 'qr'])->default('online');
            $table->enum('status', ['pending', 'success', 'failed', 'refunded'])->default('pending');
            $table->string('gateway')->nullable();
            $table->string('gateway_transaction_id')->nullable()->index();
            $table->boolean('is_80g')->default(false);
            $table->string('donor_name')->nullable();
            $table->string('donor_email')->nullable();
            $table->string('donor_mobile')->nullable();
            $table->string('donor_pan')->nullable();
            $table->text('donor_address')->nullable();
            $table->boolean('is_anonymous')->default(false);
            $table->text('note')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'paid_at']);
            $table->index('donor_id');
        });

        Schema::create('donation_receipts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('donation_id')->constrained()->cascadeOnDelete();
            $table->string('receipt_no')->unique();
            $table->string('receipt_type'); // general, 80g
            $table->string('pdf_path')->nullable();
            $table->boolean('is_void')->default(false);
            $table->timestamps();
        });

        // ---------------------------------------------------------------
        // Accommodation
        // ---------------------------------------------------------------
        Schema::create('room_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('temple_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('name_gu')->nullable();
            $table->text('description')->nullable();
            $table->decimal('tariff', 10, 2)->default(0);
            $table->integer('capacity')->default(2);
            $table->json('amenities')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_type_id')->constrained()->cascadeOnDelete();
            $table->string('number')->unique();
            $table->string('floor')->nullable();
            $table->enum('housekeeping_status', ['clean', 'dirty', 'inspected'])->default('clean');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('room_bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_no')->unique();
            $table->foreignId('room_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('guest_name');
            $table->string('guest_email')->nullable();
            $table->string('guest_mobile');
            $table->integer('guests')->default(1);
            $table->date('check_in');
            $table->date('check_out');
            $table->decimal('amount', 10, 2)->default(0);
            $table->enum('payment_mode', ['online', 'pay_at_temple'])->default('online');
            $table->enum('status', ['pending', 'confirmed', 'checked_in', 'checked_out', 'cancelled'])->default('pending');
            $table->string('gateway_transaction_id')->nullable();
            $table->timestamp('checked_in_at')->nullable();
            $table->timestamp('checked_out_at')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['room_id', 'check_in', 'check_out']);
        });

        Schema::create('meeting_halls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('temple_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('name_gu')->nullable();
            $table->integer('capacity')->default(50);
            $table->decimal('tariff', 10, 2)->default(0);
            $table->json('amenities')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('hall_bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_no')->unique();
            $table->foreignId('meeting_hall_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('guest_name');
            $table->string('guest_mobile');
            $table->date('event_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('attendees')->default(1);
            $table->decimal('amount', 10, 2)->default(0);
            $table->enum('status', ['pending', 'confirmed', 'cancelled'])->default('pending');
            $table->text('note')->nullable();
            $table->timestamps();
        });

        Schema::create('housekeeping_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('status', ['clean', 'dirty', 'inspected']);
            $table->text('note')->nullable();
            $table->timestamps();
        });

        // ---------------------------------------------------------------
        // Financial management
        // ---------------------------------------------------------------
        Schema::create('receipts', function (Blueprint $table) {
            $table->id();
            $table->string('receipt_no')->unique();
            $table->foreignId('temple_id')->nullable()->constrained()->nullOnDelete();
            $table->string('source'); // donation, booking, shop, other
            $table->morphs('receiptable'); // link to donation/booking/order
            $table->decimal('amount', 12, 2);
            $table->enum('payment_mode', ['online', 'upi', 'cash', 'cheque', 'bank_transfer']);
            $table->date('date');
            $table->text('note')->nullable();
            $table->timestamps();
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('voucher_no')->unique();
            $table->foreignId('temple_id')->nullable()->constrained()->nullOnDelete();
            $table->string('payee');
            $table->string('category'); // vendor, salary, expense, utility
            $table->decimal('amount', 12, 2);
            $table->enum('payment_mode', ['online', 'cash', 'cheque', 'bank_transfer']);
            $table->date('date');
            $table->string('approved_by')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });

        Schema::create('bank_statements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('temple_id')->nullable()->constrained()->nullOnDelete();
            $table->date('date');
            $table->string('description');
            $table->decimal('debit', 12, 2)->default(0);
            $table->decimal('credit', 12, 2)->default(0);
            $table->decimal('balance', 12, 2)->default(0);
            $table->string('reference')->nullable();
            $table->enum('reconciliation_status', ['unmatched', 'matched', 'ignored'])->default('unmatched');
            $table->nullableMorphs('reconcilable');
            $table->timestamps();
        });

        // ---------------------------------------------------------------
        // Feedback & suggestions
        // ---------------------------------------------------------------
        Schema::create('feedbacks', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['suggestion', 'feedback', 'complaint']);
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('mobile')->nullable();
            $table->string('category')->nullable();
            $table->unsignedTinyInteger('rating')->nullable();
            $table->longText('message');
            $table->enum('status', ['open', 'in_progress', 'closed', 'spam'])->default('open');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->longText('admin_reply')->nullable();
            $table->timestamps();
        });

        // ---------------------------------------------------------------
        // Communications
        // ---------------------------------------------------------------
        Schema::create('notification_templates', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('channel'); // sms, email, whatsapp
            $table->string('subject')->nullable();
            $table->longText('body');
            $table->longText('body_gu')->nullable();
            $table->json('variables')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('notification_logs', function (Blueprint $table) {
            $table->id();
            $table->string('channel'); // sms, email, whatsapp
            $table->string('recipient');
            $table->foreignId('template_id')->nullable()->constrained('notification_templates')->nullOnDelete();
            $table->longText('content');
            $table->enum('status', ['queued', 'sent', 'failed', 'delivered'])->default('queued');
            $table->string('provider_message_id')->nullable();
            $table->text('error')->nullable();
            $table->morphs('notifiable');
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();

            $table->index(['channel', 'status']);
        });

        // ---------------------------------------------------------------
        // CMS
        // ---------------------------------------------------------------
        Schema::create('cms_pages', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('title_gu')->nullable();
            $table->longText('content')->nullable();
            $table->longText('content_gu')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_image')->nullable();
            $table->boolean('is_published')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('image_path');
            $table->string('link')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamp('publish_at')->nullable();
            $table->timestamp('unpublish_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('title_gu')->nullable();
            $table->longText('excerpt')->nullable();
            $table->longText('excerpt_gu')->nullable();
            $table->longText('content');
            $table->longText('content_gu')->nullable();
            $table->string('image_path')->nullable();
            $table->string('category')->nullable();
            $table->json('tags')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->boolean('is_published')->default(true);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['is_published', 'published_at']);
        });

        Schema::create('galleries', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('title_gu')->nullable();
            $table->string('image_path');
            $table->string('alt_text')->nullable();
            $table->string('category')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('title_gu')->nullable();
            $table->string('source'); // youtube, vimeo
            $table->string('source_id');
            $table->string('thumbnail_path')->nullable();
            $table->string('category')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('publications', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('title_gu')->nullable();
            $table->string('file_path');
            $table->string('category')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->string('question');
            $table->string('question_gu')->nullable();
            $table->longText('answer');
            $table->longText('answer_gu')->nullable();
            $table->string('category')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // ---------------------------------------------------------------
        // Shop
        // ---------------------------------------------------------------
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('name_gu')->nullable();
            $table->longText('description')->nullable();
            $table->longText('description_gu')->nullable();
            $table->string('image_path')->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('compare_at_price', 10, 2)->nullable();
            $table->integer('stock')->default(0);
            $table->string('category')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_no')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('customer_name');
            $table->string('customer_email')->nullable();
            $table->string('customer_mobile');
            $table->text('shipping_address');
            $table->decimal('subtotal', 12, 2);
            $table->decimal('shipping', 12, 2)->default(0);
            $table->decimal('tax', 12, 2)->default(0);
            $table->decimal('total', 12, 2);
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');
            $table->enum('fulfilment_status', ['new', 'packed', 'shipped', 'delivered', 'cancelled'])->default('new');
            $table->string('gateway_transaction_id')->nullable();
            $table->string('tracking_no')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->decimal('price', 10, 2);
            $table->integer('quantity');
            $table->decimal('total', 12, 2);
            $table->timestamps();
        });

        // ---------------------------------------------------------------
        // Live darshan & site settings
        // ---------------------------------------------------------------
        Schema::create('live_darshan_config', function (Blueprint $table) {
            $table->id();
            $table->foreignId('temple_id')->nullable()->constrained()->nullOnDelete();
            $table->string('stream_url');
            $table->boolean('is_live')->default(false);
            $table->string('poster_path')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->timestamps();
        });

        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->longText('value')->nullable();
            $table->string('group')->default('general');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
        Schema::dropIfExists('live_darshan_config');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('products');
        Schema::dropIfExists('faqs');
        Schema::dropIfExists('publications');
        Schema::dropIfExists('videos');
        Schema::dropIfExists('galleries');
        Schema::dropIfExists('news');
        Schema::dropIfExists('banners');
        Schema::dropIfExists('cms_pages');
        Schema::dropIfExists('notification_logs');
        Schema::dropIfExists('notification_templates');
        Schema::dropIfExists('feedbacks');
        Schema::dropIfExists('bank_statements');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('receipts');
        Schema::dropIfExists('housekeeping_logs');
        Schema::dropIfExists('hall_bookings');
        Schema::dropIfExists('meeting_halls');
        Schema::dropIfExists('room_bookings');
        Schema::dropIfExists('rooms');
        Schema::dropIfExists('room_types');
        Schema::dropIfExists('donation_receipts');
        Schema::dropIfExists('donations');
        Schema::dropIfExists('donation_categories');
        Schema::dropIfExists('trustees');
        Schema::dropIfExists('festivals');
        Schema::dropIfExists('temple_timings');
        Schema::dropIfExists('temple_translations');
        Schema::dropIfExists('temples');
    }
};
