<?php

namespace Tests\Feature;

use App\Models\Package;
use App\Models\City;
use App\Services\ExternalTours\ExternalTourImportService;
use App\Services\ExternalTours\LuxorAndAswanTourPageParser;
use App\Services\WebsiteDestinationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ShoreExcursionPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_shore_excursions_have_a_dedicated_listing_and_the_old_filter_redirects_to_it(): void
    {
        $shoreExcursion = Package::create([
            'title' => ['en' => 'Safaga Port VIP Shore Excursion'],
            'slug' => 'safaga-port-vip-shore-excursion',
            'package_type' => 'shore_excursion',
            'duration_days' => 1,
            'is_active' => true,
        ]);
        Package::create([
            'title' => ['en' => 'Cairo Regular Day Tour'],
            'slug' => 'cairo-regular-day-tour',
            'package_type' => 'day_tour',
            'duration_days' => 1,
            'is_active' => true,
        ]);

        $this->get(route('website.shore_excursions.index'))
            ->assertOk()
            ->assertSee('Egypt Shore Excursions')
            ->assertSee('Browse by Cruise Port')
            ->assertSee('Tours from Safaga Port')
            ->assertDontSee('Safaga Port VIP Shore Excursion')
            ->assertDontSee('Filter Results')
            ->assertDontSee('Cairo Regular Day Tour');

        $this->get(route('website.shore_excursions.section', 'safaga'))
            ->assertOk()
            ->assertSee('Tours from Safaga Port')
            ->assertSee('Filter Results')
            ->assertSee('Safaga Port VIP Shore Excursion')
            ->assertSee(route('website.tours.show', $shoreExcursion->slug))
            ->assertDontSee('Cairo Regular Day Tour');

        $this->get(route('website.shore_excursions.index', ['destination' => 'safaga']))
            ->assertRedirect(route('website.shore_excursions.section', 'safaga'));

        $this->get(route('website.tours.all', ['type' => 'shore_excursion']))
            ->assertRedirect(route('website.shore_excursions.index'));
    }

    public function test_all_shore_excursion_sections_are_accessible_and_filterable(): void
    {
        $sections = [
            'safaga' => 'Tours from Safaga Port',
            'alexandria' => 'Tours from Alexandria Port',
            'port-said' => 'Tours from Port Said Port',
            'sharm-el-sheikh' => 'Sharm El Sheikh Shore Excursions',
            'ain-el-sokhna' => 'Tours from Ain El Sokhna Port',
            'accessible' => 'Accessible Shore Excursions',
        ];

        foreach ($sections as $slug => $title) {
            $this->get(route('website.shore_excursions.section', $slug))
                ->assertOk()
                ->assertSee($title)
                ->assertSee('Filter Results')
                ->assertSee('port-nav-bar', false);
        }

        // Test alias support (ain-sokhna -> ain-el-sokhna)
        $this->get(route('website.shore_excursions.section', 'ain-sokhna'))
            ->assertOk()
            ->assertSee('Tours from Ain El Sokhna Port');

        // Test invalid section returns 404
        $this->get(route('website.shore_excursions.section', 'invalid-port-xyz'))
            ->assertNotFound();
    }

    public function test_shore_excursion_uses_day_tour_group_pricing_and_booking_layout(): void
    {
        $package = Package::create([
            'title' => ['en' => 'Safaga Port Group Excursion'],
            'slug' => 'safaga-port-group-excursion',
            'package_type' => 'shore_excursion',
            'duration_days' => 1,
            'price_from' => 320,
            'group_pricing_tiers' => [[
                'title' => 'Small Group',
                'min' => 2,
                'max' => 3,
                'price_per_person' => 390,
            ]],
            'is_active' => true,
        ]);

        $this->get(route('website.tours.show', $package->slug))
            ->assertOk()
            ->assertSee('group-pricing-grid', false)
            ->assertSee('Small Group')
            ->assertSee('id="sidebarBookingForm"', false);
    }

    public function test_ramasside_shore_excursion_content_is_mapped_to_native_trip_fields(): void
    {
        $html = <<<'HTML'
<!doctype html><html><head>
<meta property="og:title" content="VIP Trip To Valley Of Kings From Safaga Port | Ramasside Tours">
<meta property="og:image" content="https://www.ramassidetours.com/uploaded/tours/main.jpg">
</head><body>
<div class="tour-gallery"><a class="glightbox" data-gallery="tour-gallery" href="https://www.ramassidetours.com/uploaded/tours/gallery.jpg"></a></div>
<h1>VIP Trip To Valley Of Kings From Safaga Port</h1>
<span>Approx. 12-14 hours</span><span>Everyday</span>
<div class="content-card"><h3>Tour Highlights</h3><div class="tour-text"><p>Valley of the Kings</p><p>King Tut Tomb</p></div></div>
<div class="content-card"><h3>Tour Overview</h3><div class="tour-text">
<p>Your guide will meet you at Safaga Port and transfer you to Luxor.</p>
<p>Visit the West Bank and return to your cruise ship.</p>
<h3>Tour Includes:</h3><p>Air-conditioned port transfers</p><p>English Egyptologist guide</p>
<h3>Tour Excludes:</h3><p>Tipping</p>
</div></div>
<div class="faq-tour-item"><div class="day-title">Will I return to my ship on time?</div><div class="day-body">Yes, the itinerary is planned around the ship schedule.</div></div>
<script>const tourPricing = {basePrice: 320, groupPlans: [{"single":"460","persons_2_3":"390","persons_4_6":"380","persons_7_10":"370"}]};</script>
</body></html>
HTML;

        $data = app(LuxorAndAswanTourPageParser::class)->parse(
            $html,
            'https://www.ramassidetours.com/vip-trip-to-valley-of-kings-from-safaga-port'
        );

        $this->assertSame('shore_excursion', $data['package_type']);
        $this->assertSame('Approx. 12-14 hours', $data['duration_text']);
        $this->assertSame('Safaga Port', $data['pickup_location']);
        $this->assertSame(['Safaga', 'Luxor'], $data['cities']);
        $this->assertCount(2, $data['highlights']);
        $this->assertCount(2, $data['inclusions']);
        $this->assertCount(1, $data['exclusions']);
        $this->assertSame(320.0, $data['price_from']);
        $this->assertCount(5, $data['group_pricing_tiers']);
        $this->assertCount(2, $data['images']);
        $this->assertCount(1, $data['itinerary']);
    }

    public function test_importer_creates_missing_city_without_exposing_it_in_site_navigation(): void
    {
        $url = 'https://www.ramassidetours.com/safaga-port-test-excursion';
        Http::fake([
            $url => Http::response(<<<'HTML'
<!doctype html><html><head><title>Luxor Shore Excursion From Safaga Port</title></head><body>
<h1>Luxor Shore Excursion From Safaga Port</h1><span>Approx. 12 hours</span><span>Everyday</span>
<div class="content-card"><h3>Tour Overview</h3><div class="tour-text">
<p>Meet at Safaga Port for a guided visit to Luxor before returning to the ship.</p>
<h3>Tour Includes:</h3><p>Port transfers</p><h3>Tour Excludes:</h3><p>Tipping</p>
</div></div>
<script>const tourPricing = {basePrice: 140, groupPlans: [{"single":"200","persons_2_3":"170"}]};</script>
</body></html>
HTML, 200, ['Content-Type' => 'text/html']),
        ]);

        $package = app(ExternalTourImportService::class)->import($url, [
            'rewrite' => false,
            'download_images' => false,
        ])['package'];

        $safaga = City::where('slug', 'safaga')->sole();
        $this->assertTrue($safaga->is_active);
        $this->assertFalse($safaga->is_featured);
        $this->assertSame(9999, $safaga->sort_order);
        $this->assertContains($safaga->id, $package->cities()->pluck('cities.id')->all());
        $this->assertFalse(
            app(WebsiteDestinationService::class)->homeDestinations(777)->contains('title', 'Safaga')
        );
    }
}
