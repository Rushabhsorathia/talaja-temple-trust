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
        Schema::table('users', function (Blueprint $table) {
            $table->string('mobile')->nullable()->after('email');
            $table->string('pan')->nullable()->after('mobile');
            $table->string('name_as_per_pan')->nullable()->after('pan');
            $table->text('address')->nullable()->after('name_as_per_pan');
            $table->enum('type', ['devotee', 'staff', 'admin', 'trustee'])->default('devotee')->after('address');
            $table->boolean('is_active')->default(true)->after('type');
            $table->timestamp('mobile_verified_at')->nullable()->after('is_active');
            $table->timestamp('last_login_at')->nullable()->after('mobile_verified_at');
            $table->string('last_login_ip')->nullable()->after('last_login_at');
            $table->json('mfa_settings')->nullable()->after('last_login_ip');
            $table->softDeletes()->after('updated_at');

            $table->index(['type', 'is_active']);
            $table->index('mobile');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['type', 'is_active']);
            $table->dropIndex('mobile');
            $table->dropColumn([
                'mobile', 'pan', 'name_as_per_pan', 'address', 'type',
                'is_active', 'mobile_verified_at', 'last_login_at', 'last_login_ip', 'mfa_settings',
                'deleted_at',
            ]);
        });
    }
};
