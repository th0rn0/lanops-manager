@extends('layouts.admin.default')

@section('content')

<div class="row">
  <div class="col-lg-12">
    <h1 class="page-header">Events - {{ $event->display_name }} - Participant - {{ $participant->user->username }}</h1>
    <ol class="breadcrumb">
      <li>
        <a href="/admin/events/">Events</a>
      </li>
      <li>
        <a href="/admin/events/{{ $event->slug }}">{{ $event->display_name }}</a> 
      </li>
      <li>
        <a href="/admin/events/{{ $event->slug }}/participants">Participants</a>
      </li>
      <li class="active">
        {{ $participant->user->steamname }}
      </li>
    </ol>
  </div>
</div>

@include('layouts._partials._admin._event.dashMini')

<div class="row">
  <div class="col-lg-8">

    <div class="panel panel-default">
      <div class="panel-heading">
        Edit Participant
      </div>
      <div class="panel-body">
        @if(!empty($_POST))
          Successfully Posted
        @endif
        <div class="dataTable_wrapper">
          <table width="100%" class="table table-striped table-hover" id="seating_table">
            <thead>
              <tr>
                <th></th>
                <th>Name</th>
                <th>Steam Name</th>
                <th>Seat</th>
                <th>Ticket</th>
                <th>Paypal Email</th>
                <th>Gift</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>
                </td>
                <td>{{ $participant->user->username }}</td>
                <td>{{ $participant->user->steamname }}</td>
                <td>@if($participant->seat) {{ $participant->seat->seat }} @endif</td>
                <td>
                  @if($participant->ticket)
                    {{ $participant->ticket->name }}
                  @else
                    No Ticket Bought
                  @endif
                </td>
                <td>@if($participant->purchase) {{ $participant->purchase->paypal_email }} @endif</td>
                <td>
                  @if ($participant->gift == 'Y')
                    Yes
                  @else
                    No
                  @endif
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>
  <div class="col-lg-4">

    <div class="panel panel-default">
      <div class="panel-heading">
        More Editing
      </div>
      <div class="panel-body">
        purchase
        refund
        ticket
        @if(!$participant->signed_in)
          {{ Form::open(array('url'=>'/admin/events/' . $event->slug . '/participants/' . $participant->id . '/signin')) }}
            <button type="submit" class="btn btn-default">Sign in</button>
          {{ Form::close() }}
        @else
          User is already signed in at present at the event
        @endif
      </div>
    </div>

  </div>
</div>

@endsection