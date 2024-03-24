@extends ('layouts.default')

@section ('page_title', 'Login to continue')

@section ('content')

    <div class="container">
        <div class="page-header">
            <h1>Please Login</h1> 
        </div>
        <div class="row">
            <div class="col-sm-12 col-md-6">
                <p>Use one of the login methods below to continue</p>
                <a href="/login/steam">
                    <img class="img img-responsive" src="https://steamcommunity-a.akamaihd.net/public/images/signinthroughsteam/sits_01.png">
                </a>
            </div>
        </div>
    </div>

@endsection