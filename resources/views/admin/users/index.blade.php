@extends ('layouts.admin-default')

@section ('page_title', 'Users')

@section ('content')

<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">Users</h1>
		<ol class="breadcrumb">
			<li class="active">
				Users
			</li>
		</ol> 
	</div>
</div>

<div class="row">
	<div class="col-lg-12">

		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-users fa-fw"></i> Users
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
							@foreach ($users as $user)
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
							@endforeach
						</tbody>
					</table>
				</div>
			</div>  
		</div>
		
	</div>
</div>
 
@endsection