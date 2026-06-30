@extends ('layouts.default')

@section ('page_title', config('app.name') . ' ' . $newsArticle->title)

@section ('content')

{{-- Article Header --}}
<div class="news-article-header">
    <div class="container">
        <a href="{{ url('/news') }}" class="news-article-back">&larr; All News</a>
        <h1 class="news-article-title">{{ $newsArticle->title }}</h1>
        <div class="news-article-meta">
            <span>{{ date('F j, Y', strtotime($newsArticle->created_at)) }}</span>
            <span class="news-article-meta-sep">&middot;</span>
            <span>{{ $newsArticle->user->username }}</span>
            @if ($newsArticle->tags->count())
                <span class="news-article-meta-sep">&middot;</span>
                @foreach ($newsArticle->tags as $tag)
                    <a href="{{ url('/news/tags') }}/{{ $tag->slug }}" class="news-tag">{{ $tag->tag }}</a>
                @endforeach
            @endif
        </div>
    </div>
</div>

{{-- Article Body --}}
<div class="news-article-section section-padding">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-md-8 col-md-offset-2">
                <div class="news-article-body">
                    {!! $newsArticle->article !!}
                </div>

                <div class="news-article-share">
                    <span class="news-article-share-label">Share:</span>
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ url('/news') }}/{{ $newsArticle->slug }}&t={{ urlencode($newsArticle->title) }}" target="_blank" rel="noopener">
                        <img alt="Share on Facebook" class="news-post-share-button" src="/images/social/facebook.png">
                    </a>
                    <a href="http://twitter.com/share?text={{ urlencode($newsArticle->title) }}&url={{ urlencode(url('/news/' . $newsArticle->slug)) }}&hashtags={{ $newsArticle->getTags(',') }}" target="_blank" rel="noopener">
                        <img alt="Share on Twitter" class="news-post-share-button" src="/images/social/twitter.png">
                    </a>
                </div>
            </div>
        </div>

        {{-- Comments --}}
        <div class="row">
            <div class="col-xs-12 col-md-8 col-md-offset-2">
                <h2 class="section-heading" style="margin-top: 40px;">Comments</h2>

                @forelse ($newsArticle->comments->reverse() as $comment)
                    @if ($comment->approved || (Auth::user() && Auth::user()->getAdmin()) || (Auth::user() && Auth::id() == $comment->user_id))
                        @include ('layouts._partials._news.comment-warnings')
                        <div class="news-comment">
                            <div class="news-comment-avatar">
                                <img class="img-responsive img-rounded" alt="{{ $comment->user->username }}'s Avatar" src="{{ $comment->user->avatar }}"/>
                                <p class="news-comment-author">{{ $comment->user->username }}</p>
                            </div>
                            <div class="news-comment-body">
                                <p class="news-comment-text">{{ $comment->comment }}</p>
                                <div class="news-comment-actions">
                                    <span class="news-comment-date">{{ date('M j, Y', strtotime($comment->created_at)) }}</span>
                                    @if (Auth::user() && Auth::id() == $comment->user_id)
                                        <a href="" onclick="editComment('{{ $comment->comment }}', '{{ $comment->id }}')" data-toggle="modal" data-target="#editCommentModal" class="news-comment-action">Edit</a>
                                    @endif
                                    @if (Auth::user() && (Auth::user()->getAdmin() || $comment->user_id == Auth::id()))
                                        @php $postUrl = Auth::user()->getAdmin() ? '/admin' : ''; @endphp
                                        <a href="{{ $postUrl }}/news/{{ $comment->newsArticle->slug }}/comments/{{ $comment->id }}/delete" class="news-comment-action news-comment-action--danger">Delete</a>
                                    @endif
                                    @if ($comment->approved && $comment->reviewed && Auth::user() && !$comment->reports->pluck('user_id')->contains(Auth::id()) && $comment->user_id != Auth::id())
                                        <a href="/news/{{ $newsArticle->slug }}/comments/{{ $comment->id }}/report" class="news-comment-action">Report</a>
                                    @endif
                                    @if (Auth::user() && $comment->reports->pluck('user_id')->contains(Auth::id()))
                                        <span class="news-comment-reported">You reported this comment</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                @empty
                    <p class="text-muted">No comments yet — be the first!</p>
                @endforelse

                {{-- Post a comment --}}
                <div class="news-comment-form">
                    <h3 class="news-comment-form-title">Post a Comment</h3>
                    @if (Auth::user())
                        {{ Form::open(['url' => '/news/' . $newsArticle->slug . '/comments']) }}
                            <div class="form-group">
                                {{ Form::textarea('comment', '', ['id' => 'comment', 'class' => 'form-control', 'rows' => '4', 'placeholder' => 'Write a comment...']) }}
                            </div>
                            <button type="submit" class="btn btn-orange">Post Comment</button>
                        {{ Form::close() }}
                    @else
                        <p class="text-muted"><a href="{{ url('/login') }}">Log in</a> to post a comment.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Comment Modal -->
<div class="modal fade" id="editCommentModal" tabindex="-1" role="dialog" aria-labelledby="editCommentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="editCommentModalLabel">Edit Comment</h4>
            </div>
            @if (Auth::user())
                {{ Form::open(['url' => '/news/' . $newsArticle->slug . '/comments', 'id' => 'edit_comment_modal_form']) }}
                    <div class="modal-body">
                        <div class="form-group">
                            {{ Form::textarea('comment_modal', '', ['id' => 'comment_modal', 'class' => 'form-control', 'rows' => '4', 'placeholder' => 'Edit your comment']) }}
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-orange">Save</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    </div>
                {{ Form::close() }}
            @else
                <div class="modal-body">
                    <p>Please log in to edit a comment.</p>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
function editComment(comment, comment_id) {
    $("#comment_modal").val(comment);
    $("#edit_comment_modal_form").prop('action', '/news/{{ $newsArticle->slug }}/comments/' + comment_id);
}
</script>
@endpush

@endsection
