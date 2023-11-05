@extends ('layouts.admin-default')

@section('page_title', 'Appearance')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <h3 class="pb-2 mt-4 mb-4 border-bottom">Appearance</h3>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="/admin/settings">Settings</a>
                </li>
                <li class="breadcrumb-item active">
                    Appearance
                </li>
            </ol>
        </div>
    </div>

    @include ('layouts._partials._admin._settings.dashMini')

    <div class="row">
        <div class="col-12 col-sm-6">
            <div class="card mb-3">
                <div class="card-header">
                    <i class="fa fa-users fa-fw"></i> CSS Variables
                </div>
                <div class="card-body">
                    @if (config('appearance.disable_admin_appearance_css_settings') == 'true')
                        <div class="alert alert-warning">
                            You can not edit the CSS here because it is disabled by the
                            APPEAR_DISABLE_ADMIN_APPEARANCE_CSS_SETTINGS environment variable. Contact your hoster if this
                            is wrong!
                        </div>
                    @endif

                    <div class="alert alert-info">
                        Dark Themes are currently not properly supported. Please feel free to use the Custom CSS form to get
                        it working.<br>
                        Theme editing is not supported for the admin interface.
                    </div>

                    {{ Form::open(['url' => '/admin/settings/appearance/css/variables', 'onsubmit' => 'return ConfirmSubmit()']) }}
                    <fieldset @if (config('appearance.disable_admin_appearance_css_settings') == 'true') disabled="disabled" @endif>
                        <h3>Primary Colors</h3>
                        @foreach ($cssVariables['primary'] as $cssVariable)
                            <div class="row">
                                <div class="mb-3 col-10 col-sm-8">
                                    {{ Form::label('css_variables[' . $cssVariable->key . ']', ucwords(str_replace('color', '', str_replace('_', ' ', $cssVariable->key))), ['id' => '', 'class' => '']) }}
                                    {{ Form::text('css_variables[' . $cssVariable->key . ']', $cssVariable->value, ['id' => 'css_variables[' . $cssVariable->key . ']', 'class' => 'form-control']) }}
                                </div>
                                <div class="col-2 col-sm-4">
                                    {{ Form::label('css_variables_preview[' . $cssVariable->key . ']', 'Preview', ['id' => '', 'class' => '']) }}
                                    <div class="alert alert-info" style="background-color: {{ $cssVariable->value }}"></div>
                                </div>
                            </div>
                        @endforeach
                        <h3>Secondary / Footer Colors</h3>
                        @foreach ($cssVariables['secondary'] as $cssVariable)
                            <div class="row">
                                <div class="mb-3 col-10 col-sm-8">
                                    {{ Form::label('css_variables[' . $cssVariable->key . ']', ucwords(str_replace('color', '', str_replace('_', ' ', $cssVariable->key))), ['id' => '', 'class' => '']) }}
                                    {{ Form::text('css_variables[' . $cssVariable->key . ']', $cssVariable->value, ['id' => 'css_variables[' . $cssVariable->key . ']', 'class' => 'form-control']) }}
                                </div>
                                <div class="col-2 col-sm-4">
                                    {{ Form::label('css_variables_preview[' . $cssVariable->key . ']', 'Preview', ['id' => '', 'class' => '']) }}
                                    <div class="alert alert-info" style="background-color: {{ $cssVariable->value }}"></div>
                                </div>
                            </div>
                        @endforeach
                        <h3>Body Colors</h3>
                        @foreach ($cssVariables['body'] as $cssVariable)
                            <div class="row">
                                <div class="mb-3 col-10 col-sm-8">
                                    {{ Form::label('css_variables[' . $cssVariable->key . ']', ucwords(str_replace('color', '', str_replace('_', ' ', $cssVariable->key))), ['id' => '', 'class' => '']) }}
                                    {{ Form::text('css_variables[' . $cssVariable->key . ']', $cssVariable->value, ['id' => 'css_variables[' . $cssVariable->key . ']', 'class' => 'form-control']) }}
                                </div>
                                <div class="col-2 col-sm-4">
                                    {{ Form::label('css_variables_preview[' . $cssVariable->key . ']', 'Preview', ['id' => '', 'class' => '']) }}
                                    <div class="alert alert-info" style="background-color: {{ $cssVariable->value }}">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <h3>Header Colors</h3>
                        @foreach ($cssVariables['header'] as $cssVariable)
                            <div class="row">
                                <div class="mb-3 col-10 col-sm-8">
                                    {{ Form::label('css_variables[' . $cssVariable->key . ']', ucwords(str_replace('color', '', str_replace('_', ' ', $cssVariable->key))), ['id' => '', 'class' => '']) }}
                                    {{ Form::text('css_variables[' . $cssVariable->key . ']', $cssVariable->value, ['id' => 'css_variables[' . $cssVariable->key . ']', 'class' => 'form-control']) }}
                                </div>
                                <div class="col-2 col-sm-4">
                                    {{ Form::label('css_variables_preview[' . $cssVariable->key . ']', 'Preview', ['id' => '', 'class' => '']) }}
                                    <div class="alert alert-info" style="background-color: {{ $cssVariable->value }}">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <button type="submit" class="btn btn-success btn-block">Submit</button>
                    </fieldset>

                    {{ Form::close() }}
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-header">
                    <i class="fa fa-users fa-fw"></i> Custom CSS
                </div>
                <div class="card-body">
                    @if (config('appearance.disable_admin_appearance_css_settings') == 'true')
                        <div class="alert alert-warning">
                            You can not edit the CSS here because it is disabled by the
                            APPEAR_DISABLE_ADMIN_APPEARANCE_CSS_SETTINGS environment variable. Contact your hoster if this
                            is wrong!
                        </div>
                    @endif
                    @if ($userOverrideCss)
                        {{ Form::open(['url' => '/admin/settings/appearance/css/override', 'onsubmit' => 'return ConfirmSubmit()']) }}
                        <fieldset @if (config('appearance.disable_admin_appearance_css_settings') == 'true') disabled="disabled" @endif>
                            <div class="mb-3">
                                {{ Form::textarea('css', $userOverrideCss, ['id' => '', 'class' => 'form-control', 'rows' => '30']) }}
                            </div>
                            <button type="submit" class="btn btn-success btn-block">Submit</button>
                        </fieldset>
                        {{ Form::close() }}
                    @endif
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6">
            <!-- Front Page Slider -->
            <div class="card mb-3">
                <div class="card-header">
                    <i class="fa fa-wrench fa-fw"></i> Front Page Images
                </div>
                <div class="card-body">
                    <span class="text-muted"><small>Images must be the same dimensions for the slider to function
                            properly!</small></span>
                    {{ Form::open(['url' => '/admin/settings/appearance/slider/images', 'files' => 'true']) }}
                    <input type="hidden" name="slider" value="frontpage">
                    <div class="mb-3">
                        {{ Form::file('images[]', ['id' => 'images', 'class' => 'form-control', 'multiple' => false]) }}
                    </div>
                    <button type="submit" class="btn btn-block btn-success">Upload</button>
                    {{ Form::close() }}
                    <hr>
                    @foreach ($sliderImages as $key => $image)
                        <picture>
                            <source srcset="{{ $image->path }}.webp" type="image/webp">
                            <source srcset="{{ $image->path }}" type="image/jpeg">
                            <img class="img img-fluid mb-3" src="{{ $image->path }}" />
                        </picture>

                        <!-- <br> -->
                        {{ Form::open(['url' => '/admin/settings/appearance/slider/images/' . $image->id, 'files' => 'true', 'class' => 'd-inline-block w-auto']) }}
                        <!-- <input type="hidden" name="slider" value="frontpage"> -->
                        <div class="row">
                            <div class="col">
                                <div class="mb-3">
                                    {{ Form::label('order', 'Order', ['id' => '']) }}
                                    {{ Form::number('order', $image->order, ['id' => 'order' . $key, 'name' => 'order', 'class' => 'form-control']) }}
                                </div>
                            </div>
                            <div class="col mt-auto">
                                <button type="submit" class="btn btn-success btn-block">Submit</button>
                            </div>
                        </div>
                        {{ Form::close() }}
                        <br>
                        <div class="row">
                            <div class="col-12">
                                {{ Form::open(['url' => '/admin/settings/appearance/slider/images/' . $image->id, 'files' => 'true', 'onsubmit' => 'return ConfirmDelete()']) }}
                                <input type="hidden" name="slider" value="frontpage">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="btn btn-block btn-danger">Delete</button>
                                {{ Form::close() }}
                                @if (!$loop->last)
                                    <hr>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-header">
                    <i class="fa fa-plus fa-fw"></i> Misc
                </div>
                <div class="card-body">
                    <a href="/admin/settings/appearance/css/updatedatabasefromfile"><button
                            class="btn btn-success btn-block">Update CSS variables from file </button></a>
                </div>
                <div class="card-body">
                    <a href="/admin/settings/appearance/css/recompile"><button class="btn btn-success btn-block">Recompile
                            CSS</button></a>
                </div>
            </div>
        </div>
    </div>

@endsection
