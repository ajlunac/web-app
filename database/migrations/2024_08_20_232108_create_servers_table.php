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
        Schema::create('servers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('country_id')->constrained()->cascadeOnDelete();
            $table->foreignId('state_id')->constrained()->cascadeOnDelete();
            $table->foreignId('city_id')->constrained()->cascadeOnDelete();
            $table->foreignId('department_id')->constrained()->cascadeOnDelete();
            $table->string('name', 100);
            $table->string('ip', 20);
            $table->string('operating_system', 100);
            $table->string('brand', 100);
            $table->string('name_plate', 50);
            $table->string('serial_number', 50);
            $table->string('ram_capacity', 20);
            $table->string('processor', 100);
            $table->string('stora_capacity', 50);
            $table->enum('type',['virtual','physical'])->default('virtual');
            $table->timestamp('date_installation');
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servers');
    }
};
