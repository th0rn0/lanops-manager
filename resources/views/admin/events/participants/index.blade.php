@extends('layouts.admin.default')

@section('page_title', 'Participants - ' . $event->display_name . ' | ' . Settings::getOrgName() . ' Admin')

@section('content')

<div class="row">
  <div class="col-lg-12">
    <h1 class="page-header">Participants</h1>
    <ol class="breadcrumb">
      <li>
        <a href="/admin/events/">Events</a>
      </li>
      <li>
        <a href="/admin/events/{{ $event->id }}">{{ $event->display_name }}</a> 
      </li>
      <li class="active">
        Participants
      </li>
    </ol>
  </div>
</div>

@include('layouts._partials._admin._event.dashMini')

<div class="row">
  <div class="col-lg-12">

    <div class="panel panel-default">
      <div class="panel-heading">
        <i class="fa fa-users fa-fw"></i> All Participants
        <a href="/admin/events/{{ $event->id }}/tickets#freebies" class="btn btn-info btn-xs pull-right">Freebies</a>
      </div>
      <div class="panel-body">
        <div class="dataTable_wrapper">
          <table width="100%" class="table table-striped table-hover" id="seating_table">
            <thead>
              <tr>
                <th>Steam Name</th>
                <th>Name</th>
                <th>Seat</th>
                <th>Ticket</th>
                <th>Paypal Email</th>
                <th>Gift</th>
                <th>Free/Staff</th>
                <th>Signed in</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
               @foreach ($event->eventParticipants as $participant)
                <tr class="odd gradeX">
                  <td>{{ $participant->user->steamname }}</td>
                  <td>{{ $participant->user->username }}</td>
                  <td>
                    @if(isset($participant->seat)) {{ $participant->seat->seat }} @endif
                  </td>
                  <td>
                    @if ($participant->ticket) {{ $participant->ticket->name }} @endif
                  </td>
                  <td>
                    @if ($participant->purchase) {{ $participant->purchase->paypal_email }} @endif
                  </td>
                  @if ($participant->gift == 'Y')
                    <td>Yes</td>
                  @else
                    <td>No</td>
                  @endif
                  <td>
                    @if ($participant->free)
                      <strong>Free</strong>
                      <small>Assigned by: {{ $participant->getAssignedByUser()->steamname }}</small>
                    @elseif ($participant->staff)
                      <strong>Staff</strong>
                      <small>Assigned by: {{ $participant->getAssignedByUser()->steamname }}</small>
                    @endif
                  </td>
                  <td>
                    @if ($participant->signed_in)
                      Yes
                    @else
                      No
                    @endif
                  </td>
                  <td width="10%">
                    <a href="/admin/events/{{ $event->id }}/participants/{{ $participant->id }}">
                      <button type="button" class="btn btn-primary btn-sm btn-block">Edit</button>
                    </a>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>
</div>

@endsection