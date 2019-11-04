@extends ('layouts.default')

@section ('page_title', 'Login to continue')

@section ('content')

    <div class="container">
        <div class="page-header">
            <h1>Please Login</h1> 
        </div>
        <p>Use one of the login methods below to continue or <a href="/register/standard">register</a></p>
        @if (in_array('steam', $activeLoginMethods))
            <a href="/login/steam">
                <img class="img img-responsive" src="https://steamcommunity-a.akamaihd.net/public/images/signinthroughsteam/sits_01.png">
            </a>
        @endif
        @if (in_array('standard', $activeLoginMethods))
            <form method="POST" action="/login/standard">
                @csrf
                <label for="email" class="sr-only">Email address</label>
                <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Email address" required autofocus>
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                <label for="password" class="sr-only">Password</label>
                <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password" required>
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                <div class="checkbox">
                    <label>
                        <input type="checkbox" value="remember-me"> Remember me
                    </label>
                </div>
                <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>

                <a class="btn btn-link" href="/login/forgot">
                    Forgot Your Password?
                </a>
            </form>
        @endif
    </div>

@endsection