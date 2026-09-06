<?php

namespace Tests\Feature;

use App\Models\City;
use App\Models\Country;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DestinationAndCategoryLandingPagesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $country = Country::create([
            'code' => 'EG',
            'name' => ['en' => 'Egypt', 'ar' => 'مصر'],
            'slug' => 'egypt',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        City::create([
            'country_id' => $country->id,
            'name' => ['en' => 'Cairo', 'ar' => 'القاهرة'],
            'slug' => 'cairo',
            'short_description' => ['en' => 'The vibrant historic capital of Egypt.'],
            'description' => ['en' => 'Explore the Great Pyramids, ancient museums, and vibrant bazaars.'],
            'is_active' => true,
            'is_featured' => true,
            'sort_order' => 1,
        ]);
    }

    public function test_cairo_destination_page_shows_large_category_cards_without_nile_cruises_and_no_bottom_filters(): void
    {
        $response = $this->get('/destinations/cairo');

        $response->assertOk();

        // Cairo shows Day Tours and Travel Packages
        $response->assertSee('Day Tours');
        $response->assertSee('Travel Packages');

        // Links point to the designated landing pages
        $response->assertSee(route('website.day_tours.index'));
        $response->assertSee(route('website.travel_packages.index'));

        // Cairo category cards should NOT include Nile Cruises card
        $response->assertDontSee(route('website.nile_cruises.index') . '">Discover Nile Cruises', false);

        // Bottom filter card and search form should NOT be rendered
        $response->assertDontSee('class="filters-card"', false);
        $response->assertDontSee('id="destination-search"', false);
        $response->assertDontSee('id="destination-type"', false);
    }

    public function test_day_tours_landing_page_renders_with_destinations_and_faqs(): void
    {
        $response = $this->get('/day-tours');

        $response->assertOk();
        $response->assertSee('Egypt Excursions and Day Tours');
        $response->assertSee('Cairo Day Tours');
        $response->assertSee('Top Luxor Day Tours');
        $response->assertSee('Aswan Day Tours');
        $response->assertSee('Hurghada Day Tours');
        $response->assertSee('Sharm El Sheikh Day Tours');
        $response->assertSee('Marsa Alam Day Tours');
        $response->assertSee('Dahab Day Tours');
        $response->assertSee('Egypt Day Tours FAQs');

        // Legacy / mirror route
        $this->get('/Egypt/day-tours')->assertOk();
    }

    public function test_travel_packages_landing_page_renders_with_duration_cards_and_faqs(): void
    {
        $response = $this->get('/travel-packages');

        $response->assertOk();
        $response->assertSee('Best Egypt Vacation');
        $response->assertSee('Travel Packages');
        $response->assertSee('2 Days Egypt Vacation');
        $response->assertSee('7 Days Egypt Tour Packages');
        $response->assertSee('10 Days Egypt Long Stay Holidays Tours');
        $response->assertSee('15 Days Egypt Tour Packages');
        $response->assertSee('Luxury Egypt Tour');
        $response->assertSee('Egypt Tours Packages FAQs');

        // Legacy / mirror routes
        $this->get('/Egypt/travel-packages')->assertOk();
        $this->get('/Egypt/travel-pakages')->assertOk();
    }

    public function test_trips_route_only_shows_travel_packages_and_never_day_tours_or_nile_cruises(): void
    {
        $country = Country::first();

        // 1. Travel Package (2 days)
        \App\Models\Package::create([
            'primary_country_id' => $country->id,
            'package_type' => 'travel_package',
            'title' => ['en' => 'Cairo 2-Day Private Vacation'],
            'slug' => 'cairo-2-day-private-vacation',
            'duration_days' => 2,
            'is_active' => true,
        ]);

        // 2. Nile Cruise (4 days)
        \App\Models\Package::create([
            'primary_country_id' => $country->id,
            'package_type' => 'nile_cruise',
            'title' => ['en' => 'Luxor to Aswan Nile Cruise 4 Days'],
            'slug' => 'luxor-aswan-nile-cruise-4-days',
            'duration_days' => 4,
            'is_active' => true,
        ]);

        // 3. Day Tour (1 day)
        \App\Models\Package::create([
            'primary_country_id' => $country->id,
            'package_type' => 'day_tour',
            'title' => ['en' => 'Giza Pyramids Classic Day Tour'],
            'slug' => 'giza-pyramids-classic-day-tour',
            'duration_days' => 1,
            'is_active' => true,
        ]);

        // Test general /trips
        $response = $this->get('/trips');
        $response->assertOk();
        $response->assertSee('Cairo 2-Day Private Vacation');
        $response->assertDontSee('Luxor to Aswan Nile Cruise 4 Days');
        $response->assertDontSee('Giza Pyramids Classic Day Tour');

        // Test /trips?duration=2
        $durationResponse = $this->get('/trips?duration=2');
        $durationResponse->assertOk();
        $durationResponse->assertSee('Cairo 2-Day Private Vacation');
        $durationResponse->assertDontSee('Luxor to Aswan Nile Cruise 4 Days');
        $durationResponse->assertDontSee('Giza Pyramids Classic Day Tour');

        // Test that Nile cruise is NOT returned even if duration matches 4
        $nileDurationResponse = $this->get('/trips?duration=4');
        $nileDurationResponse->assertOk();
        $nileDurationResponse->assertDontSee('Luxor to Aswan Nile Cruise 4 Days');
    }
}
