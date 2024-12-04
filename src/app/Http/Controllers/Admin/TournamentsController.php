<?php

namespace App\Http\Controllers\Admin;

use Session;

use App\Models\Tournament;
use App\Models\TournamentTeam;
use App\Models\TournamentParticipant;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;

class TournamentsController extends Controller
{
    /**
     * Show All Tournaments Index Page
     * @return View
     */
    public function index()
    {
        return view('admin.tournaments.index')
            ->withTournaments(Tournament::paginate(20));
    }

    /**
     * Show Tournament Page
     * @param Tournament $tournament
     * @return View
     */
    public function show(Tournament $tournament)
    {
        return view('admin.tournaments.show')
            ->withTournament($tournament);
    }

    /**
     * Add Poll to Database
     * @param  Request $request
     * @return Redirect
     */
    public function store(Request $request)
    {
        $rules = [
            'name'          => 'required',
            'team_size'     => 'required|integer',
        ];
        $messages = [
            'name.required'         => 'Name is required',
            'team_size.required'    => 'Team Size is required',
            'team_size.integer'     => 'Team Size must be a number',
        ];
        if (isset($request->event_id) && $request->event_id != 0) {
            $rules['event_id'] = 'exists:events,id';
            $messages['event_id'] = 'Event does not exist';
        }
        $this->validate($request, $rules, $messages);
        $tournament = new Tournament();
        $tournament->name = $request->name;
        $tournament->team_size = $request->team_size;
        $tournament->event_id = null;
        if (isset($request->event_id) && $request->event_id != 0) {
            $tournament->event_id = $request->event_id;
        }
        if (!$tournament->save()) {
            Session::flash('alert-danger', 'Cannot create Tournament!');
            return Redirect::back();
        }
        Session::flash('alert-success', 'Successfully created Tournament!');
        return Redirect::to('/admin/tournaments/' . $tournament->slug);
    }

    /**
     * Delete Tournament from Database
     * @param  Tournament  $tournament
     * @return Redirect
     */
    public function destroy(Tournament $tournament)
    {
        if ($tournament->isComplete()) {
            Session::flash('alert-danger', 'Cannot delete tournament that has ended!');
            return Redirect::back();
        }

        if (!$tournament->delete()) {
            Session::flash('alert-danger', 'Cannot delete Tournament!');
            return Redirect::back();
        }

        Session::flash('alert-success', 'Successfully deleted Tournament!');
        return Redirect::to('admin/tournaments/');
    }
    
    /**
     * Update Tournament
     * @param  Tournament $tournament
     * @param  Request    $request
     * @return Redirect
     */
    public function update(Tournament $tournament, Request $request)
    {
        $rules = [
            'name'              => 'required',
            'team_size'         => 'required|integer',
            'status'            => 'in:DRAFT,OPEN,CLOSED,LIVE,COMPLETE',
        ];
        $messages = [
            'name.required'         => 'Name is required',
            'team_size.required'    => 'Team Size is required',
            'team_size.integer'     => 'Team Size must be a number',
            'status.in'             => 'Status must be DRAFT, OPEN, CLOSED, LIVE, COMPLETE',
        ];
        if (isset($request->event_id) && $request->event_id != 0) {
            $rules['event_id'] = 'exists:events,id';
            $messages['event_id'] = 'Event does not exist';
        }
        $this->validate($request, $rules, $messages);
        $tournament->name = $request->name;
        $tournament->status = $request->status;
        $tournament->team_size = $request->team_size;
        $tournament->event_id = null;
        if (isset($request->event_id) && $request->event_id != 0) {
            $tournament->event_id = $request->event_id;
        }
        if (!$tournament->save()) {
            Session::flash('alert-danger', 'Cannot update Tournament!');
            return Redirect::back();
        }
        Session::flash('alert-success', 'Successfully updated Tournament!');
        return Redirect::to('/admin/tournaments/' . $tournament->slug);
    }
}
