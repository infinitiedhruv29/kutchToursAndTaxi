<?php

namespace Database\Seeders;

use App\Models\Destination;
use Illuminate\Database\Seeder;

class DestinationSeeder extends Seeder
{
    public function run(): void
    {
        $destinations = [
            [
                'name' => 'White Rann of Kutch',
                'short_description' => 'The world-famous salt desert covering thousands of square kilometers. Stunning sunrises, moonlit white nights and vibrant cultural festivals.',
                'image_path' => 'images/index-page/white-rann-hero.webp',
                'image_alt' => 'White Rann of Kutch salt desert',
                'is_published' => true,
                'is_featured' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'UNESCO World Heritage Dholavira',
                'short_description' => 'One of the five largest Harappan (Indus Valley) metropolises dating back over 4,500 years with sophisticated ancient urban engineering.',
                'image_path' => 'images/packages-page/package-3n-4d/dholavira.webp',
                'image_alt' => 'Dholavira archaeological site Kutch',
                'is_published' => true,
                'is_featured' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Mandvi Beach',
                'short_description' => 'Tranquil Arabian Sea coastline, majestic 450-acre royal beach palace of the Jadeja rulers, and historic wooden shipbuilding yards.',
                'image_path' => 'images/index-page/mandvi-beach-2.webp',
                'image_alt' => 'Mandvi Beach in Kutch',
                'is_published' => true,
                'is_featured' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Road to Heaven',
                'short_description' => 'Spectacular 30-km road cutting straight through the shimmering waters and white salt expanse of the Great Rann towards Dholavira.',
                'image_path' => 'images/index-page/road_to_heaven_2.webp',
                'image_alt' => 'Road to Heaven in Kutch',
                'is_published' => true,
                'is_featured' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Prag Mahal & Aina Mahal (Bhuj)',
                'short_description' => 'Grand gothic 19th-century palace tower alongside the delicate 18th-century Palace of Mirrors crafted by master artisan Ram Singh Malam.',
                'image_path' => 'images/index-page/prag-mahal-new.webp',
                'image_alt' => 'Prag Mahal and Aina Mahal in Bhuj',
                'is_published' => true,
                'is_featured' => false,
                'sort_order' => 5,
            ],
            [
                'name' => 'Lakhpat Fort & Western Coast',
                'short_description' => 'Massive 7-km walled fort town once guarding the bustling mouth of the Indus river, now a haunting desert citadel on the border.',
                'image_path' => 'images/index-page/lakhpat-fort-new.webp',
                'image_alt' => 'Lakhpat Fort defensive walls Kutch',
                'is_published' => true,
                'is_featured' => false,
                'sort_order' => 6,
            ],
        ];

        foreach ($destinations as $item) {
            Destination::updateOrCreate(['name' => $item['name']], $item);
        }
    }
}
