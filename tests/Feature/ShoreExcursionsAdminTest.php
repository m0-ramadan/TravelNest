<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Booking;
use App\Models\City;
use App\Models\Country;
use App\Models\Currency;
use App\Models\Package;
use App\Models\PackageCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShoreExcursionsAdminTest extends TestCase
{
    use RefreshDatabase;

    protected Admin $admin;
    protected Country $country;
    protected City $city;
    protected Currency $currency;
    protected PackageCategory $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = Admin::create([
            'name' => 'Shore Admin',
            'email' => 'shore_admin@example.com',
            'password' => bcrypt('password'),
        ]);

        $this->country = Country::create([
            'name' => ['en' => 'Egypt', 'ar' => 'مصر'],
            'slug' => 'egypt',
            'code' => 'EG',
        ]);

        $this->city = City::create([
            'country_id' => $this->country->id,
            'name' => ['en' => 'Alexandria', 'ar' => 'الإسكندرية'],
            'slug' => 'alexandria',
        ]);

        $this->currency = Currency::create([
            'code' => 'USD',
            'symbol' => '$',
            'name' => 'US Dollar',
            'is_default' => true,
        ]);

        $this->category = PackageCategory::create([
            'name' => ['en' => 'Shore Excursions', 'ar' => 'رحلات شاطئية'],
            'slug' => 'shore-excursions',
            'category_type' => 'shore_excursion',
            'is_active' => true,
        ]);
    }

    public function test_admin_can_access_shore_excursions_index(): void
    {
        $shore = Package::create([
            'slug' => 'alexandria-port-tour',
            'title' => ['en' => 'Alexandria Port Day Tour', 'ar' => 'جولة ميناء الإسكندرية'],
            'package_type' => 'shore_excursion',
            'shore_section' => 'alexandria',
            'pickup_location' => ['en' => 'Alexandria Port Gate 3', 'ar' => 'بوابة 3 ميناء الإسكندرية'],
            'price_from' => 85,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.shore-excursions.index'));

        $response->assertOk()
            ->assertSee('Shore Excursions')
            ->assertSee('Add Shore Excursion')
            ->assertSee('Alexandria Port Day Tour')
            ->assertSee('Alexandria Port Gate 3')
            ->assertSee('Alexandria Port');
    }

    public function test_generic_packages_index_excludes_shore_excursions_by_default(): void
    {
        $genericPackage = Package::create([
            'slug' => 'cairo-classic-tour',
            'title' => ['en' => 'Cairo Classic Tour', 'ar' => 'جولة القاهرة الكلاسيكية'],
            'package_type' => 'day_tour',
            'price_from' => 50,
            'is_active' => true,
        ]);

        $shorePackage = Package::create([
            'slug' => 'safaga-luxor-shore',
            'title' => ['en' => 'Safaga to Luxor Shore Excursion', 'ar' => 'رحلة شاطئية من سفاجا إلى الأقصر'],
            'package_type' => 'shore_excursion',
            'shore_section' => 'safaga',
            'price_from' => 120,
            'is_active' => true,
        ]);

        // Generic index without filter
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.packages.index'));

        $response->assertOk()
            ->assertSee('Cairo Classic Tour')
            ->assertDontSee('Safaga to Luxor Shore Excursion');

        // Filtered explicitly with package_type=shore_excursion
        $filteredResponse = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.packages.index', ['package_type' => 'shore_excursion']));

        $filteredResponse->assertOk()
            ->assertSee('Safaga to Luxor Shore Excursion')
            ->assertDontSee('Cairo Classic Tour');
    }

    public function test_admin_can_access_tailored_create_form(): void
    {
        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.shore-excursions.create'));

        $response->assertOk()
            ->assertSee('Add New Shore Excursion')
            ->assertSee('Main Port / Section')
            ->assertSee('Port Meeting Point / Pickup Location')
            ->assertSee('Group Pricing Tiers')
            ->assertSee("What's Included", false)
            ->assertSee('Optional Add-ons');
    }

    public function test_admin_can_store_shore_excursion(): void
    {
        $payload = [
            'title' => [
                'en' => 'Port Said to Cairo Day Tour',
                'ar' => 'رحلة اليوم الواحد من بورسعيد للقاهرة',
            ],
            'slug' => 'port-said-to-cairo-tour',
            'port_section' => 'port-said',
            'country_id' => $this->country->id,
            'city_id' => $this->city->id,
            'currency_id' => $this->currency->id,
            'duration_days' => 1,
            'duration_nights' => 0,
            'price_1_person' => 190,
            'price_2_persons' => 130,
            'pickup_location' => [
                'en' => 'Port Said Cruise Terminal Dock 1',
                'ar' => 'محطة ركاب ميناء بورسعيد رصيف 1',
            ],
            'operating_days' => [0, 2, 4],
            'inclusions' => [
                'Air-conditioned port transport',
            ],
            'exclusions' => [
                'Gratuities',
            ],
            'addons' => [
                [
                    'title' => 'Buffet Lunch by the Nile',
                    'price' => 25,
                    'price_unit' => 'per_person',
                ],
            ],
            'is_active' => 1,
            'is_featured' => 1,
        ];

        $response = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.shore-excursions.store'), $payload);

        $response->assertRedirect(route('admin.shore-excursions.index'));

        $package = Package::query()->where('slug', 'port-said-to-cairo-tour')->firstOrFail();
        $this->assertSame('shore_excursion', $package->package_type);
        $this->assertStringContainsString('Port Said', $package->getTranslation('destinations_text', 'en'));
        $this->assertSame(94.0, (float) $package->price_from);
        $this->assertSame('Port Said Cruise Terminal Dock 1', $package->getTranslation('pickup_location', 'en'));
        $this->assertSame('محطة ركاب ميناء بورسعيد رصيف 1', $package->getTranslation('pickup_location', 'ar'));
        $this->assertSame([0, 2, 4], $package->operating_days);
        $this->assertCount(1, $package->addons);
        $this->assertSame('Buffet Lunch by the Nile', (string) $package->addons->first()->title);
    }

    public function test_admin_can_edit_and_update_shore_excursion(): void
    {
        $package = Package::create([
            'slug' => 'ain-sokhna-pyramids',
            'title' => ['en' => 'Ain Sokhna to Pyramids', 'ar' => 'من العين السخنة للأهرامات'],
            'package_type' => 'shore_excursion',
            'destinations_text' => ['en' => 'Ain Sokhna Port', 'ar' => 'ميناء العين السخنة'],
            'price_from' => 95,
            'is_active' => true,
        ]);

        $editResponse = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.shore-excursions.edit', $package));

        $editResponse->assertOk()
            ->assertSee('Edit Shore Excursion')
            ->assertSee('Ain Sokhna to Pyramids')
            ->assertSee(route('admin.shore-excursions.package-bookings', $package));

        $updateResponse = $this->actingAs($this->admin, 'admin')
            ->put(route('admin.shore-excursions.update', $package), [
                'title' => [
                    'en' => 'Ain Sokhna to Pyramids VIP',
                    'ar' => 'من العين السخنة للأهرامات VIP',
                ],
                'slug' => 'ain-sokhna-pyramids',
                'port_section' => 'ain-el-sokhna',
                'price_1_person' => 125,
                'is_active' => 1,
            ]);

        $updateResponse->assertRedirect(route('admin.shore-excursions.index'));

        $package->refresh();
        $this->assertSame('Ain Sokhna to Pyramids VIP', $package->getTranslation('title', 'en'));
        $this->assertSame(72.0, (float) $package->price_from);
    }

    public function test_shore_excursion_shortcuts_redirect_to_filtered_bookings(): void
    {
        $package = Package::create([
            'slug' => 'sharm-shore-trip',
            'title' => ['en' => 'Sharm El Sheikh Shore Trip', 'ar' => 'رحلة شرم الشيخ الشاطئية'],
            'package_type' => 'shore_excursion',
            'destinations_text' => ['en' => 'Sharm El Sheikh', 'ar' => 'شرم الشيخ'],
            'price_from' => 75,
            'is_active' => true,
        ]);

        // All shore excursion bookings shortcut
        $allBookingsResponse = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.shore-excursions.bookings'));

        $allBookingsResponse->assertRedirect(route('admin.bookings.index', ['package_type' => 'shore_excursion']));

        // Trip-specific bookings shortcut
        $tripBookingsResponse = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.shore-excursions.package-bookings', $package));

        $tripBookingsResponse->assertRedirect(route('admin.bookings.index', ['package_id' => $package->id]));
    }

    public function test_admin_can_toggle_status_and_duplicate_shore_excursion(): void
    {
        $package = Package::create([
            'slug' => 'toggle-test-shore',
            'title' => ['en' => 'Toggle Test Shore', 'ar' => 'اختبار التبديل'],
            'package_type' => 'shore_excursion',
            'destinations_text' => ['en' => 'Alexandria Port', 'ar' => 'ميناء الإسكندرية'],
            'price_from' => 60,
            'is_active' => true,
        ]);

        // Toggle status
        $this->actingAs($this->admin, 'admin')
            ->post(route('admin.shore-excursions.toggle-status', $package));

        $this->assertFalse((bool) $package->fresh()->is_active);

        // Duplicate
        $duplicateResponse = $this->actingAs($this->admin, 'admin')
            ->post(route('admin.shore-excursions.duplicate', $package));

        $duplicated = Package::query()->where('slug', 'like', 'toggle-test-shore-copy%')->firstOrFail();
        $duplicateResponse->assertRedirect(route('admin.shore-excursions.edit', $duplicated->id));
        $this->assertSame('shore_excursion', $duplicated->package_type);
        $this->assertStringContainsString('Alexandria', $duplicated->getTranslation('destinations_text', 'en'));
    }

    public function test_admin_can_remove_specific_gallery_image_on_update(): void
    {
        $package = Package::create([
            'slug' => 'gallery-delete-test',
            'title' => ['en' => 'Gallery Delete Test', 'ar' => 'اختبار حذف المعرض'],
            'package_type' => 'shore_excursion',
            'gallery_images' => [
                'storage/packages/gallery/photo1.jpg',
                'storage/packages/gallery/photo2.jpg',
                'storage/packages/gallery/photo3.jpg',
            ],
            'price_from' => 100,
            'is_active' => true,
        ]);

        // Submit form keeping only photo1.jpg and photo3.jpg (removing photo2.jpg)
        $response = $this->actingAs($this->admin, 'admin')
            ->put(route('admin.shore-excursions.update', $package), [
                'title' => ['en' => 'Gallery Delete Test', 'ar' => 'اختبار حذف المعرض'],
                'slug' => 'gallery-delete-test',
                'port_section' => 'alexandria',
                'gallery_section_present' => '1',
                'existing_gallery' => [
                    'storage/packages/gallery/photo1.jpg',
                    'storage/packages/gallery/photo3.jpg',
                ],
                'price_1_person' => 100,
            ]);

        $response->assertRedirect(route('admin.shore-excursions.index'));

        $package->refresh();
        $this->assertSame([
            'storage/packages/gallery/photo1.jpg',
            'storage/packages/gallery/photo3.jpg',
        ], $package->gallery_images);
    }
}
