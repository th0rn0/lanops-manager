<?php

namespace App\Http\Controllers;

use Auth;
use Session;

use App\Models\Poll;
use App\Models\PollOption;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;

class PollsController extends Controller
{
    /**
     * Show Polls Index Page
     * @return View
     */
    public function index()
    {
        $activePolls = Poll::where('end', '==', null)
            ->orWhereBetween('end', ['0000-00-00 00:00:00', date("Y-m-d H:i:s")]);
        $endedPolls = Poll::where('end', '!=', null)
            ->orWhereBetween('end', ['0000-00-00 00:00:00', date("Y-m-d H:i:s")])
            ->paginate(10, ['*'], 'page');
        return view("polls.index")
            ->withActivePolls($activePolls)
            ->withEndedPolls($endedPolls);
    }

    /**
     * Show Poll Page
     * @return View
     */
    public function show(Poll $poll)
    {
        $poll->sortOptions();
        return view("polls.show")
            ->withPoll($poll);
    }

    /**
     * Vote on Poll Option
     * @return Redirect
     */
    public function vote(Poll $poll, PollOption $option)
    {
        if ($poll->hasEnded()) {
            Session::flash('alert-danger', 'Poll has ended');
            return Redirect::back();
        }
        if ($option->hasVoted()) {
            Session::flash('alert-danger', 'Cannot Vote Twice!');
            return Redirect::back();
        }
        if (!$poll->allow_options_multi) {
            foreach ($poll->options as $pollOption) {
                if (!$pollOption->votes->where('user_id', Auth::id())->isEmpty()) {
                    Session::flash('alert-danger', 'Cannot Vote Twice!');
                    return Redirect::back();
                }
            }
        }
        if (!$option->vote()) {
            Session::flash('alert-danger', 'Cannot Vote!');
            return Redirect::back();
        }
        Session::flash('alert-success', 'Successfully Voted!');
        return Redirect::back();
    }

    /**
     * Abstain on Poll Option
     * @return Redirect
     */
    public function abstain(Poll $poll, PollOption $option)
    {
        if ($poll->hasEnded()) {
            Session::flash('alert-danger', 'Poll has ended');
            return Redirect::back();
        }
        if (!$option->abstain()) {
            Session::flash('alert-danger', 'Cannot remove Vote!');
            return Redirect::back();
        }
        Session::flash('alert-success', 'Successfully removed Vote!');
        return Redirect::back();
    }

    /**
     * Store option for poll from user
     * @return Redirect
     */
    public function storeOption(Poll $poll, Request $request)
    {
        if ($poll->hasEnded()) {
            Session::flash('alert-danger', 'Poll has ended');
            return Redirect::back();
        }
        if (!$poll->allow_options_user) {
            Session::flash('alert-danger', 'Only Admins can add options to this Poll!');
            return Redirect::back();
        }
        $rules = [
            'options'       => 'filled|array',
        ];
        $messages = [
            'options.filled'        => 'Options cannot be empty',
            'options.array'         => 'Options must be an array',
        ];
        $this->validate($request, $rules, $messages);
        foreach ($request->options as $option) {
            if ($poll->options->where('name', $option)->count() > 0) {
                continue;
            }
            if (!$poll->addOption($option)) {
                Session::flash('alert-danger', 'Cannot create Option!');
                return Redirect::back();
            }
        }
        if (!$poll->addOption($request->name)) {
            Session::flash('alert-danger', 'Cannot create Option!');
            return Redirect::back();
        }
        Session::flash('alert-success', 'Successfully created Option!');
        return Redirect::to('/polls/' . $poll->slug);
    }
}
