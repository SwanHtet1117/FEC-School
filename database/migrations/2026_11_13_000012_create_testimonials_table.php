<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->string('parent_name');
            $table->string('student_context');
            $table->text('quote');
            $table->unsignedInteger('sort_order')->default(0)->index();
            $table->timestamps();
        });

        $now = now();
        DB::table('testimonials')->insert([
            [
                'parent_name' => 'Daw Mya Mya',
                'student_context' => 'Parent of Primary Student',
                'quote' => 'FEC has provided an excellent foundation for my child. The small class sizes and individual attention have made a significant difference in their academic progress.',
                'sort_order' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'parent_name' => 'U Kyaw Kyaw',
                'student_context' => 'Parent of Secondary Student',
                'quote' => 'The international curriculum at FEC is outstanding. My daughter has developed not only academically but also in confidence and character.',
                'sort_order' => 2,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'parent_name' => 'Daw Su Su',
                'student_context' => 'Parent of Early Years Student',
                'quote' => 'The teachers at FEC are truly dedicated. They go above and beyond to ensure each student reaches their full potential. Highly recommended!',
                'sort_order' => 3,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('testimonials');
    }
};
