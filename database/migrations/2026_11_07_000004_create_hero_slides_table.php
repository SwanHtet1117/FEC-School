<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hero_slides', function (Blueprint $table) {
            $table->id();
            $table->string('image_path');
            $table->string('alt_text');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_enabled')->default(true);
            $table->timestamps();
        });

        $now = now();
        $slides = config('homepage.hero_images');

        DB::table('hero_slides')->insert(array_map(
            fn (array $slide): array => [
                'image_path' => $slide['path'],
                'alt_text' => $slide['alt_text'],
                'sort_order' => $slide['sort_order'],
                'is_enabled' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            $slides
        ));
    }

    public function down(): void
    {
        Schema::dropIfExists('hero_slides');
    }
};
