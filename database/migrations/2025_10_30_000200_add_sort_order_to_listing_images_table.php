<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('listing_images', function (Blueprint $table) {
            $table->unsignedInteger('sort_order')->default(0)->after('filename');
        });

        $images = DB::table('listing_images')
            ->select('id', 'listing_id')
            ->orderBy('listing_id')
            ->orderBy('id')
            ->get();

        $positions = [];

        foreach ($images as $image) {
            $positions[$image->listing_id] = ($positions[$image->listing_id] ?? 0) + 1;

            DB::table('listing_images')
                ->where('id', $image->id)
                ->update(['sort_order' => $positions[$image->listing_id]]);
        }
    }

    public function down(): void
    {
        Schema::table('listing_images', function (Blueprint $table) {
            $table->dropColumn('sort_order');
        });
    }
};
