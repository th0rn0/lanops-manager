@extends ('layouts.default')

@section ('page_title', config('app.name') . ' News')

@section ('content')

<div class="news-grid-section section-padding">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <h1 class="section-heading">News</h1>
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
                                @if ($newsArticle->tags->count())
                                    <div class="news-card-tags">
                                        @foreach ($newsArticle->tags as $tag)
                                            <a href="{{ url('/news/tags') }}/{{ $tag->slug }}" class="news-tag">{{ $tag->tag }}</a>
                                        @endforeach
                                    </div>
                                @endif
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
                <div class="col-xs-12">
                    {{ $newsArticles->links() }}
                </div>
            </div>
        @else
            <p class="text-muted">Nothing here yet — check back soon.</p>
        @endif
    </div>
</div>

@endsection
