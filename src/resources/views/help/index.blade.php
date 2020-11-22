@extends ('layouts.default')

@section ('page_title', Settings::getOrgName() . ' - ' . __('help.help'))

@section ('content')

<div class="container">

	<div class="pb-2 mt-4 mb-4 border-bottom">
		<h1>@lang('help.help')</h1>
	</div>
		<div class="input-group mb-3">
			<div class="input-group-prepend">
				<span class="input-group-text" id="search-addon"><i class="fas fa-search"></i></span>
			</div>
			<input id="searchtext" type="text" class="form-control" placeholder="Search.." aria-label="searchtext" aria-describedby="search-addon">
		</div>

		<div class="card-group flex-column" id="helpentries" role="tablist" aria-multiselectable="false">
			@foreach ($helpCategorys as $helpCategory)

				@foreach ($helpCategory->entrys as $entry)
					<div class="card @if(Colors::isBodyDarkMode()) border-light @endif">
						<a role="button" data-toggle="collapse" class="accordion-toggle accordion-arrow-toggle @if(!$loop->first) collapsed @endif" data-parent="#helpentries" href="#collapse_{{ $entry->nice_name }}" aria-expanded="false" aria-controls="collapse_{{ $entry->nice_name }}">
							<div class="card-header @if(Colors::isBodyDarkMode()) border-light @endif " role="tab" id="{{ $entry->nice_name }}">
								<span>
									<h4 class="card-title m-0 d-inline">
										{{ $entry->display_name }}
									</h4>
									<span class="float-right">
										<span class="badge badge-info">{{ $helpCategory->name }}</span>
									</span>
								</span>
							</div>
						</a>
						<div id="collapse_{{ $entry->nice_name }}" class="collapse" role="tabpanel" aria-labelledby="{{ $entry->nice_name }}" data-parent="#helpentries">
							<div class="card-body">
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
					document.addEventListener("DOMContentLoaded", function(event) {
						$("#searchtext").on("keyup", function() {
							var value = $(this).val().toLowerCase();
							$("#helpentries .card").filter(function() {
								$(this).toggle($(this).html().toLowerCase().indexOf(value) > -1)
							});
						});
					});
				</script>
			@endforeach
		</div>
	</div>

@endsection
