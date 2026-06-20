<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Event;
use App\Models\EventVenue;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EventsControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_events_index_returns_200()
    {
        $response = $this->get('/events');

        $response->assertStatus(200);
    }

    public function test_events_index_lists_published_events()
    {
        factory(Event::class)->create([
            'display_name' => 'LAN Ops 56',
            'status'       => 'PUBLISHED',
        ]);

        $response = $this->get('/events');

        $response->assertStatus(200);
        $response->assertSee('LAN Ops 56');
    }

    public function test_events_index_hides_draft_events_from_guests()
    {
        Event::withoutGlobalScopes()->create([
            'display_name' => 'Secret Draft',
            'nice_name'    => 'secret-draft',
            'slug'         => 'secret-draft',
            'type'         => 'LAN',
            'status'       => 'DRAFT',
            'start'        => now()->addMonths(2),
            'end'          => now()->addMonths(2)->addDays(2),
        ]);

        $response = $this->get('/events');

        $response->assertStatus(200);
        $response->assertDontSee('Secret Draft');
    }

    public function test_event_show_returns_200()
    {
        // The show view accesses $event->venue->* without null checks, so a venue is required.
        $venue = factory(EventVenue::class)->create();
        $event = factory(Event::class)->create([
            'display_name'   => 'LAN Ops 56',
            'event_venue_id' => $venue->id,
        ]);

        $response = $this->get('/events/' . $event->slug);

        $response->assertStatus(200);
        $response->assertSee('LAN Ops 56');
    }

    public function test_event_show_displays_venue()
    {
        $venue = factory(EventVenue::class)->create(['display_name' => 'City Arena']);
        $event = factory(Event::class)->create(['event_venue_id' => $venue->id]);

        $response = $this->get('/events/' . $event->slug);

        $response->assertStatus(200);
        $response->assertSee('City Arena');
    }

    public function test_event_not_found_returns_404()
    {
        $response = $this->get('/events/this-does-not-exist');

        $response->assertStatus(404);
    }

    public function test_ticket_purchase_requires_auth()
    {
        $event  = factory(Event::class)->create();
        $ticket = factory(\App\Models\EventTicket::class)->create(['event_id' => $event->id]);

        $response = $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class)
            ->post('/tickets/purchase/' . $ticket->id);

        $response->assertRedirect('/login');
    }

    public function test_account_page_requires_auth()
    {
        $response = $this->get('/account');

        $response->assertRedirect('/login');
    }
}
