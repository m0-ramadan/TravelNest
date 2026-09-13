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
}
