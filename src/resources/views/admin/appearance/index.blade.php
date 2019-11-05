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
	<div class="col-xs-12 col-sm-8">
		Primary Color
		Primary Color Text
		Secondary Color (footer)
		Secondary Color Text
		Body Color
		Body Text Color
		Link Color
		Header - bg, text, text hover
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-users fa-fw"></i> Appearance
			</div>
			<div class="panel-body">
				@if ($userOverrideCss)
					{{ Form::open(array('url'=>'/admin/appearance/css/override', 'onsubmit' => 'return ConfirmSubmit()')) }}
						<div class="form-group">
							{{ Form::label('css','Add Custom CSS',array('id'=>'','class'=>'')) }}
							{{ Form::textarea('css', $userOverrideCss ,array('id'=>'','class'=>'form-control', 'rows'=>'25')) }}
						</div>
						<button type="submit" class="btn btn-default">Submit</button>
					{{ Form::close() }}
				@endif
			</div>  
		</div>
	</div>
	<div class="col-xs-12 col-sm-4">
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-plus fa-fw"></i> Options
			</div>
			<div class="panel-body">
				{{ Form::open(array('url'=>'/admin/appearance/css/variables', 'onsubmit' => 'return ConfirmSubmit()')) }}
					@foreach ($cssVariables as $cssVariable)
						<div class="form-group">
							{{ Form::label('css_variables[' . $cssVariable->key . ']',$cssVariable->key,array('id'=>'','class'=>'')) }}
							{{ Form::text('css_variables[' . $cssVariable->key . ']', $cssVariable->value,array('id'=>'css_variables[]','class'=>'form-control')) }}
						</div>
					@endforeach
					<button type="submit" class="btn btn-default">Submit</button>
				{{ Form::close() }}
				<hr>
				<a href="/admin/appearance/css/recompile"><button class="btn btn-default btn-sm">Recompile CSS</button></a>
			</div>
		</div>
	</div>
</div>

@endsection