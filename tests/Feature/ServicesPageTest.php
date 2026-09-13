<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServicesPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_services_page_uses_the_static_page_template_when_no_database_page_exists(): void
    {
        $this->get(route('website.services'))
            ->assertOk()
            ->assertSee('Our Services');
    }
}
