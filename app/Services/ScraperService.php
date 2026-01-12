<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Destination;
use App\Models\TourType;
use App\Models\Tour;
use App\Models\Cruise;
use App\Models\Hotel;
use App\Models\Excursion;
use App\Models\Page;
use App\Models\Faq;
use App\Models\Season;
use App\Models\Price;
use Illuminate\Support\Str;

class ScraperService
{
    private $baseUrl = 'https://www.luxorandaswan.com';
    private $client;
    
    public function __construct()
    {
        $this->client = Http::withOptions([
            'timeout' => 30,
            'verify' => false,
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                'Accept-Language' => 'en-US,en;q=0.5',
                'Accept-Encoding' => 'gzip, deflate, br',
                'Connection' => 'keep-alive',
                'Upgrade-Insecure-Requests' => '1',
            ]
        ]);
    }

    /**
     * استخراج جميع البيانات من الموقع
     */
    public function scrapeAllData()
    {
        $data = [];
        
        try {
            // 1. استخراج بيانات المستخدمين (من نماذج الاتصال)
            $data['users'] = $this->scrapeUsers();
            
            // 2. استخراج الوجهات
            $data['destinations'] = $this->scrapeDestinations();
            
            // 3. استخراج أنواع الرحلات
            $data['tour_types'] = $this->scrapeTourTypes();
            
            // 4. استخراج الرحلات
            $data['tours'] = $this->scrapeTours();
            
            // 5. استخراج الكروزات
            $data['cruises'] = $this->scrapeCruises();
            
            // 6. استخراج الفنادق
            $data['hotels'] = $this->scrapeHotels();
            
            // 7. استخراج الجولات الإضافية
            $data['excursions'] = $this->scrapeExcursions();
            
            // 8. استخراج المواسم
            $data['seasons'] = $this->scrapeSeasons();
            
            // 9. استخراج الأسعار
            $data['prices'] = $this->scrapePrices();
            
            // 10. استخراج الصفحات
            $data['pages'] = $this->scrapePages();
            
            // 11. استخراج الأسئلة الشائعة
            $data['faqs'] = $this->scrapeFaqs();
            
            return $data;
            
        } catch (\Exception $e) {
            Log::error('Web scraping failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * استخراج بيانات المستخدمين
     */
    private function scrapeUsers()
    {
        $users = [];
        
        try {
            $response = $this->client->get($this->baseUrl . '/contact');
            $html = $response->body();
            $crawler = new Crawler($html);
            
            // استخراج بيانات من نماذج الاتصال (هذا مثال)
            $users[] = [
                'name' => 'Admin User',
                'email' => 'admin@luxorandaswan.com',
                'phone' => '+201234567890',
                'country' => 'Egypt',
                'nationality' => 'Egyptian',
                'password' => bcrypt('password123'),
                'role' => 'admin',
                'is_active' => true,
                'email_verified_at' => now(),
            ];
            
            // إضافة مستخدمين افتراضيين
            $users[] = [
                'name' => 'Customer Test',
                'email' => 'customer@example.com',
                'phone' => '+201112223344',
                'country' => 'USA',
                'nationality' => 'American',
                'password' => bcrypt('password123'),
                'role' => 'customer',
                'is_active' => true,
                'email_verified_at' => now(),
            ];
            
        } catch (\Exception $e) {
            Log::error('Failed to scrape users: ' . $e->getMessage());
        }
        
        return $users;
    }

    /**
     * استخراج الوجهات السياحية
     */
    private function scrapeDestinations()
    {
        $destinations = [];
        
        try {
            $response = $this->client->get($this->baseUrl . '/destinations');
            $html = $response->body();
            $crawler = new Crawler($html);
            
            // استخراج قائمة الوجهات (هذا مثال - تحتاج لتعديل حسب هيكل الموقع الفعلي)
            $destinationsData = [
                [
                    'name' => 'Luxor',
                    'slug' => 'luxor',
                    'country' => 'Egypt',
                    'region' => 'Upper Egypt',
                    'description' => 'Luxor is often called the world\'s greatest open-air museum...',
                    'short_description' => 'The ancient city of Thebes, home to Valley of the Kings',
                    'main_image' => 'luxor-temple.jpg',
                    'featured' => true,
                ],
                [
                    'name' => 'Aswan',
                    'slug' => 'aswan',
                    'country' => 'Egypt',
                    'region' => 'Upper Egypt',
                    'description' => 'Aswan is the ancient city of Swenett...',
                    'short_description' => 'Famous for the High Dam and Philae Temple',
                    'main_image' => 'aswan-dam.jpg',
                    'featured' => true,
                ],
                [
                    'name' => 'Cairo',
                    'slug' => 'cairo',
                    'country' => 'Egypt',
                    'region' => 'Lower Egypt',
                    'description' => 'The capital of Egypt and home to the Pyramids of Giza...',
                    'short_description' => 'Home to the Great Pyramids and Egyptian Museum',
                    'main_image' => 'cairo-pyramids.jpg',
                    'featured' => true,
                ],
                [
                    'name' => 'Abu Simbel',
                    'slug' => 'abu-simbel',
                    'country' => 'Egypt',
                    'region' => 'Upper Egypt',
                    'description' => 'The Abu Simbel temples are two massive rock temples...',
                    'short_description' => 'Temple of Ramses II relocated to save from flooding',
                    'main_image' => 'abu-simbel.jpg',
                    'featured' => false,
                ],
                [
                    'name' => 'Alexandria',
                    'slug' => 'alexandria',
                    'country' => 'Egypt',
                    'region' => 'Mediterranean Coast',
                    'description' => 'Founded by Alexander the Great in 331 BC...',
                    'short_description' => 'Mediterranean port city with ancient library',
                    'main_image' => 'alexandria.jpg',
                    'featured' => false,
                ],
            ];
            
            foreach ($destinationsData as $dest) {
                $dest['gallery'] = json_encode([$dest['main_image'], 'gallery1.jpg', 'gallery2.jpg']);
                $dest['map_coordinates'] = '26.8206,30.8025';
                $dest['climate_info'] = 'Hot desert climate with very little rainfall';
                $dest['best_time_to_visit'] = 'October to April';
                $dest['meta_title'] = $dest['name'] . ' Tours & Travel Guide';
                $dest['meta_description'] = 'Book your ' . $dest['name'] . ' tour with Luxor and Aswan Tours. Best prices guaranteed.';
                $dest['active'] = true;
                $destinations[] = $dest;
            }
            
        } catch (\Exception $e) {
            Log::error('Failed to scrape destinations: ' . $e->getMessage());
        }
        
        return $destinations;
    }

    /**
     * استخراج أنواع الرحلات
     */
    private function scrapeTourTypes()
    {
        return [
            [
                'name' => 'Nile Cruise',
                'slug' => 'nile-cruise',
                'icon' => 'ship',
                'description' => 'Luxurious Nile River cruises between Luxor and Aswan',
                'display_order' => 1,
            ],
            [
                'name' => 'Day Tours',
                'slug' => 'day-tours',
                'icon' => 'map-marked',
                'description' => 'Private guided day tours to archaeological sites',
                'display_order' => 2,
            ],
            [
                'name' => 'Multi-day Packages',
                'slug' => 'packages',
                'icon' => 'suitcase',
                'description' => 'Complete vacation packages including hotels and tours',
                'display_order' => 3,
            ],
            [
                'name' => 'Dahabiya Nile Cruise',
                'slug' => 'dahabiya-cruise',
                'icon' => 'sailboat',
                'description' => 'Traditional sailing boat Nile cruises',
                'display_order' => 4,
            ],
            [
                'name' => 'Shore Excursions',
                'slug' => 'shore-excursions',
                'icon' => 'anchor',
                'description' => 'Tours for cruise ship passengers',
                'display_order' => 5,
            ],
            [
                'name' => 'Tailor Made',
                'slug' => 'tailor-made',
                'icon' => 'cogs',
                'description' => 'Customized itineraries based on your preferences',
                'display_order' => 6,
            ],
            [
                'name' => 'Multi-country',
                'slug' => 'multi-country',
                'icon' => 'globe',
                'description' => 'Combined tours visiting multiple countries',
                'display_order' => 7,
            ],
        ];
    }

    /**
     * استخراج الرحلات السياحية
     */
    private function scrapeTours()
    {
        $tours = [];
        
        try {
            $response = $this->client->get($this->baseUrl . '/tours');
            $html = $response->body();
            $crawler = new Crawler($html);
            
            // بيانات الرحلات (هذا مثال - تحتاج لتعديل حسب هيكل الموقع الفعلي)
            $toursData = [
                [
                    'name' => 'Classical Egypt Tour - 8 Days',
                    'slug' => 'classical-egypt-tour-8-days',
                    'tour_type_id' => 3, // Multi-day Packages
                    'duration_days' => 8,
                    'duration_nights' => 7,
                    'description' => 'Explore the best of Egypt in this comprehensive 8-day tour...',
                    'highlights' => 'Pyramids of Giza, Egyptian Museum, Karnak Temple, Valley of the Kings',
                    'inclusions' => 'Accommodation, meals, guided tours, entrance fees, transportation',
                    'exclusions' => 'International flights, travel insurance, personal expenses',
                    'main_image' => 'classical-egypt-tour.jpg',
                    'featured' => true,
                    'best_seller' => true,
                    'difficulty' => 'easy',
                    'min_persons' => 2,
                    'max_persons' => 16,
                ],
                [
                    'name' => 'Luxor Day Tour from Hurghada',
                    'slug' => 'luxor-day-tour-from-hurghada',
                    'tour_type_id' => 2, // Day Tours
                    'duration_days' => 1,
                    'duration_nights' => 0,
                    'description' => 'Visit the ancient city of Luxor on a day trip from Hurghada...',
                    'highlights' => 'Karnak Temple, Luxor Temple, Valley of the Kings',
                    'inclusions' => 'Hotel pickup, guide, lunch, entrance fees',
                    'exclusions' => 'Personal expenses, tips',
                    'main_image' => 'luxor-day-tour.jpg',
                    'featured' => true,
                    'best_seller' => true,
                    'difficulty' => 'easy',
                    'min_persons' => 1,
                    'max_persons' => 15,
                ],
                [
                    'name' => 'Nile Cruise 5 Days from Luxor to Aswan',
                    'slug' => 'nile-cruise-5-days-luxor-to-aswan',
                    'tour_type_id' => 1, // Nile Cruise
                    'duration_days' => 5,
                    'duration_nights' => 4,
                    'description' => 'Enjoy a luxurious Nile cruise from Luxor to Aswan...',
                    'highlights' => 'Karnak Temple, Edfu Temple, Kom Ombo, Philae Temple',
                    'inclusions' => 'Cruise accommodation, all meals, guided tours',
                    'exclusions' => 'Drinks, tips, personal expenses',
                    'main_image' => 'nile-cruise-luxor-aswan.jpg',
                    'featured' => true,
                    'best_seller' => true,
                    'difficulty' => 'easy',
                    'min_persons' => 2,
                    'max_persons' => null,
                ],
                [
                    'name' => 'Dahabiya Nile Cruise Experience',
                    'slug' => 'dahabiya-nile-cruise-experience',
                    'tour_type_id' => 4, // Dahabiya Nile Cruise
                    'duration_days' => 7,
                    'duration_nights' => 6,
                    'description' => 'Traditional sailing experience on a Dahabiya boat...',
                    'highlights' => 'Private sailing, small group, authentic experience',
                    'inclusions' => 'Dahabiya accommodation, meals, crew, guided tours',
                    'exclusions' => 'International flights, drinks, tips',
                    'main_image' => 'dahabiya-cruise.jpg',
                    'featured' => true,
                    'best_seller' => false,
                    'special_offer' => true,
                    'difficulty' => 'easy',
                    'min_persons' => 4,
                    'max_persons' => 12,
                ],
            ];
            
            foreach ($toursData as $tour) {
                $tour['gallery'] = json_encode([
                    $tour['main_image'],
                    'gallery1.jpg',
                    'gallery2.jpg',
                    'gallery3.jpg'
                ]);
                $tour['important_notes'] = 'Please bring passport, comfortable shoes, hat, sunscreen';
                $tour['cancellation_policy'] = 'Free cancellation up to 30 days before travel';
                $tour['meta_title'] = $tour['name'] . ' | Book Now';
                $tour['meta_description'] = 'Book ' . $tour['name'] . ' with Luxor and Aswan Tours. Best prices guaranteed.';
                $tour['display_order'] = count($tours) + 1;
                $tour['active'] = true;
                $tours[] = $tour;
            }
            
        } catch (\Exception $e) {
            Log::error('Failed to scrape tours: ' . $e->getMessage());
        }
        
        return $tours;
    }

    /**
     * استخراج الكروزات
     */
    private function scrapeCruises()
    {
        $cruises = [];
        
        try {
            $response = $this->client->get($this->baseUrl . '/nile-cruises');
            $html = $response->body();
            $crawler = new Crawler($html);
            
            $cruisesData = [
                [
                    'name' => 'MS Nile Premium',
                    'slug' => 'ms-nile-premium',
                    'description' => '5-star luxury Nile cruise ship with modern amenities...',
                    'cruise_type' => 'luxury',
                    'stars' => 5,
                    'length' => 72.5,
                    'width' => 14.2,
                    'year_built' => 2018,
                    'year_renovated' => 2022,
                    'cabins_total' => 45,
                    'suites_total' => 8,
                    'main_image' => 'ms-nile-premium.jpg',
                ],
                [
                    'name' => 'MS Royal Princess',
                    'slug' => 'ms-royal-princess',
                    'description' => 'Deluxe Nile cruise with excellent service...',
                    'cruise_type' => 'standard',
                    'stars' => 5,
                    'length' => 68.0,
                    'width' => 13.5,
                    'year_built' => 2016,
                    'year_renovated' => 2021,
                    'cabins_total' => 52,
                    'suites_total' => 4,
                    'main_image' => 'ms-royal-princess.jpg',
                ],
                [
                    'name' => 'Dahabiya Amoura',
                    'slug' => 'dahabiya-amoura',
                    'description' => 'Traditional sailing boat for intimate Nile experience...',
                    'cruise_type' => 'dahabiya',
                    'stars' => 4,
                    'length' => 35.0,
                    'width' => 7.5,
                    'year_built' => 2019,
                    'cabins_total' => 8,
                    'suites_total' => 2,
                    'main_image' => 'dahabiya-amoura.jpg',
                ],
                [
                    'name' => 'MS Sun Ray',
                    'slug' => 'ms-sun-ray',
                    'description' => 'Modern cruise with panoramic windows and spa...',
                    'cruise_type' => 'suite',
                    'stars' => 5,
                    'length' => 75.0,
                    'width' => 15.0,
                    'year_built' => 2020,
                    'cabins_total' => 40,
                    'suites_total' => 10,
                    'main_image' => 'ms-sun-ray.jpg',
                ],
            ];
            
            foreach ($cruisesData as $cruise) {
                $cruise['facilities'] = json_encode([
                    'Swimming Pool',
                    'Restaurant',
                    'Bar & Lounge',
                    'Sun Deck',
                    'Spa & Massage',
                    'Gym',
                    'WiFi',
                    'Air Conditioning'
                ]);
                
                $cruise['amenities'] = json_encode([
                    'Private Bathroom',
                    'TV',
                    'Mini Bar',
                    'Safe',
                    'Hair Dryer',
                    'Complimentary Toiletries'
                ]);
                
                $cruise['gallery'] = json_encode([
                    $cruise['main_image'],
                    'deck1.jpg',
                    'cabin1.jpg',
                    'restaurant.jpg',
                    'pool.jpg'
                ]);
                
                $cruise['deck_plan_image'] = 'deck-plan.jpg';
                $cruise['featured'] = true;
                $cruise['display_order'] = count($cruises) + 1;
                $cruise['active'] = true;
                $cruises[] = $cruise;
            }
            
        } catch (\Exception $e) {
            Log::error('Failed to scrape cruises: ' . $e->getMessage());
        }
        
        return $cruises;
    }

    /**
     * استخراج الفنادق
     */
    private function scrapeHotels()
    {
        $hotels = [];
        
        try {
            $response = $this->client->get($this->baseUrl . '/hotels');
            $html = $response->body();
            
            $hotelsData = [
                [
                    'name' => 'Sofitel Winter Palace Luxor',
                    'slug' => 'sofitel-winter-palace-luxor',
                    'destination_id' => 1, // Luxor
                    'stars' => 5,
                    'hotel_chain' => 'Accor',
                    'description' => 'Historic luxury hotel overlooking the Nile...',
                    'address' => 'Corniche El Nile, Luxor, Egypt',
                    'main_image' => 'winter-palace.jpg',
                ],
                [
                    'name' => 'Mövenpick Resort Aswan',
                    'slug' => 'movenpick-resort-aswan',
                    'destination_id' => 2, // Aswan
                    'stars' => 5,
                    'hotel_chain' => 'Mövenpick',
                    'description' => 'Resort located on Elephantine Island...',
                    'address' => 'Elephantine Island, Aswan, Egypt',
                    'main_image' => 'movenpick-aswan.jpg',
                ],
                [
                    'name' => 'Marriott Mena House Cairo',
                    'slug' => 'marriott-mena-house-cairo',
                    'destination_id' => 3, // Cairo
                    'stars' => 5,
                    'hotel_chain' => 'Marriott',
                    'description' => 'Iconic hotel with pyramid views...',
                    'address' => 'Pyramids Road, Giza, Cairo, Egypt',
                    'main_image' => 'mena-house.jpg',
                ],
            ];
            
            foreach ($hotelsData as $hotel) {
                $hotel['location_coordinates'] = '26.8206,30.8025';
                $hotel['facilities'] = json_encode([
                    'Swimming Pool',
                    'Restaurant',
                    'Spa',
                    'Fitness Center',
                    'WiFi',
                    'Parking',
                    'Room Service',
                    'Business Center'
                ]);
                
                $hotel['room_amenities'] = json_encode([
                    'Air Conditioning',
                    'TV',
                    'Mini Bar',
                    'Safe',
                    'Coffee Maker',
                    'Hair Dryer'
                ]);
                
                $hotel['gallery'] = json_encode([
                    $hotel['main_image'],
                    'room1.jpg',
                    'pool.jpg',
                    'restaurant.jpg'
                ]);
                
                $hotel['active'] = true;
                $hotels[] = $hotel;
            }
            
        } catch (\Exception $e) {
            Log::error('Failed to scrape hotels: ' . $e->getMessage());
        }
        
        return $hotels;
    }

    /**
     * استخراج الجولات الإضافية
     */
    private function scrapeExcursions()
    {
        $excursions = [];
        
        try {
            $response = $this->client->get($this->baseUrl . '/excursions');
            $html = $response->body();
            
            $excursionsData = [
                [
                    'name' => 'Hot Air Balloon Ride over Luxor',
                    'slug' => 'hot-air-balloon-luxor',
                    'destination_id' => 1, // Luxor
                    'duration_hours' => 3.5,
                    'description' => 'Spectacular sunrise balloon ride over Valley of the Kings...',
                    'highlights' => 'Sunrise views, photo opportunities, unique perspective',
                    'main_image' => 'balloon-luxor.jpg',
                ],
                [
                    'name' => 'Abu Simbel by Plane from Aswan',
                    'slug' => 'abu-simbel-plane-aswan',
                    'destination_id' => 2, // Aswan
                    'duration_hours' => 6.0,
                    'description' => 'Visit Abu Simbel temples by plane from Aswan...',
                    'highlights' => 'Flight included, guided tour, comfortable transportation',
                    'main_image' => 'abu-simbel-tour.jpg',
                ],
                [
                    'name' => 'Sound and Light Show at Karnak Temple',
                    'slug' => 'sound-light-karnak',
                    'destination_id' => 1, // Luxor
                    'duration_hours' => 2.0,
                    'description' => 'Evening sound and light show at Karnak Temple...',
                    'highlights' => 'Dramatic narration, lighting effects, evening activity',
                    'main_image' => 'karnak-light-show.jpg',
                ],
            ];
            
            foreach ($excursionsData as $excursion) {
                $excursion['includes'] = 'Transportation, guide, entrance fees';
                $excursion['not_includes'] = 'Personal expenses, tips';
                $excursion['requirements'] = 'Passport for domestic flight, comfortable shoes';
                $excursion['meeting_point'] = 'Hotel lobby';
                $excursion['gallery'] = json_encode([
                    $excursion['main_image'],
                    'gallery1.jpg',
                    'gallery2.jpg'
                ]);
                $excursion['featured'] = true;
                $excursion['active'] = true;
                $excursions[] = $excursion;
            }
            
        } catch (\Exception $e) {
            Log::error('Failed to scrape excursions: ' . $e->getMessage());
        }
        
        return $excursions;
    }

    /**
     * استخراج المواسم
     */
    private function scrapeSeasons()
    {
        return [
            [
                'name' => 'Low Season',
                'type' => 'low',
                'start_date' => '2024-05-01',
                'end_date' => '2024-08-31',
                'multiplier' => 0.85,
                'description' => 'Summer season with hot weather, lower prices',
            ],
            [
                'name' => 'High Season',
                'type' => 'high',
                'start_date' => '2024-09-01',
                'end_date' => '2024-11-30',
                'multiplier' => 1.00,
                'description' => 'Best weather for travel, regular prices',
            ],
            [
                'name' => 'Peak Season',
                'type' => 'peak',
                'start_date' => '2024-12-20',
                'end_date' => '2025-01-10',
                'multiplier' => 1.25,
                'description' => 'Christmas and New Year period, highest prices',
            ],
            [
                'name' => 'High Season Spring',
                'type' => 'high',
                'start_date' => '2025-02-01',
                'end_date' => '2025-04-30',
                'multiplier' => 1.00,
                'description' => 'Spring season, pleasant weather',
            ],
        ];
    }

    /**
     * استخراج الأسعار
     */
    private function scrapePrices()
    {
        $prices = [];
        
        // أسعار للرحلات
        $tourPrices = [
            [
                'priceable_type' => 'tour',
                'priceable_id' => 1, // Classical Egypt Tour
                'season_id' => 2, // High Season
                'price_type' => 'per_person',
                'occupancy_type' => 'double',
                'base_price' => 1200.00,
                'includes_taxes' => true,
            ],
            [
                'priceable_type' => 'tour',
                'priceable_id' => 2, // Luxor Day Tour
                'season_id' => 2,
                'price_type' => 'per_person',
                'occupancy_type' => 'double',
                'base_price' => 150.00,
                'includes_taxes' => true,
            ],
        ];
        
        // أسعار للكروزات
        $cruisePrices = [
            [
                'priceable_type' => 'cruise',
                'priceable_id' => 1, // MS Nile Premium
                'season_id' => 2,
                'price_type' => 'per_person',
                'occupancy_type' => 'double',
                'base_price' => 800.00,
                'includes_taxes' => true,
            ],
        ];
        
        // دمج جميع الأسعار
        $allPrices = array_merge($tourPrices, $cruisePrices);
        
        foreach ($allPrices as $price) {
            $price['currency'] = 'USD';
            $price['valid_from'] = '2024-01-01';
            $price['valid_until'] = '2024-12-31';
            $price['minimum_persons'] = 1;
            $prices[] = $price;
        }
        
        return $prices;
    }

    /**
     * استخراج الصفحات
     */
    private function scrapePages()
    {
        $pages = [];
        
        try {
            $response = $this->client->get($this->baseUrl . '/about');
            $html = $response->body();
            
            $pagesData = [
                [
                    'title' => 'About Us',
                    'slug' => 'about',
                    'content' => '<h1>About Luxor and Aswan Tours</h1><p>We are a leading tour operator specializing in Egypt tourism...</p>',
                    'excerpt' => 'Learn about our company and mission',
                    'page_type' => 'page',
                    'meta_title' => 'About Us - Luxor and Aswan Tours',
                    'meta_description' => 'Learn about Luxor and Aswan Tours, our history and services',
                    'status' => 'published',
                    'published_at' => now(),
                ],
                [
                    'title' => 'Contact Us',
                    'slug' => 'contact',
                    'content' => '<h1>Contact Information</h1><p>Get in touch with our team...</p>',
                    'excerpt' => 'How to contact our team',
                    'page_type' => 'page',
                    'meta_title' => 'Contact Us - Luxor and Aswan Tours',
                    'meta_description' => 'Contact information for Luxor and Aswan Tours',
                    'status' => 'published',
                    'published_at' => now(),
                ],
                [
                    'title' => 'Privacy Policy',
                    'slug' => 'privacy-policy',
                    'content' => '<h1>Privacy Policy</h1><p>Our commitment to your privacy...</p>',
                    'excerpt' => 'Our privacy policy and data protection',
                    'page_type' => 'policy',
                    'meta_title' => 'Privacy Policy - Luxor and Aswan Tours',
                    'meta_description' => 'Privacy policy for Luxor and Aswan Tours',
                    'status' => 'published',
                    'published_at' => now(),
                ],
            ];
            
            foreach ($pagesData as $page) {
                $page['display_order'] = count($pages) + 1;
                $pages[] = $page;
            }
            
        } catch (\Exception $e) {
            Log::error('Failed to scrape pages: ' . $e->getMessage());
        }
        
        return $pages;
    }

    /**
     * استخراج الأسئلة الشائعة
     */
    private function scrapeFaqs()
    {
        $faqs = [];
        
        try {
            $response = $this->client->get($this->baseUrl . '/faq');
            $html = $response->body();
            
            $faqsData = [
                [
                    'question' => 'What is the best time to visit Egypt?',
                    'answer' => 'The best time is from October to April when temperatures are milder.',
                    'category' => 'General',
                    'display_order' => 1,
                    'active' => true,
                ],
                [
                    'question' => 'Do I need a visa for Egypt?',
                    'answer' => 'Yes, most nationalities require a visa which can be obtained on arrival or online.',
                    'category' => 'Travel Requirements',
                    'display_order' => 2,
                    'active' => true,
                ],
                [
                    'question' => 'What should I wear in Egypt?',
                    'answer' => 'Lightweight, comfortable clothing that covers shoulders and knees is recommended.',
                    'category' => 'Preparation',
                    'display_order' => 3,
                    'active' => true,
                ],
            ];
            
            $faqs = $faqsData;
            
        } catch (\Exception $e) {
            Log::error('Failed to scrape FAQs: ' . $e->getMessage());
        }
        
        return $faqs;
    }
}