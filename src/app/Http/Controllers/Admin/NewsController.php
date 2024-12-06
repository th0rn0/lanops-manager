<?php

namespace App\Http\Controllers\Admin;

use Auth;
use Session;

use App\Models\NewsArticle;
use App\Models\NewsComment;
use App\Models\NewsCommentReport;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    /**
     * Show News Index Page
     * @return View
     */
    public function index()
    {
        return view('admin.news.index')
            ->withNewsArticles(NewsArticle::orderBy('created_at', 'DESC')->paginate(10))
            ->withCommentsToApprove(
                NewsComment::where([['approved', '=', false], ['reviewed', '=', false]])
                    ->get()
                    ->reverse()
            )
            ->withCommentsReported(NewsCommentReport::where('reviewed', false)->get()->reverse())
        ;
    }

    /**
     * Show News Article Page
     * @return View
     */
    public function show(NewsArticle $newsArticle)
    {
        return view('admin.news.show')
            ->withNewsArticle($newsArticle)
            ->withComments($newsArticle->comments()->paginate(10, ['*'], 'cm'))
        ;
    }

    /**
     * Add News Articleto Database
     * @param  Request $request
     * @return Redirect
     */
    public function store(Request $request)
    {
        $rules = [
            'title'     => 'required|unique:news_feed,title',
            'article'   => 'required|filled',
            'tags'      => 'required|filled',
        ];
        $messages = [
            'title.required'    => 'Title is required.',
            'article.required'  => 'Article is required.',
            'article.filled'    => 'Article cannot be empty.',
            'tags.required'     => 'You must add Tags.',
            'tags.filled'       => 'You cannont be empty.',
        ];
        $this->validate($request, $rules, $messages);

        $newsArticle = new NewsArticle();
        $newsArticle->title = $request->title;
        $newsArticle->article = $request->article;
        $newsArticle->user_id = Auth::id();
        
        if (!$newsArticle->save()) {
            Session::flash('alert-danger', 'Cannot Save News Article!');
            return Redirect::to('/admin/events/');
        }
        if (!$newsArticle->storeTags(explode(',', $request->tags))) {
            $newsArticle->delete();
            Session::flash('alert-danger', 'Cannot Save News Article!');
            return Redirect::to('/admin/events/');
        }

        Session::flash('alert-success', 'Successfully Saved News Article!');
        return Redirect::to('/admin/news/');
    }

    /**
     * Update News Article
     * @param  NewsArticle $newsArticle
     * @param  Request $request
     * @return Redirect
     */
    public function update(NewsArticle $newsArticle, Request $request)
    {
        $rules = [
            'title'     => 'filled',
            'article'   => 'filled',
            'tags'      => 'filled',
        ];
        $messages = [
            'title.filled'          => 'Title cannot be empty.',
            'article.filled'        => 'Article cannot be empty.',
            'tags.filled'           => 'You must add Tags.',
        ];
        $this->validate($request, $rules, $messages);

        $newsArticle->title     = $request->title;
        $newsArticle->article   = $request->article;

        if (!$newsArticle->storeTags(explode(',', $request->tags)) && !$newsArticle->save()) {
            Session::flash('alert-danger', 'Cannot Update News Article!');
            return Redirect::to('admin/news/' . $newsArticle->slug);
        }

        Session::flash('alert-success', 'Successfully Updated News Article!');
        return Redirect::to('admin/news/' . $newsArticle->slug);
    }

    /**
     * Delete News Article from Database
     * @param  NewsArticle $newsArticle
     * @return Redirect
     */
    public function destroy(NewsArticle $newsArticle)
    {
        if (!$newsArticle->delete()) {
            Session::flash('alert-danger', 'Cannot Delete News Article!');
            return Redirect::back();
        }

        Session::flash('alert-success', 'Successfully Deleted News Article!');
        return Redirect::to('admin/news/');
    }


    /**
     * Delete News Article Comment from Database
     * @param  NewsArticle  $newsArticle
     * @param  NewsComment  $newsComment
     * @return Redirect
     */
    public function destroyComment(NewsArticle $newsArticle, NewsComment $newsComment)
    {
        foreach ($newsComment->reports as $report) {
            if (!$report->delete()) {
                Session::flash('alert-danger', 'Cannot Delete News Article Comment!');
                return Redirect::back();
            }
        }
        if (!$newsComment->delete()) {
            Session::flash('alert-danger', 'Cannot Delete News Article Comment!');
            return Redirect::back();
        }

        Session::flash('alert-success', 'Successfully Deleted News Article Comment!');
        return Redirect::back();
    }


    /**
     * Approve News Article Comment
     * @param  NewsArticle  $newsArticle
     * @param  NewsComment  $newsComment
     * @return Redirect
     */
    public function approveComment(NewsArticle $newsArticle, NewsComment $newsComment, Request $request)
    {
        if (!$newsComment->review(true) || !$newsComment->approve(true)) {
            $newsComment->review(false);
            $newsComment->approve(false);
            Session::flash('alert-danger', 'Cannot Approve News Article Comment!');
            return Redirect::back();
        }

        Session::flash('alert-success', 'Successfully Approved News Article Comment!');
        return Redirect::back();
    }

    /**
     * Reject News Article Comment
     * @param  NewsArticle  $newsArticle
     * @param  NewsComment  $newsComment
     * @return Redirect
     */
    public function rejectComment(NewsArticle $newsArticle, NewsComment $newsComment, Request $request)
    {
        if (!$newsComment->review(true) && !$newsComment->approve(false)) {
            Session::flash('alert-danger', 'Cannot Reject News Article Comment!');
            return Redirect::back();
        }

        Session::flash('alert-success', 'Successfully Rejected News Article Comment!');
        return Redirect::back();
    }

    public function destroyReport(
        NewsArticle $newsArticle,
        NewsComment $newsComment,
        NewsCommentReport $newsCommentReport,
        Request $request
    ) {
        if (!$newsCommentReport->delete()) {
            Session::flash('alert-danger', 'Cannot Ignore Reject News Article Report!');
            return Redirect::back();
        }

        Session::flash('alert-success', 'Successfully Approved News Article Comment!');
        return Redirect::back();
    }
}
