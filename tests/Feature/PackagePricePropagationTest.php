<?php

namespace Tests\Feature;

use App\Models\Package;
use App\Services\PackagePricingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PackagePricePropagationTest extends TestCase
{
    use RefreshDatabase;

    public function test_general_price_rows_update_travel_package_price_range(): void
    {
        $package = Package::create([
            'title' => ['en' => 'Price propagation tour'],
            'slug' => 'price-propagation-tour',
            'package_type' => 'travel_package',
            'is_active' => true,
        ]);

        $package->prices()->create(['amount' => 850]);
        $package->prices()->create(['amount' => 1250]);

        app(PackagePricingService::class)->recalculate($package);

        $package->refresh();
        $this->assertSame(850.0, (float) $package->start_from_price);
        $this->assertSame(850.0, (float) $package->price_from);
        $this->assertSame(1250.0, (float) $package->price_to);
    }

    public function test_day_tour_with_general_prices_displays_them_when_group_tiers_are_not_configured(): void
    {
        $package = Package::create([
            'title' => ['en' => 'Legacy priced day tour'],
            'slug' => 'legacy-priced-day-tour',
            'package_type' => 'day_tour',
            'is_active' => true,
        ]);

        $package->prices()->create([
            'label' => ['en' => 'Two person private tour'],
            'price_type' => 'per_person',
            'pax_min' => 2,
            'pax_max' => 2,
            'amount' => 175,
        ]);

        app(PackagePricingService::class)->recalculate($package);

        $package->refresh();
        $this->assertSame(175.0, (float) $package->start_from_price);

        $this->get(route('website.tours.show', $package->slug))
            ->assertOk()
            ->assertSee('group-pricing-grid', false)
            ->assertSee('From $145.00');
    }

    public function test_existing_day_tour_price_rows_are_visible_before_the_tour_is_resaved(): void
    {
        $package = Package::create([
            'title' => ['en' => 'Existing day tour'],
            'slug' => 'existing-day-tour',
            'package_type' => 'day_tour',
            'is_active' => true,
        ]);

        $package->prices()->create([
            'label' => ['en' => 'Private tour price'],
            'price_type' => 'per_person',
            'amount' => 210,
        ]);

        $this->get(route('website.tours.show', $package->slug))
            ->assertOk()
            ->assertSee('Private tour price')
            ->assertSee('$210.00');
    }
}
