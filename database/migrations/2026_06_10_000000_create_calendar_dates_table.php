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
        Schema::create('calendar_dates', function (Blueprint $table) {
            // Anchor utama
            $table->date('gregorian_date')->primary();

            // Kalender Masehi
            $table->string('gregorian_day_name')->nullable();
            $table->unsignedTinyInteger('gregorian_day')->nullable();
            $table->unsignedTinyInteger('gregorian_month')->nullable();
            $table->string('gregorian_month_name')->nullable();
            $table->unsignedSmallInteger('gregorian_year')->nullable();

            // Kalender Hijriyah
            $table->string('hijri_day_name')->nullable();
            $table->unsignedTinyInteger('hijri_day')->nullable();
            $table->unsignedTinyInteger('hijri_month')->nullable();
            $table->string('hijri_month_name')->nullable();
            $table->unsignedSmallInteger('hijri_year')->nullable();
            $table->string('hijri_designation')->nullable();
            $table->string('hijri_source')->nullable();
            $table->boolean('hijri_is_verified')->default(false);

            // Kalender Jawa
            $table->string('javanese_day_name')->nullable();
            $table->string('javanese_market_day')->nullable();
            $table->unsignedTinyInteger('javanese_day')->nullable();
            $table->unsignedTinyInteger('javanese_month')->nullable();
            $table->string('javanese_month_name')->nullable();
            $table->unsignedSmallInteger('javanese_year')->nullable();
            $table->string('javanese_designation')->nullable();
            $table->string('javanese_source')->nullable();
            $table->boolean('javanese_is_verified')->default(false);

            // Kalender Sunda
            $table->string('sundanese_day_name')->nullable();
            $table->string('sundanese_day_name_common')->nullable();
            $table->string('sundanese_market_day')->nullable();
            $table->unsignedTinyInteger('sundanese_day')->nullable();
            $table->unsignedTinyInteger('sundanese_month')->nullable();
            $table->string('sundanese_month_name')->nullable();
            $table->unsignedSmallInteger('sundanese_year')->nullable();
            $table->string('sundanese_designation')->nullable();
            $table->string('sundanese_source')->nullable();
            $table->boolean('sundanese_is_verified')->default(false);

            // Status kelengkapan data
            $table->boolean('is_generated')->default(false);
            $table->boolean('is_complete')->default(false);
            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index(['gregorian_year', 'gregorian_month']);
            $table->index(['hijri_year', 'hijri_month']);
            $table->index(['javanese_year', 'javanese_month']);
            $table->index(['sundanese_year', 'sundanese_month']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calendar_dates');
    }
};
