@extends ('layouts.default')

@section ('page_title', 'Login to continue')

@section ('content')

    <div class="container">
        <div class="page-header">
            <h1>Please Login</h1> 
        </div>
        <div class="row">
            <div class="col-sm-12 col-md-6">
                @if (in_array('standard', $activeLoginMethods))
                    <form method="POST" action="/login/standard" class="form-horizontal">
                        @csrf
                        <div class="form-group row">
                            <label for="email" class="col-sm-2 control-label">Email</label>
                            <div class="col-sm-10 @error('email') has-error @enderror">
                                <input type="email" id="email" name="email" class="form-control" placeholder="Email address" required autofocus>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="password" class="col-sm-2 control-label">Password</label>
                            <div class="col-sm-10 @error('password') has-error @enderror">
                                <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" value="remember-me"> Remember me
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
                            </div>
                        </div>
                    </form>
                    <div class="row">
                        <div class="col-sm-offset-2 col-sm-10">
                            <a href="/register/standard">
                                <button class="btn btn-lg btn-primary btn-block">Register</button>
                            </a>
                        </div>
                        <div class="col-sm-offset-2 col-sm-10">
                            <a class="btn btn-link" href="/login/forgot">
                                Forgot Your Password?
                            </a>
                        </div>
                    </div>
                @endif
            </div>
            <div class="col-sm-12 col-md-6">
                <p>Use one of the login methods below to continue or <a href="/register/standard">register</a></p>
                @if (in_array('steam', $activeLoginMethods))
                    <a href="/login/steam">
                        <div class="">
                            <button class="btn btn-lg btn-primary btn-block" type="submit"><img class="img img-responsive img-rounded login-icon-button" src="/images/login/steam.png"/>Steam</button>
                        </div>
                    </a>
                @endif
            </div>
        </div>
    </div>

@endsection