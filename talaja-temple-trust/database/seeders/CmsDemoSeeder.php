<?php

namespace Database\Seeders;

use App\Models\Banner;
use App\Models\CmsPage;
use App\Models\DonationCategory;
use App\Models\Faq;
use App\Models\Festival;
use App\Models\Gallery;
use App\Models\MeetingHall;
use App\Models\News;
use App\Models\Product;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\Temple;
use App\Models\TempleTiming;
use App\Models\Trustee;
use App\Models\Video;
use Illuminate\Database\Seeder;

class CmsDemoSeeder extends Seeder
{
    public function run(): void
    {
        $temple = Temple::create([
            'name' => 'Talaja Temple Trust',
            'slug' => 'talaja-temple',
            'is_primary' => true,
            'is_active' => true,
            'address' => 'Talaja, Bhavnagar, Gujarat 364140',
            'phone' => '+91 0000000000',
            'email' => 'contact@talajatemple.org',
            'map_embed' => 'https://www.google.com/maps?q=Talaja&output=embed',
        ]);

        $temple->translations()->createMany([
            ['locale' => 'en', 'about_trust' => '<p>Welcome to Talaja Temple Trust, a sacred abode of devotion and service where the divine presence continues to inspire countless hearts. The trust manages temple operations, community welfare, and devotional services.</p>', 'history' => '<p>The temple was established centuries ago and has been a centre of faith for generations of devotees.</p>', 'trust_info' => '<p>Managed by a dedicated trust.</p>'],
            ['locale' => 'gu', 'about_trust' => '<p>તળાજા મંદિર ટ્રસ્ટમાં આપનું સ્વાગત છે.</p>', 'history' => '<p>મંદિરનો ઇતિહાસ.</p>', 'trust_info' => '<p>ટ્રસ્ટ દ્વારા સંચાલિત.</p>'],
        ]);

        foreach ([['darshan', 'Morning Darshan', '06:00', '12:00'], ['darshan', 'Evening Darshan', '16:00', '20:00'], ['aarti', 'Mangla Aarti', '06:30', null], ['aarti', 'Sandhya Aarti', '19:00', null], ['pooja', 'Abhishek', '08:00', '09:00']] as $t) {
            TempleTiming::create([
                'temple_id' => $temple->id, 'type' => $t[0], 'title' => $t[1],
                'title_gu' => $t[1], 'start_time' => $t[2], 'end_time' => $t[3],
                'day_of_week' => 'Daily', 'fee' => $t[0] === 'pooja' ? 251 : 0, 'is_active' => true, 'sort_order' => 0,
            ]);
        }

        Festival::create(['temple_id' => $temple->id, 'title' => 'Navratri Mahotsav', 'title_gu' => 'નવરાત્રી મહોત્સવ', 'description' => 'Nine nights of devotion and celebration.', 'start_date' => now()->addMonth(), 'is_active' => true]);
        Festival::create(['temple_id' => $temple->id, 'title' => 'Janmashtami', 'title_gu' => 'જન્માષ્ટમી', 'description' => 'Celebration of the divine birth.', 'start_date' => now()->addMonths(2), 'is_active' => true]);

        foreach ([['Shri Trustee', 'President'], ['Smt. Devotee', 'Vice President'], ['Shri Sevak', 'Secretary'], ['Shri Manager', 'Treasurer']] as $tr) {
            Trustee::create(['name' => $tr[0], 'designation' => $tr[1], 'bio' => 'Dedicated to the service of the temple.', 'is_active' => true, 'sort_order' => 0]);
        }

        for ($i = 1; $i <= 4; $i++) {
            News::create([
                'title' => "News Update {$i}",
                'title_gu' => "સમાચાર {$i}",
                'slug' => "news-update-{$i}",
                'excerpt' => '<p>A short summary of the latest temple event.</p>',
                'content' => '<p>This is the full content of news article number '.$i.'. Detailed information about the event and its significance.</p>',
                'category' => 'event',
                'is_published' => true,
                'published_at' => now()->subDays($i),
            ]);
        }

        Banner::create(['title' => 'Welcome', 'image_path' => 'banners/placeholder.jpg', 'is_active' => true, 'sort_order' => 0]);
        foreach (range(1, 6) as $i) {
            Gallery::create(['title' => "Photo {$i}", 'category' => $i % 2 ? 'Events' : 'Temple', 'image_path' => "gallery/photo-{$i}.jpg", 'is_active' => true, 'sort_order' => $i]);
        }
        Video::create(['title' => 'Aarti Live', 'source' => 'youtube', 'source_id' => 'dQw4w9WgXcQ', 'category' => 'Aarti', 'is_active' => true]);

        CmsPage::create(['slug' => 'facilities', 'title' => 'Facilities & Offerings', 'content' => '<p>Dharamshala, Vishram Gruh, Annashetra, Havan Khand and more.</p>', 'is_published' => true]);

        Faq::create(['question' => 'What are the darshan timings?', 'answer' => 'Morning 6 AM to 12 PM, Evening 4 PM to 8 PM.', 'category' => 'General', 'is_active' => true]);
        Faq::create(['question' => 'How can I donate?', 'answer' => 'You can donate online via our donate page.', 'category' => 'Donations', 'is_active' => true]);

        // Donation categories
        foreach ([['General Donation', true], ['Anna Seva', true], ['Nitya Pooja', true], ['Building Fund', true], ['Gau Seva', false]] as $idx => [$name, $eligible]) {
            DonationCategory::create(['name' => $name, 'description' => $name, 'is_80g_eligible' => $eligible, 'is_active' => true, 'sort_order' => $idx]);
        }

        // Accommodation
        $standard = RoomType::create(['temple_id' => $temple->id, 'name' => 'Standard Room', 'tariff' => 300, 'capacity' => 2, 'is_active' => true]);
        $deluxe = RoomType::create(['temple_id' => $temple->id, 'name' => 'Deluxe Room', 'tariff' => 600, 'capacity' => 3, 'is_active' => true]);
        foreach (range(1, 4) as $n) {
            Room::create(['room_type_id' => $standard->id, 'number' => 'S-'.$n, 'housekeeping_status' => 'clean', 'is_active' => true]);
        }
        foreach (range(1, 2) as $n) {
            Room::create(['room_type_id' => $deluxe->id, 'number' => 'D-'.$n, 'housekeeping_status' => 'clean', 'is_active' => true]);
        }
        MeetingHall::create(['temple_id' => $temple->id, 'name' => 'Community Hall', 'capacity' => 150, 'tariff' => 2000, 'is_active' => true]);

        // Shop products
        foreach ([['Prasadam Pack', 101, 'Prasad'], ['Holy Book', 251, 'Books'], ['Idol (small)', 551, 'Souvenirs'], ['Photo Frame', 351, 'Souvenirs']] as [$name, $price, $cat]) {
            Product::create(['slug' => \Illuminate\Support\Str::slug($name), 'name' => $name, 'price' => $price, 'stock' => 50, 'category' => $cat, 'is_active' => true]);
        }
    }
}
