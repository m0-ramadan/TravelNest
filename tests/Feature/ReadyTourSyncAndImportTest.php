<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\City;
use App\Models\Country;
use App\Models\Currency;
use App\Models\Package;
use App\Models\PackageCategory;
use App\Models\SavvyTourTemplate;
use App\Services\ReadyTourImportService;
use App\Services\ReadyTourTaxonomyMapper;
use App\Services\SavvyHostTourTemplateService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ReadyTourSyncAndImportTest extends TestCase
{
    use RefreshDatabase;

    protected Admin $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = Admin::create([
            'name' => 'Test Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);

        // Seed basic currency, country, city & category
        Currency::create([
            'code' => 'USD',
            'symbol' => '$',
            'name' => 'US Dollar',
            'is_default' => true,
        ]);

        $country = Country::create([
            'name' => ['en' => 'Egypt', 'ar' => 'مصر'],
            'slug' => 'egypt',
            'code' => 'EG',
        ]);

        City::create([
            'country_id' => $country->id,
            'name' => ['en' => 'Cairo', 'ar' => 'القاهرة'],
            'slug' => 'cairo',
        ]);

        PackageCategory::create([
            'name' => 'Day Tours',
            'slug' => 'day-tours',
            'category_type' => 'day_tour',
        ]);
    }

    public function test_sync_all_fetches_paginated_templates_from_savvyhost_api()
    {
        config([
            'services.savvyhost.base_url' => 'https://api.savvyhost.net',
            'services.savvyhost.email' => null,
            'services.savvyhost.password' => null,
            'services.savvyhost.token' => 'test-token',
            'services.savvyhost.tenant' => 'test-tenant',
        ]);

        Http::fake([
            'https://api.savvyhost.net/api/v1/dashboard/ai/templates*' => Http::response([
                'success' => true,
                'data' => [
                    'current_page' => 1,
                    'data' => [
                        [
                            'id' => 101,
                            'slug' => 'cairo-pyramids-full-day',
                            'name' => ['en' => 'Cairo Pyramids Full Day', 'ar' => 'جولة الأهرامات بالكامل'],
                            'tour_type' => 'excursion',
                            'category' => 'day-tours',
                            'region' => 'Cairo',
                            'cities' => ['cairo'],
                            'duration_value' => 8,
                            'duration_unit' => 'hours',
                            'suggested_min_price' => 75.00,
                            'price_currency' => 'USD',
                            'is_active' => true,
                        ],
                    ],
                    'first_page_url' => 'https://api.savvyhost.net/api/v1/dashboard/ai/templates?page=1',
                    'from' => 1,
                    'last_page' => 1,
                    'last_page_url' => 'https://api.savvyhost.net/api/v1/dashboard/ai/templates?page=1',
                    'next_page_url' => null,
                    'per_page' => 50,
                    'prev_page_url' => null,
                    'to' => 1,
                    'total' => 1,
                ],
                'meta' => [
                    'total' => 1,
                    'current_page' => 1,
                    'last_page' => 1,
                    'per_page' => 50,
                ],
            ], 200),
        ]);

        $service = app(SavvyHostTourTemplateService::class);
        $result = $service->syncAll('test-process', $this->admin->id);

        $this->assertEquals(1, $result['processed_count']);
        $this->assertDatabaseHas('savvy_tour_templates', [
            'remote_id' => '101',
            'remote_tour_type' => 'excursion',
            'duration_value' => 8,
        ]);
    }

    public function test_sync_does_not_store_pagination_metadata_as_templates()
    {
        config([
            'services.savvyhost.base_url' => 'https://api.savvyhost.net',
            'services.savvyhost.email' => null,
            'services.savvyhost.password' => null,
            'services.savvyhost.token' => 'test-token',
            'services.savvyhost.tenant' => 'test-tenant',
        ]);

        Http::fake([
            'https://api.savvyhost.net/api/v1/dashboard/ai/templates*' => Http::response([
                'success' => true,
                'data' => [
                    'current_page' => 1,
                    'data' => [
                        [
                            'id' => 108,
                            'slug' => 'cairo-fayoum-3day',
                            'name' => ['en' => 'Cairo & Fayoum Oasis 3-Day Escape'],
                            'tour_type' => 'package',
                        ],
                    ],
                    'first_page_url' => 'https://api.savvyhost.net/api/v1/dashboard/ai/templates?page=1',
                    'from' => 1,
                    'last_page' => 1,
                    'last_page_url' => 'https://api.savvyhost.net/api/v1/dashboard/ai/templates?page=1',
                    'next_page_url' => null,
                    'per_page' => 50,
                    'prev_page_url' => null,
                    'to' => 1,
                    'total' => 1,
                ],
            ], 200),
        ]);

        $service = app(SavvyHostTourTemplateService::class);
        $service->syncAll('test-process', $this->admin->id);

        // Ensure pagination URL metadata and pagination keys were NOT inserted as templates
        $this->assertEquals(0, SavvyTourTemplate::where('remote_id', 'like', 'http%')->count());
        $this->assertDatabaseMissing('savvy_tour_templates', [
            'remote_id' => 'https://api.savvyhost.net/api/v1/dashboard/ai/templates?page=1',
        ]);

        // Ensure only valid template #108 was saved
        $this->assertEquals(1, SavvyTourTemplate::count());
        $this->assertDatabaseHas('savvy_tour_templates', [
            'remote_id' => '108',
            'remote_slug' => 'cairo-fayoum-3day',
        ]);
    }

    public function test_real_4_page_pagination_sync()
    {
        config([
            'services.savvyhost.base_url' => 'https://api.savvyhost.net',
            'services.savvyhost.email' => null,
            'services.savvyhost.password' => null,
            'services.savvyhost.token' => 'test-token',
            'services.savvyhost.tenant' => 'test-tenant',
        ]);

        Http::fake(function ($request) {
            $url = $request->url();
            parse_str(parse_url($url, PHP_URL_QUERY) ?? '', $query);
            $page = (int) ($query['page'] ?? 1);

            $count = ($page < 4) ? 50 : 26;
            $items = [];

            for ($i = 1; $i <= $count; $i++) {
                $id = (($page - 1) * 50) + $i;
                $items[] = [
                    'id' => $id,
                    'slug' => "tour-template-{$id}",
                    'name' => ['en' => "Tour Template #{$id}"],
                    'tour_type' => 'package',
                    'category' => 'cultural',
                    'cities' => ['cairo'],
                ];
            }

            return Http::response([
                'success' => true,
                'data' => [
                    'current_page' => $page,
                    'data' => $items,
                    'last_page' => 4,
                    'per_page' => 50,
                    'total' => 176,
                ],
                'meta' => [
                    'total' => 176,
                    'current_page' => $page,
                    'last_page' => 4,
                    'per_page' => 50,
                ],
            ], 200);
        });

        $service = app(SavvyHostTourTemplateService::class);
        $result = $service->syncAll('multi-page-test', $this->admin->id);

        $this->assertEquals(176, $result['api_total']);
        $this->assertEquals(176, $result['processed_count']);
        $this->assertEquals(0, $result['skipped_count']);
        $this->assertEquals(0, $result['error_count']);
        $this->assertEquals(4, $result['last_page']);

        $this->assertEquals(176, SavvyTourTemplate::count());
        $this->assertEquals(0, SavvyTourTemplate::where('remote_id', 'like', 'http%')->count());
    }

    public function test_invalid_payload_skips_non_array_and_incomplete_items()
    {
        config([
            'services.savvyhost.base_url' => 'https://api.savvyhost.net',
            'services.savvyhost.email' => null,
            'services.savvyhost.password' => null,
            'services.savvyhost.token' => 'test-token',
            'services.savvyhost.tenant' => 'test-tenant',
        ]);

        Http::fake([
            'https://api.savvyhost.net/api/v1/dashboard/ai/templates*' => Http::response([
                'success' => true,
                'data' => [
                    'current_page' => 1,
                    'data' => [
                        123, // Scalar item (must be skipped)
                        'string-item', // String item (must be skipped)
                        ['id' => 'https://example.com/url'], // URL ID (must be skipped)
                        ['id' => 999], // Missing slug & name (must be skipped)
                        [
                            'id' => 200,
                            'slug' => 'valid-tour',
                            'name' => ['en' => 'Valid Tour'],
                            'tour_type' => 'excursion',
                        ],
                    ],
                    'last_page' => 1,
                    'total' => 5,
                ],
            ], 200),
        ]);

        $service = app(SavvyHostTourTemplateService::class);
        $result = $service->syncAll('invalid-test', $this->admin->id);

        $this->assertEquals(5, $result['received_count']);
        $this->assertEquals(1, $result['valid_count']);
        $this->assertEquals(4, $result['skipped_count']);
        $this->assertEquals(1, SavvyTourTemplate::count());
        $this->assertDatabaseHas('savvy_tour_templates', ['remote_id' => '200']);
    }

    public function test_repair_command_cleans_corrupted_rows_safely()
    {
        // Seed corrupted rows
        SavvyTourTemplate::create([
            'remote_id' => 'https://api.savvyhost.net/api/v1/dashboard/ai/templates?page=1',
            'remote_slug' => null,
            'name' => null,
            'remote_tour_type' => null,
        ]);

        SavvyTourTemplate::create([
            'remote_id' => '176',
            'remote_slug' => null,
            'name' => null,
            'remote_tour_type' => null,
        ]);

        $this->assertEquals(2, SavvyTourTemplate::count());

        config([
            'services.savvyhost.base_url' => 'https://api.savvyhost.net',
            'services.savvyhost.email' => null,
            'services.savvyhost.password' => null,
            'services.savvyhost.token' => 'test-token',
            'services.savvyhost.tenant' => 'test-tenant',
        ]);

        Http::fake([
            'https://api.savvyhost.net/api/v1/dashboard/ai/templates*' => Http::response([
                'success' => true,
                'data' => [
                    'current_page' => 1,
                    'data' => [
                        [
                            'id' => 108,
                            'slug' => 'clean-tour',
                            'name' => ['en' => 'Clean Tour'],
                            'tour_type' => 'package',
                        ],
                    ],
                    'last_page' => 1,
                    'total' => 1,
                ],
            ], 200),
        ]);

        $this->artisan('savvy:tours:repair')
            ->expectsOutputToContain('Deleted corrupted rows: 2')
            ->expectsOutputToContain('Repair completed successfully')
            ->assertExitCode(0);

        $this->assertEquals(1, SavvyTourTemplate::count());
        $this->assertDatabaseHas('savvy_tour_templates', ['remote_id' => '108']);
    }

    public function test_taxonomy_mapper_converts_excursion_to_day_tour_and_default_private_type()
    {
        $mapper = new ReadyTourTaxonomyMapper();

        $packageType = $mapper->mapPackageType('excursion');
        $tourType = $mapper->mapLocalTourType('excursion');

        $this->assertEquals('day_tour', $packageType);
        $this->assertEquals('private', $tourType);
    }

    public function test_import_template_creates_package_record_with_source_tracking()
    {
        $template = SavvyTourTemplate::create([
            'remote_id' => '202',
            'remote_slug' => 'luxor-day-trip',
            'name' => ['en' => 'Luxor Day Trip', 'ar' => 'رحلة الأقصر اليومية'],
            'remote_tour_type' => 'excursion',
            'remote_category' => 'day-tours',
            'region' => 'Luxor',
            'cities' => ['cairo'],
            'duration_value' => 1,
            'duration_unit' => 'days',
            'suggested_min_price' => 150.00,
            'price_currency' => 'USD',
            'highlights' => ['Pyramids visit', 'Museum tour'],
            'includes' => ['Lunch included', 'Private guide'],
            'excludes' => ['Personal expenses'],
        ]);

        $this->actingAs($this->admin, 'admin');

        $importService = app(ReadyTourImportService::class);
        $result = $importService->importTemplate($template, 'single-test', $this->admin->id);

        $this->assertInArray($result['status'], ['success', 'already_imported']);
        $this->assertNotNull($result['package_id']);

        $package = Package::find($result['package_id']);
        $this->assertNotNull($package);
        $this->assertEquals('savvy_template', $package->source_type);
        $this->assertEquals('202', $package->source_remote_id);
        $this->assertEquals('day_tour', $package->package_type);
        $this->assertEquals('private', $package->tour_type);

        // Check template status updated
        $template->refresh();
        $this->assertInArray($template->import_status, ['imported', 'imported_with_warnings']);
        $this->assertEquals($package->id, $template->imported_package_id);
    }

    public function test_duplicate_import_returns_existing_package_without_duplication()
    {
        $template = SavvyTourTemplate::create([
            'remote_id' => '303',
            'remote_slug' => 'alexandria-day-tour',
            'name' => ['en' => 'Alexandria Day Tour'],
            'remote_tour_type' => 'excursion',
            'cities' => ['cairo'],
            'duration_value' => 6,
            'duration_unit' => 'hours',
            'suggested_min_price' => 90.00,
        ]);

        $this->actingAs($this->admin, 'admin');
        $importService = app(ReadyTourImportService::class);

        // First import
        $res1 = $importService->importTemplate($template, 'test-1', $this->admin->id);
        $packageCount1 = Package::count();

        // Second import (Duplicate attempt)
        $res2 = $importService->importTemplate($template, 'test-2', $this->admin->id);
        $packageCount2 = Package::count();

        $this->assertEquals('already_imported', $res2['status']);
        $this->assertEquals($packageCount1, $packageCount2);
    }

    public function test_admin_ready_tours_index_route_loads_successfully()
    {
        $this->actingAs($this->admin, 'admin');

        $response = $this->get(route('admin.ready-tours.index'));
        $response->assertStatus(200);
        $response->assertSee('Ready Tours');
    }

    protected function assertInArray($needle, array $haystack)
    {
        $this->assertTrue(in_array($needle, $haystack, true));
    }
}
