@extends ('layouts.default')

@section ('page_title', Settings::getOrgName() . ' - ' . __('help.help'))

@section ('content')


<div class="container">
	<div class="page-header">
		<h1>{{ $helpCategory->name }}</h1>
		@if ($helpCategory->event)
			<h4>From {{ $helpCategory->event->display_name }}</h4>
		@endif
	</div>
	
	<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
		@foreach ($helpCategory->entrys as $entry)

					<div class="panel panel-default">
						<div class="panel-heading" role="tab" id="headingOne">
							<h4 class="panel-title">
								<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse_{{ $entry->id }}" aria-expanded="true" aria-controls="collapse_{{ $entry->id }}">
									{{ $entry->name }}
								</a>
							</h4>
						</div>
						<div id="collapse_{{ $entry->id }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="collaspe_{{ $entry->id }}">
							<div class="panel-body">
								{!! $entry->content !!}
							</div>
						</div>
					</div>
		@endforeach
	</div>

</div>

@endsection