<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Event;
use App\Models\EventVenue;
use App\Models\NewsArticle;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HomeControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_returns_200()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_home_page_loads_without_events()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Coming Soon');
    }

    public function test_home_page_shows_upcoming_lan_event()
    {
        $event = factory(Event::class)->create([
            'display_name' => 'LAN Ops 56',
            'type'         => Event::$typeLan,
            'status'       => 'PUBLISHED',
            'start'        => now()->addMonths(2),
            'end'          => now()->addMonths(2)->addDays(2),
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('LAN Ops 56');
        $response->assertSee('Next LAN');
    }

    public function test_home_page_shows_upcoming_tabletop_event()
    {
        $event = factory(Event::class)->create([
            'display_name' => 'Tabletop Night',
            'type'         => Event::$typeTabletop,
            'status'       => 'PUBLISHED',
            'start'        => now()->addMonths(1),
            'end'          => now()->addMonths(1)->addDays(1),
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Tabletop Night');
        $response->assertSee('Next Tabletop');
    }

    public function test_home_page_shows_venue_name_when_set()
    {
        $venue = factory(EventVenue::class)->create(['display_name' => 'The Warehouse']);
        factory(Event::class)->create([
            'type'           => Event::$typeLan,
            'status'         => 'PUBLISHED',
            'event_venue_id' => $venue->id,
            'start'          => now()->addMonths(2),
            'end'            => now()->addMonths(2)->addDays(2),
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('The Warehouse');
    }

    public function test_home_page_shows_venue_full_address_when_set()
    {
        $venue = factory(EventVenue::class)->create([
            'display_name'   => 'The Warehouse',
            'address_city'   => 'Leeds',
            'address_postcode' => 'LS1 1AA',
        ]);
        factory(Event::class)->create([
            'type'           => Event::$typeLan,
            'status'         => 'PUBLISHED',
            'event_venue_id' => $venue->id,
            'start'          => now()->addMonths(2),
            'end'            => now()->addMonths(2)->addDays(2),
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Leeds');
        $response->assertSee('LS1 1AA');
    }

    public function test_home_page_shows_countdown_data_attribute()
    {
        factory(Event::class)->create([
            'type'   => Event::$typeLan,
            'status' => 'PUBLISHED',
            'start'  => now()->addMonths(2),
            'end'    => now()->addMonths(2)->addDays(2),
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('hero-countdown');
        $response->assertSee('data-countdown');
    }

    public function test_home_page_does_not_show_draft_events()
    {
        Event::withoutGlobalScopes()->create([
            'display_name' => 'Secret Event',
            'nice_name'    => 'secret-event',
            'slug'         => 'secret-event',
            'type'         => Event::$typeLan,
            'status'       => 'DRAFT',
            'start'        => now()->addMonths(2),
            'end'          => now()->addMonths(2)->addDays(2),
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertDontSee('Secret Event');
    }

    public function test_home_page_shows_latest_news()
    {
        factory(NewsArticle::class)->create(['title' => 'Big Announcement']);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Big Announcement');
    }

    public function test_about_page_returns_200()
    {
        $response = $this->get('/about');

        $response->assertStatus(200);
    }

    public function test_contact_page_returns_200()
    {
        $response = $this->get('/contact');

        $response->assertStatus(200);
    }

    public function test_terms_page_returns_200()
    {
        $response = $this->get('/terms');

        $response->assertStatus(200);
    }
}
