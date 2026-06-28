<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\PageSection;
use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class CmsStructureSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedSiteSettings();
        $this->seedPages();
    }

    protected function seedSiteSettings(): void
    {
        $settings = [
            ['group' => 'branding', 'key' => 'site_name',     'value' => 'Shri Talaja Temple Trust', 'type' => 'text'],
            ['group' => 'branding', 'key' => 'site_tagline',  'value' => '|| Jay Mataji ||',         'type' => 'text'],
            ['group' => 'branding', 'key' => 'site_logo',     'value' => 'temple/logo.jpg',         'type' => 'image'],

            ['group' => 'header', 'key' => 'header_show_donate', 'value' => '1', 'type' => 'boolean'],

            ['group' => 'footer', 'key' => 'footer_about',     'value' => 'A sacred abode of devotion, transparency and service — serving devotees from across the world.', 'type' => 'textarea'],
            ['group' => 'footer', 'key' => 'footer_copyright', 'value' => '© :year Talaja Temple Trust. All rights reserved.', 'type' => 'text'],

            ['group' => 'social', 'key' => 'social_youtube',   'value' => '#', 'type' => 'text'],
            ['group' => 'social', 'key' => 'social_instagram', 'value' => '#', 'type' => 'text'],
            ['group' => 'social', 'key' => 'social_facebook',  'value' => '#', 'type' => 'text'],
            ['group' => 'social', 'key' => 'social_twitter',   'value' => null, 'type' => 'text'],

            ['group' => 'contact', 'key' => 'contact_phone',   'value' => '+91 9909912345', 'type' => 'text'],
            ['group' => 'contact', 'key' => 'contact_email',   'value' => 'contact@talajatemple.org', 'type' => 'text'],
            ['group' => 'contact', 'key' => 'contact_address', 'value' => "Shri Talaja Temple,\nTalaja, Bhavnagar,\nGujarat 364140", 'type' => 'textarea'],
            ['group' => 'contact', 'key' => 'contact_map',     'value' => 'https://www.google.com/maps?q=Talaja,Gujarat&output=embed', 'type' => 'textarea'],

            ['group' => 'seo', 'key' => 'seo_default_title',       'value' => 'Shri Talaja Temple Trust — Devotion, Service & Transparency', 'type' => 'text'],
            ['group' => 'seo', 'key' => 'seo_default_description', 'value' => 'Official portal of Shri Talaja Temple Trust. Donate, book darshan, accommodation & events online.', 'type' => 'textarea'],
            ['group' => 'seo', 'key' => 'seo_default_image',       'value' => 'hero/temple-1.jpg', 'type' => 'image'],

            ['group' => 'scripts', 'key' => 'scripts_head', 'value' => null, 'type' => 'textarea'],
            ['group' => 'scripts', 'key' => 'scripts_body', 'value' => null, 'type' => 'textarea'],
        ];

        foreach ($settings as $s) {
            SiteSetting::updateOrCreate(['key' => $s['key']], $s);
        }
    }

    protected function seedPages(): void
    {
        // Map slug → sections seeded if missing.
        $pages = [
            [
                'slug' => 'home', 'title' => 'Home', 'route_name' => 'home', 'is_published' => true,
                'meta_title' => 'Shri Talaja Temple Trust — Official Portal',
                'meta_description' => 'Live darshan, donations, accommodation and event bookings at Shri Talaja Temple.',
                'sections' => [
                    ['section_key' => 'hero', 'type' => 'hero_slider', 'title' => 'Welcome', 'subtitle' => '|| Jay Mataji ||',
                     'data' => [
                         'slides' => [
                             ['title' => 'Talaja Temple Trust', 'subtitle' => 'A sacred abode of devotion and service', 'tag' => '|| Jay Mataji ||', 'button_label' => 'Donate Now', 'button_href' => '/donate', 'image_path' => 'hero/temple-1.jpg'],
                             ['title' => 'Connect With the Divine', 'subtitle' => 'Live darshan, donations and blessings — anytime, anywhere', 'tag' => '|| Om Namah Shivay ||', 'button_label' => 'Live Darshan', 'button_href' => '/live-darshan', 'image_path' => 'hero/temple-2.jpg'],
                             ['title' => 'A Legacy of Faith', 'subtitle' => 'Serving devotees with devotion for generations', 'tag' => '|| Har Har Mahadev ||', 'button_label' => 'Donate Now', 'button_href' => '/donate', 'image_path' => 'hero/temple-3.jpg'],
                         ],
                     ],
                     'sort_order' => 1, 'is_active' => true,
                    ],
                    ['section_key' => 'intro', 'type' => 'richtext', 'title' => 'A Legacy of Devotion and Service',
                     'subtitle' => 'Welcome to',
                     'content' => '<p>Nestled in the serene landscape of Talaja, our temple has been a beacon of faith for generations. The trust manages temple operations, community welfare, accommodation for pilgrims, and a range of online services connecting devotees worldwide.</p>',
                     'sort_order' => 2, 'is_active' => true,
                    ],
                    ['section_key' => 'cta', 'type' => 'cta', 'title' => 'Begin Your Spiritual Journey',
                     'subtitle' => 'Join thousands of devotees. Donate, book your stay, or simply receive blessings.',
                     'data' => ['cta_label' => 'Donate Now', 'cta_href' => '/donate'],
                     'sort_order' => 99, 'is_active' => true,
                    ],
                ],
            ],

            [
                'slug' => 'about', 'title' => 'About the Trust', 'route_name' => 'about', 'is_published' => true,
                'meta_title' => 'About Shri Talaja Temple Trust',
                'meta_description' => 'Our mission, values and heritage — managing the temple with devotion and transparency.',
                'sections' => [
                    ['section_key' => 'hero', 'type' => 'richtext', 'title' => 'About the Trust',
                     'subtitle' => 'A sacred legacy of devotion, service and community',
                     'content' => '<p>Shri Talaja Temple Trust welcomes you to the sacred abode where the divine presence continues to inspire countless hearts. Established to preserve and promote devotional service, the trust manages temple operations, community welfare, accommodation for pilgrims, and a range of online services connecting devotees worldwide.</p>',
                     'sort_order' => 1, 'is_active' => true,
                    ],
                    ['section_key' => 'values', 'type' => 'values', 'title' => 'Our Core Values',
                     'data' => [
                         'items' => [
                             'Devotion'      => 'Upholding the sanctity of worship and tradition.',
                             'Service'       => 'Extending charitable reach to all in need.',
                             'Transparency'  => 'Ethical, accountable governance of every offering.',
                             'Heritage'      => 'Preserving our spiritual legacy for generations.',
                         ],
                     ],
                     'sort_order' => 2, 'is_active' => true,
                    ],
                    ['section_key' => 'place', 'type' => 'richtext', 'title' => 'A Place of Peace',
                     'subtitle' => 'image_split',
                     'content' => '<p>Beside the serene waters and sacred hills, the temple offers a tranquil retreat for meditation, prayer and reflection. Devotees from across the world come to seek blessings and experience the divine presence.</p>',
                     'sort_order' => 3, 'is_active' => true,
                    ],
                ],
            ],

            [
                'slug' => 'history', 'title' => 'Our History', 'route_name' => 'history', 'is_published' => true,
                'meta_title' => 'History of Shri Talaja Temple',
                'meta_description' => 'A timeless journey of faith across generations.',
                'sections' => [
                    ['section_key' => 'intro', 'type' => 'richtext', 'title' => 'Our History',
                     'subtitle' => 'A timeless journey of faith across generations',
                     'content' => '<p>The temple has stood for centuries as a beacon of faith on the Talaja hill. Revered by generations of devotees, it has been expanded and maintained by successive custodians, evolving into the vibrant spiritual centre it is today.</p>',
                     'sort_order' => 1, 'is_active' => true,
                    ],
                    ['section_key' => 'timeline', 'type' => 'timeline', 'title' => 'Our Journey',
                     'data' => [
                         'items' => [
                             'Ancient Era'       => 'Sacred origins — worshipped by sages and devotees for centuries.',
                             'Royal Patronage'   => 'Successive rulers expanded the temple into the grand structure seen today.',
                             'Trust Formation'   => 'A formal trust was constituted to ensure transparent governance.',
                             'Modern Era'        => 'Digital devotion — online darshan, donations and global outreach.',
                         ],
                     ],
                     'sort_order' => 2, 'is_active' => true,
                    ],
                ],
            ],

            [
                'slug' => 'facilities', 'title' => 'Facilities & Offerings', 'route_name' => 'facilities', 'is_published' => true,
                'meta_title' => 'Facilities & Offerings — Talaja Temple',
                'meta_description' => 'Dharamshala, Annashetra, Havan Khand and more for devotees.',
                'sections' => [
                    ['section_key' => 'intro', 'type' => 'richtext', 'title' => 'Facilities & Offerings',
                     'subtitle' => 'Serving devotees with comfort, care and devotion',
                     'content' => '<p>The trust offers a wide range of facilities and community services for devotees and visitors.</p>',
                     'sort_order' => 1, 'is_active' => true,
                    ],
                    ['section_key' => 'cta', 'type' => 'cta', 'title' => 'Plan Your Visit',
                     'subtitle' => 'Book your accommodation or event hall in advance for a comfortable stay.',
                     'data' => ['cta_label' => 'Book Now', 'cta_href' => '/bookings'],
                     'sort_order' => 99, 'is_active' => true,
                    ],
                ],
            ],

            [
                'slug' => 'contact', 'title' => 'Contact Us', 'route_name' => 'contact', 'is_published' => true,
                'meta_title' => 'Contact Us — Talaja Temple Trust',
                'meta_description' => 'Reach out to us with feedback, suggestions or prayer requests.',
                'sections' => [
                    ['section_key' => 'intro', 'type' => 'richtext', 'title' => 'Contact Us',
                     'sort_order' => 1, 'is_active' => true,
                    ],
                ],
            ],

            [
                'slug' => 'live-darshan', 'title' => 'Live Darshan', 'route_name' => 'live.darshan', 'is_published' => true,
                'meta_title' => 'Live Darshan — Talaja Temple',
                'meta_description' => 'Watch live darshan from the temple, anytime, anywhere.',
                'sections' => [
                    ['section_key' => 'intro', 'type' => 'richtext', 'title' => 'Darshan, Anytime, Anywhere',
                     'subtitle' => 'Experience the divine presence from anywhere in the world',
                     'content' => '<p>Distance should never come in the way of devotion. Whether near or far, stay connected to the temple and be part of every aarti and special event as it happens. Darshan timings follow the temple\'s aarti and seva schedule.</p>',
                     'sort_order' => 1, 'is_active' => true,
                    ],
                ],
            ],

            [
                'slug' => 'temple-info', 'title' => 'Temple Information', 'route_name' => 'temple.info', 'is_published' => true,
                'meta_title' => 'Temple Information — Timings & Festivals',
                'meta_description' => 'Darshan, aarti and pooja timings, festival calendar and how to reach the temple.',
                'sections' => [
                    ['section_key' => 'intro', 'type' => 'richtext', 'title' => 'Temple Information',
                     'subtitle' => 'Timings, schedules, festivals and how to reach us',
                     'sort_order' => 1, 'is_active' => true,
                    ],
                ],
            ],
        ];

        foreach ($pages as $p) {
            $sections = $p['sections'] ?? [];
            unset($p['sections']);
            $page = Page::updateOrCreate(['slug' => $p['slug']], $p);
            foreach ($sections as $s) {
                PageSection::updateOrCreate(
                    ['page_id' => $page->id, 'section_key' => $s['section_key']],
                    array_merge($s, ['page_id' => $page->id])
                );
            }
        }
    }
}