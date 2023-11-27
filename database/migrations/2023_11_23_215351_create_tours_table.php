<?php

use App\Models\Tour;
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
        Schema::create('tours', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('travelId')
                ->constrained(table: 'travels')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->string('name');
            $table->date('startingDate');
            $table->date('endingDate');
            $table->unsignedInteger('price');
            $table->timestamp(Tour::CREATED_AT)->nullable();
            $table->timestamp(Tour::UPDATED_AT)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tours');
    }
};
