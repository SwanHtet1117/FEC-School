<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gallery_videos', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('category')->index();
            $table->string('youtube_id', 11)->nullable();
            $table->string('duration', 30);
            $table->unsignedInteger('views')->default(0);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        $videos = [
            ['title' => 'FEC School Student Activity', 'category' => 'events', 'youtube_id' => 's9xq0rOPltg', 'duration' => 'Short', 'views' => 5678],
            ['title' => 'Science Fair Exhibition', 'category' => 'events', 'youtube_id' => null, 'duration' => '8:45', 'views' => 4321],
            ['title' => 'Cultural Festival', 'category' => 'events', 'youtube_id' => null, 'duration' => '15:20', 'views' => 7892],
            ['title' => 'Graduation Ceremony', 'category' => 'events', 'youtube_id' => null, 'duration' => '25:10', 'views' => 9456],
            ['title' => 'FEC School Student Activity', 'category' => 'campus-life', 'youtube_id' => 's9xq0rOPltg', 'duration' => 'Short', 'views' => 3456],
            ['title' => 'Student Life', 'category' => 'campus-life', 'youtube_id' => null, 'duration' => '7:30', 'views' => 2789],
            ['title' => 'Facilities Overview', 'category' => 'campus-life', 'youtube_id' => null, 'duration' => '9:45', 'views' => 4123],
            ['title' => 'Extracurricular Activities', 'category' => 'campus-life', 'youtube_id' => null, 'duration' => '11:20', 'views' => 3890],
            ['title' => 'FEC School Student Activity', 'category' => 'academics', 'youtube_id' => 's9xq0rOPltg', 'duration' => 'Short', 'views' => 6234],
            ['title' => 'Science Experiments', 'category' => 'academics', 'youtube_id' => null, 'duration' => '12:10', 'views' => 5123],
            ['title' => 'Art & Music Classes', 'category' => 'academics', 'youtube_id' => null, 'duration' => '10:50', 'views' => 4567],
            ['title' => 'Sports Training', 'category' => 'academics', 'youtube_id' => null, 'duration' => '13:40', 'views' => 5890],
        ];
        $now = now();

        DB::table('gallery_videos')->insert(array_map(
            static fn (array $video, int $order): array => [
                ...$video,
                'sort_order' => $order,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            $videos,
            array_keys($videos)
        ));
    }

    public function down(): void
    {
        Schema::dropIfExists('gallery_videos');
    }
};
