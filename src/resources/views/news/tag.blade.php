@extends ('layouts.default')

@section ('page_title', config('app.name') . ' News Tag: ' . $tag)

@section ('content')

<div class="news-grid-section section-padding">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <h1 class="section-heading">Tagged: {{ $tag }}</h1>
                <p style="margin-top: -20px; margin-bottom: 30px;">
                    <a href="{{ url('/news') }}" class="news-card-more">&larr; All News</a>
                </p>
            </div>
        </div>
        @if (!$newsArticles->isEmpty())
            <div class="row">
                @foreach ($newsArticles as $newsArticle)
                    <div class="col-xs-12 col-sm-6 col-md-4">
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
        @else
            <p class="text-muted">No articles tagged "{{ $tag }}" yet.</p>
        @endif
    </div>
</div>

@endsection
