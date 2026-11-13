<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->string('question');
            $table->text('answer');
            $table->unsignedInteger('sort_order')->default(0)->index();
            $table->timestamps();
        });

        $now = now();
        DB::table('faqs')->insert([
            [
                'question' => 'What is the age requirement for admission?',
                'answer' => 'Early Years: 3-5 years, Primary: 6-11 years, Secondary: 12-16 years. Age requirements may vary based on previous education.',
                'sort_order' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'question' => 'Is there an entrance examination?',
                'answer' => 'Yes, age-appropriate assessments are conducted to determine proper placement and identify learning needs.',
                'sort_order' => 2,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'question' => 'When does the academic year begin?',
                'answer' => 'Our academic year typically begins in June and runs through March, with breaks scheduled for holidays.',
                'sort_order' => 3,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'question' => 'Do you accept international students?',
                'answer' => 'Yes, we welcome international students and provide support for their transition to our school community.',
                'sort_order' => 4,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'question' => 'What is the student-teacher ratio?',
                'answer' => 'We maintain small class sizes with a student-teacher ratio of approximately 15:1 to ensure personalized attention.',
                'sort_order' => 5,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'question' => 'Is transportation available?',
                'answer' => 'Yes, we provide school bus services covering major areas. Contact admissions for route information.',
                'sort_order' => 6,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('faqs');
    }
};
