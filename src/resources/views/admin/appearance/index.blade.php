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
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-users fa-fw"></i> Appearance
			</div>
			<div class="panel-body">
				
			</div>  
		</div>
	</div>
	<div class="col-xs-12 col-sm-4">
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-plus fa-fw"></i> Options
			</div>
			<div class="panel-body">
				<a href="/admin/appearance/recompile/css"><button class="btn btn-default btn-sm">Recompile CSS</button></a>
				<p>edit main variables for quick colors</p>
				<p>upload additional custom css</p>
			</div>
		</div>
	</div>
</div>

@endsection