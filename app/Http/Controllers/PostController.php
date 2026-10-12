<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class PostController extends Controller
{
    private const POSTS = [
        'fec-students-win-national-science-competition' => [
            'title' => 'FEC Students Win National Science Competition',
            'category' => 'News',
            'date' => 'April 15, 2026',
            'image' => 'primary_student_activity_01.jpg',
            'alt' => 'Primary students working on academic activities',
            'excerpt' => 'Our students achieved remarkable success in the National Science Fair, bringing home multiple awards for their innovative projects.',
            'body' => 'FEC students represented the school at this year\'s National Science Fair with projects developed through research, teamwork, and hands-on experimentation. Their work earned recognition from the judging panel across multiple categories.',
        ],
        'launch-of-advanced-stem-program' => [
            'title' => 'Launch of Advanced STEM Program',
            'category' => 'News',
            'date' => 'April 10, 2026',
            'image' => 'secondary_student_activity_01.jpg',
            'alt' => 'Secondary students learning in their classroom',
            'excerpt' => 'FEC International School introduces cutting-edge STEM curriculum to prepare students for future technological challenges.',
            'body' => 'The program brings science, technology, engineering, and mathematics together through practical classroom projects. Students will build problem-solving skills as they explore concepts and apply them to real-world challenges.',
        ],
        'teacher-recognition-award' => [
            'title' => 'Teacher Recognition Award',
            'category' => 'News',
            'date' => 'April 5, 2026',
            'image' => 'early_student_activity_07.jpg',
            'alt' => 'Teacher supporting a student in class',
            'excerpt' => 'Our dedicated faculty member receives excellence in teaching award from the Ministry of Education.',
            'body' => 'The recognition celebrates a commitment to thoughtful teaching and student growth. The FEC community congratulates our faculty member on this achievement and thanks the teaching team for their continued dedication.',
        ],
        'school-open-day' => [
            'title' => 'School Open Day',
            'category' => 'Event',
            'date' => 'May 20, 2026',
            'image' => 'fec_building_02.jpg',
            'alt' => 'FEC International School campus for Open Day',
            'excerpt' => 'Join us for our annual open day to explore our campus, meet our teachers, and learn about our programs.',
            'location' => 'FEC Campus, Naypyidaw',
            'body' => 'Families are invited to visit the campus, meet members of our teaching team, and learn more about the school\'s academic programs. Our staff will be available to answer questions about learning and student life.',
        ],
        'annual-sports-day' => [
            'title' => 'Annual Sports Day',
            'category' => 'Event',
            'date' => 'June 15, 2026',
            'image' => 'all_student_activity_01.jpg',
            'alt' => 'Students gathered outdoors at a school event',
            'excerpt' => 'Celebrate athleticism and teamwork at our exciting sports day featuring various competitions and activities.',
            'location' => 'School Sports Ground',
            'body' => 'Students will take part in a range of friendly competitions and team activities throughout the day. Families are welcome to come along and support the participants as they celebrate teamwork and school spirit.',
        ],
        'parent-teacher-workshop' => [
            'title' => 'Parent-Teacher Workshop',
            'category' => 'Event',
            'date' => 'July 10, 2026',
            'image' => 'early_student_activity_04.jpg',
            'alt' => 'Teacher leading a classroom activity',
            'excerpt' => 'Interactive workshop for parents to understand modern teaching methods and student development.',
            'location' => 'Conference Hall',
            'body' => 'This session gives parents and teachers an opportunity to discuss how students learn and develop. The workshop will include practical activities and time for questions with our educators.',
        ],
        'school-holiday-notice' => [
            'title' => 'School Holiday Notice',
            'category' => 'Announcement',
            'date' => 'April 20, 2026',
            'image' => 'fec_building_01.jpg',
            'alt' => 'FEC school campus during the holiday period',
            'excerpt' => 'School will be closed from April 25-28 for the upcoming holiday period. Classes resume on April 29.',
            'department' => 'Administration',
            'body' => 'Please note the school closure dates and plan for classes to resume on April 29. For questions about the academic calendar, contact the school administration.',
        ],
        'new-uniform-policy' => [
            'title' => 'New Uniform Policy',
            'category' => 'Announcement',
            'date' => 'April 18, 2026',
            'image' => 'all_student_activity_02.jpg',
            'alt' => 'FEC students wearing their school uniforms',
            'excerpt' => 'Updated uniform guidelines for the upcoming academic year. Please review the new dress code policy.',
            'department' => 'Administration',
            'body' => 'Families are asked to review the updated dress code before the upcoming academic year. Please contact the administration office if you need clarification about the uniform guidelines.',
        ],
        'transportation-schedule-update' => [
            'title' => 'Transportation Schedule Update',
            'category' => 'Announcement',
            'date' => 'April 15, 2026',
            'image' => 'fec_building.jpg',
            'alt' => 'FEC campus served by the school transport routes',
            'excerpt' => 'New bus routes and schedules for school transportation. Please check the updated routes for your area.',
            'department' => 'Transport Department',
            'body' => 'Updated routes and schedules are available for families using school transportation. Please review the information for your area and contact the Transport Department with any questions.',
        ],
        'cultural-festival' => [
            'title' => 'Cultural Festival',
            'category' => 'Event',
            'date' => 'August 15, 2026',
            'image' => 'early_student_activity_05.jpg',
            'alt' => 'Students celebrating together at a classroom event',
            'excerpt' => 'Annual cultural celebration showcasing diverse traditions, performances, and artistic expressions from our community.',
            'location' => 'School Auditorium',
            'body' => 'Students and families will come together to share traditions and enjoy performances and creative work from across the school community. Further event information will be shared by the school as the date approaches.',
        ],
        'graduation-ceremony-2026' => [
            'title' => 'Graduation Ceremony 2026',
            'category' => 'News',
            'date' => 'March 25, 2026',
            'image' => 'ged_student_activity_01.jpg',
            'alt' => 'GED students participating in a school activity',
            'excerpt' => 'Celebrating the achievements of our graduating class as they embark on their next educational journey.',
            'body' => 'The FEC community celebrates the hard work and achievements of the graduating class. We wish every graduate success as they continue their education and pursue their next goals.',
        ],
        'library-hours-extended' => [
            'title' => 'Library Hours Extended',
            'category' => 'Announcement',
            'date' => 'April 12, 2026',
            'image' => 'primary_student_activity_01.jpg',
            'alt' => 'Students studying in a classroom',
            'excerpt' => 'School library will now remain open until 6 PM on weekdays to support student learning and research.',
            'department' => 'Library Department',
            'body' => 'Students can use the additional weekday hours for reading, research, and independent study. Please contact the Library Department for information about available resources.',
        ],
    ];

    public function show(string $slug): View
    {
        abort_unless(isset(self::POSTS[$slug]), 404);

        return view('main.post-detail', ['post' => self::POSTS[$slug]]);
    }
}