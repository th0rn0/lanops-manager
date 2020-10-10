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
	
	
		@foreach ($helpCategory->entrys as $entry)

					{{ $entry->name }}
					
								{!! $entry->content !!}
			
		@endforeach


</div>

@endsection