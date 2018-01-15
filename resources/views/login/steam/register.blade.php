@extends('layouts.default')

@section('page_title', 'Updating your Profile')

@section('content')

	<div class="container">
		<div class="row">
			<div class="col-md-10 col-md-offset-1">
				<div class="panel  panel-default">
					<div class="panel-heading">
						Update Details
					</div>
					<div class="panel-body">
						{{ Form::open(array('url'=>'/steamlogin/register/' . $user->id )) }}
							{{ method_field('PATCH') }}
							<div class="form-group">
								{{ Form::label('firstname','Firstname',array('id'=>'','class'=>'')) }}
      					{{ Form::text('firstname', NULL,array('id'=>'firstname','class'=>'form-control', 'required')) }}
							</div>
							<div class="form-group">
								{{ Form::label('surname','Surname',array('id'=>'','class'=>'')) }}
      					{{ Form::text('surname', NULL,array('id'=>'surname','class'=>'form-control', 'required')) }}
							</div>
							<div class="form-group">
								{{ Form::label('username','Username',array('id'=>'','class'=>'')) }}
      					{{ Form::text('username', NULL,array('id'=>'username','class'=>'form-control', 'required')) }}
							</div>
							<div class="form-group">
								{{ Form::label('steamname','Steam Name',array('id'=>'','class'=>'')) }}
      					{{ Form::text('steamname', $user->steamname,array('id'=>'steamname','class'=>'form-control', 'disabled'=>'true')) }}
							</div>
							<button type="submit" class="btn  btn-primary">Update Details</button>
							{{ csrf_field() }}
	  				{{ Form::close() }}
					</div><!-- end .panel-body -->
				</div><!-- end .panel .panel-default -->
			</div><!-- end .col-md-10 .col-md-offset-1 -->
		</div><!-- end .row -->
	</div><!-- end .container -->

@endsection