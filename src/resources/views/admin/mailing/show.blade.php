@extends ('layouts.admin-default')

@section ('page_title', 'Mailing')

@section ('content')

<div class="row">
	<div class="col-lg-12">
		<h3 class="pb-2 mt-4 mb-4 border-bottom">Mailing - {{ $mailTemplate->subject }}</h3>
		<ol class="breadcrumb">
			<li class="breadcrumb-item">
				<a href="/admin/mailing">Mailing</a>
			</li>
			<li class="breadcrumb-item active">
				{{ $mailTemplate->subject }}
			</li>
		</ol>
	</div>
</div>
<div class="row">
	<div class="col-12 col-sm-8">
		<div class="card mb-3">
			<div class="card-header">
				<i class="fa fa-pencil fa-fw"></i> Edit {{ $mailTemplate->subject }}
			</div>
			<div class="card-body">
				{{ Form::open(array('url'=>'/admin/mailing/' . $mailTemplate->id, 'files' => 'true')) }}
					<div class="form-group">
						{{ Form::label('subject','Subject',array('id'=>'','class'=>'')) }}
						{{ Form::text('subject', $mailTemplate->subject ,array('id'=>'subject','class'=>'form-control')) }}
					</div>
					<div class="form-group">
						{{ Form::label('html_template','HTML Template',array('id'=>'','class'=>'')) }}
						{{ Form::textarea('html_template', $mailTemplate->html_template, array('id'=>'html_template','class'=>'form-control')) }}
					</div>
					<div class="form-group">
						{{ Form::label('text_template','Text Template',array('id'=>'','class'=>'')) }}
						{{ Form::textarea('text_template', $mailTemplate->text_template, array('id'=>'text_template','class'=>'form-control')) }}
					</div>

					<button type="submit" class="btn btn-success btn-block">Submit</button>
				{{ Form::close() }}
				@if ($mailTemplate->mailable == "App\Mail\EventulaMailingMail")
					<hr>
					{{ Form::open(array('url'=>'/admin/mailing/' . $mailTemplate->id, 'onsubmit' => 'return ConfirmDelete()')) }}
						{{ Form::hidden('_method', 'DELETE') }}
						<button type="submit" class="btn btn-danger btn-block">Delete</button>
					{{ Form::close() }}
				@endif
			</div>
		</div>
	</div>
	<div class="col-lg-4">
		<div class="card mb-3">
			<div class="card-header">
				<i class="fa fa-plus fa-fw"></i> Usable Variables
			</div>
			<div class="card-body">
				<div class="list-group">
					@foreach ($mailVariables as $mailVariable)
					 <?php
					 echo "{{". $mailVariable . "}}<br>" 
					 ?>
					@endforeach
				</div>
			</div>
		</div>

	</div>
</div>


@endsection