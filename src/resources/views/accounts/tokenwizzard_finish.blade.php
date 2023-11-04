@extends ('layouts.default')

@if(isset($application))
	@section ('page_title', __('accounts.tokenwizzard') . " " . __('accounts.tokenwizzardfor') ." ". $application)
@else
	@section ('page_title', __('accounts.tokenwizzard'))
@endif

@section ('content')

<div class="container pt-1">
	<div class="pb-2 mt-4 mb-4 border-bottom">
		<h1>				
			@if(isset($application))
				@lang('accounts.tokenwizzard') @lang('accounts.tokenwizzardfor') {{ $application }}
			@else
				@lang('accounts.tokenwizzard') 
			@endif
		</h1>

	</div>
	<div class="card mb-3">
		<div class="card-header ">
			<h3 class="card-title">
				@if(isset($application))
					@lang('accounts.tokenwizzard') @lang('accounts.tokenwizzardfor') {{ $application }}
				@else
					@lang('accounts.tokenwizzard') 
				@endif					
			</h3>
		</div>
		<div class="card-body">

			@if ($status == 'del_failed')
				<div class="alert alert-danger">
					@lang('accounts.new_token_wizzard_del_failed')
				</div>
			@endif
			@if ($status == 'creation_failed')
			<div class="alert alert-danger">
				@lang('accounts.new_token_wizzard_creation_failed')
			</div>
			@endif
			@if ($status == 'success')
				@if (isset($newtoken))
				<div class="alert alert-success">
					@lang('accounts.new_token_wizzard_success_1') {{ $application }} @lang('accounts.new_token_wizzard_success_2')<br><font size="3"><strong> @lang('accounts.new_token_wizzard_success_3')</strong></font>
				</div>

				<div class="input-group mt-2 mb-3">
					<input class="form-control" id="newtoken" type="text" readonly value="{{ $newtoken }}">
					<span class="input-group-btn">
						<button class="btn btn-primary " type="button" onclick="copyToClipBoard('newtoken')"><i class="fas fa-external-link-alt"></i></button>
					</span>
					@if(isset($callbackurl) && $callbackurl != "")
						<a class="btn btn-primary btn-block " id="connectback" href="{{ $callbackurl }}" role="button">@lang('accounts.new_token_wizzard_connect_back') {{ $application }}</a>
					@endif
				</div>

				@endif
			@endif

		</div>
	</div>
</div>
			

@endsection

<script language="javascript" type="text/javascript">

	function copyToClipBoard(inputId) {
		/* Get the text field */
		var copyText = document.getElementById(inputId);

		/* Select the text field */
		copyText.select();
		copyText.setSelectionRange(0, 99999); /*For mobile devices*/

		/* Copy the text inside the text field */
		document.execCommand("copy");
	}

</script>