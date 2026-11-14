<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('category')->index();
            $table->date('published_at')->index();
            $table->string('image_path');
            $table->string('alt_text');
            $table->string('excerpt', 500);
            $table->text('body');
            $table->string('location')->nullable();
            $table->string('department')->nullable();
            $table->timestamps();
        });

        $now = now();
        $posts = [
            [
                'slug' => 'fec-students-win-national-science-competition',
                'title' => 'FEC Students Win National Science Competition',
                'category' => 'News',
                'published_at' => '2026-04-15',
                'image_path' => 'primary_student_activity_01.jpg',
                'alt_text' => 'Primary students working on academic activities',
                'excerpt' => 'Our students achieved remarkable success in the National Science Fair, bringing home multiple awards for their innovative projects.',
                'body' => 'FEC students represented the school at this year\'s National Science Fair with projects developed through research, teamwork, and hands-on experimentation. Their work earned recognition from the judging panel across multiple categories.',
                'location' => null,
                'department' => null,
            ],
            [
                'slug' => 'launch-of-advanced-stem-program',
                'title' => 'Launch of Advanced STEM Program',
                'category' => 'News',
                'published_at' => '2026-04-10',
                'image_path' => 'secondary_student_activity_01.jpg',
                'alt_text' => 'Secondary students learning in their classroom',
                'excerpt' => 'FEC International School introduces cutting-edge STEM curriculum to prepare students for future technological challenges.',
                'body' => 'The program brings science, technology, engineering, and mathematics together through practical classroom projects. Students will build problem-solving skills as they explore concepts and apply them to real-world challenges.',
                'location' => null,
                'department' => null,
            ],
            [
                'slug' => 'teacher-recognition-award',
                'title' => 'Teacher Recognition Award',
                'category' => 'News',
                'published_at' => '2026-04-05',
                'image_path' => 'early_student_activity_07.jpg',
                'alt_text' => 'Teacher supporting a student in class',
                'excerpt' => 'Our dedicated faculty member receives excellence in teaching award from the Ministry of Education.',
                'body' => 'The recognition celebrates a commitment to thoughtful teaching and student growth. The FEC community congratulates our faculty member on this achievement and thanks the teaching team for their continued dedication.',
                'location' => null,
                'department' => null,
            ],
            [
                'slug' => 'school-open-day',
                'title' => 'School Open Day',
                'category' => 'Event',
                'published_at' => '2026-05-20',
                'image_path' => 'fec_building_02.jpg',
                'alt_text' => 'FEC International School campus for Open Day',
                'excerpt' => 'Join us for our annual open day to explore our campus, meet our teachers, and learn about our programs.',
                'body' => 'Families are invited to visit the campus, meet members of our teaching team, and learn more about the school\'s academic programs. Our staff will be available to answer questions about learning and student life.',
                'location' => 'FEC Campus, Naypyidaw',
                'department' => null,
            ],
            [
                'slug' => 'annual-sports-day',
                'title' => 'Annual Sports Day',
                'category' => 'Event',
                'published_at' => '2026-06-15',
                'image_path' => 'all_student_activity_01.jpg',
                'alt_text' => 'Students gathered outdoors at a school event',
                'excerpt' => 'Celebrate athleticism and teamwork at our exciting sports day featuring various competitions and activities.',
                'body' => 'Students will take part in a range of friendly competitions and team activities throughout the day. Families are welcome to come along and support the participants as they celebrate teamwork and school spirit.',
                'location' => 'School Sports Ground',
                'department' => null,
            ],
            [
                'slug' => 'parent-teacher-workshop',
                'title' => 'Parent-Teacher Workshop',
                'category' => 'Event',
                'published_at' => '2026-07-10',
                'image_path' => 'early_student_activity_04.jpg',
                'alt_text' => 'Teacher leading a classroom activity',
                'excerpt' => 'Interactive workshop for parents to understand modern teaching methods and student development.',
                'body' => 'This session gives parents and teachers an opportunity to discuss how students learn and develop. The workshop will include practical activities and time for questions with our educators.',
                'location' => 'Conference Hall',
                'department' => null,
            ],
            [
                'slug' => 'school-holiday-notice',
                'title' => 'School Holiday Notice',
                'category' => 'Announcement',
                'published_at' => '2026-04-20',
                'image_path' => 'fec_building_01.jpg',
                'alt_text' => 'FEC school campus during the holiday period',
                'excerpt' => 'School will be closed from April 25-28 for the upcoming holiday period. Classes resume on April 29.',
                'body' => 'Please note the school closure dates and plan for classes to resume on April 29. For questions about the academic calendar, contact the school administration.',
                'location' => null,
                'department' => 'Administration',
            ],
            [
                'slug' => 'new-uniform-policy',
                'title' => 'New Uniform Policy',
                'category' => 'Announcement',
                'published_at' => '2026-04-18',
                'image_path' => 'all_student_activity_02.jpg',
                'alt_text' => 'FEC students wearing their school uniforms',
                'excerpt' => 'Updated uniform guidelines for the upcoming academic year. Please review the new dress code policy.',
                'body' => 'Families are asked to review the updated dress code before the upcoming academic year. Please contact the administration office if you need clarification about the uniform guidelines.',
                'location' => null,
                'department' => 'Administration',
            ],
            [
                'slug' => 'transportation-schedule-update',
                'title' => 'Transportation Schedule Update',
                'category' => 'Announcement',
                'published_at' => '2026-04-15',
                'image_path' => 'fec_building.jpg',
                'alt_text' => 'FEC campus served by the school transport routes',
                'excerpt' => 'New bus routes and schedules for school transportation. Please check the updated routes for your area.',
                'body' => 'Updated routes and schedules are available for families using school transportation. Please review the information for your area and contact the Transport Department with any questions.',
                'location' => null,
                'department' => 'Transport Department',
            ],
            [
                'slug' => 'cultural-festival',
                'title' => 'Cultural Festival',
                'category' => 'Event',
                'published_at' => '2026-08-15',
                'image_path' => 'early_student_activity_05.jpg',
                'alt_text' => 'Students celebrating together at a classroom event',
                'excerpt' => 'Annual cultural celebration showcasing diverse traditions, performances, and artistic expressions from our community.',
                'body' => 'Students and families will come together to share traditions and enjoy performances and creative work from across the school community. Further event information will be shared by the school as the date approaches.',
                'location' => 'School Auditorium',
                'department' => null,
            ],
            [
                'slug' => 'graduation-ceremony-2026',
                'title' => 'Graduation Ceremony 2026',
                'category' => 'News',
                'published_at' => '2026-03-25',
                'image_path' => 'ged_student_activity_01.jpg',
                'alt_text' => 'GED students participating in a school activity',
                'excerpt' => 'Celebrating the achievements of our graduating class as they embark on their next educational journey.',
                'body' => 'The FEC community celebrates the hard work and achievements of the graduating class. We wish every graduate success as they continue their education and pursue their next goals.',
                'location' => null,
                'department' => null,
            ],
            [
                'slug' => 'library-hours-extended',
                'title' => 'Library Hours Extended',
                'category' => 'Announcement',
                'published_at' => '2026-04-12',
                'image_path' => 'primary_student_activity_01.jpg',
                'alt_text' => 'Students studying in a classroom',
                'excerpt' => 'School library will now remain open until 6 PM on weekdays to support student learning and research.',
                'body' => 'Students can use the additional weekday hours for reading, research, and independent study. Please contact the Library Department for information about available resources.',
                'location' => null,
                'department' => 'Library Department',
            ],
        ];

        DB::table('posts')->insert(array_map(
            static fn (array $post): array => [...$post, 'created_at' => $now, 'updated_at' => $now],
            $posts
        ));
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
