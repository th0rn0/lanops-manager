@extends ('layouts.default')

@section ('page_title', Settings::getOrgName() . ' - ' . __('auth.verify_email'))

@section ('content')
<div class="container">
    <div class="page-header">
        <h1>@lang('auth.verify_email')</h1>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            @lang('auth.fresh_verification')
                        </div>
                    @endif

                    <p>@lang('auth.check_mail')</p>
                    <p>@lang('auth.mail_not_received') <a href="{{ route('verification.resend') }}">@lang('auth.request_verification')</a>.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
