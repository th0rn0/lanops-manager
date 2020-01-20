@extends ('layouts.admin-default')

@section ('page_title', 'Settings')

@section ('content')

<div class="row">
	<div class="col-lg-12">
		<h3 class="page-header">API</h3>
		<ol class="breadcrumb">
			<li>
				<a href="/admin/settings">Settings</a>
			</li>
			<li class="active">
				API
			</li>
		</ol> 
	</div>
</div>

@include ('layouts._partials._admin._settings.dashMini', ['active' => 'auth'])

<div class="row">
	<div class="col-xs-12">
		<!-- APIs -->
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-wrench fa-fw"></i> Api
			</div>
			<div class="panel-body">
				<div class="table-responsive">
					<table class="table table-striped table-hover">
						<thead>
							<tr>
								<th>Setting</th>
								<th>Value</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							@foreach ($apiKeys as $apiKey)
								<tr>
									{{ Form::open(array('url'=>'/admin/settings/', 'onsubmit' => 'return ConfirmSubmit()')) }}
										<td>{{ $apiKey->key }}</td>
										<td>{{ Form::text($apiKey->value, $apiKey->value ,array('id'=>'key','class'=>'form-control')) }}</td>
										<td><button type="submit" class="btn btn-success btn-sm btn-block">Update</button></td>
									{{ Form::close() }}
								</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
 
@endsection