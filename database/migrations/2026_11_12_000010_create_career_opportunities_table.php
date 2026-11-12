<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('career_opportunities', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('department');
            $table->string('employment_type');
            $table->string('location');
            $table->text('description');
            $table->string('application_email');
            $table->date('closes_at')->nullable()->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('career_opportunities');
    }
};
