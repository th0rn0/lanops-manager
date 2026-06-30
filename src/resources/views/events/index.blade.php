@extends ('layouts.default')

@section ('page_title', config('app.name') . ' - Events')

@section ('content')

<div class="featured-events section-padding">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <h1 class="section-heading">Events</h1>
            </div>
        </div>
        @if ($events->isEmpty())
            <p class="text-muted">No events yet — check back soon.</p>
        @else
            <div class="row">
                @foreach ($events as $event)
                    @php
                        $capacity = count($event->seatingPlans) > 0
                            ? $event->getSeatingCapacity()
                            : $event->capacity;
                        $filled     = $event->eventParticipants->count();
                        $remaining  = max($capacity - $filled, 0);
                        $pct        = $capacity > 0 ? round(($filled / $capacity) * 100) : 0;
                    @endphp
                    <div class="col-xs-12 col-sm-6 col-lg-4">
                        <div class="event-card">
                            <div class="event-card-header">
                                @if ($event->type)
                                    <span class="event-card-badge {{ $event->type === 'TABLETOP' ? 'event-card-badge--alt' : '' }}">
                                        {{ $event->type }}
                                    </span>
                                @endif
                                <h3 class="event-card-title">
                                    <a href="/events/{{ $event->slug }}" style="color: inherit; text-decoration: none;">{{ $event->display_name }}</a>
                                </h3>
                            </div>
                            <div class="event-card-body">
                                <dl class="event-card-meta">
                                    <dt>When</dt>
                                    <dd>{{ date('jS', strtotime($event->start)) }} &ndash; {{ date('jS F Y', strtotime($event->end)) }}</dd>
                                    @if ($event->venue)
                                        @php
                                            $v = $event->venue;
                                            $addr = implode(', ', array_filter([$v->address_1, $v->address_2, $v->address_street, $v->address_city, $v->address_postcode]));
                                            $mapsQuery = urlencode(implode(', ', array_filter([$v->display_name, $addr])));
                                        @endphp
                                        <dt>Where</dt>
                                        <dd>
                                            <a href="https://www.google.com/maps/search/?api=1&query={{ $mapsQuery }}" target="_blank" rel="noopener">
                                                {{ $v->display_name }}@if($addr)<br>{{ $addr }}@endif
                                            </a>
                                        </dd>
                                    @endif
                                    @if ($event->tickets && $event->tickets->count())
                                        <dt>From</dt>
                                        <dd>{{ config('app.currency_symbol') }}{{ $event->getCheapestTicket() }}</dd>
                                    @endif
                                </dl>
                                @if ($event->desc_short)
                                    <div class="event-card-desc">{!! $event->desc_short !!}</div>
                                @endif
                                @if ($capacity > 0)
                                    <div class="capacity-wrap">
                                        <div class="capacity-bar">
                                            <div class="capacity-fill" style="width: {{ $pct }}%"></div>
                                        </div>
                                        <span class="capacity-text">{{ $remaining }} of {{ $capacity }} tickets remaining</span>
                                    </div>
                                @endif
                            </div>
                            <div class="event-card-footer">
                                <a href="/events/{{ $event->slug }}" class="btn btn-default btn-sm">Details</a>
                                @if ($event->tickets && $event->tickets->count())
                                    <a href="/events/{{ $event->slug }}#tickets" class="btn btn-orange btn-sm">Book Now</a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

@endsection
