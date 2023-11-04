@extends ('layouts.admin-default')

@section('page_title', 'Settings')

@section('content')

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
                        {{ Form::open(['url' => '/admin/settings/systems', 'onsubmit' => 'return ConfirmSubmit()', 'files' => 'true']) }}
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    {{ Form::checkbox('publicuse', null, $isSystemsMatchMakingPublicuseEnabled, ['id' => 'publicuse']) }}
                                    Public use enabled (show Matchmaking in main Navigation)
                                </div>
                                <div class="mb-3">
                                    {{ Form::label('maxopenperuser', 'Maximal Open matches per user (0 unlimited)', ['id' => '', 'class' => '']) }}
                                    {{ Form::number('maxopenperuser', $maxOpenPerUser, ['id' => 'maxopenperuser', 'class' => 'form-control']) }}
                                </div>

                                <button type="submit" class="btn btn-success btn-block">Submit</button>
                            </div>

                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            @endif
            @if ($isShopEnabled)
            <div class="card mb-3">
                <div class="card-header">
                    <i class="fa fa-wrench fa-fw"></i> Shop System
                </div>
                <div class="card-body">
                    {{ Form::open(array('url'=>'/admin/settings/systems' )) }}
                            <div class="mb-3">
                                {{ Form::label('shop_welcome_message','Welcome Message',array('id'=>'','class'=>'')) }}
                                {{ Form::text('shop_welcome_message', $shopWelcomeMessage, array('id'=>'shop_welcome_message','class'=>'form-control')) }}
                                <small>Displayed at the top of the index page of the shop.</small>
                            </div>
                            <div class="mb-3">
                                {{ Form::label('shop_open','Shop Status',array('id'=>'','class'=>'')) }}
                                {{
                                    Form::select(
                                        'shop_status',
                                        array(
                                            'OPEN'=>'Open',
                                            'CLOSED'=>'Closed'
                                        ),
                                        $shopStatus,
                                        array(
                                            'id'=>'shop_status',
                                            'class'=>'form-control'
                                        )
                                    )
                                }}
                            </div>
                            <div class="mb-3">
                                {{ Form::label('shop_closed_message','Closed Message',array('id'=>'','class'=>'')) }}
                                {{ Form::text('shop_closed_message', $shopClosedMessage, array('id'=>'shop_closed_message','class'=>'form-control')) }}
                                <small>Displayed at the top of the index page when the shop is closed.</small>
                            </div>
                            <button type="submit" class="btn btn-block btn-success">Submit</button>
                    {{ Form::close() }}
                </div>
            </div>
            @endif



        </div>
        <div class="col-12 col-md-6">
            @if ($isCreditEnabled)
                <div class="card mb-3">
                    <div class="card-header">
                        <i class="fa fa-credit-card fa-fw"></i> Credit System
                    </div>
                    <div class="card-body">
                        <h4>Automatic Credit Rewards</h4>
                        <hr>
                        {{ Form::open(['url' => '/admin/settings/systems']) }}
                        <h5>Tournament Credit Allocation Settings</h4>
                            <div class="row">
                                <div class="col-12 col-sm-6">
                                    <div class="mb-3">
                                        {{ Form::label('tournament_participation', 'Participation') }}
                                        {{ Form::number('tournament_participation', $creditAwardTournamentParticipation, ['id' => 'tournament_participation', 'class' => 'form-control']) }}
                                    </div>
                                    <div class="mb-3">
                                        {{ Form::label('tournament_second', 'Second Place') }}
                                        {{ Form::number('tournament_second', $creditAwardTournamentSecond, ['id' => 'tournament_second', 'class' => 'form-control']) }}
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6">
                                    <div class="mb-3">
                                        {{ Form::label('tournament_first', 'First Place') }}
                                        {{ Form::number('tournament_first', $creditAwardTournamentFirst, ['id' => 'tournament_first', 'class' => 'form-control']) }}
                                    </div>
                                    <div class="mb-3">
                                        {{ Form::label('tournament_third', 'Third Place') }}
                                        {{ Form::number('tournament_third', $creditAwardTournamentThird, ['id' => 'tournament_third', 'class' => 'form-control']) }}
                                    </div>
                                </div>
                            </div>
                            <h5>Registration Credit Allocation Settings</h4>
                                <div class="mb-3">
                                    {{ Form::label('registration_event', 'Event') }}
                                    {{ Form::number('registration_event', $creditAwardRegistrationEvent, ['id' => 'registration_event', 'class' => 'form-control']) }}
                                </div>
                                <div class="mb-3">
                                    {{ Form::label('registration_site', 'Site') }}
                                    {{ Form::number('registration_site', $creditAwardRegistrationSite, ['id' => 'registration_site', 'class' => 'form-control']) }}
                                </div>
                                <hr>

                                <button type="submit" class="btn btn-block btn-success">Submit</button>
                                {{ Form::close() }}
                    </div>
                </div>
            @endif
        </div>
    </div>

@endsection
