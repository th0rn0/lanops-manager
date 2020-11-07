@extends ('layouts.admin-default')

@section ('page_title', 'Appearance')

@section ('content')
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
				<div class="alert alert-info">
					Dark Themes are currently not properly supported. Please feel free to use the Custom CSS form to get it working.<br>
					Theme editing is not supported for the admin interface.
				</div>
				{{ Form::open(array('url'=>'/admin/settings/appearance/css/variables', 'onsubmit' => 'return ConfirmSubmit()')) }}
					<h3>Primary Colors</h3>
					@foreach ($cssVariables['primary'] as $cssVariable)
						<div class="row">
							<div class="form-group col-10 col-sm-8">
								{{ Form::label('css_variables[' . $cssVariable->key . ']',ucwords(str_replace('color', '', str_replace('_', ' ', $cssVariable->key))),array('id'=>'','class'=>'')) }}
								{{ Form::text('css_variables[' . $cssVariable->key . ']', $cssVariable->value,array('id'=>'css_variables[' . $cssVariable->key . ']','class'=>'form-control')) }}
							</div>
							<div class="col-2 col-sm-4">
								{{ Form::label('css_variables_preview[' . $cssVariable->key . ']', 'Preview', array('id'=>'','class'=>'')) }}
								<div class="alert alert-info" style="background-color: {{ $cssVariable->value }}"></div>
							</div>
						</div>
					@endforeach
					<h3>Secondary / Footer Colors</h3>
					@foreach ($cssVariables['secondary'] as $cssVariable)
						<div class="row">
							<div class="form-group col-10 col-sm-8">
								{{ Form::label('css_variables[' . $cssVariable->key . ']',ucwords(str_replace('color', '', str_replace('_', ' ', $cssVariable->key))),array('id'=>'','class'=>'')) }}
								{{ Form::text('css_variables[' . $cssVariable->key . ']', $cssVariable->value,array('id'=>'css_variables[' . $cssVariable->key . ']','class'=>'form-control')) }}
							</div>
							<div class="col-2 col-sm-4">
								{{ Form::label('css_variables_preview[' . $cssVariable->key . ']', 'Preview', array('id'=>'','class'=>'')) }}
								<div class="alert alert-info" style="background-color: {{ $cssVariable->value }}"></div>
							</div>
						</div>
					@endforeach
					<h3>Body Colors</h3>
					@foreach ($cssVariables['body'] as $cssVariable)
						<div class="row">
							<div class="form-group col-10 col-sm-8">
								{{ Form::label('css_variables[' . $cssVariable->key . ']',ucwords(str_replace('color', '', str_replace('_', ' ', $cssVariable->key))),array('id'=>'','class'=>'')) }}
								{{ Form::text('css_variables[' . $cssVariable->key . ']', $cssVariable->value,array('id'=>'css_variables[' . $cssVariable->key . ']','class'=>'form-control')) }}
							</div>
							<div class="col-2 col-sm-4">
								{{ Form::label('css_variables_preview[' . $cssVariable->key . ']', 'Preview', array('id'=>'','class'=>'')) }}
								<div class="alert alert-info" style="background-color: {{ $cssVariable->value }}"></div>
							</div>
						</div>
					@endforeach
					<h3>Header Colors</h3>
					@foreach ($cssVariables['header'] as $cssVariable)
						<div class="row">
							<div class="form-group col-10 col-sm-8">
								{{ Form::label('css_variables[' . $cssVariable->key . ']',ucwords(str_replace('color', '', str_replace('_', ' ', $cssVariable->key))),array('id'=>'','class'=>'')) }}
								{{ Form::text('css_variables[' . $cssVariable->key . ']', $cssVariable->value,array('id'=>'css_variables[' . $cssVariable->key . ']','class'=>'form-control')) }}
							</div>
							<div class="col-2 col-sm-4">
								{{ Form::label('css_variables_preview[' . $cssVariable->key . ']', 'Preview', array('id'=>'','class'=>'')) }}
								<div class="alert alert-info" style="background-color: {{ $cssVariable->value }}"></div>
							</div>
						</div>
					@endforeach
					<button type="submit" class="btn btn-success btn-block">Submit</button>
				{{ Form::close() }}
			</div>
		</div>
		<div class="card mb-3">
			<div class="card-header">
				<i class="fa fa-users fa-fw"></i> Custom CSS
			</div>
			<div class="card-body">
				@if ($userOverrideCss)
					{{ Form::open(array('url'=>'/admin/settings/appearance/css/override', 'onsubmit' => 'return ConfirmSubmit()')) }}
						<div class="form-group">
							{{ Form::textarea('css', $userOverrideCss ,array('id'=>'','class'=>'form-control', 'rows'=>'30')) }}
						</div>
						<button type="submit" class="btn btn-success btn-block">Submit</button>
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
				<span class="text-muted"><small>Images must be the same dimensions for the slider to function properly!</small></span>
				{{ Form::open(array('url'=>'/admin/settings/appearance/slider/images', 'files' => 'true')) }}
					{{ csrf_field() }}
            		<input type="hidden" name="slider" value="frontpage">
					<div class="form-group">
						{{ Form::file('images[]',array('id'=>'images','class'=>'form-control', 'multiple'=>false)) }}
					</div>
					<button type="submit" class="btn btn-block btn-success">Upload</button>
				{{ Form::close() }}
				<hr>
				@foreach ($sliderImages as $key => $image)
					<img class="img img-fluid mb-3" src="{{ $image->path }}" />

						<!-- <br> -->
						{{ Form::open(array('url'=>'/admin/settings/appearance/slider/images/' . $image->id, 'files' => 'true', 'class' => "form-inline")) }}
							<!-- <input type="hidden" name="slider" value="frontpage"> -->
							<div class="row">
								<div class="col">
									<div class="form-group">
										{{ Form::label('order','Order',array('id'=>'')) }}
										{{ Form::number('order', $image->order, array('id'=>'order' . $key, 'name' => 'order', 'class'=>'form-control')) }}
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
							{{ Form::open(array('url'=>'/admin/settings/appearance/slider/images/' . $image->id, 'files' => 'true', 'onsubmit' => 'return ConfirmDelete()')) }}
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
				<a href="/admin/settings/appearance/css/recompile"><button class="btn btn-success btn-block">Recompile CSS</button></a>
			</div>
		</div>
	</div>
</div>

@endsection