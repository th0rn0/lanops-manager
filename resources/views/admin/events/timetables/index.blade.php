@extends('layouts.admin.default')

@section('page_title', 'Timetables - ' . $event->display_name)

@section('content')

<div class="row">
  <div class="col-lg-12">
    <h1 class="page-header">Timetables</h1>
    <ol class="breadcrumb">
      <li>
        <a href="/admin/events/">Events</a>
      </li>
      <li>
        <a href="/admin/events/{{ $event->id }}">{{ $event->display_name }}</a> 
      </li>
      <li class="active">
        Timetables
      </li>
    </ol>
  </div>
</div>

@include('layouts._partials._admin._event.dashMini')

<div class="row">
  <div class="col-lg-8">
    
    <div class="panel panel-default">
      <div class="panel-heading">
        <i class="fa fa-calendar fa-fw"></i> Timetables
      </div>
      <div class="panel-body">
        <div class="table-responsive">
          <table class="table table-striped table-hover">
            <thead>
              <tr>
                <th>Name</th>
                <th>Status</th>
                <th></th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              @foreach($event->timetables as $timetable)
                <tr>
                  <td>
                    {{ $timetable->name }}
                  </td>
                  <td>
                    {{ $timetable->status }}
                  </td>
                  <td width="15%">
                    <a href="/admin/events/{{ $event->id }}/timetables/{{ $timetable->id }}">
                      <button type="button" class="btn btn-primary btn-sm btn-block">Edit</button>
                    </a>
                  </td>
                  <td width="15%">
                    {{ Form::open(array('url'=>'/admin/events/' . $event->id . '/timetables/' . $timetable->id, 'onsubmit' => 'return ConfirmDelete()')) }}
                      {{ Form::hidden('_method', 'DELETE') }}
                      <button type="submit" class="btn btn-danger btn-sm btn-block">Delete</button>
                    {{ Form::close() }}
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>        
      </div>
    </div>
  
  </div>
  <div class="col-lg-4">

    <div class="panel panel-default">
      <div class="panel-heading">
        <i class="fa fa-plus fa-fw"></i> Add New Timetable
      </div>
      <div class="panel-body">
        {{ Form::open(array('url'=>'/admin/events/' . $event->id . '/timetables')) }}
          <div class="form-group">
            {{ Form::label('timetable_name','Name',array('id'=>'','class'=>'')) }}
            {{ Form::text('name', NULL ,array('id'=>'timetable_name','class'=>'form-control')) }}
          </div> 
          <button type="submit" class="btn btn-default">Submit</button>
        {{ Form::close() }}
      </div>
    </div>
  
  </div>
</div>

@endsection
