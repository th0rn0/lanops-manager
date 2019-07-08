@extends ('layouts.default')

@section ('page_title', Settings::getOrgName() . ' Polls')

@section ('content')
			
<div class="container">
	<div class="page-header">
		<h1>
			Polls
		</h1> 
	</div>
	@foreach ($activePolls as $poll)
		<div class="panel panel-default">
			<div class="panel-heading">
				<strong><a href="/polls/{{ $poll->slug }}">{{ $poll->name }}</a></strong>
				@if ($poll->status != 'PUBLISHED')
					<small> - {{ $poll->status }}</small>
				@endif
			</div>
			<div class="panel-body">
				@if (!empty($poll->description))
					<p class="bg-info  padding">{{ $poll->description }}</p>
				@endif
				<p class="bg-info  padding">Options: {{ $poll->options->count() }}</p>
				<p class="bg-info  padding">Votes: {{ $poll->getTotalVotes() }}</p>
			</div>
		</div>
	@endforeach
	<hr>
	<div class="page-header">
		<h3>
			Previous Polls
		</h3> 
	</div>
	@foreach ($endedPolls as $poll)
		<div class="panel panel-default">
			<div class="panel-heading">
				<strong><a href="/polls/{{ $poll->slug }}">{{ $poll->name }}</a></strong>
				@if ($poll->status != 'PUBLISHED')
					<small> - {{ $poll->status }}</small>
				@endif
			</div>
			<div class="panel-body">
				@if (!empty($poll->description))
					<p class="bg-info  padding">{{ $poll->description }}</p>
				@endif
				<p class="bg-success  padding">Start: {{ date('H:i d-m-Y', strtotime($poll->start)) }}</p>
				<p class="bg-danger  padding">End: {{ date('H:i d-m-Y', strtotime($poll->end)) }}</p>
				<p class="bg-info  padding">Options: {{ $poll->options->count() }}</p>
				<p class="bg-info  padding">Votes: {{ $poll->getTotalVotes() }}</p>
			</div>
		</div>
	@endforeach
	{{ $endedPolls->links() }}
</div>

@endsection
