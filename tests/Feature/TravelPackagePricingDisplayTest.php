<?php

namespace Tests\Feature;

use App\Models\Package;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TravelPackagePricingDisplayTest extends TestCase
{
    use RefreshDatabase;

    public function test_travel_package_hides_group_pricing_cards_and_keeps_accommodation_tiers(): void
    {
        $package = Package::create([
            'title' => ['en' => 'Travel Package Pricing Display'],
            'slug' => 'travel-package-pricing-display',
            'package_type' => 'travel_package',
            'price_from' => 590,
            'group_pricing_tiers' => [[
                'label' => 'Solo Traveler Hidden Tier',
                'min' => 1,
                'max' => 1,
                'price_per_person' => 767,
            ]],
            'is_active' => true,
        ]);

        $accommodation = $package->tourPackageAccommodations()->create([
            'name' => 'Standard Accommodation Visible Tier',
            'is_active' => true,
        ]);
        $season = $accommodation->seasons()->create([
            'package_id' => $package->id,
            'name' => ['en' => 'Winter'],
            'is_active' => true,
        ]);
        $season->items()->create([
            'occupancy_type' => 'double',
            'price' => 923,
            'is_active' => true,
        ]);

        $response = $this->get(route('website.packages.show.simple', $package->slug));

        $response->assertOk();
        $response->assertDontSee('class="group-pricing-grid"', false);
        $response->assertSee('Standard Accommodation Visible Tier');
        $response->assertSee('Accommodation Tiers &amp; Season Pricing', false);
        $response->assertSee('id="sidebarDynamicPrice"', false);
        $response->assertSee('From $1,846.00');
    }
}
