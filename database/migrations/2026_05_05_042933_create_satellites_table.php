<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('satellites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ground_station_id')->nullable()->constrained('ground_stations')->onDelete('set null');
            $table->string('name');
            $table->string('country');
            $table->date('launch_date')->nullable();
            $table->enum('orbit_type', ['LEO', 'MEO', 'GEO', 'HEO'])->default('LEO');
            $table->text('tle_line1')->nullable();
            $table->text('tle_line2')->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('satellites');
    }
};