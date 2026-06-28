<?php

namespace App\Http\Middleware;

use App\Models\Facility;
use App\Models\HomeService;
use App\Models\HomeSlide;
use App\Models\HomeStat;
use App\Models\Setting;
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
                'name' => $temple?->name ?? config('app.name'),
                'tagline' => $setting('site_tagline', env('SITE_TAGLINE', '|| Jay Mataji ||')),
                'phone' => $temple?->phone ?? env('SITE_PHONE', '+91 0000000000'),
                'email' => $temple?->email ?? env('SITE_EMAIL', 'contact@talajatemple.org'),
                'address' => $temple?->address ?? env('SITE_ADDRESS', 'Talaja, Gujarat'),
                'logo' => $temple?->logo_path,
            ],
            'homeStats' => $homeStats,
            'heroSlides' => $slides,
            'services' => $services,
            'facilities' => $facilities,
        ];
    }
}
