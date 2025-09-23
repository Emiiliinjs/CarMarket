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
        Schema::create('listings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('marka');
            $table->string('modelis');
            $table->year('gads');
            $table->integer('nobraukums');
            $table->decimal('cena', 10, 2);
            $table->string('degviela');
            $table->string('parnesumkarba');
            $table->text('apraksts')->nullable();
            $table->json('images')->nullable(); // šeit glabājam bildes kā JSON masīvu
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('listings');
    }
};
