@extends('layouts.default')

@section('page_title', 'Updating your Profile')

@section('content')
      
<div class="container">

  @foreach ($events as $event)

  	<div class="jumbotron">
    	<h2><a href="/events/{{$event->id}}">{{$event->display_name}}</a></h2>
  		<div class="event-information__from-date">
     		<span class="glyphicon glyphicon-calendar" aria-hidden="true"></span> Start Date: {{ $event->start }}				
			</div>
			<div class="event-information__to-date">
  			<span class="glyphicon glyphicon-calendar" aria-hidden="true"></span> End Date: {{ $event->end }}
			</div>
    </div>
  @endforeach
</div>

@endsection
