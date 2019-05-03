<div class="row">
		<div class="col-xs-12 col-sm-6 col-md-9">
			<table width="100%" class="table table-striped table-hover">
				@foreach ($poll->options->reverse() as $option)
					<tr class="table-row odd gradeX">
						@if (!$poll->hasEnded())
							<td width="15%">
								@if (Auth::user() && !$option->hasVoted())
									<a href="/polls/{{ $poll->slug }}/options/{{ $option->id }}/vote">
										<button type="button" class="btn btn-default btn-sm btn-block">Vote</button>
									</a>
								@elseif (Auth::user() && $option->hasVoted())
									<a href="/polls/{{ $poll->slug }}/options/{{ $option->id }}/abstain">
										<button type="button" class="btn btn-default btn-sm btn-block">Remove</button>
									</a>
								@endif
							</td>
						@endif
						<td width="30%">{{ $option->name }}</td>
						<td width="40%">
							<div class="progress-bar" role="progressbar" aria-valuenow="{{ number_format($option->getPercentage(), 2) }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $option->getPercentage() }}%;">
								{{ $option->getTotalVotes() }}
							</div>
						</td>
						<td width="10%">{{ $option->getTotalVotes() }} Votes</td>
					</tr>
				@endforeach
			</table>
		</div>
		<div class="col-xs-12 col-sm-6 col-md-3">
			@if (Auth::user())
				{{ Form::label('name','You have voted for...',array('id'=>'','class'=>'')) }}
				@foreach ($poll->options as $option)
					@if ($option->hasVoted())
						<p>{{ $option->name }}</p>
					@endif
				@endforeach
				@if (!$poll->hasEnded() && $poll->allow_options_user)
					{{ Form::open(array('url'=>'/polls/' . $poll->slug . '/options', 'files' => 'true')) }}
						<div class="form-group">
							{{ Form::label('name','Add Options',array('id'=>'','class'=>'')) }}
							@include ('layouts._partials._polls.add-options')
						</div>
						<button type="submit" class="btn btn-default btn-block">Submit</button> 
					{{ Form::close() }}
				@endif
			@else
				<div class="alert alert-info">
					<p>Please log in to Vote</p>
				</div>
			@endif
		</div>
	</div>