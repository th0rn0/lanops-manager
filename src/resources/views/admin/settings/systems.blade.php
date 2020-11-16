@extends ('layouts.admin-default')

@section ('page_title', 'Settings')

@section ('content')

<div class="row">
	<div class="col-lg-12">
		<h3 class="pb-2 mt-4 mb-4 border-bottom">Opt Systems</h3>
		<ol class="breadcrumb">
			<li class="breadcrumb-item">
				<a href="/admin/settings">Settings</a>
			</li>
			<li class="breadcrumb-item active">
				Opt Systems
			</li>
		</ol>
	</div>
</div>

@include ('layouts._partials._admin._settings.dashMini', ['active' => 'auth'])

<div class="row">
	<div class="col-12">
		<div class="alert alert-info">
			All the settings are only working and shown if the corresponding System is enabled via the Main settings.
		</div>
	</div>
	<div class="col-12 col-md-6">

		@if ($isMatchMakingEnabled)
			<!-- Matchmaking -->
			<div class="card mb-3">
				<div class="card-header">
					<i class="fa fa-wrench fa-fw"></i> Matchmaking System
				</div>
				<div class="card-body">
					{{ Form::open(array('url'=>'/admin/settings/systems', 'onsubmit' => 'return ConfirmSubmit()', 'files' => 'true')) }}
						<div class="row">
							<div class="col-12 col-md-6">
								<div class="form-group">
									<div class="form-check">
											<label class="form-check-label">
												{{ Form::checkbox('publicuse', null, $isSystemsMatchMakingPublicuseEnabled, array('id'=>'publicuse')) }} Public use enabled (show Matchmaking in main Navigation)
											</label>
									</div>
								</div>								
								<div class="form-group">
									<div class="form-check">
											<label class="form-check-label">
												{{ Form::checkbox('autostart', null, $isSystemsMatchMakingAutostartEnabled, array('id'=>'autostart')) }} Match autostart enabled (Command must be setted in the corresponding game)
											</label>
									</div>
								</div>
								<button type="submit" class="btn btn-success btn-block">Submit</button>
							</div>

						</div>
					{{ Form::close() }}
				</div>
			</div>
		@endif
	</div>
	<div class="col-12 col-md-6">
		@if ($isCreditEnabled)
			<div class="card mb-3">
				<div class="card-header">
					<i class="fa fa-wrench fa-fw"></i> Credit System
				</div>
				<div class="card-body">

				</div>
			</div>
		@endif
	</div>
</div>

@endsection