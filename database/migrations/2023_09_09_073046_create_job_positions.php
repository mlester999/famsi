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
        Schema::create('job_positions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_type_id')->references('id')->on('job_types');
            $table->foreignId('employee_type_id')->references('id')->on('employee_types');
            $table->foreignId('industry_id')->references('id')->on('industries');
            $table->string('title');
            $table->string('description');
            $table->text('company_profile');
            $table->string('location');
            $table->string('schedule');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_positions');
    }
};
