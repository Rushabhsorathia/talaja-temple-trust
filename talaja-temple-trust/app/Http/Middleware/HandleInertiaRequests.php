<?php

namespace App\Http\Middleware;

use App\Models\Facility;
use App\Models\HomeService;
use App\Models\HomeSlide;
use App\Models\HomeStat;
use App\Models\Page;
use App\Models\Setting;
use App\Models\SiteSetting;
use App\Models\Temple;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Tighten\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $locale = session('locale', config('app.locale'));

        // DB-backed site settings (cached for the request), with sensible
        // fallbacks so the site never breaks on a fresh install.
        $temple = cache()->remember('temple.primary', 60, fn () => Temple::where('is_primary', true)->first());

        $setting = fn (string $key, $default = null) => Setting::get($key, $default);

        $decode = function (string $key, array $default) use ($setting) {
            $raw = $setting($key);

            return $raw ? json_decode($raw, true) ?? $default : $default;
        };

        $defaultStats = [
            ['value' => '5L+', 'label' => 'Devotees Served', 'icon' => 'users'],
            ['value' => '100+', 'label' => 'Years of Legacy', 'icon' => 'clock'],
            ['value' => '500+', 'label' => 'Daily Annaseva', 'icon' => 'soup'],
            ['value' => '24/7', 'label' => 'Live Darshan', 'icon' => 'wifi'],
        ];

        $defaultSlides = [
            ['img' => '/storage/hero/temple-1.jpg', 'title' => 'Talaja Temple Trust', 'sub' => 'A sacred abode of devotion and service', 'tag' => '|| Jay Mataji ||'],
            ['img' => '/storage/hero/temple-2.jpg', 'title' => 'Connect With the Divine', 'sub' => 'Live darshan, donations and blessings — anytime, anywhere', 'tag' => '|| Om Namah Shivay ||'],
            ['img' => '/storage/hero/temple-3.jpg', 'title' => 'A Legacy of Faith', 'sub' => 'Serving devotees with devotion for generations', 'tag' => '|| Har Har Mahadev ||'],
        ];

        $defaultServices = [
            ['icon' => 'video', 'title' => 'Live Darshan', 'desc' => 'Experience divine darshan from anywhere in the world.', 'href' => '/live-darshan', 'badge' => 'Live'],
            ['icon' => 'heart', 'title' => 'Donate', 'desc' => 'Support the temple with secure online donations (80G eligible).', 'href' => '/donate'],
            ['icon' => 'bed', 'title' => 'Bookings', 'desc' => 'Reserve rooms and halls for your stay and events.', 'href' => '/bookings'],
            ['icon' => 'bag', 'title' => 'Shop', 'desc' => 'Prasad, books and souvenirs delivered to your home.', 'href' => '/shop'],
        ];

        $defaultFacilities = [
            ['icon' => 'bed', 'title' => 'Dharamshala', 'desc' => 'Comfortable accommodation for pilgrims with AC and non-AC rooms.', 'image' => '/storage/facilities/dharamshala.jpg'],
            ['icon' => 'home', 'title' => 'Vishram Gruh', 'desc' => 'A peaceful rest house for devotees seeking a quiet retreat.', 'image' => '/storage/about/temple.jpg'],
            ['icon' => 'soup', 'title' => 'Annashetra', 'desc' => 'Free wholesome meals (anna seva) served daily to all devotees.', 'image' => '/storage/facilities/anna.jpg'],
            ['icon' => 'flame', 'title' => 'Havan Khand', 'desc' => 'Dedicated space for havan, yagna and sacred fire rituals.', 'image' => '/storage/facilities/havan.jpg'],
            ['icon' => 'cross', 'title' => 'Free Medical Services', 'desc' => 'Periodic health camps providing free check-ups and medicines.', 'image' => '/storage/facilities/medical.jpg'],
            ['icon' => 'trees', 'title' => 'Environment Initiatives', 'desc' => 'Tree plantation and green drives around the temple grounds.', 'image' => '/storage/facilities/tree.jpg'],
        ];

        // CMS-managed home content (each record editable via Filament admin),
        // cached briefly, with sensible fallbacks for a fresh install.
        $slides = cache()->remember('cms.slides', 30, fn () => HomeSlide::active()->ordered()->get()->map(fn ($s) => [
            'img' => $s->image_path ? asset('storage/'.$s->image_path) : null,
            'title' => $s->title,
            'sub' => $s->subtitle,
            'tag' => $s->tag,
            'button_label' => $s->button_label,
            'button_href' => $s->button_href,
        ])->all()) ?: $defaultSlides;

        $services = cache()->remember('cms.services', 30, fn () => HomeService::active()->ordered()->get()->map(fn ($s) => [
            'icon' => $s->icon, 'title' => $s->title, 'desc' => $s->description,
            'href' => $s->href, 'badge' => $s->badge,
        ])->all()) ?: $defaultServices;

        $homeStats = cache()->remember('cms.stats', 30, fn () => HomeStat::active()->ordered()->get()->map(fn ($s) => [
            'value' => $s->value, 'label' => $s->label, 'icon' => $s->icon,
        ])->all()) ?: $defaultStats;

        $facilities = cache()->remember('cms.facilities', 30, fn () => Facility::active()->ordered()->get()->map(fn ($f) => [
            'icon' => $f->icon, 'title' => $f->title, 'desc' => $f->description,
            'image' => $f->image_path ? asset('storage/'.$f->image_path) : null,
        ])->all()) ?: $defaultFacilities;

        // CMS-managed pages — each page's sections are editable in Admin →
        // Site Content → Pages & Sections. Cached briefly; if no record exists
        // the Vue page falls back to its built-in copy.
        $pages = cache()->remember('cms.pages', 30, fn () => Page::with(['sections' => fn ($q) => $q->ordered()])->where('is_published', true)->get()->keyBy('slug')->map(function ($p) {
            return [
                'title'    => $p->title,
                'meta'     => array_filter([
                    'meta_title'       => $p->meta_title,
                    'meta_description' => $p->meta_description,
                    'meta_image'       => $p->meta_image,
                ]),
                'sections' => $p->sections->where('is_active', true)->mapWithKeys(fn ($s) => [
                    $s->section_key => [
                        'type'    => $s->type,
                        'title'   => $s->title,
                        'subtitle'=> $s->subtitle,
                        'content' => $s->content,
                        'data'    => $s->data,
                    ],
                ]),
            ];
        }));

        // Grouped site-wide settings (header/footer/social/branding/contact/
        // seo/scripts). One source of truth instead of raw settings key/value.
        $siteSettingsGrouped = cache()->remember('cms.site_settings', 30, fn () => SiteSetting::all()->groupBy('group')->map->pluck('value', 'key'));

        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
            ],
            'ziggy' => fn () => [
                ...(new Ziggy)->toArray(),
                'location' => $request->url(),
            ],
            'locale' => $locale,
            'nav' => __('nav'),
            'flash' => [
                'success' => $request->session()->get('success'),
                'error' => $request->session()->get('error'),
            ],
            'siteSettings' => [
                'name' => $siteSettingsGrouped['branding']['site_name'] ?? $temple?->name ?? config('app.name'),
                'tagline' => $siteSettingsGrouped['branding']['site_tagline'] ?? $setting('site_tagline', env('SITE_TAGLINE', '|| Jay Mataji ||')),
                'logo' => $siteSettingsGrouped['branding']['site_logo'] ?? $temple?->logo_path,
                'phone' => $siteSettingsGrouped['contact']['contact_phone'] ?? $temple?->phone ?? env('SITE_PHONE', '+91 0000000000'),
                'email' => $siteSettingsGrouped['contact']['contact_email'] ?? $temple?->email ?? env('SITE_EMAIL', 'contact@talajatemple.org'),
                'address' => $siteSettingsGrouped['contact']['contact_address'] ?? $temple?->address ?? env('SITE_ADDRESS', 'Talaja, Gujarat'),
                'map_embed' => $siteSettingsGrouped['contact']['contact_map'] ?? $temple?->map_embed,
                'social' => [
                    'youtube'   => $siteSettingsGrouped['social']['social_youtube']   ?? null,
                    'instagram' => $siteSettingsGrouped['social']['social_instagram'] ?? null,
                    'facebook'  => $siteSettingsGrouped['social']['social_facebook']  ?? null,
                    'twitter'   => $siteSettingsGrouped['social']['social_twitter']   ?? null,
                ],
                'footer' => [
                    'about'      => $siteSettingsGrouped['footer']['footer_about']      ?? null,
                    'copyright'  => $siteSettingsGrouped['footer']['footer_copyright']  ?? null,
                ],
                'header' => [
                    'show_donate' => ($siteSettingsGrouped['header']['header_show_donate'] ?? '1') !== '0',
                ],
                'seo' => [
                    'default_title'       => $siteSettingsGrouped['seo']['seo_default_title']       ?? null,
                    'default_description' => $siteSettingsGrouped['seo']['seo_default_description'] ?? null,
                    'default_image'       => $siteSettingsGrouped['seo']['seo_default_image']       ?? null,
                ],
                'scripts' => [
                    'head'  => $siteSettingsGrouped['scripts']['scripts_head']  ?? null,
                    'body'  => $siteSettingsGrouped['scripts']['scripts_body']  ?? null,
                ],
            ],
            'pages' => $pages,
            'homeStats' => $homeStats,
            'heroSlides' => $slides,
            'services' => $services,
            'facilities' => $facilities,
        ];
    }
}
