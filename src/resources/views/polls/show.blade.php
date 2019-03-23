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
	</div>
	<div class="row">
		<div class="col-xs-12 col-sm-6 col-md-8">
			<table width="100%" class="table table-striped table-hover">
				@foreach ($poll->options as $option)
					<tr class="table-row odd gradeX">
						<td width="15%">
							@if (Auth::user())
								<a href="/polls/{{ $poll->slug }}/options/{{ $option->id }}/vote">
									<button type="button" class="btn btn-default btn-sm btn-block">Vote</button>
								</a>
							@endif
						</td>
						<td width="30%">{{ $option->name }}</td>
						<td width="5%">{{ $option->getTotalVotes() }}</td>
						<td width="50%">%</td>
					</tr>
				@endforeach
			</table>
		</div>
		<div class="col-xs-12 col-sm-6 col-md-4">
			@if (Auth::user())
				<p>You have voted for...</p>
				@if ($poll->allow_options_user)
					<p>add you own</p>
				@endif
			@else
				<p>Please log in to post a Vote</p>
			@endif
		</div>
	</div>
</div>

@endsection
