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

        // Hero carousel slides (Admin → Settings → hero_slides)
        Setting::set('hero_slides', json_encode([
            ['img' => '/storage/hero/temple-1.jpg', 'title' => 'Talaja Temple Trust', 'sub' => 'A sacred abode of devotion and service', 'tag' => '|| Jay Mataji ||'],
            ['img' => '/storage/hero/temple-2.jpg', 'title' => 'Connect With the Divine', 'sub' => 'Live darshan, donations and blessings — anytime, anywhere', 'tag' => '|| Om Namah Shivay ||'],
            ['img' => '/storage/hero/temple-3.jpg', 'title' => 'A Legacy of Faith', 'sub' => 'Serving devotees with devotion for generations', 'tag' => '|| Har Har Mahadev ||'],
        ]), 'content');

        // Home service cards (Admin → Settings → services)
        Setting::set('services', json_encode([
            ['icon' => 'video', 'title' => 'Live Darshan', 'desc' => 'Experience divine darshan from anywhere in the world.', 'href' => '/live-darshan', 'badge' => 'Live'],
            ['icon' => 'heart', 'title' => 'Donate', 'desc' => 'Support the temple with secure online donations (80G eligible).', 'href' => '/donate'],
            ['icon' => 'bed', 'title' => 'Bookings', 'desc' => 'Reserve rooms and halls for your stay and events.', 'href' => '/bookings'],
            ['icon' => 'bag', 'title' => 'Shop', 'desc' => 'Prasad, books and souvenirs delivered to your home.', 'href' => '/shop'],
        ]), 'content');
    }
}
