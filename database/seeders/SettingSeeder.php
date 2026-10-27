<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        Setting::updateOrCreate(
            ['id' => Setting::SINGLETON_ID],
            [
                'business_name' => 'Kutch Tours & Taxi',
                'primary_color' => '#E7BE67',
                'logo_path' => 'images/logo/logo-black.svg',
                'dark_logo_path' => 'images/logo/logo-gold.svg',
                'favicon_path' => 'images/favicon.svg',
                'contact_number' => '+91 99042 22293',
                'contact_number_2' => '+91 94274 34567',
                'whatsapp_number' => '919773239587',
                'contact_email' => 'contact@kutchtoursandtaxi.com',
                'address' => 'Station Road, Near Bus Station, Bhuj, Kutch, Gujarat — 370001',
                'google_map_link' => 'https://maps.google.com/?q=Bhuj,Kutch,Gujarat',
                'instagram_link' => 'https://www.instagram.com/kutch.holidays?igsh=dW54OHgwc243Zm5q',
                'facebook_link' => null,
                'youtube_link' => null,
                'gst_number' => '24ABCDE1234F1Z5',
                'general_inclusions' => [
                    'Pickup & drop from Bhuj Airport, Railway Station or Bus Stop',
                    'All sightseeing in private AC vehicle with experienced local driver',
                    '2 meals daily — Breakfast & Dinner (on tour packages)',
                    '3-Star hotel or traditional Bhunga/Tent stay at White Rann',
                    'All local transfers — Bhuj to Bhuj',
                    'White Rann permit assistance',
                ],
                'general_exclusions' => [
                    'Pickup/drop outside Bhuj city limits (extra on actuals)',
                    'Lunch & personal snacks',
                    'Toll taxes, parking fees, and driver night allowance (for outstation)',
                    'Monument & museum entry tickets',
                    'Personal expenses, safari rides & handicraft shopping',
                ],
            ]
        );
    }
}
