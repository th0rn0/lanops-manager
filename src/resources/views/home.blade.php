@extends ('layouts.default')

@section ('content')

{{-- Hero --}}
<div id="hero-carousel" class="carousel fade hero-section" data-ride="carousel" data-interval="8000">
    <div class="carousel-inner" role="listbox">
        @foreach ($sliderImages as $image)
            <div class="item @if ($loop->first) active @endif">
                <div class="hero-slide" style="background-image: url('{{ $image }}')"></div>
            </div>
        @endforeach
    </div>
    <div class="hero-content">
        @if ($nextEventLan)
            <span class="hero-event-type">Next LAN Event</span>
            <h1 class="hero-event-name">{{ $nextEventLan->display_name }}</h1>
            <p class="hero-event-date">
                {{ date('jS', strtotime($nextEventLan->start)) }} &ndash; {{ date('jS F Y', strtotime($nextEventLan->end)) }}
            </p>
            @if ($nextEventLan->venue)
                <p class="hero-event-venue">{{ $nextEventLan->venue->display_name }}</p>
            @endif
            <a href="/events/{{ $nextEventLan->slug }}#tickets" class="btn btn-orange btn-lg hero-cta">Get Your Ticket</a>
        @elseif ($nextEvent)
            <span class="hero-event-type">Next Event</span>
            <h1 class="hero-event-name">{{ $nextEvent->display_name }}</h1>
            <p class="hero-event-date">
                {{ date('jS', strtotime($nextEvent->start)) }} &ndash; {{ date('jS F Y', strtotime($nextEvent->end)) }}
            </p>
            <a href="/events/{{ $nextEvent->slug }}#tickets" class="btn btn-orange btn-lg hero-cta">Get Your Ticket</a>
        @else
            <span class="hero-event-type">Gaming Events</span>
            <h1 class="hero-event-name">Coming Soon</h1>
            <p class="hero-event-date">Stay tuned for our next event announcement</p>
            <a href="/news" class="btn btn-orange btn-lg hero-cta">Read the News</a>
        @endif
    </div>
</div>

{{-- Upcoming Events --}}
@if ($nextEventLan || $nextEventTabletop)
<div class="featured-events section-padding">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <h2 class="section-heading">Upcoming Events</h2>
            </div>
        </div>
        <div class="row">

            @if ($nextEventLan)
            @php
                $lanCapacity = count($nextEventLan->seatingPlans) > 0
                    ? $nextEventLan->getSeatingCapacity()
                    : $nextEventLan->capacity;
                $lanFilled   = $nextEventLan->eventParticipants->count();
                $lanRemaining = max($lanCapacity - $lanFilled, 0);
                $lanPct      = $lanCapacity > 0 ? round(($lanFilled / $lanCapacity) * 100) : 0;
            @endphp
            <div class="col-xs-12 {{ $nextEventTabletop ? 'col-md-6' : '' }}">
                <div class="event-card">
                    <div class="event-card-header">
                        <span class="event-card-badge">LAN</span>
                        <h3 class="event-card-title">{{ $nextEventLan->display_name }}</h3>
                    </div>
                    <div class="event-card-body">
                        <dl class="event-card-meta">
                            <dt>When</dt>
                            <dd>{{ date('jS', strtotime($nextEventLan->start)) }} &ndash; {{ date('jS F Y', strtotime($nextEventLan->end)) }}</dd>
                            @if ($nextEventLan->venue)
                                <dt>Where</dt>
                                <dd>{{ $nextEventLan->venue->display_name }}</dd>
                            @endif
                            @if ($nextEventLan->tickets && $nextEventLan->tickets->count())
                                <dt>From</dt>
                                <dd>{{ config('app.currency_symbol') }}{{ $nextEventLan->getCheapestTicket() }}</dd>
                            @endif
                        </dl>
                        @if ($nextEventLan->desc_short)
                            <div class="event-card-desc">{!! $nextEventLan->desc_short !!}</div>
                        @endif
                        <div class="capacity-wrap">
                            <div class="capacity-bar">
                                <div class="capacity-fill" style="width: {{ $lanPct }}%"></div>
                            </div>
                            <span class="capacity-text">{{ $lanRemaining }} of {{ $lanCapacity }} tickets remaining</span>
                        </div>
                    </div>
                    <div class="event-card-footer">
                        <a href="/events/{{ $nextEventLan->slug }}" class="btn btn-default btn-sm">Details</a>
                        <a href="/events/{{ $nextEventLan->slug }}#tickets" class="btn btn-orange btn-sm">Book Now</a>
                    </div>
                </div>
            </div>
            @endif

            @if ($nextEventTabletop)
            @php
                $ttCapacity  = count($nextEventTabletop->seatingPlans) > 0
                    ? $nextEventTabletop->getSeatingCapacity()
                    : $nextEventTabletop->capacity;
                $ttFilled    = $nextEventTabletop->eventParticipants->count();
                $ttRemaining = max($ttCapacity - $ttFilled, 0);
                $ttPct       = $ttCapacity > 0 ? round(($ttFilled / $ttCapacity) * 100) : 0;
            @endphp
            <div class="col-xs-12 {{ $nextEventLan ? 'col-md-6' : '' }}">
                <div class="event-card">
                    <div class="event-card-header">
                        <span class="event-card-badge event-card-badge--alt">TABLETOP</span>
                        <h3 class="event-card-title">{{ $nextEventTabletop->display_name }}</h3>
                    </div>
                    <div class="event-card-body">
                        <dl class="event-card-meta">
                            <dt>When</dt>
                            <dd>{{ date('jS', strtotime($nextEventTabletop->start)) }} &ndash; {{ date('jS F Y', strtotime($nextEventTabletop->end)) }}</dd>
                            @if ($nextEventTabletop->venue)
                                <dt>Where</dt>
                                <dd>{{ $nextEventTabletop->venue->display_name }}</dd>
                            @endif
                            @if ($nextEventTabletop->tickets && $nextEventTabletop->tickets->count())
                                <dt>From</dt>
                                <dd>{{ config('app.currency_symbol') }}{{ $nextEventTabletop->getCheapestTicket() }}</dd>
                            @endif
                        </dl>
                        @if ($nextEventTabletop->desc_short)
                            <div class="event-card-desc">{!! $nextEventTabletop->desc_short !!}</div>
                        @endif
                        <div class="capacity-wrap">
                            <div class="capacity-bar">
                                <div class="capacity-fill" style="width: {{ $ttPct }}%"></div>
                            </div>
                            <span class="capacity-text">{{ $ttRemaining }} of {{ $ttCapacity }} tickets remaining</span>
                        </div>
                    </div>
                    <div class="event-card-footer">
                        <a href="/events/{{ $nextEventTabletop->slug }}" class="btn btn-default btn-sm">Details</a>
                        <a href="/events/{{ $nextEventTabletop->slug }}#tickets" class="btn btn-orange btn-sm">Book Now</a>
                    </div>
                </div>
            </div>
            @endif

        </div>
    </div>
</div>
@endif

{{-- About + Event Calendar --}}
<div class="about-calendar-section section-padding">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-md-7">
                <h2 class="section-heading">About {{ config('app.name') }}</h2>
                @include ('layouts._partials._about.short')
                <a href="/about" class="btn btn-default" style="margin-top:10px;">Learn More</a>
            </div>
            <div class="col-xs-12 col-md-5">
                <h2 class="section-heading" style="margin-top:30px;">Event Calendar</h2>
                @php $futureEvents = $events->filter(fn($e) => $e->start > \Carbon\Carbon::today()); @endphp
                @if ($futureEvents->count() > 0)
                    <ul class="cal-list">
                        @foreach ($futureEvents as $calEvent)
                            <li class="cal-item">
                                <a href="/events/{{ $calEvent->slug }}" class="cal-name">
                                    {{ $calEvent->display_name }}
                                    @if ($calEvent->status !== 'PUBLISHED')
                                        <span class="label label-default" style="font-size:10px;">{{ $calEvent->status }}</span>
                                    @endif
                                </a>
                                <span class="cal-date">
                                    {{ date('jS', strtotime($calEvent->start)) }} &ndash; {{ date('jS F Y', strtotime($calEvent->end)) }}
                                </span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted">No upcoming events — check back soon.</p>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Latest News --}}
<div class="news-grid-section section-padding">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <h2 class="section-heading">Latest News</h2>
            </div>
        </div>
        @if (!$newsArticles->isEmpty())
            <div class="row">
                @foreach ($newsArticles as $newsArticle)
                    <div class="col-xs-12 col-sm-6 col-lg-3">
                        <div class="news-card">
                            <div class="news-card-body">
                                <h4 class="news-card-title">
                                    <a href="/news/{{ $newsArticle->slug }}">{{ $newsArticle->title }}</a>
                                </h4>
                                <p class="news-card-excerpt">{{ substr(strip_tags($newsArticle->article), 0, 160) }}...</p>
                            </div>
                            <div class="news-card-foot">
                                <span class="news-card-date">{{ date('M j, Y', strtotime($newsArticle->created_at)) }}</span>
                                <a href="/news/{{ $newsArticle->slug }}" class="news-card-more">Read more &rarr;</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="row">
                <div class="col-xs-12 text-center" style="margin-top:20px;">
                    <a href="/news" class="btn btn-default">All News</a>
                </div>
            </div>
        @else
            <p class="text-muted">Nothing here yet...</p>
        @endif
    </div>
</div>

@endsection
