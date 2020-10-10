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
	
	<div class="panel-group" >
		@foreach ($helpCategory->entrys as $entry)
			<div class="panel panel-default">
				<div class="panel-heading" >
					<h4 class="panel-title">
						{{ $entry->display_name }}
					</h4>
				</div>
				<div class="panel-body">
					{!! $entry->content !!}
				</div>		
			</div>
			<div class="panel panel-default">
				<div class="panel-heading" >
					<h4 class="panel-title">
						<a class="collapsed clickable"  data-toggle="collapse" data-parent="#accordion" href="#collapse_{{ $entry->id }}">
							{{ $entry->display_name }}
						</a>
					</h4>
				</div>
				<div id="collapse_{{ $entry->id }}" class="panel-collapse collapse">
					<div class="panel-body">
						{!! $entry->content !!}
					</div>
				</div>
			
			</div>
		@endforeach
	</div>

</div>

@endsection