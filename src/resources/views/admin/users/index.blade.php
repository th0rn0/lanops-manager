@extends ('layouts.admin-default')

@section ('page_title', 'Users')

@section ('content')

<div class="row">
	<div class="col-lg-12">
		<h3 class="pb-2 mt-4 mb-4 border-bottom">Users</h3>
		<ol class="breadcrumb">
			<li class="breadcrumb-item active">
				Users
			</li>
		</ol>
	</div>
</div>

<div class="row">
	<div class="col-lg-12">

		<div class="card mb-3">
			<div class="card-header">
				<i class="fa fa-users fa-fw"></i> Users
			</div>
			<div class="card-body">
				<table class="table table-striped table-hover table-responsive">
					<thead>
						<tr>
							<th>Avatar</th>
							<th>User</th>
							<th>Name</th>
							@if (Settings::isCreditEnabled())
								<th>Credit</th>
							@endif
							<th>Admin</th>
							<th>Edit</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($users as $user)
							<tr>
								<td width="3%">
									<img class="img-fluid rounded" src="{{ $user->avatar }}">
								</td>
								<td>
									{{ $user->username }}
									@if ($user->steamid)
										- <span class="text-muted"><small>Steam: {{ $user->steamname }}</small></span>
									@endif
								</td>
								<td>{{ $user->firstname }} {{ $user->surname }}</td>
								@if (Settings::isCreditEnabled())
									<td>
										{{ $user->credit_total }}
									</td>
								@endif
								<td>
									@if ($user->admin)
										Yes
									@else
										No
									@endif
								</td>
								<td>
									<a href="/admin/users/{{ $user->id }}">
										<button class="btn btn-primary btn-sm btn-block">Edit</button>
									</a>
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
				{{ $users->links() }}
			</div>
		</div>

	</div>
</div>

@endsection