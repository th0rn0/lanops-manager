@extends ('layouts.default')

@section ('page_title', Settings::getOrgName() . ' ' . $poll->name)

@section ('content')
			
<div class="container">

	<div class="page-header">
		<h1>
			{{ $poll->name }}
			@if ($poll->status != 'PUBLISHED')
				<small> - {{ $poll->status }}</small>
			@endif
		</h1>
		@if (!empty($poll->description))
			<p>{{ $poll->description }}</p>
		@endif
	</div>
	<div class="row">
		<div class="col-xs-12 col-sm-6 col-md-8">
			<table width="100%" class="table table-striped table-hover">
				@foreach ($poll->options as $option)
					<tr class="table-row odd gradeX">
						<td width="15%">
							@if (Auth::user() && !$option->hasVoted())
								<a href="/polls/{{ $poll->slug }}/options/{{ $option->id }}/vote">
									<button type="button" class="btn btn-default btn-sm btn-block">Vote</button>
								</a>
							@elseif (Auth::user() && $option->hasVoted())
								<a href="/polls/{{ $poll->slug }}/options/{{ $option->id }}/abstain">
									<button type="button" class="btn btn-default btn-sm btn-block">Abstain</button>
								</a>
							@endif
						</td>
						<td width="30%">{{ $option->name }}</td>
						<td width="5%">{{ $option->getTotalVotes() }}</td>
						<td width="50%">
							<div class="progress-bar" role="progressbar" aria-valuenow="{{ $option->getPercentage() }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $option->getPercentage() }}%;">
								{{ $option->getPercentage() }}%
							</div>
						</td>
					</tr>
				@endforeach
			</table>
		</div>
		<div class="col-xs-12 col-sm-6 col-md-4">
			@if (Auth::user())
				<h5>You have voted for...</h5>
				@foreach ($poll->options as $option)
					@if ($option->hasVoted())
						<p>{{ $option->name }}</p>
					@endif
				@endforeach
				@if ($poll->allow_options_user)
					{{ Form::open(array('url'=>'/polls/' . $poll->slug . '/options', 'files' => 'true')) }}
						<div class="form-group">
							{{ Form::label('name','Add Option',array('id'=>'','class'=>'')) }}
							{{ Form::text('name', '', array('id'=>'', 'class'=>'form-control')) }}
						</div>
						<button type="submit" class="btn btn-default btn-block">Submit</button> 
					{{ Form::close() }}
				@endif
			@else
				<div class="alert alert-info">
					<p>Please log in to post a Vote</p>
				</div>
			@endif
		</div>
	</div>
</div>

@endsection
