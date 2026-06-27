<?php

namespace Database\Seeders;

use App\Models\Banner;
use App\Models\CmsPage;
use App\Models\Donation;
use App\Models\DonationCategory;
use App\Models\DonationReceipt;
use App\Models\Faq;
use App\Models\Feedback;
use App\Models\Festival;
use App\Models\Gallery;
use App\Models\HallBooking;
use App\Models\HousekeepingLog;
use App\Models\LiveDarshanConfig;
use App\Models\MeetingHall;
use App\Models\News;
use App\Models\NotificationLog;
use App\Models\NotificationTemplate;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Publication;
use App\Models\Receipt;
use App\Models\Room;
use App\Models\RoomBooking;
use App\Models\RoomType;
use App\Models\Temple;
use App\Models\TempleTiming;
use App\Models\Trustee;
use App\Models\User;
use App\Models\Video;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ComprehensiveDataSeeder extends Seeder
{
    protected array $firstNames = ['Rajesh', 'Suresh', 'Mahesh', 'Kiran', 'Nilesh', 'Bhavesh', 'Hitesh', 'Jignesh', 'Ketan', 'Pankaj', 'Rakesh', 'Sanjay', 'Dipak', 'Hardik', 'Mitesh', 'Ashok', 'Prakash', 'Vimal', 'Anand', 'Chirag', 'Priya', 'Sneha', 'Pooja', 'Komal', 'Ritu', 'Anjali', 'Dipti', 'Hetal', 'Jigna', 'Meena', 'Nisha', 'Reena', 'Smita', 'Varsha', 'Bhavna', 'Geeta', 'Kiranben', 'Lataben', 'Manjulaben', 'Rasilaben'];
    protected array $lastNames = ['Patel', 'Shah', 'Mehta', 'Joshi', 'Desai', 'Gandhi', 'Modi', 'Patel', 'Thakkar', 'Bhatt', 'Pandya', 'Chauhan', 'Soni', 'Vyas', 'Dave', 'Shastri', 'Acharya', 'Oza', 'Raval', 'Janjmera'];
    protected array $cities = ['Talaja', 'Bhavnagar', 'Ahmedabad', 'Rajkot', 'Surat', 'Baroda', 'Junagadh', 'Jamnagar', 'Gandhinagar', 'Mumbai', 'Botad', 'Amreli', 'Palitana', 'Sihor'];

    public function run(): void
    {
        $this->seedTemple();
        $this->seedContent();
        $this->seedDonationCategories();
        $this->seedTrustees();
        $this->seedAccommodation();
        $this->seedShop();
        $this->seedUsers();
        $this->seedDonations();
        $this->seedBookings();
        $this->seedOrders();
        $this->seedFinance();
        $this->seedFeedback();
        $this->seedNotifications();
    }

    protected function seedTemple(): void
    {
        $temple = Temple::create([
            'name' => 'Shri Talaja Temple Trust',
            'slug' => 'talaja-temple',
            'is_primary' => true,
            'is_active' => true,
            'address' => "Shri Talaja Temple,\nTalaja, Bhavnagar,\nGujarat 364140",
            'phone' => '+91 9909912345',
            'email' => 'contact@talajatemple.org',
            'map_embed' => 'https://www.google.com/maps?q=Talaja,Gujarat&output=embed',
        ]);

        $temple->translations()->createMany([
            ['locale' => 'en', 'about_trust' => '<p>Shri Talaja Temple Trust welcomes you to the sacred abode where the divine presence continues to inspire countless hearts. Established to preserve and promote devotional service, the trust manages temple operations, community welfare, accommodation for pilgrims, and a range of online services connecting devotees worldwide.</p><p>Our mission is to uphold the sanctity of worship, ensure transparent governance, and extend the temple\'s charitable reach through anna seva, gau seva, education support and medical aid.</p>', 'history' => '<p>The temple has stood for centuries as a beacon of faith on the Talaja hill. Revered by generations of devotees, it has been expanded and maintained by successive custodians, evolving into the vibrant spiritual centre it is today.</p>', 'trust_info' => '<p>The trust is governed by an elected board of trustees committed to transparent administration, financial discipline, and devotional excellence.</p>'],
            ['locale' => 'gu', 'about_trust' => '<p>શ્રી તળાજા મંદિર ટ્રસ્ટમાં આપનું હાર્દિક સ્વાગત છે. અહીં ઈશ્વરીય સાનિધ્ય અનેક હૃદયને પ્રેરણા આપે છે.</p>', 'history' => '<p>આ મંદિર સદીઓથી શ્રદ્ધાનું કેન્દ્ર રહ્યું છે.</p>', 'trust_info' => '<p>ટ્રસ્ટ પારદર્શક વહીવટ માટે સમર્પિત છે.</p>'],
        ]);

        foreach ([
            ['darshan', 'Mangal Darshan', '05:30', '07:00', 'Daily', 0],
            ['darshan', 'Morning Darshan', '07:00', '12:00', 'Daily', 0],
            ['darshan', 'Evening Darshan', '16:00', '19:30', 'Daily', 0],
            ['darshan', 'Shayan Darshan', '20:30', '21:00', 'Daily', 0],
            ['aarti', 'Mangla Aarti', '05:00', '05:30', 'Daily', 0],
            ['aarti', 'Shringar Aarti', '07:30', '08:00', 'Daily', 0],
            ['aarti', 'Rajbhog Aarti', '12:00', '12:30', 'Daily', 0],
            ['aarti', 'Sandhya Aarti', '19:00', '19:30', 'Daily', 0],
            ['pooja', 'Abhishek Pooja', '08:00', '09:00', 'Daily', 251],
            ['pooja', 'Satyanarayan Katha', '09:30', '11:00', 'Purnima', 1100],
            ['pooja', 'Navchandi Yagna', '07:00', '12:00', 'On Request', 11001],
        ] as $i => $t) {
            TempleTiming::create([
                'temple_id' => $temple->id, 'type' => $t[0], 'title' => $t[1], 'title_gu' => $t[1],
                'start_time' => $t[2], 'end_time' => $t[3], 'day_of_week' => $t[4], 'fee' => $t[5],
                'is_active' => true, 'sort_order' => $i,
            ]);
        }

        $festivals = [
            ['Maha Shivratri', 'Maha Shivratri is celebrated with great devotion, featuring an all-night vigil, abhishek and aarti.', now()->subWeeks(3)],
            ['Holi / Dol Purnima', 'The festival of colours is observed with traditional fervour and prasad distribution.', now()->subDays(10)],
            ['Ram Navami', 'Celebration of the divine birth with katha, bhajan and community meal.', now()->addWeeks(2)],
            ['Janmashtami', 'Midnight celebrations marking the divine birth.', now()->addMonth()],
            ['Navratri Mahotsav', 'Nine nights of devotion, garba and aarti.', now()->addMonths(2)],
        ];
        foreach ($festivals as $i => $f) {
            Festival::create([
                'temple_id' => $temple->id, 'title' => $f[0], 'title_gu' => $f[0], 'description' => $f[1],
                'start_date' => $f[2]->toDateString(), 'end_date' => $f[2]->copy()->addDay()->toDateString(),
                'is_active' => true,
            ]);
        }
    }

    protected function seedContent(): void
    {
        $newsItems = [
            ['Lok Dayro Programme', 'event', 'A mesmerising evening of folk music and devotional songs drew hundreds of devotees. Renowned artists performed traditional Gujarati dayro, filling the temple premises with spiritual fervour.'],
            ['Maha Shivratri Celebrations', 'festival', 'The temple witnessed grand celebrations on Maha Shivratri with continuous abhishek, aarti and an all-night jagran attended by thousands of devotees from across Gujarat.'],
            ['Anna Seva Initiative Expanded', 'announcement', 'The trust expanded its free meal service (anna seva) to serve over 500 devotees daily. Contributions towards the anna kshetra are welcome.'],
            ['New Dharamshala Wing Inaugurated', 'announcement', 'A new wing of the Krishnakumarsinhji Yatrik Bhawan was inaugurated to provide comfortable accommodation for visiting pilgrims.'],
            ['Gyan Yagna Concludes', 'event', 'A week-long Gyan Yagna of katha and pravachan concluded with mass aarti and prasad. Devotees gathered in large numbers for the daily discourses.'],
            ['Free Medical Camp Success', 'event', 'The trust organised a free medical check-up camp benefiting over 300 villagers. Specialists in general medicine, eye care and dental care volunteered their services.'],
            ['Environment Drive: Tree Plantation', 'announcement', 'As part of sustainability efforts, the trust planted 500 saplings around the temple grounds and lake area.'],
        ];
        foreach ($newsItems as $i => $n) {
            News::create([
                'title' => $n[0], 'title_gu' => $n[0], 'slug' => Str::slug($n[0]),
                'excerpt' => '<p>'.Str::limit($n[2], 140).'</p>',
                'content' => '<p>'.$n[2].'</p><p>For more details and photographs, please visit the temple office or contact us through the official website.</p>',
                'category' => $n[1], 'is_published' => true,
                'published_at' => now()->subDays($i * 4 + 2),
            ]);
        }

        $cmsPages = [
            ['about-us', 'About the Trust', '<p>Shri Talaja Temple Trust is dedicated to devotional service and transparent governance. We welcome devotees from all walks of life to participate in the various seva and community welfare programmes.</p>'],
            ['facilities', 'Facilities & Offerings', '<p>The trust provides: Dharamshala (Krishnakumarsinhji Yatrik Bhawan), Vishram Gruh rest house, Lapsi Ghar, Annashetra (free meals), Havan Khand, and medical & environment services.</p>'],
            ['darshan', 'Darshan Information', '<p>Darshan is open daily during Mangal, Morning, Evening and Shayan timings. Please refer to the Temple Info page for the detailed schedule.</p>'],
        ];
        foreach ($cmsPages as $p) {
            CmsPage::create(['slug' => $p[0], 'title' => $p[1], 'content' => $p[2], 'is_published' => true, 'sort_order' => 0]);
        }

        Banner::create(['title' => 'Welcome to Talaja Temple', 'image_path' => 'banners/hero-1.jpg', 'is_active' => true, 'sort_order' => 0]);
        Banner::create(['title' => 'Jay Mataji', 'image_path' => 'banners/hero-2.jpg', 'is_active' => true, 'sort_order' => 1]);

        $galleryCats = ['Temple', 'Festivals', 'Events', 'Community'];
        for ($i = 1; $i <= 16; $i++) {
            Gallery::create([
                'title' => 'Photo '.$i, 'category' => $galleryCats[$i % 4],
                'image_path' => "gallery/photo-{$i}.jpg", 'alt_text' => 'Temple photograph',
                'is_active' => true, 'sort_order' => $i,
            ]);
        }

        foreach ([['Live Aarti - Sandhya', 'dQw4w9WgXcQ', 'Aarti'], ['Maha Shivratri Highlights', 'dQw4w9WgXcQ', 'Festival'], ['Katha Pravachan', 'dQw4w9WgXcQ', 'Katha']] as $i => $v) {
            Video::create(['title' => $v[0], 'source' => 'youtube', 'source_id' => $v[1], 'category' => $v[2], 'is_active' => true, 'sort_order' => $i]);
        }

        Publication::create(['title' => 'Annual Report 2024-25', 'file_path' => 'publications/annual-report.pdf', 'category' => 'Reports', 'is_active' => true]);
        Publication::create(['title' => 'Pooja Vidhi Guide', 'file_path' => 'publications/pooja-vidhi.pdf', 'category' => 'Guides', 'is_active' => true]);

        $faqs = [
            ['General', 'What are the darshan timings?', 'Darshan is open 5:30–12:00 and 16:00–21:00 daily.'],
            ['General', 'Is there an entry fee?', 'No, darshan is free for all devotees.'],
            ['Donations', 'Are donations eligible for 80G?', 'Yes, most categories qualify. Provide your PAN for the receipt.'],
            ['Donations', 'How can I donate online?', 'Visit the Donate page and pay securely via UPI, card or netbanking.'],
            ['Accommodation', 'Can I book a room online?', 'Yes, registered devotees can book rooms via the Bookings page.'],
            ['Accommodation', 'Is anna seva (free meal) available?', 'Yes, free meals are served daily during designated hours.'],
        ];
        foreach ($faqs as $i => $f) {
            Faq::create(['category' => $f[0], 'question' => $f[1], 'answer' => $f[2], 'is_active' => true, 'sort_order' => $i]);
        }

        LiveDarshanConfig::create([
            'temple_id' => 1, 'stream_url' => 'https://www.youtube.com/embed/live_stream?channel=UCTalajaTemple',
            'is_live' => true, 'start_time' => '05:30', 'end_time' => '21:00',
        ]);

        foreach ([
            ['donation_success', 'sms', 'Dear {name}, we received your donation of Rs.{amount}. Receipt: {receipt}. Thank you. - Talaja Temple'],
            ['booking_confirm', 'sms', 'Dear {name}, your room booking {booking} is confirmed. We look forward to welcoming you.'],
            ['order_placed', 'email', 'Dear {name}, your order {order} has been placed and will be dispatched shortly.'],
            ['otp', 'sms', 'Your Talaja Temple OTP is {otp}. Valid for 5 minutes. Do not share.'],
            ['festival_wish', 'whatsapp', 'Dear {name}, {festival} ki hardik shubhkamana! Jay Mataji.'],
        ] as $t) {
            NotificationTemplate::create(['code' => $t[0], 'channel' => $t[1], 'subject' => $t[0], 'body' => $t[2], 'is_active' => true]);
        }
    }

    protected function seedDonationCategories(): void
    {
        $cats = [
            ['General Donation', 'General contribution to the temple trust.', true],
            ['Anna Seva', 'Sponsor free meals for devotees.', true],
            ['Nitya Pooja', 'Daily pooja sponsorship.', true],
            ['Building Fund', 'Construction and renovation corpus.', true],
            ['Gau Seva', 'Cow welfare and shelter.', false],
            ['Education Aid', 'Support for needy students.', true],
            ['Medical Aid', 'Free medical camps and aid.', true],
        ];
        foreach ($cats as $i => $c) {
            DonationCategory::create([
                'name' => $c[0], 'description' => $c[1], 'is_80g_eligible' => $c[2],
                'is_active' => true, 'sort_order' => $i,
            ]);
        }
    }

    protected function seedTrustees(): void
    {
        $trustees = [
            ['Shri Govindbhai Patel', 'President', 'A respected community leader dedicated to temple service for over three decades.'],
            ['Shri Nareshbhai Shah', 'Vice President', 'Oversees construction and infrastructure projects of the trust.'],
            ['Shri Rashmikant Mehta', 'Secretary', 'Manages day-to-day administration and devotee relations.'],
            ['Shri Kamlesh Joshi', 'Treasurer', 'Ensures financial discipline and transparent accounting.'],
            ['Smt. Hansaben Desai', 'Trustee', 'Heads the women\'s welfare and cultural programmes.'],
            ['Shri Vipulbhai Gandhi', 'Trustee', 'Coordinates anna seva and community outreach activities.'],
        ];
        foreach ($trustees as $i => $t) {
            Trustee::create(['name' => $t[0], 'designation' => $t[1], 'bio' => $t[2], 'is_active' => true, 'sort_order' => $i]);
        }
    }

    protected function seedAccommodation(): void
    {
        $types = [
            ['Standard (Non-AC)', 350, 2],
            ['Deluxe (AC)', 750, 3],
            ['Family Suite (AC)', 1200, 5],
            ['Dormitory', 150, 8],
        ];
        $typeIds = [];
        foreach ($types as $t) {
            $rt = RoomType::create(['temple_id' => 1, 'name' => $t[0], 'tariff' => $t[1], 'capacity' => $t[2], 'is_active' => true]);
            $typeIds[] = $rt->id;
        }

        $roomNo = 101;
        foreach ($typeIds as $idx => $typeId) {
            $count = $idx === 3 ? 6 : 4;
            for ($i = 0; $i < $count; $i++) {
                Room::create([
                    'room_type_id' => $typeId, 'number' => (string) $roomNo++,
                    'floor' => $idx < 2 ? 'Ground' : 'First',
                    'housekeeping_status' => $this->pick(['clean', 'clean', 'clean', 'dirty']),
                    'is_active' => true,
                ]);
            }
        }

        MeetingHall::create(['temple_id' => 1, 'name' => 'Community Hall (AC)', 'capacity' => 200, 'tariff' => 2500, 'is_active' => true]);
        MeetingHall::create(['temple_id' => 1, 'name' => 'Sabhagrüh', 'capacity' => 80, 'tariff' => 1200, 'is_active' => true]);
    }

    protected function seedShop(): void
    {
        $products = [
            ['Prasadam Pack (500g)', 101, 'Prasad', 200],
            ['Holy Book - Talaja Mahatmya', 251, 'Books', 150],
            ['Brass Idol (small)', 751, 'Souvenirs', 60],
            ['Photo Frame - Deity', 351, 'Souvenirs', 80],
            ['Incense & Dhoop Set', 151, 'Pooja Items', 300],
            ['Cotton Wicks & Camphor', 51, 'Pooja Items', 400],
            ['Silver Coin (10g)', 1851, 'Souvenirs', 40],
            ['Calendar 2026', 101, 'Stationery', 250],
        ];
        foreach ($products as $p) {
            Product::create([
                'slug' => Str::slug($p[0]), 'name' => $p[0], 'price' => $p[1],
                'stock' => $p[3], 'category' => $p[2], 'is_active' => true,
            ]);
        }
    }

    protected function seedUsers(): void
    {
        // Devotees (some will be referenced by donations/bookings)
        for ($i = 0; $i < 40; $i++) {
            $name = $this->firstNames[array_rand($this->firstNames)].' '.$this->lastNames[array_rand($this->lastNames)];
            User::create([
                'name' => $name,
                'email' => Str::slug($name).$i.'@example.com',
                'mobile' => '9'.mt_rand(600000000, 999999999),
                'password' => bcrypt('password'),
                'type' => 'devotee',
                'is_active' => true,
                'mobile_verified_at' => now(),
                'pan' => $i % 3 === 0 ? strtoupper(Str::random(5)).'1234'.strtoupper(Str::random(1)) : null,
                'address' => $this->cities[array_rand($this->cities)].', Gujarat',
            ]);
        }
    }

    protected function seedDonations(): void
    {
        $categories = DonationCategory::all();
        $catIds = $categories->pluck('id')->toArray();
        $amounts = [51, 101, 251, 501, 501, 1001, 1001, 2501, 5001, 10001];
        $modes = ['online' => 0.5, 'upi' => 0.3, 'cash' => 0.15, 'qr' => 0.05];
        $devotees = User::where('type', 'devotee')->get();

        $receiptSeq = 0;
        $g80Seq = 0;

        for ($i = 0; $i < 120; $i++) {
            $daysAgo = mt_rand(0, 60);
            $date = Carbon::today()->subDays($daysAgo)->setTime(mt_rand(6, 20), mt_rand(0, 59));
            $amount = $amounts[array_rand($amounts)];
            $mode = $this->weighted($modes);
            $status = $this->weighted(['success' => 0.85, 'pending' => 0.08, 'failed' => 0.07]);
            $devotee = $devotees->random();
            $category = $categories->random();

            $is80g = $category->is_80g_eligible && mt_rand(0, 1) === 1 && $status === 'success';

            $donation = Donation::create([
                'receipt_no' => 'DON-'.date('Y').'-'.str_pad((string) ($receiptSeq + 1), 5, '0', STR_PAD_LEFT),
                'temple_id' => 1,
                'donor_id' => mt_rand(0, 1) ? $devotee->id : null,
                'donation_category_id' => $category->id,
                'amount' => $amount,
                'currency' => 'INR',
                'payment_mode' => $mode,
                'status' => $status,
                'gateway' => $mode === 'cash' ? null : 'razorpay',
                'gateway_transaction_id' => $status === 'success' && $mode !== 'cash' ? 'pay_'.Str::random(14) : null,
                'is_80g' => $is80g,
                'donor_name' => $devotee->name,
                'donor_email' => $devotee->email,
                'donor_mobile' => $devotee->mobile,
                'donor_pan' => $is80g ? $devotee->pan : null,
                'donor_address' => $devotee->address,
                'is_anonymous' => mt_rand(0, 9) === 0,
                'paid_at' => $status === 'success' ? $date : null,
            ]);
            $receiptSeq++;

            if ($status === 'success') {
                // Financial receipt record
                $receiptMode = $mode === 'qr' ? 'upi' : $mode;
                Receipt::create([
                    'receipt_no' => $donation->receipt_no, 'temple_id' => 1, 'source' => 'donation',
                    'receiptable_type' => Donation::class, 'receiptable_id' => $donation->id,
                    'amount' => $amount, 'payment_mode' => $receiptMode, 'date' => $date->toDateString(),
                ]);

                // Donor-facing receipt (80G or general)
                $type = $is80g ? '80g' : 'general';
                $receiptNo = $type === '80g'
                    ? '80G-'.date('Y').'-'.str_pad((string) ($g80Seq + 1), 5, '0', STR_PAD_LEFT)
                    : 'DON-R-'.date('Y').'-'.str_pad((string) ($receiptSeq), 5, '0', STR_PAD_LEFT);
                if ($type === '80g') {
                    $g80Seq++;
                }
                DonationReceipt::create([
                    'donation_id' => $donation->id, 'receipt_no' => $receiptNo,
                    'receipt_type' => $type, 'pdf_path' => "receipts/{$receiptNo}.pdf", 'is_void' => false,
                ]);

                // Notification log
                NotificationLog::create([
                    'channel' => 'sms', 'recipient' => $devotee->mobile,
                    'content' => "Dear {$devotee->name}, received Rs.{$amount}. Receipt: {$receiptNo}.",
                    'status' => 'sent', 'sent_at' => $date,
                ]);
            }
        }
    }

    protected function seedBookings(): void
    {
        $rooms = Room::all();
        $devotees = User::where('type', 'devotee')->get();
        $statuses = ['confirmed', 'checked_in', 'checked_out', 'cancelled'];

        for ($i = 0; $i < 45; $i++) {
            $room = $rooms->random();
            $checkIn = Carbon::today()->subDays(mt_rand(0, 50));
            $nights = mt_rand(1, 3);
            $checkOut = $checkIn->copy()->addDays($nights);
            $devotee = $devotees->random();
            $status = $this->pick($statuses);

            $booking = RoomBooking::create([
                'booking_no' => 'RB-'.strtoupper(Str::random(8)),
                'room_id' => $room->id,
                'user_id' => $devotee->id,
                'guest_name' => $devotee->name,
                'guest_email' => $devotee->email,
                'guest_mobile' => $devotee->mobile,
                'guests' => mt_rand(1, $room->type->capacity),
                'check_in' => $checkIn, 'check_out' => $checkOut,
                'amount' => $room->type->tariff * $nights,
                'payment_mode' => $this->pick(['online', 'pay_at_temple']),
                'status' => $status,
                'checked_in_at' => in_array($status, ['checked_in', 'checked_out']) ? $checkIn->copy()->setTime(12, 0) : null,
                'checked_out_at' => $status === 'checked_out' ? $checkOut->copy()->setTime(10, 0) : null,
                'note' => mt_rand(0, 2) === 0 ? 'Requested early check-in' : null,
            ]);

            // Financial receipt for non-cancelled
            if ($status !== 'cancelled') {
                Receipt::create([
                    'receipt_no' => $booking->booking_no, 'temple_id' => 1, 'source' => 'booking',
                    'receiptable_type' => RoomBooking::class, 'receiptable_id' => $booking->id,
                    'amount' => $booking->amount, 'payment_mode' => $booking->payment_mode === 'online' ? 'online' : 'cash',
                    'date' => $checkIn->toDateString(),
                ]);
            }

            // Housekeeping log for checked-out
            if ($status === 'checked_out') {
                HousekeepingLog::create(['room_id' => $room->id, 'user_id' => 1, 'status' => 'dirty', 'note' => 'Checkout cleaning pending']);
            }
        }

        // Hall bookings
        $halls = MeetingHall::all();
        for ($i = 0; $i < 12; $i++) {
            $hall = $halls->random();
            $date = Carbon::today()->subDays(mt_rand(0, 50));
            $devotee = $devotees->random();
            HallBooking::create([
                'booking_no' => 'HB-'.strtoupper(Str::random(8)),
                'meeting_hall_id' => $hall->id, 'user_id' => $devotee->id,
                'guest_name' => $devotee->name, 'guest_mobile' => $devotee->mobile,
                'event_date' => $date, 'start_time' => '18:00', 'end_time' => '21:00',
                'attendees' => mt_rand(30, $hall->capacity), 'amount' => $hall->tariff,
                'status' => $this->pick(['confirmed', 'confirmed', 'cancelled']),
                'note' => mt_rand(0, 2) === 0 ? 'For community gathering' : null,
            ]);
        }
    }

    protected function seedOrders(): void
    {
        $products = Product::all();
        $devotees = User::where('type', 'devotee')->get();

        for ($i = 0; $i < 35; $i++) {
            $date = Carbon::today()->subDays(mt_rand(0, 60));
            $devotee = $devotees->random();
            $itemCount = mt_rand(1, 3);
            $items = [];
            $subtotal = 0;
            for ($j = 0; $j < $itemCount; $j++) {
                $product = $products->random();
                $qty = mt_rand(1, 2);
                $lineTotal = $product->price * $qty;
                $subtotal += $lineTotal;
                $items[] = ['product' => $product, 'qty' => $qty, 'total' => $lineTotal];
            }
            $shipping = $subtotal > 1000 ? 0 : 60;
            $tax = round($subtotal * 0.05, 2);

            $order = Order::create([
                'order_no' => 'ORD-'.strtoupper(Str::random(8)),
                'user_id' => $devotee->id,
                'customer_name' => $devotee->name, 'customer_email' => $devotee->email, 'customer_mobile' => $devotee->mobile,
                'shipping_address' => $devotee->address,
                'subtotal' => $subtotal, 'shipping' => $shipping, 'tax' => $tax, 'total' => $subtotal + $shipping + $tax,
                'payment_status' => 'paid', 'fulfilment_status' => $this->pick(['delivered', 'shipped', 'packed', 'new']),
                'tracking_no' => mt_rand(0, 1) ? 'TRK'.mt_rand(100000, 999999) : null,
            ]);

            foreach ($items as $it) {
                OrderItem::create([
                    'order_id' => $order->id, 'product_id' => $it['product']->id,
                    'name' => $it['product']->name, 'price' => $it['product']->price,
                    'quantity' => $it['qty'], 'total' => $it['total'],
                ]);
            }
        }
    }

    protected function seedFinance(): void
    {
        $vendors = ['Electricity Board (PGVCL)', 'Water Supply Dept', 'Housekeeping Agency', 'Florist - Jayantilal', 'Prasad Supplier', 'Security Agency', 'Internet/IT Services', 'Construction Contractor'];
        $categories = ['Utility', 'Salary', 'Maintenance', 'Supplies', 'Service', 'Construction'];
        for ($i = 0; $i < 30; $i++) {
            $date = Carbon::today()->subDays(mt_rand(0, 60));
            Payment::create([
                'voucher_no' => 'PAY-'.date('Y').'-'.str_pad((string) ($i + 1), 4, '0', STR_PAD_LEFT),
                'temple_id' => 1, 'payee' => $vendors[array_rand($vendors)],
                'category' => $categories[array_rand($categories)],
                'amount' => $this->pick([2500, 5000, 12000, 18000, 35000, 75000]),
                'payment_mode' => $this->pick(['bank_transfer', 'cheque', 'cash']),
                'date' => $date->toDateString(), 'approved_by' => 'Kamlesh Joshi',
                'note' => mt_rand(0, 2) === 0 ? 'Monthly recurring' : null,
            ]);
        }
    }

    protected function seedFeedback(): void
    {
        $messages = [
            'suggestion' => ['Please start a mobile app for darshan booking.', 'Would love to see more frequent live streams.', 'Consider adding online prasad delivery.'],
            'feedback' => ['Excellent arrangements during our visit. Very peaceful.', 'Clean premises and helpful staff. Thank you.', 'The anna seva was very well organised.'],
            'complaint' => ['Room AC was not cooling properly.', 'Long queue during evening aarti, please manage better.'],
        ];
        $devotees = User::where('type', 'devotee')->get();
        for ($i = 0; $i < 25; $i++) {
            $type = $this->pick(['suggestion', 'feedback', 'feedback', 'complaint']);
            $devotee = $devotees->random();
            $status = $type === 'complaint' ? $this->pick(['open', 'in_progress', 'closed']) : $this->pick(['closed', 'closed', 'open']);
            Feedback::create([
                'type' => $type, 'name' => $devotee->name, 'email' => $devotee->email, 'mobile' => $devotee->mobile,
                'category' => ucfirst($type), 'rating' => mt_rand(3, 5),
                'message' => $this->pick($messages[$type]), 'status' => $status,
                'admin_reply' => $status === 'closed' ? 'Thank you for your input. We have addressed this.' : null,
            ]);
        }
    }

    protected function seedNotifications(): void
    {
        $channels = ['sms', 'email', 'whatsapp'];
        $devotees = User::where('type', 'devotee')->get();
        for ($i = 0; $i < 40; $i++) {
            $devotee = $devotees->random();
            $channel = $this->pick($channels);
            NotificationLog::create([
                'channel' => $channel,
                'recipient' => $channel === 'email' ? $devotee->email : $devotee->mobile,
                'content' => 'Automated devotional update / receipt notification.',
                'status' => $this->pick(['sent', 'sent', 'delivered', 'failed']),
                'sent_at' => Carbon::today()->subDays(mt_rand(0, 60))->setTime(mt_rand(8, 20), 0),
            ]);
        }
    }

    protected function pick(array $items)
    {
        return $items[array_rand($items)];
    }

    protected function weighted(array $weights)
    {
        $rand = mt_rand() / mt_getrandmax();
        $cum = 0;
        foreach ($weights as $value => $weight) {
            $cum += $weight;
            if ($rand <= $cum) {
                return $value;
            }
        }

        return array_key_first($weights);
    }
}
