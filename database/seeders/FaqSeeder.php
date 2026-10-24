<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [
            [
                'question' => 'What is the best time to visit Kutch and the White Rann?',
                'answer' => '<p>The best season to visit Kutch is from <strong>November to February</strong> during the winter months when the salt desert dries up into a sparkling white crust and the Rann Utsav festival takes place. Weather is pleasant with daytime temperatures around 25°C and cool desert nights.</p>',
                'is_published' => true,
                'sort_order' => 1,
            ],
            [
                'question' => 'Do I need a permit to visit the White Rann of Kutch?',
                'answer' => '<p><strong>Yes</strong>, because White Rann lies close to the international border with Pakistan, Indian citizens and foreign nationals require an official entry permit. Permits can be obtained online or at the Bhirandiyara checkpoint. When booking your tour or taxi with us, our driver assists you directly with permit verification.</p>',
                'is_published' => true,
                'sort_order' => 2,
            ],
            [
                'question' => 'How does taxi booking and payment work?',
                'answer' => '<p>All bookings are conveniently handled via <strong>WhatsApp or Direct Call</strong>. Simply click "Book on WhatsApp", share your travel dates, vehicle preference, and pickup location. We confirm availability instantly and provide transparent, all-inclusive pricing with no hidden charges.</p>',
                'is_published' => true,
                'sort_order' => 3,
            ],
            [
                'question' => 'Can we customize our tour itinerary or taxi schedule?',
                'answer' => '<p><strong>Absolutely!</strong> While we offer popular packages (2N3D, 3N4D, 4N5D), our team specializes in custom itineraries tailored to your flight/train timings, family size, and preferred destinations (e.g. adding Mandvi beach, Dholavira, or handicraft villages).</p>',
                'is_published' => true,
                'sort_order' => 4,
            ],
            [
                'question' => 'Are your drivers experienced with Kutch desert routes?',
                'answer' => '<p><strong>Yes, 100%.</strong> All our drivers are local Kutchi residents with 5+ years of desert and highway driving experience. They know every local shortcut, sunrise/sunset vantage points, authentic local eating joints, and cultural etiquette.</p>',
                'is_published' => true,
                'sort_order' => 5,
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::updateOrCreate(['question' => $faq['question']], $faq);
        }
    }
}
