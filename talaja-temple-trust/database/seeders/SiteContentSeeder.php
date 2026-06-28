<?php

namespace Database\Seeders;

use App\Models\Facility;
use App\Models\HomeService;
use App\Models\HomeSlide;
use App\Models\HomeStat;
use App\Models\Setting;
use Illuminate\Database\Seeder;

class SiteContentSeeder extends Seeder
{
    public function run(): void
    {
        // Editable site tagline (still a simple key/value setting).
        Setting::set('site_tagline', '|| Jay Mataji ||', 'general');

        // --- Hero carousel slides (Admin → Homepage → Home Slides) ---
        $slides = [
            ['image_path' => 'hero/temple-1.jpg', 'title' => 'Talaja Temple Trust', 'subtitle' => 'A sacred abode of devotion and service', 'tag' => '|| Jay Mataji ||', 'button_label' => 'Donate Now', 'button_href' => '/donate'],
            ['image_path' => 'hero/temple-2.jpg', 'title' => 'Connect With the Divine', 'subtitle' => 'Live darshan, donations and blessings — anytime, anywhere', 'tag' => '|| Om Namah Shivay ||', 'button_label' => 'Live Darshan', 'button_href' => '/live-darshan'],
            ['image_path' => 'hero/temple-3.jpg', 'title' => 'A Legacy of Faith', 'subtitle' => 'Serving devotees with devotion for generations', 'tag' => '|| Har Har Mahadev ||', 'button_label' => 'Donate Now', 'button_href' => '/donate'],
        ];
        foreach ($slides as $i => $s) {
            HomeSlide::create(array_merge($s, ['sort_order' => $i, 'is_active' => true]));
        }

        // --- Home service cards (Admin → Homepage → Home Services) ---
        $services = [
            ['icon' => 'video', 'title' => 'Live Darshan', 'description' => 'Experience divine darshan from anywhere in the world.', 'href' => '/live-darshan', 'badge' => 'Live'],
            ['icon' => 'heart', 'title' => 'Donate', 'description' => 'Support the temple with secure online donations (80G eligible).', 'href' => '/donate'],
            ['icon' => 'bed', 'title' => 'Bookings', 'description' => 'Reserve rooms and halls for your stay and events.', 'href' => '/bookings'],
            ['icon' => 'bag', 'title' => 'Shop', 'description' => 'Prasad, books and souvenirs delivered to your home.', 'href' => '/shop'],
        ];
        foreach ($services as $i => $s) {
            HomeService::create(array_merge($s, ['sort_order' => $i, 'is_active' => true]));
        }

        // --- Home stat cards (Admin → Homepage → Home Stats) ---
        $stats = [
            ['value' => '5L+', 'label' => 'Devotees Served', 'icon' => 'users'],
            ['value' => '100+', 'label' => 'Years of Legacy', 'icon' => 'clock'],
            ['value' => '500+', 'label' => 'Daily Annaseva', 'icon' => 'soup'],
            ['value' => '24/7', 'label' => 'Live Darshan', 'icon' => 'wifi'],
        ];
        foreach ($stats as $i => $s) {
            HomeStat::create(array_merge($s, ['sort_order' => $i, 'is_active' => true]));
        }

        // --- Facilities cards (Admin → Homepage → Facilities) ---
        $facilities = [
            ['icon' => 'bed', 'title' => 'Dharamshala', 'description' => 'Comfortable accommodation for pilgrims with AC and non-AC rooms.', 'image_path' => 'facilities/dharamshala.jpg'],
            ['icon' => 'home', 'title' => 'Vishram Gruh', 'description' => 'A peaceful rest house for devotees seeking a quiet retreat.', 'image_path' => 'about/temple.jpg'],
            ['icon' => 'soup', 'title' => 'Annashetra', 'description' => 'Free wholesome meals (anna seva) served daily to all devotees.', 'image_path' => 'facilities/anna.jpg'],
            ['icon' => 'flame', 'title' => 'Havan Khand', 'description' => 'Dedicated space for havan, yagna and sacred fire rituals.', 'image_path' => 'facilities/havan.jpg'],
            ['icon' => 'cross', 'title' => 'Free Medical Services', 'description' => 'Periodic health camps providing free check-ups and medicines.', 'image_path' => 'facilities/medical.jpg'],
            ['icon' => 'trees', 'title' => 'Environment Initiatives', 'description' => 'Tree plantation and green drives around the temple grounds.', 'image_path' => 'facilities/tree.jpg'],
        ];
        foreach ($facilities as $i => $f) {
            Facility::create(array_merge($f, ['sort_order' => $i, 'is_active' => true]));
        }
    }
}
