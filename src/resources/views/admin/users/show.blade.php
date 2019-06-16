@extends ('layouts.admin-default')

@section ('page_title', 'Users - View '. $user->username)

@section ('content')

<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">{{ $user->username }} <small>{{ $user->steamname }}</small></h1>
		<ol class="breadcrumb">
			<li>
				<a href="/admin/users/">Users</a>
			</li>
			<li class="active">
				{{ $user->username }}
			</li>
		</ol> 
	</div>
</div>

<div class="row">
	<div class="col-lg-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-users fa-fw"></i> Add Credit
			</div>
			<div class="panel-body">
				{{ Form::open(array('url'=>'/admin/credit/edit')) }}
					<div class="form-group">
						{{ Form::hidden('user_id', $user->id) }}
						{{ Form::label('amount','Amount',array('id'=>'','class'=>'')) }}
						{{ Form::number('amount', '',array('id'=>'amount','class'=>'form-control')) }}
					</div>
					<button type="submit" class="btn btn-block btn-success">Submit</button>
				{{ Form::close() }}
			</div>
		</div>
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-users fa-fw"></i> User
			</div>
			<div class="panel-body">
				<div class="table-responsive">
					<table class="table table-striped table-hover">
						<thead>
							<tr>
								<th></th>
								<th>Username</th>
								<th>Steam Name</th>
								<th>Name</th>
								<th>Avatar</th>
								<th>Admin</th>
								<th>Edit</th>
							</tr>
						</thead>
						<tbody>
							<tr class="">
								<td></td>
								<td>{{ $user->username }}</td>
								<td>{{ $user->steamname }}</td>
								<td>{{ $user->firstname }} {{ $user->surname }}</td>
								<td>
									<img class="img-responsive img-rounded" width="15%" src="{{ $user->avatar }}">
								</td>
								<td>{{ $user->admin }}</td>
								<td></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>  
		</div>
		
	</div>
</div>
 
@endsection