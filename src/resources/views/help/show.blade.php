@extends ('layouts.default')

@section ('page_title', Settings::getOrgName() . ' - ' . __('help.help'))

@section ('content')


<div class="container">
	<div class="page-header">
		<h1>{{ $helpCategory->name }}</h1>
		@if ($helpCategory->event)
			<h4>{{ $helpCategory->event->display_name }}</h4>
		@endif
	</div>
	
	<div class="panel-group" id="helpcategory_{{ $helpCategory->slug }}" role="tablist" aria-multiselectable="false">
		@foreach ($helpCategory->entrys as $entry)
			<div class="panel panel-default">
				<div class="panel-heading" role="tab" id="{{ $entry->nice_name }}">
					<h4 class="panel-title" style="display: flex">
						<a class="btn btn-link" type="button" onclick="copyTextToClipBoard('{{url()->full()}}#{{ $entry->nice_name }}')" data-toggle="tooltip" data-placement="top" title="@lang('help.copylink')"><i class="far fa-clipboard"></i></a>
						<a class="btn btn-link" role="button" data-toggle="collapse" class="accordion-toggle accordion-arrow-toggle" data-parent="#helpcategory_{{ $helpCategory->slug }}" href="#collapse_{{ $entry->nice_name }}" aria-expanded="false" aria-controls="collapse_{{ $entry->nice_name }}">
							{{ $entry->display_name }}
						</a>
					</h4>
				</div>
				<div id="collapse_{{ $entry->nice_name }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="{{ $entry->nice_name }}">
					<div class="panel-body">
						{!! $entry->content !!}
					</div>
				</div>
			</div>
		@endforeach

		<script>
			function copyTextToClipBoard(copyText) {
				const el = document.createElement('textarea');
				el.value = copyText;
				el.setAttribute('readonly', '');
				el.style.position = 'absolute';
				el.style.left = '-9999px';
				document.body.appendChild(el);
				el.select();
				document.execCommand('copy');
				document.body.removeChild(el);
			}
		</script>
	</div>
</div>
@endsection