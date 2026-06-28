<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SiteContentSeeder extends Seeder
{
    public function run(): void
    {
        // Home page stat cards (editable via Admin -> Settings -> home_stats)
        Setting::set('home_stats', json_encode([
            ['value' => '5L+', 'label' => 'Devotees Served', 'icon' => 'users'],
            ['value' => '100+', 'label' => 'Years of Legacy', 'icon' => 'clock'],
            ['value' => '500+', 'label' => 'Daily Annaseva', 'icon' => 'soup'],
            ['value' => '24/7', 'label' => 'Live Darshan', 'icon' => 'wifi'],
        ]), 'content');

        // Facilities cards (editable via Admin -> Settings -> facilities)
        Setting::set('facilities', json_encode([
            ['icon' => 'bed', 'title' => 'Dharamshala', 'desc' => 'Comfortable accommodation for pilgrims with AC and non-AC rooms.', 'image' => '/storage/facilities/dharamshala.jpg'],
            ['icon' => 'home', 'title' => 'Vishram Gruh', 'desc' => 'A peaceful rest house for devotees seeking a quiet retreat.', 'image' => '/storage/about/temple.jpg'],
            ['icon' => 'soup', 'title' => 'Annashetra', 'desc' => 'Free wholesome meals (anna seva) served daily to all devotees.', 'image' => '/storage/facilities/anna.jpg'],
            ['icon' => 'flame', 'title' => 'Havan Khand', 'desc' => 'Dedicated space for havan, yagna and sacred fire rituals.', 'image' => '/storage/facilities/havan.jpg'],
            ['icon' => 'cross', 'title' => 'Free Medical Services', 'desc' => 'Periodic health camps providing free check-ups and medicines.', 'image' => '/storage/facilities/medical.jpg'],
            ['icon' => 'trees', 'title' => 'Environment Initiatives', 'desc' => 'Tree plantation and green drives around the temple grounds.', 'image' => '/storage/facilities/tree.jpg'],
        ]), 'content');

        // Editable site tagline
        Setting::set('site_tagline', '|| Jay Mataji ||', 'general');
    }
}
