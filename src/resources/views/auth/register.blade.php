@extends ('layouts.default')

@section ('page_title', 'Register')

@section ('content')

<div class="container">
    <div class="page-header">
        <h1>Register Details</h1>
    </div>
    <div class="row">
        {{ Form::open(array('url'=>'/register/' . $loginMethod )) }}
            {{ csrf_field() }}
            {{ Form::hidden('method', $loginMethod, array('id'=>'method','class'=>'form-control')) }}
            @if ($loginMethod == "steam")
                {{ Form::hidden('avatar', $avatar, array('id'=>'avatar','class'=>'form-control')) }}
                {{ Form::hidden('steamid', $steamid, array('id'=>'steamid','class'=>'form-control')) }}
                {{ Form::hidden('steamname', $steamname, array('id'=>'steamname','class'=>'form-control')) }}
            @endif
            <div class="col-xs-12 col-md-6">
                <div class="row">
                    <div class="col-xs-12 col-md-6">
                        <div class="form-group @error('firstname') has-error @enderror">
                            {{ Form::label('firstname','Firstname',array('id'=>'','class'=>'')) }}
                            <input id="firstname" type="firstname" class="form-control" name="firstname" value="{{ old('firstname') }}" required autocomplete="firstname">
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6">
                        <div class="form-group  @error('surname') has-error @enderror">
                            {{ Form::label('surname','Surname',array('id'=>'','class'=>'')) }}
                            <input id="surname" type="surname" class="form-control" name="surname" value="{{ old('surname') }}" required autocomplete="surname">
                        </div>
                    </div>
                </div>
                <div class="form-group @error('username') has-error @enderror">
                    {{ Form::label('username','Username',array('id'=>'','class'=>'')) }}
                    <input id="username" type="username" class="form-control" name="username" value="{{ old('username') }}" required autocomplete="username">
                </div>
                @if ($loginMethod == "standard")
                    <div class="form-group @error('email') has-error @enderror">
                        {{ Form::label('email','E-Mail',array('id'=>'','class'=>'')) }}
                        <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autocomplete="email">
                    </div>
                    <div class="form-group @error('password1') has-error @enderror">
                        {{ Form::label('password1','Password',array('id'=>'','class'=>'')) }}
                         <input id="password1" type="password" class="form-control" name="password1" required autocomplete="new-password">
                    </div>
                    <div class="form-group @error('password2') has-error @enderror">
                        {{ Form::label('password2','Confirm Password',array('id'=>'','class'=>'')) }}
                        <input id="password2" type="password" class="form-control" name="password2" required autocomplete="new-password">
                    </div>
                    <input id="url" type="hidden" class="form-control" name="url">

                @endif
                @if ($loginMethod == "steam")
                    <div class="form-group">
                        {{ Form::label('steamname','Steam Name',array('id'=>'','class'=>'')) }}
                        {{ Form::text('steamname', $steamname, array('id'=>'steamname','class'=>'form-control', 'disabled'=>'true')) }}
                    </div>
                @endif
            </div>
            <div class="col-xs-12 col-md-6">
                {!! Settings::getRegistrationTermsAndConditions() !!}
                <h5>By Clicking on Confirm you are agreeing to the Terms and Conditions as set by {!! Settings::getOrgName() !!}</h5>
                <button type="submit" class="btn btn-block btn-primary">Register</button>
            </div>
        {{ Form::close() }}
    </div>
</div>

@endsection