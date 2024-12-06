<?php

namespace App\Http\Controllers;

use Auth;

use App\Models\NewsArticle;
use App\Models\NewsComment;
use App\Models\NewsTag;

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
        return view('news.index')
            ->withNewsArticles(NewsArticle::orderBy('created_at', 'DESC')->paginate(20));
    }

    /**
     * Show News Article Page
     * @param  NewsArticle $newsArticle
     * @return View
     */
    public function show(NewsArticle $newsArticle)
    {
        return view('news.show')
            ->withNewsArticle($newsArticle);
    }

    /**
     * Show News Articles for Given Tag
     * @param  NewsTag $newsTag
     * @return View
     */
    public function showTag(NewsTag $newsTag)
    {
        foreach (NewsTag::where('tag', $newsTag->tag)->get()->reverse() as $newsTag) {
            $newsArticles[] = $newsTag->newsArticle;
        }
        return view('news.tag')
            ->withTag($newsTag->tag)
            ->withNewsArticles($newsArticles);
    }

    /**
     * Store News Article Comment
     * @param  NewsArticle $newsArticle
     * @param  Request $request
     * @return View
     */
    public function storeComment(NewsArticle $newsArticle, Request $request)
    {
        if (!Auth::user()) {
            $request->session()->flash('alert-danger', 'Please Login.');
            return Redirect::to('login');
        }
        $rules = [
            'comment'       => 'required|filled|max:200',
        ];
        $messages = [
            'comment.required'      => 'A Comment is required',
            'comment.filled'        => 'Comment cannot be empty',
            'comment.max'           => 'Comment can only be a max of 200 Characters',
        ];
        $this->validate($request, $rules, $messages);

        $comment = [
            'comment'       => trim($request->comment),
            'news_feed_id'  => $newsArticle->id,
            'user_id'       => Auth::id()
        ];
        if (!NewsComment::create($comment)) {
            $request->session()->flash('alert-danger', 'Cannot Post Comment. Please try again.');
            return Redirect::back();
        }
        $request->session()->flash('alert-success', 'Comment Posted and is waiting for Admin Approval!');
        return Redirect::back();
    }

    /**
     * Report News Article Comment
     * @param  NewsArticle $newsArticle
     * @param  NewsComment $newsComment
     * @return View
     */
    public function reportComment(NewsArticle $newsArticle, NewsComment $newsComment, Request $request)
    {
        if (!Auth::user()) {
            $request->session()->flash('alert-danger', 'Please Login.');
            return Redirect::to('login');
        }
        if (!$newsComment->report()) {
            $request->session()->flash('alert-danger', 'Cannot Report Comment. Please try again.');
            return Redirect::back();
        }
        $request->session()->flash('alert-success', 'Comment Reported and is waiting Admin Review!');
        return Redirect::back();
    }

    /**
     * Report News Article Comment
     * @param  NewsArticle $newsArticle
     * @param  NewsComment $newsComment
     * @return View
     */
    public function destroyComment(NewsArticle $newsArticle, NewsComment $newsComment, Request $request)
    {
        if (!Auth::user()) {
            $request->session()->flash('alert-danger', 'Please Login.');
            return Redirect::to('login');
        }
        if (Auth::id() != $newsComment->user_id) {
            $request->session()->flash('alert-danger', 'This is not your comment to delete!');
            return Redirect::back();
        }
        if (!$newsComment->delete()) {
            $request->session()->flash('alert-danger', 'Cannot Delete Comment. Please try again.');
            return Redirect::back();
        }
        $request->session()->flash('alert-success', 'Comment Deleted!');
        return Redirect::back();
    }

    /**
     * Edit News Article Comment
     * @param  NewsArticle $newsArticle
     * @param  NewsComment $newsComment
     * @return View
     */
    public function editComment(NewsArticle $newsArticle, NewsComment $newsComment, Request $request)
    {
        if (!Auth::user()) {
            $request->session()->flash('alert-danger', 'Please Login.');
            return Redirect::to('login');
        }
        $rules = [
            'comment_modal'         => 'filled|max:200',
        ];
        $messages = [
            'comment_modal.filled'  => 'Comment cannot be empty',
            'comment_modal.max'     => 'Comment can only be a max of 200 Characters',
        ];
        $this->validate($request, $rules, $messages);

        if (Auth::id() != $newsComment->user_id) {
            $request->session()->flash('alert-danger', 'This is not your comment to edit!');
            return Redirect::back();
        }
        if (!$newsComment->edit($request->comment_modal)) {
            $request->session()->flash('alert-danger', 'Cannot Edit Comment. Please try again.');
            return Redirect::back();
        }
        $request->session()->flash('alert-success', 'Comment Edited!');
        return Redirect::back();
    }
}
