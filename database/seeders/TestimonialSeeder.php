<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $testimonials = [
            [
                'client_name' => 'Rajesh Sharma',
                'service_name' => 'White Rann & Dholavira 3N4D',
                'youtube_link' => null,
                'youtube_video_id' => null,
                'review' => 'Excellent service! The Innova was spotless, and our driver Mr. Jadeja was extremely polite and knowledgeable about local history. Driving along Road to Heaven was breathtaking!',
                'is_published' => true,
                'sort_order' => 1,
            ],
            [
                'client_name' => 'Pooja Iyer & Family',
                'service_name' => 'Bhuj to Mandvi Taxi Trip',
                'youtube_link' => null,
                'youtube_video_id' => null,
                'review' => 'We booked an Ertiga for 2 days. Punctual pickup from Bhuj airport, very clean car, transparent per-km billing, and helpful driver who guided us to the best authentic Kutchi thali in town.',
                'is_published' => true,
                'sort_order' => 2,
            ],
            [
                'client_name' => 'Amitabh Sen',
                'service_name' => 'Kutch Grand Explorer 4N5D',
                'youtube_link' => null,
                'youtube_video_id' => null,
                'review' => 'Seamless coordination from inquiry to drop-off. Everything was arranged via WhatsApp in minutes. No hassles, honest pricing, and true Kutchi warmth. Highly recommended!',
                'is_published' => true,
                'sort_order' => 3,
            ],
        ];

        foreach ($testimonials as $t) {
            Testimonial::updateOrCreate(['client_name' => $t['client_name']], $t);
        }
    }
}
