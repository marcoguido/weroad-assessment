<?php

use App\Models\Travel;
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
        Schema::create('travels', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->boolean('isPublic')->default(false);
            $table->string('slug');
            $table->string('name');
            $table->longText('description');
            $table->unsignedInteger('numberOfDays');
            $table->unsignedInteger('numberOfNights')
                ->virtualAs('numberOfDays - 1');
            $table->json('moods')->default('[]');
            $table->timestamp(Travel::CREATED_AT)->nullable();
            $table->timestamp(Travel::UPDATED_AT)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('travels');
    }
};
