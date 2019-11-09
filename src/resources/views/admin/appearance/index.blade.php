@extends ('layouts.admin-default')

@section ('page_title', 'Appearance')

@section ('content')
<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">Appearance</h1>
		<ol class="breadcrumb">
			<li class="active">
				Appearance
			</li>
		</ol> 
	</div>
</div>

<div class="row">
	<div class="col-xs-12 col-sm-6">
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-users fa-fw"></i> CSS Variables
			</div>
			<div class="panel-body">
				<div class="alert alert-info">
					Dark Themes are currently not properly supported. Please feel free to use the Custom CSS form to get it working.<br>
					Theme editing is not supported for the admin interface.
				</div>
				{{ Form::open(array('url'=>'/admin/appearance/css/variables', 'onsubmit' => 'return ConfirmSubmit()')) }}
					<h3>Primary Colors</h3>
					@foreach ($cssVariables['primary'] as $cssVariable)
						<div class="row">
							<div class="form-group col-xs-10 col-sm-8">
								{{ Form::label('css_variables[' . $cssVariable->key . ']',ucwords(str_replace('color', '', str_replace('_', ' ', $cssVariable->key))),array('id'=>'','class'=>'')) }}
								{{ Form::text('css_variables[' . $cssVariable->key . ']', $cssVariable->value,array('id'=>'css_variables[]','class'=>'form-control')) }}
							</div>
							<div class="col-xs-2 col-sm-4">
								{{ Form::label('css_variables_preview[' . $cssVariable->key . ']', 'Preview', array('id'=>'','class'=>'')) }}
								<div class="alert alert-info" style="background-color: {{ $cssVariable->value }}"></div>
							</div>
						</div>
					@endforeach
					<h3>Secondary / Footer Colors</h3>
					@foreach ($cssVariables['secondary'] as $cssVariable)
						<div class="row">
							<div class="form-group col-xs-10 col-sm-8">
								{{ Form::label('css_variables[' . $cssVariable->key . ']',ucwords(str_replace('color', '', str_replace('_', ' ', $cssVariable->key))),array('id'=>'','class'=>'')) }}
								{{ Form::text('css_variables[' . $cssVariable->key . ']', $cssVariable->value,array('id'=>'css_variables[]','class'=>'form-control')) }}
							</div>
							<div class="col-xs-2 col-sm-4">
								{{ Form::label('css_variables_preview[' . $cssVariable->key . ']', 'Preview', array('id'=>'','class'=>'')) }}
								<div class="alert alert-info" style="background-color: {{ $cssVariable->value }}"></div>
							</div>
						</div>
					@endforeach
					<h3>Body Colors</h3>
					@foreach ($cssVariables['body'] as $cssVariable)
						<div class="row">
							<div class="form-group col-xs-10 col-sm-8">
								{{ Form::label('css_variables[' . $cssVariable->key . ']',ucwords(str_replace('color', '', str_replace('_', ' ', $cssVariable->key))),array('id'=>'','class'=>'')) }}
								{{ Form::text('css_variables[' . $cssVariable->key . ']', $cssVariable->value,array('id'=>'css_variables[]','class'=>'form-control')) }}
							</div>
							<div class="col-xs-2 col-sm-4">
								{{ Form::label('css_variables_preview[' . $cssVariable->key . ']', 'Preview', array('id'=>'','class'=>'')) }}
								<div class="alert alert-info" style="background-color: {{ $cssVariable->value }}"></div>
							</div>
						</div>
					@endforeach
					<h3>Header Colors</h3>
					@foreach ($cssVariables['header'] as $cssVariable)
						<div class="row">
							<div class="form-group col-xs-10 col-sm-8">
								{{ Form::label('css_variables[' . $cssVariable->key . ']',ucwords(str_replace('color', '', str_replace('_', ' ', $cssVariable->key))),array('id'=>'','class'=>'')) }}
								{{ Form::text('css_variables[' . $cssVariable->key . ']', $cssVariable->value,array('id'=>'css_variables[]','class'=>'form-control')) }}
							</div>
							<div class="col-xs-2 col-sm-4">
								{{ Form::label('css_variables_preview[' . $cssVariable->key . ']', 'Preview', array('id'=>'','class'=>'')) }}
								<div class="alert alert-info" style="background-color: {{ $cssVariable->value }}"></div>
							</div>
						</div>
					@endforeach
					<button type="submit" class="btn btn-default ">Submit</button>
				{{ Form::close() }}
			</div>  
		</div>
	</div>
	<div class="col-xs-12 col-sm-6">
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-users fa-fw"></i> Custom CSS
			</div>
			<div class="panel-body">
				@if ($userOverrideCss)
					{{ Form::open(array('url'=>'/admin/appearance/css/override', 'onsubmit' => 'return ConfirmSubmit()')) }}
						<div class="form-group">
							{{ Form::textarea('css', $userOverrideCss ,array('id'=>'','class'=>'form-control', 'rows'=>'30')) }}
						</div>
						<button type="submit" class="btn btn-default">Submit</button>
					{{ Form::close() }}
				@endif
			</div>  
		</div>
	</div>
	<div class="col-xs-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-plus fa-fw"></i> Misc
			</div>
			<div class="panel-body">
				<a href="/admin/appearance/css/recompile"><button class="btn btn-default btn-sm">Recompile CSS</button></a>
			</div>
		</div>
	</div>
</div>

@endsection