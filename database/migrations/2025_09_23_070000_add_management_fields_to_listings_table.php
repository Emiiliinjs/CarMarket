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
        Schema::table('listings', function (Blueprint $table) {
            $table->enum('status', ['available', 'reserved', 'sold'])->default('available')->after('apraksts');
            $table->boolean('is_approved')->default(false)->after('status');
            $table->text('contact_info')->nullable()->after('is_approved');
            $table->boolean('show_contact')->default(true)->after('contact_info');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('listings', function (Blueprint $table) {
            $table->dropColumn(['status', 'is_approved', 'contact_info', 'show_contact']);
        });
    }
};
