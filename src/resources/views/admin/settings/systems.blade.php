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
								<div class="mb-3">
												{{ Form::checkbox('publicuse', null, $isSystemsMatchMakingPublicuseEnabled, array('id'=>'publicuse')) }} Public use enabled (show Matchmaking in main Navigation)
								</div>														
								<div class="mb-3">
												{{ Form::label('maxopenperuser','Maximal Open matches per user (0 unlimited)',array('id'=>'','class'=>'')) }}
												{{ Form::number('maxopenperuser', $maxOpenPerUser, array('id'=>'maxopenperuser', 'class'=>'form-control')) }}
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
					<i class="fa fa-credit-card fa-fw"></i> Automated Credit Awards
				</div>
				<div class="card-body">
					{{ Form::open(array('url'=>'/admin/settings/systems')) }}
						<h4>Tournament Credit Allocation Settings</h4>
						<hr>
						<div class="row">
							<div class="col-12 col-sm-6">
								<div class="mb-3">
									{{ Form::label('tournament_participation','Participation') }}
									{{ Form::number('tournament_participation', $creditAwardTournamentParticipation, array('id'=>'tournament_participation','class'=>'form-control'))}}
								</div>
								<div class="mb-3">
									{{ Form::label('tournament_second','Second Place') }}
									{{ Form::number('tournament_second', $creditAwardTournamentSecond, array('id'=>'tournament_second','class'=>'form-control'))}}
								</div>
							</div>
							<div class="col-12 col-sm-6">
								<div class="mb-3">
									{{ Form::label('tournament_first','First Place') }}
									{{ Form::number('tournament_first', $creditAwardTournamentFirst, array('id'=>'tournament_first','class'=>'form-control'))}}
								</div>
								<div class="mb-3">
									{{ Form::label('tournament_third','Third Place') }}
									{{ Form::number('tournament_third', $creditAwardTournamentThird, array('id'=>'tournament_third','class'=>'form-control'))}}
								</div>
							</div>
						</div>
						<h4>Registration Credit Allocation Settings</h4>
						<hr>
						<div class="mb-3">
							{{ Form::label('registration_event','Event') }}
							{{ Form::number('registration_event', $creditAwardRegistrationEvent, array('id'=>'registration_event','class'=>'form-control'))}}
						</div>
						<div class="mb-3">
							{{ Form::label('registration_site','Site') }}
							{{ Form::number('registration_site', $creditAwardRegistrationSite, array('id'=>'registration_site','class'=>'form-control'))}}
						</div>
						<button type="submit" class="btn btn-block btn-success">Submit</button>
					{{ Form::close() }}
				</div>
			</div>
		@endif
	</div>
</div>

@endsection