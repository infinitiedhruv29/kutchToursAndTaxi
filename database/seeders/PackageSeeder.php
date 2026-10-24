<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    public function run(): void
    {
        // 1. White Rann & Dholavira (3N/4D) — FEATURED
        $pkg1 = Package::updateOrCreate(
            ['slug' => 'whiterann-dholavira'],
            [
                'title' => 'White Rann & UNESCO Dholavira (3N/4D)',
                'package_type' => 'Kutch',
                'highlight' => 'White Desert Sunset, Road to Heaven & Harappan Ruins',
                'duration' => '3 Nights - 4 Days',
                'description' => 'The definitive Kutch holiday experience: witness the magical crimson sunset over the boundless White Rann, cultural nights at Tent City Dhordo, the famous Road to Heaven highway, and 4500-year-old Indus Valley ruins at Dholavira.',
                'image_path' => 'images/index-page/white-rann-new.webp',
                'image_alt' => 'White Rann of Kutch salt desert sunset',
                'price_2p' => 28000.00,
                'price_4p' => 48000.00,
                'price_6p' => 55000.00,
                'is_published' => true,
                'is_featured' => true,
                'sort_order' => 1,
                'seo_title' => 'White Rann & Dholavira Tour Package 3N4D — Kutch Tours & Taxi',
                'seo_description' => 'Experience the White Rann sunset, Dhordo Tent City, Road to Heaven, and UNESCO Dholavira with all transfers & sightseeing.',
            ]
        );

        $pkg1->itineraries()->delete();
        $pkg1->galleryImages()->delete();
        $pkg1->features()->delete();

        $pkg1->itineraries()->createMany([
            [
                'day_number' => 1,
                'title' => 'Arrival & Bhuj Historic Palaces',
                'description' => 'Arrival at Bhuj (Airport / Railway Station). Check-in at hotel, refresh, and explore the royal capital of Kutch:',
                'spots' => [
                    'Aaina Mahal — 18th-century Palace of Mirrors with Venetian glass chandeliers',
                    'Prag Mahal — Gothic revival palace with panoramic clocktower',
                    'Shree Swaminarayan Temple Bhuj — Dazzling white marble architectural masterpiece',
                    'Smritivan Earthquake Memorial Park & Museum on Bhujia Hill',
                ],
                'note' => 'Overnight stay at hotel in Bhuj.',
            ],
            [
                'day_number' => 2,
                'title' => 'Dhordo Tent City & White Rann Sunset Excursion',
                'description' => 'After breakfast, depart for northern Kutch toward the great salt flats of Dhordo:',
                'spots' => [
                    'Khavda & Nirona Artisan Villages — Copper bell casting & Rogan art demonstration',
                    'Dhordo Tent City — Rann Utsav cultural handicrafts bazaar and desert activity stalls',
                    'White Rann of Kutch — Breathtaking golden sunset and camel cart rides across the white salt crust',
                ],
                'note' => 'Evening cultural folk music performance. Return to Bhuj hotel for dinner.',
            ],
            [
                'day_number' => 3,
                'title' => 'Road to Heaven & Dholavira UNESCO World Heritage Site',
                'description' => 'Full day expedition to Khadir Bet island across the salt plains:',
                'spots' => [
                    'Kalo Dungar (Black Hill) — Highest peak in Kutch (462 m) with historic Dattatreya Temple',
                    'Road to Heaven — Iconic 30-km highway cutting straight through glistening white salt water',
                    'Dholavira Archaeological Site — 4,500-year-old Harappan urban metropolis with world-class stepwell reservoirs',
                    'Fossil Park & Chiya Point — Ancient marine fossils from the Jurassic era',
                ],
                'note' => 'Late evening scenic drive back to Bhuj hotel.',
            ],
            [
                'day_number' => 4,
                'title' => 'Mandvi Seaside Palaces & Departure',
                'description' => 'Check-out after breakfast and head toward the southern coast of Kutch:',
                'spots' => [
                    '72 Jinalay — Sacred white marble Jain pilgrimage temple complex',
                    'Vijay Vilas Palace — Royal seaside summer residence on private beach',
                    'Mandvi Wind Farm Beach — Sunset stroll, camel rides and water sports',
                ],
                'note' => 'Drop at Bhuj Airport / Railway Station / Bus Stop with happy memories.',
            ],
        ]);

        $pkg1->galleryImages()->createMany([
            ['image_path' => 'images/packages-page/package-3n-4d/white-rann.webp', 'caption' => 'Endless horizon at the White Rann of Kutch', 'sort_order' => 1],
            ['image_path' => 'images/packages-page/package-3n-4d/road-to-heaven.webp', 'caption' => 'Road to Heaven cutting through the white salt flats', 'sort_order' => 2],
            ['image_path' => 'images/packages-page/package-3n-4d/dholavira.webp', 'caption' => 'Harappan stone water reservoirs at Dholavira', 'sort_order' => 3],
            ['image_path' => 'images/packages-page/package-3n-4d/tent-city.webp', 'caption' => 'Tent City Dhordo during Rann Utsav festival', 'sort_order' => 4],
            ['image_path' => 'images/packages-page/package-3n-4d/museum-bhuj.webp', 'caption' => 'Historic Kutch Museum exhibits in Bhuj', 'sort_order' => 5],
        ]);

        $pkg1->features()->createMany([
            ['content' => 'Pickup & drop from Bhuj Airport / Railway Station / Bus Stop', 'type' => 'inclusion', 'sort_order' => 1],
            ['content' => '3 nights accommodation in well-rated hotel with AC rooms', 'type' => 'inclusion', 'sort_order' => 2],
            ['content' => 'Daily Breakfast and Dinner at hotel included', 'type' => 'inclusion', 'sort_order' => 3],
            ['content' => 'All sightseeing in private dedicated AC car (Dzire / Ertiga / Innova)', 'type' => 'inclusion', 'sort_order' => 4],
            ['content' => 'Driver allowance, fuel, toll taxes, parking fees, and border permits', 'type' => 'inclusion', 'sort_order' => 5],
            ['content' => 'Lunch & personal snack expenses', 'type' => 'exclusion', 'sort_order' => 1],
            ['content' => 'Entry tickets for monuments, museums & camera permits', 'type' => 'exclusion', 'sort_order' => 2],
            ['content' => 'Adventure sports, paramotoring, camel safari rides, and shopping', 'type' => 'exclusion', 'sort_order' => 3],
        ]);

        // 2. White Rann Special Tour (2N/3D)
        $pkg2 = Package::updateOrCreate(
            ['slug' => 'whiterann-special'],
            [
                'title' => 'White Rann Special Tour (2N/3D)',
                'package_type' => 'Kutch',
                'highlight' => 'Short Weekend Escape: White Rann Sunset & Dholavira',
                'duration' => '2 Nights - 3 Days',
                'description' => 'The perfect weekend getaway! Experience the world-famous White Rann sunset, panoramic views from Kalo Dungar, the gravity-defying Magnetic Hill, and the ancient UNESCO Harappan city of Dholavira via the Road to Heaven.',
                'image_path' => 'images/packages-page/package-3n-4d/dholavira.webp',
                'image_alt' => 'Dholavira and White Rann special tour',
                'price_2p' => 26000.00,
                'price_4p' => 45000.00,
                'price_6p' => 52000.00,
                'is_published' => true,
                'is_featured' => false,
                'sort_order' => 2,
                'seo_title' => 'White Rann Special 2N3D Tour — Kutch Tours & Taxi',
                'seo_description' => 'Fast 2-night 3-day White Rann weekend tour covering White Desert sunset, Kalo Dungar and Dholavira.',
            ]
        );

        $pkg2->itineraries()->delete();
        $pkg2->galleryImages()->delete();
        $pkg2->features()->delete();

        $pkg2->itineraries()->createMany([
            [
                'day_number' => 1,
                'title' => 'Bhuj Arrival & White Rann Sunset Experience',
                'description' => 'Pickup from Bhuj Airport/Railway Station. Travel northward towards the Great Rann:',
                'spots' => [
                    'Bhujodi Village — Kutchi weaving & handicraft shopping',
                    'Khavda village — Famous Mawa sweet delicacy & pottery',
                    'White Rann of Kutch — Spectacular sunset over the vast white salt desert',
                    'Dhordo Cultural Grounds — Traditional folk dance & music performances',
                ],
                'note' => 'Dinner and overnight stay in Bhuj / Dhordo.',
            ],
            [
                'day_number' => 2,
                'title' => 'Kalo Dungar & Dholavira UNESCO Harappan Ruins',
                'description' => 'Full-day excursion to the highest peak and the Indus Valley ruins:',
                'spots' => [
                    'Kalo Dungar (Black Hill) — Panoramic 360-degree views of the White Desert',
                    'Magnetic Hill — Gravity hill phenomenon in Kutch',
                    'Road to Heaven — Unforgettable highway crossing the white salt water',
                    'Dholavira Ancient Site & Museum — Excavated Harappan citadel, reservoirs & artifacts',
                ],
                'note' => 'Return to Bhuj hotel. Dinner and rest.',
            ],
            [
                'day_number' => 3,
                'title' => 'Bhuj Heritage & Departure',
                'description' => 'Morning heritage walk in Bhuj before your departing flight or train:',
                'spots' => [
                    'Aaina Mahal & Prag Mahal palaces',
                    'Shree Swaminarayan Temple — Marble architecture',
                    'Local Bhuj dry fruit & Kutchi bandhani bazaar shopping',
                ],
                'note' => 'Drop at Bhuj Airport or Railway Station.',
            ],
        ]);

        $pkg2->galleryImages()->createMany([
            ['image_path' => 'images/packages-page/package-3n-4d/dholavira.webp', 'caption' => 'Ancient stepwell reservoirs of Dholavira', 'sort_order' => 1],
            ['image_path' => 'images/gallery-page/white-rann/road_to_heaven_2.webp', 'caption' => 'Road to Heaven across the salt desert', 'sort_order' => 2],
            ['image_path' => 'images/gallery-page/white-rann/white-rann-new.webp', 'caption' => 'White Rann sunset views', 'sort_order' => 3],
        ]);

        $pkg2->features()->createMany([
            ['content' => 'Pickup & drop at Bhuj Airport / Railway / Bus Stop', 'type' => 'inclusion', 'sort_order' => 1],
            ['content' => '2 nights comfortable hotel stay with breakfast and dinner', 'type' => 'inclusion', 'sort_order' => 2],
            ['content' => 'All sightseeing in private AC cab with trusted driver', 'type' => 'inclusion', 'sort_order' => 3],
            ['content' => 'Driver allowance, parking, toll taxes, and border permits', 'type' => 'inclusion', 'sort_order' => 4],
            ['content' => 'Lunch and personal refreshments', 'type' => 'exclusion', 'sort_order' => 1],
            ['content' => 'Monument entry tickets and guide fees', 'type' => 'exclusion', 'sort_order' => 2],
        ]);

        // 3. Kutch Classic Tour (2N/3D)
        $pkg3 = Package::updateOrCreate(
            ['slug' => 'kutch-classic'],
            [
                'title' => 'Kutch Classic Tour (2N/3D)',
                'package_type' => 'Kutch',
                'highlight' => 'Mandvi Beach, Palaces & Western Kutch Heritage',
                'duration' => '2 Nights - 3 Days',
                'description' => 'Explore Mandvi\'s serene coastline and royal palaces, the sacred holy lakes of Narayan Sarovar and Koteshwar Shiva temple, the historic ghost fortress of Lakhpat, and Bhuj royal landmarks in 3 action-packed days.',
                'image_path' => 'images/packages-page/package-2n-3d/vijay-vilas.webp',
                'image_alt' => 'Vijay Vilas Palace in Mandvi Kutch',
                'price_2p' => 24000.00,
                'price_4p' => 42000.00,
                'price_6p' => 50000.00,
                'is_published' => true,
                'is_featured' => false,
                'sort_order' => 3,
                'seo_title' => 'Kutch Classic Tour 2N3D Package — Kutch Tours & Taxi',
                'seo_description' => 'Book 2 Nights 3 Days Kutch Tour covering Mandvi Beach, Vijay Vilas Palace, Lakhpat Fort, and Bhuj.',
            ]
        );

        $pkg3->itineraries()->delete();
        $pkg3->galleryImages()->delete();
        $pkg3->features()->delete();

        $pkg3->itineraries()->createMany([
            [
                'day_number' => 1,
                'title' => 'Mandvi Beach, Royal Palaces & Temples',
                'description' => 'Arrival at Bhuj (Airport/Railway). Check-in and refresh, then depart for the coastal town of Mandvi:',
                'spots' => [
                    '72 Jinalay — Famous white marble Jain temple complex',
                    'Vijay Vilas Palace — Grand red sandstone royal palace on private beach',
                    'Shyamji Krishna Verma Memorial (Kranti Teerth)',
                    'Mandvi Wind Farm Beach — Sunset stroll, camel rides and water sports',
                ],
                'note' => 'Overnight stay at hotel in Bhuj / Mandvi.',
            ],
            [
                'day_number' => 2,
                'title' => 'Sacred Western Kutch & Lakhpat Ghost Fort',
                'description' => 'After breakfast, venture into the rugged beauty of Western Kutch:',
                'spots' => [
                    'Mata no Madh — Ancient pilgrimage temple of Goddess Ashapura',
                    'Narayan Sarovar — One of India\'s 5 holy sacred lakes',
                    'Koteshwar Mahadev Temple — Ancient Shiva temple right on the Arabian Sea',
                    'Lakhpat Fort — Historic fort town overlooking the Great Rann and Pakistan border',
                ],
                'note' => 'Return to Bhuj by evening. Dinner and overnight stay.',
            ],
            [
                'day_number' => 3,
                'title' => 'Bhuj Heritage, Crafts Village & Departure',
                'description' => 'Explore the heritage and artisan culture of Bhuj before drop-off:',
                'spots' => [
                    'Aaina Mahal — 18th-century Palace of Mirrors',
                    'Prag Mahal — Gothic revival palace with panoramic clock tower',
                    'Shree Swaminarayan Temple Bhuj — Dazzling marble architecture',
                    'Bhujodi Artisan Village — Renowned Kutchi weaving, Ajrakh block prints, and shopping',
                ],
                'note' => 'Drop at Bhuj Airport / Railway Station with unforgettable memories.',
            ],
        ]);

        $pkg3->galleryImages()->createMany([
            ['image_path' => 'images/packages-page/package-2n-3d/vijay-vilas.webp', 'caption' => 'Vijay Vilas Palace, Mandvi', 'sort_order' => 1],
            ['image_path' => 'images/packages-page/package-2n-3d/mandvi-beach.webp', 'caption' => 'Mandvi Beach coastline', 'sort_order' => 2],
            ['image_path' => 'images/packages-page/package-2n-3d/koteshwar.webp', 'caption' => 'Koteshwar Shiva Temple at Arabian Sea', 'sort_order' => 3],
            ['image_path' => 'images/packages-page/package-2n-3d/aina-mahal.webp', 'caption' => 'Aina Mahal, Bhuj', 'sort_order' => 4],
        ]);

        $pkg3->features()->createMany([
            ['content' => 'Pickup & drop at Bhuj Airport / Railway Station / Bus Stop', 'type' => 'inclusion', 'sort_order' => 1],
            ['content' => 'All transfers & sightseeing in dedicated private AC vehicle', 'type' => 'inclusion', 'sort_order' => 2],
            ['content' => '2 nights comfortable hotel stay with breakfast included', 'type' => 'inclusion', 'sort_order' => 3],
            ['content' => 'Experienced local Kutchi driver with complete regional route knowledge', 'type' => 'inclusion', 'sort_order' => 4],
            ['content' => 'All driver allowances, fuel, toll taxes & parking fees', 'type' => 'inclusion', 'sort_order' => 5],
            ['content' => 'Lunch & personal snacks', 'type' => 'exclusion', 'sort_order' => 1],
            ['content' => 'Monuments and museum entry tickets', 'type' => 'exclusion', 'sort_order' => 2],
            ['content' => 'Personal expenses, camel/water rides, and handicrafts shopping', 'type' => 'exclusion', 'sort_order' => 3],
        ]);

        // 4. Kutch Grand Explorer (4N/5D)
        $pkg4 = Package::updateOrCreate(
            ['slug' => 'kutch-grand'],
            [
                'title' => 'Kutch Grand Explorer (4N/5D)',
                'package_type' => 'Kutch',
                'highlight' => 'The Complete Kutch Circuit: All 4 Regions Covered',
                'duration' => '4 Nights - 5 Days',
                'description' => 'The ultimate, all-encompassing Kutch tour: Bhuj city palaces, White Rann sunset at Dhordo, Kalo Dungar panoramic peak, UNESCO Dholavira via Road to Heaven, sacred shrines of Mata no Madh & Narayan Sarovar, Lakhpat ghost fort, and seaside Mandvi.',
                'image_path' => 'images/index-page/lakhpat-fort-new.webp',
                'image_alt' => 'Lakhpat Fort and Complete Kutch Circuit',
                'price_2p' => 36000.00,
                'price_4p' => 56000.00,
                'price_6p' => 72000.00,
                'is_published' => true,
                'is_featured' => false,
                'sort_order' => 4,
                'seo_title' => 'Kutch Grand Explorer 4N5D Holiday Package — Kutch Tours & Taxi',
                'seo_description' => 'Comprehensive 5-day holiday tour covering Bhuj, White Rann, Dholavira, Western Kutch, and Mandvi Beach.',
            ]
        );

        $pkg4->itineraries()->delete();
        $pkg4->galleryImages()->delete();
        $pkg4->features()->delete();

        $pkg4->itineraries()->createMany([
            [
                'day_number' => 1,
                'title' => 'Arrival & Bhuj Royal City Heritage',
                'description' => 'Pickup from Bhuj. Check-in and relax, then visit the iconic monuments of Bhuj:',
                'spots' => [
                    'Aaina Mahal & Prag Mahal palaces',
                    'Kutch Museum — Royal weaponry, coins & Kutchi crafts',
                    'Shree Swaminarayan Temple Bhuj',
                    'Smritivan Earthquake Memorial Park on Bhujia Hill',
                ],
                'note' => 'Overnight stay in Bhuj.',
            ],
            [
                'day_number' => 2,
                'title' => 'White Rann Excursion & Rann Utsav Stalls',
                'description' => 'Morning artisan village stops followed by White Rann sunset:',
                'spots' => [
                    'Khavda village — Clay pottery & local Kutchi sweets',
                    'Dhordo Tent City — Cultural bazaar, handicrafts & folk performances',
                    'White Rann — Golden sunset over the boundless salt desert',
                ],
                'note' => 'Dinner and overnight stay in Bhuj / Dhordo.',
            ],
            [
                'day_number' => 3,
                'title' => 'Road to Heaven & Dholavira UNESCO Harappan Site',
                'description' => 'Early departure for Khadir Bet island across the salt plains:',
                'spots' => [
                    'Kalo Dungar (Black Hill) — Panoramic views of the Great Rann',
                    'Road to Heaven — Scenic highway cutting through white salt flats',
                    'Dholavira Archaeological Site — 5000-year-old Indus Valley metropolis',
                    'Fossil Park & Chiya Point',
                ],
                'note' => 'Return to Bhuj. Dinner and rest.',
            ],
            [
                'day_number' => 4,
                'title' => 'Western Kutch: Mata no Madh, Narayan Sarovar & Lakhpat',
                'description' => 'Journey to the western frontiers of India along the Arabian Sea:',
                'spots' => [
                    'Mata no Madh — Ashapura Mata temple',
                    'Narayan Sarovar — One of India\'s 5 sacred holy lakes',
                    'Koteshwar Temple — Coastal Shiva shrine at land\'s edge',
                    'Lakhpat Fort — Massive ghost fort town on the Indus delta',
                ],
                'note' => 'Return to Bhuj hotel.',
            ],
            [
                'day_number' => 5,
                'title' => 'Mandvi Beach, Vijay Vilas & Departure',
                'description' => 'Southern coast excursion before departure:',
                'spots' => [
                    '72 Jinalay — Sacred Jain temple architecture',
                    'Vijay Vilas Palace — Royal seaside palace in Mandvi',
                    'Mandvi Wind Farm Beach & camel ride',
                ],
                'note' => 'Drop at Bhuj Airport / Railway Station with cherished memories.',
            ],
        ]);

        $pkg4->galleryImages()->createMany([
            ['image_path' => 'images/packages-page/package-4n-5d/prag-mahal.webp', 'caption' => 'Prag Mahal clock tower in Bhuj', 'sort_order' => 1],
            ['image_path' => 'images/packages-page/package-3n-4d/white-rann.webp', 'caption' => 'White Rann salt desert at sunset', 'sort_order' => 2],
            ['image_path' => 'images/packages-page/package-3n-4d/road-to-heaven.webp', 'caption' => 'Road to Heaven crossing the salt plains', 'sort_order' => 3],
            ['image_path' => 'images/packages-page/package-2n-3d/koteshwar.webp', 'caption' => 'Koteshwar shoreline temple', 'sort_order' => 4],
            ['image_path' => 'images/packages-page/package-2n-3d/vijay-vilas.webp', 'caption' => 'Vijay Vilas Palace Mandvi', 'sort_order' => 5],
        ]);

        $pkg4->features()->createMany([
            ['content' => 'Pickup & drop from Bhuj Airport / Railway Station / Bus Stop', 'type' => 'inclusion', 'sort_order' => 1],
            ['content' => '4 nights accommodation in clean AC rooms with daily breakfast & dinner', 'type' => 'inclusion', 'sort_order' => 2],
            ['content' => 'All transfers & sightseeing in dedicated private AC car with seasoned driver', 'type' => 'inclusion', 'sort_order' => 3],
            ['content' => 'All toll taxes, parking fees, fuel, driver night allowances, and permits', 'type' => 'inclusion', 'sort_order' => 4],
            ['content' => 'Lunch and personal refreshments', 'type' => 'exclusion', 'sort_order' => 1],
            ['content' => 'Entry tickets for monuments, museums & camera charges', 'type' => 'exclusion', 'sort_order' => 2],
        ]);

        // 5. Outstation Holiday Packages (Popular from Gujarat)
        $outstationPackages = [
            [
                'slug' => 'rajasthan-royal',
                'title' => 'Rajasthan Royal Heritage Tour (5N/6D)',
                'package_type' => 'Outstation',
                'highlight' => 'Desert Forts, Palaces & Sand Dunes',
                'duration' => '5 Nights - 6 Days',
                'description' => 'Explore the majestic golden desert fortress of Jaisalmer, the blue city of Jodhpur, and the romantic lake palaces of Udaipur in an unforgettable journey across India\'s most colourful royal state.',
                'image_path' => 'images/packages-page/rajasthan.webp',
                'image_alt' => 'Rajasthan royal palaces and camel desert',
                'price_2p' => 38000.00,
                'price_4p' => 58000.00,
                'price_6p' => 74000.00,
                'is_published' => true,
                'is_featured' => false,
                'sort_order' => 5,
            ],
            [
                'slug' => 'kashmir-paradise',
                'title' => 'Kashmir Paradise on Earth (5N/6D)',
                'package_type' => 'Outstation',
                'highlight' => 'Dal Lake Houseboat, Gulmarg Gondola & Pahalgam',
                'duration' => '5 Nights - 6 Days',
                'description' => 'Bask in the sheer beauty of Srinagar\'s Dal Lake on a traditional wooden houseboat, ride the world\'s highest cable car in Gulmarg, and walk through pine forests and Betaab Valley in Pahalgam.',
                'image_path' => 'images/packages-page/kashmir-houseboat.webp',
                'image_alt' => 'Kashmir Dal Lake shikara and houseboat',
                'price_2p' => 42000.00,
                'price_4p' => 64000.00,
                'price_6p' => 82000.00,
                'is_published' => true,
                'is_featured' => false,
                'sort_order' => 6,
            ],
            [
                'slug' => 'kerala-backwaters',
                'title' => 'God\'s Own Country — Kerala Tour (5N/6D)',
                'package_type' => 'Outstation',
                'highlight' => 'Munnar Tea Hills, Thekkady Wildlife & Alleppey Houseboat',
                'duration' => '5 Nights - 6 Days',
                'description' => 'Drift through serene palm-fringed backwaters on a private Alleppey houseboat, breathe crisp mist in Munnar\'s emerald tea plantations, and experience fragrant spice plantations in Thekkady.',
                'image_path' => 'images/packages-page/kerala_outstation.webp',
                'image_alt' => 'Kerala backwaters and traditional houseboat',
                'price_2p' => 36000.00,
                'price_4p' => 54000.00,
                'price_6p' => 70000.00,
                'is_published' => true,
                'is_featured' => false,
                'sort_order' => 7,
            ],
            [
                'slug' => 'goa-beach-holiday',
                'title' => 'Goa Beach Vacation & Heritage Tour (3N/4D)',
                'package_type' => 'Outstation',
                'highlight' => 'Golden Beaches, Portuguese Forts & Water Sports',
                'duration' => '3 Nights - 4 Days',
                'description' => 'Golden sands, swaying palms, and historic Portuguese churches. Relax on Calangute & Baga beaches, explore Aguada Fort, and cruise the Mandovi river at sunset.',
                'image_path' => 'images/packages-page/goa.webp',
                'image_alt' => 'Goa beaches and coastline',
                'price_2p' => 22000.00,
                'price_4p' => 36000.00,
                'price_6p' => 48000.00,
                'is_published' => true,
                'is_featured' => false,
                'sort_order' => 8,
            ],
            [
                'slug' => 'golden-triangle',
                'title' => 'Golden Triangle Heritage Circuit (4N/5D)',
                'package_type' => 'Outstation',
                'highlight' => 'Taj Mahal at Sunrise, Amber Fort & Red Fort',
                'duration' => '4 Nights - 5 Days',
                'description' => 'India\'s three most iconic heritage cities — Delhi, Agra, and Jaipur. Marvel at the immortal marble beauty of the Taj Mahal, the pink ramparts of Jaipur, and Delhi\'s historic monuments.',
                'image_path' => 'images/packages-page/golden_triangle_outstation.webp',
                'image_alt' => 'Taj Mahal Agra and Golden Triangle',
                'price_2p' => 32000.00,
                'price_4p' => 49000.00,
                'price_6p' => 64000.00,
                'is_published' => true,
                'is_featured' => false,
                'sort_order' => 9,
            ],
            [
                'slug' => 'manali-adventure',
                'title' => 'Manali Mountain & Solang Valley (4N/5D)',
                'package_type' => 'Outstation',
                'highlight' => 'Snow Peaks, Solang Valley Adventure & Rohtang Pass',
                'duration' => '4 Nights - 5 Days',
                'description' => 'Crisp Himalayan air, snow-capped peaks, pine forests and roaring rivers. Experience adventure activities at Solang Valley, hot springs at Vashisht, and the Tibetan monasteries of Manali.',
                'image_path' => 'images/packages-page/manali.webp',
                'image_alt' => 'Snow peaks and mountains in Manali',
                'price_2p' => 28000.00,
                'price_4p' => 44000.00,
                'price_6p' => 58000.00,
                'is_published' => true,
                'is_featured' => false,
                'sort_order' => 10,
            ],
        ];

        foreach ($outstationPackages as $outPkg) {
            Package::updateOrCreate(
                ['slug' => $outPkg['slug']],
                $outPkg
            );
        }
    }
}
