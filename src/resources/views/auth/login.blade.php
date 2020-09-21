@extends ('layouts.default')

@section ('page_title', __('auth.login_to_continue'))

@section ('content')

    <div class="container">
        <div class="page-header">
            <h1>@lang('auth.please_login')</h1> 
        </div>
        <div class="row">
            <div class="col-sm-12 col-md-6">
                <p>@lang('auth.login_methods')  
                @if (in_array('standard', $activeLoginMethods))
                @lang('auth.or')or <a href="/register/standard">@lang('auth.register')</a>
                @endif
                </p>
                @if (in_array('steam', $activeLoginMethods))
                    <a href="/login/steam">
                        <img class="img img-responsive" src="https://steamcommunity-a.akamaihd.net/public/images/signinthroughsteam/sits_01.png">
                    </a>
                @endif
            </div>
            <div class="col-sm-12 col-md-6">
                @if (in_array('standard', $activeLoginMethods))
                    <form method="POST" action="/login/standard" class="form-horizontal">
                        @csrf
                        <div class="form-group row">
                            <label for="email" class="col-sm-2 control-label">@lang('auth.email')</label>
                            <div class="col-sm-10 @error('email') has-error @enderror">
                                <input type="email" id="email" name="email" class="form-control" placeholder="Email address" required autofocus>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="password" class="col-sm-2 control-label">@lang('auth.password')</label>
                            <div class="col-sm-10 @error('password') has-error @enderror">
                                <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" value="remember-me"> @lang('auth.remember')
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button class="btn btn-lg btn-primary btn-block" type="submit">@lang('auth.signin')</button>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <a class="btn btn-link" href="/login/forgot">
                                @lang('auth.forgot_password')
                                </a>
                            </div>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>

@endsection