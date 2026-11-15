<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gallery_photos', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('category')->index();
            $table->string('image_path');
            $table->string('alt_text');
            $table->unsignedInteger('views')->default(0);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        $photos = [
            ['School Gathering', 'events', 'all_student_activity_01.jpg', 'Students gathered outdoors at school', 1234],
            ['Student Project Showcase', 'events', 'early_student_activity_01.jpg', 'Early Years students presenting a classroom project', 987],
            ['Student Activity', 'events', 'all_student_activity_02.jpg', 'Students taking part in a classroom activity', 2156],
            ['School Assembly', 'events', 'secondary_student_activity_02.jpg', 'Secondary students gathered for a school event', 3421],
            ['FEC School Building', 'campus-life', 'fec_building_01.jpg', 'Exterior view of the FEC school building', 856],
            ['Campus Grounds', 'campus-life', 'fec_building_02.jpg', 'FEC campus grounds and school building', 743],
            ['School Campus', 'campus-life', 'fec_building.jpg', 'FEC International School campus', 1102],
            ['FEC Campus Overview', 'campus-life', 'fec_wallpaper.jpg', 'Illustrated overview of the FEC campus and school community', 634],
            ['Classroom Learning', 'academics', 'secondary_student_activity_01.jpg', 'Secondary students working in their classroom', 1567],
            ['Group Study Session', 'academics', 'primary_student_activity_01.jpg', 'Primary students studying together in class', 923],
            ['Early Years Classroom', 'academics', 'early_student_activity_04.jpg', 'Early Years students learning with their teacher', 1089],
            ['GED Student Activity', 'academics', 'ged_student_activity_01.jpg', 'GED students participating in a school activity', 756],
        ];
        $now = now();

        DB::table('gallery_photos')->insert(array_map(
            static fn (array $photo, int $index): array => [
                'title' => $photo[0],
                'category' => $photo[1],
                'image_path' => $photo[2],
                'alt_text' => $photo[3],
                'views' => $photo[4],
                'sort_order' => $index,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            $photos,
            array_keys($photos)
        ));
    }

    public function down(): void
    {
        Schema::dropIfExists('gallery_photos');
    }
};
