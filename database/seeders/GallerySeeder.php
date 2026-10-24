<?php

namespace Database\Seeders;

use App\Models\GalleryCategory;
use App\Models\GalleryImage;
use Illuminate\Database\Seeder;

class GallerySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'White Rann', 'is_published' => true, 'sort_order' => 1],
            ['name' => 'Heritage & Palaces', 'is_published' => true, 'sort_order' => 2],
            ['name' => 'Mandvi Beach', 'is_published' => true, 'sort_order' => 3],
            ['name' => 'Culture & Crafts', 'is_published' => true, 'sort_order' => 4],
            ['name' => 'Wildlife & Nature', 'is_published' => true, 'sort_order' => 5],
            ['name' => 'Bhuj City', 'is_published' => true, 'sort_order' => 6],
        ];

        $categoryMap = [];
        foreach ($categories as $cat) {
            $created = GalleryCategory::updateOrCreate(['name' => $cat['name']], $cat);
            $categoryMap[$cat['name']] = $created->id;
        }

        $images = [
            // White Rann
            ['cat' => 'White Rann', 'image_path' => 'images/gallery-page/white-rann/white-rann.webp', 'caption' => 'White Rann — Vast Salt Desert', 'alt' => 'White Rann salt desert horizon'],
            ['cat' => 'White Rann', 'image_path' => 'images/gallery-page/white-rann/white-rann-new.webp', 'caption' => 'White Rann — Golden Sunset over Salt Crust', 'alt' => 'Sunset at White Rann Dhordo'],
            ['cat' => 'White Rann', 'image_path' => 'images/gallery-page/white-rann/road_to_heaven_2.webp', 'caption' => 'Road to Heaven — Scenic Desert Highway', 'alt' => 'Road to Heaven Dholavira highway'],
            ['cat' => 'White Rann', 'image_path' => 'images/gallery-page/white-rann/kalo-dungar-new.webp', 'caption' => 'Kalo Dungar (Black Hill) Sunset View', 'alt' => 'Panoramic view from Kalo Dungar peak'],
            ['cat' => 'White Rann', 'image_path' => 'images/gallery-page/white-rann/tent-city.webp', 'caption' => 'Tent City Dhordo — Rann Utsav Stays', 'alt' => 'Tent City luxury tents at Dhordo'],
            ['cat' => 'White Rann', 'image_path' => 'images/gallery-page/white-rann/bhanjano-hills.webp', 'caption' => 'Bhanjano Hills — Rugged Desert Landscape', 'alt' => 'Bhanjano hills near Rann of Kutch'],

            // Heritage & Palaces
            ['cat' => 'Heritage & Palaces', 'image_path' => 'images/gallery-page/heritage/prag-mahal-bhuj.webp', 'caption' => 'Prag Mahal — Gothic Clocktower & Royal Durbar Hall', 'alt' => 'Prag Mahal palace facade in Bhuj'],
            ['cat' => 'Heritage & Palaces', 'image_path' => 'images/gallery-page/heritage/aina-mahal-bhuj.webp', 'caption' => 'Aina Mahal — 18th-Century Palace of Mirrors', 'alt' => 'Aina Mahal interior and royal mirrors'],
            ['cat' => 'Heritage & Palaces', 'image_path' => 'images/gallery-page/mandvi/vijay-vilas.webp', 'caption' => 'Vijay Vilas Palace — Royal Beach Estate', 'alt' => 'Vijay Vilas Palace in Mandvi'],
            ['cat' => 'Heritage & Palaces', 'image_path' => 'images/gallery-page/heritage/dholavira.webp', 'caption' => 'Dholavira — 4500-Year-Old Harappan UNESCO Ruins', 'alt' => 'Ancient Harappan water reservoir at Dholavira'],
            ['cat' => 'Heritage & Palaces', 'image_path' => 'images/gallery-page/heritage/museum-bhuj.webp', 'caption' => 'Kutch Museum — Gujarat Oldest Museum', 'alt' => 'Kutch Museum historic building in Bhuj'],
            ['cat' => 'Heritage & Palaces', 'image_path' => 'images/gallery-page/heritage/shyamji-krishna-verma-memorial.webp', 'caption' => 'Kranti Teerth — Shyamji Krishna Varma Memorial', 'alt' => 'Shyamji Krishna Varma Memorial Mandvi'],

            // Mandvi Beach
            ['cat' => 'Mandvi Beach', 'image_path' => 'images/gallery-page/mandvi/mandvi-beach.webp', 'caption' => 'Mandvi Beach — Arabian Sea Coastline', 'alt' => 'Mandvi beach waves and golden sands'],
            ['cat' => 'Mandvi Beach', 'image_path' => 'images/gallery-page/mandvi/mandvi-beach-2.webp', 'caption' => 'Mandvi Beach — Windmills & Golden Sunset', 'alt' => 'Windmills along Mandvi beach during sunset'],
            ['cat' => 'Mandvi Beach', 'image_path' => 'images/gallery-page/mandvi/vijay_vilas_2.webp', 'caption' => 'Vijay Vilas Palace Gardens & Seaside Heritage', 'alt' => 'Gardens of Vijay Vilas Palace'],

            // Culture & Crafts
            ['cat' => 'Culture & Crafts', 'image_path' => 'images/gallery-page/culture/kutchi-handicraft.webp', 'caption' => 'Authentic Kutchi Handicrafts & Mirrorwork Embroidery', 'alt' => 'Kutchi embroidery and handicraft textiles'],
            ['cat' => 'Culture & Crafts', 'image_path' => 'images/gallery-page/culture/cultural-dance-image.webp', 'caption' => 'Traditional Kutchi Folk Dance Performance', 'alt' => 'Kutchi folk dancers in colourful traditional attire'],
            ['cat' => 'Culture & Crafts', 'image_path' => 'images/gallery-page/culture/rann-utsav-celebrations.webp', 'caption' => 'Rann Utsav Cultural Folk Music & Bonfire Nights', 'alt' => 'Rann Utsav folk musicians playing traditional instruments'],
            ['cat' => 'Culture & Crafts', 'image_path' => 'images/gallery-page/culture/cultural-craft.webp', 'caption' => 'Artisans Weaving & Traditional Handicraft Village', 'alt' => 'Kutchi weaver at loom in artisan village'],

            // Wildlife & Nature
            ['cat' => 'Wildlife & Nature', 'image_path' => 'images/gallery-page/flamingos-kutch.webp', 'caption' => 'Greater Flamingos of the Great Rann Wetland', 'alt' => 'Pink flamingos in Kutch desert wetland'],
            ['cat' => 'Wildlife & Nature', 'image_path' => 'images/gallery-page/chhari-dhand.webp', 'caption' => 'Migratory Birds & Pelicans at Chhari Dhand Wetland', 'alt' => 'Pelicans and migratory birds at Chhari Dhand reserve'],

            // Bhuj City
            ['cat' => 'Bhuj City', 'image_path' => 'images/gallery-page/bhuj-city/swaminarayan-mandir-bhuj.webp', 'caption' => 'Shree Swaminarayan Temple — Intricate Marble Craft', 'alt' => 'Swaminarayan Mandir white marble temple Bhuj'],
            ['cat' => 'Bhuj City', 'image_path' => 'images/gallery-page/bhuj-city/smrutivan.webp', 'caption' => 'Smritivan Earthquake Memorial & Museum', 'alt' => 'Smritivan earthquake memorial park Bhuj'],
            ['cat' => 'Bhuj City', 'image_path' => 'images/gallery-page/bhuj-city/hamirsar-lake.webp', 'caption' => 'Hamirsar Lake — The Heart of Historic Bhuj', 'alt' => 'Hamirsar lake waterfront in Bhuj'],
            ['cat' => 'Bhuj City', 'image_path' => 'images/gallery-page/bhuj-city/bhujiya-hills.webp', 'caption' => 'Bhujia Hill & Historic Fort Ramparts', 'alt' => 'Bhujia fort overlooking Bhuj city'],
        ];

        $seedPaths = array_column($images, 'image_path');
        GalleryImage::whereNotIn('image_path', $seedPaths)
            ->where('image_path', 'like', 'images/gallery-page/%')
            ->delete();

        foreach ($images as $index => $img) {
            $catId = $categoryMap[$img['cat']] ?? null;
            if ($catId) {
                GalleryImage::updateOrCreate(
                    ['image_path' => $img['image_path']],
                    [
                        'gallery_category_id' => $catId,
                        'caption' => $img['caption'],
                        'image_alt' => $img['alt'],
                        'is_published' => true,
                        'sort_order' => $index + 1,
                    ]
                );
            }
        }
    }
}
