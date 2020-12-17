@extends ('layouts.admin-default')

@section ('page_title', 'Mailing')

@section ('content')

<div class="row">
	<div class="col-lg-12">
		<h3 class="pb-2 mt-4 mb-4 border-bottom">Mailing</h3>
		<ol class="breadcrumb">
			<li class="breadcrumb-item active">
				Mailing
			</li>
		</ol>
	</div>
</div>

<div class="row">
	<div class="col-lg-8">

		<div class="card mb-3">
			<div class="card-header">
				<i class="fa fa-envelope fa-fw"></i> Mailtemplates
			</div>
			<div class="card-body">
				<div class="dataTable_wrapper">
					<table width="100%" class="table table-striped table-hover" id="dataTables-example">
						<thead>
							<tr>
								<th>Subject</th>
								<th>ID</th>
					
								<th></th>
								<th></th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							@foreach ($mailTemplates as $mailTemplate)
								<tr>
									<td>{{ $mailTemplate->subject }}</td>
									<td>{{ $mailTemplate->id }}</td>
									<td width="15%">
					 						<button class="btn btn-success btn-sm btn-block" data-toggle="modal" data-target="#sendMailModal{{ $mailTemplate->id }}">Send</button>
									</td>
									<td width="15%">
										<a href="/admin/mailing/{{ $mailTemplate->id }}">
											<button type="button" class="btn btn-primary btn-sm btn-block">Edit</button>
										</a>
									</td>
									<td width="15%">
										{{ Form::open(array('url'=>'/admin/mailing/' . $mailTemplate->id, 'onsubmit' => 'return ConfirmDelete()')) }}
											{{ Form::hidden('_method', 'DELETE') }}
											<button type="submit" class="btn btn-danger btn-sm btn-block">Delete</button>
										{{ Form::close() }}
									</td>
								</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

	<div class="col-lg-4">
		<div class="card mb-3">
			<div class="card-header">
				<i class="fa fa-plus fa-fw"></i> Add Mailtemplate
			</div>
			<div class="card-body">
				<div class="list-group">
					{{ Form::open(array('url'=>'/admin/mailing/')) }}
						<div class="form-group">
							{{ Form::label('subject','Subject',array('id'=>'','class'=>'')) }}
							{{ Form::text('subject',NULL,array('id'=>'subject','class'=>'form-control')) }}
						</div>
						<div class="form-group">
							{{ Form::label('html_template','HTML Template',array('id'=>'','class'=>'')) }}
							{{ Form::textarea('html_template', NULL, array('id'=>'html_template','class'=>'form-control wysiwyg-editor')) }}
						</div>
						<small>
							<div>Usable Variables:</div>
							@foreach ($mailVariables as $mailVariable)
							<?php
							echo "{{". $mailVariable . "}}<br>" 
							?>
						   @endforeach
						</small>
						<div class="form-group">
							{{ Form::label('text_template','Text Template',array('id'=>'','class'=>'')) }}
							{{ Form::textarea('text_template', NULL, array('id'=>'text_template','class'=>'form-control')) }}
						</div>
						<small>
							<div>Usable Variables:</div>
							@foreach ($mailVariables as $mailVariable)
							<?php
							echo "{{". $mailVariable . "}}<br>" 
							?>
						   @endforeach
						</small>
						<button type="submit" class="btn btn-success btn-block mt-3">Submit</button>

					{{ Form::close() }}
				</div>
			</div>
		</div>

	</div>
</div>


@foreach ($mailTemplates as $mailTemplate)
	@if(isset($mailTemplate->subject))
		<?php	$content= (new App\Mail\EventulaMailingMail($user,$nextevent))->render();	?>
		<!-- Select Server Modal -->
		<div class="modal fade" id="sendMailModal{{ $mailTemplate->id }}" tabindex="-1" role="dialog" aria-labelledby="sendMailModalLabel{{ $mailTemplate->id }}" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title" id="sendMailModalLabel{{ $mailTemplate->id }}">Send Mail {{ $mailTemplate->subject }}</h4>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					</div>
					<div class="modal-body">
							<small>(preview with your data)</small>
								<div class="card mb-3 mt-3" style="width: 100%;">
									<div class="card-header">
										<i class="fas fa-envelope fa-fw"></i> {{ $mailTemplate->subject }} 
									</div>
									<div class="card-body">
										{!! $content !!}
									</div>
								</div>
							If you Confirm, the Mail will be sent to the following users  <br>
							<div class="row">
								<div class="col-6">
							<small> hold strg to (de)/(multi)select users </small>
								</div>
								<div class="col">
								  <a href="#" id="selectalluserswithmails{{$mailTemplate->id}}">Select All</a>
								</div>								
								<div class="col">
									<a href="#" id="deselectalluserswithmails{{$mailTemplate->id}}">Deselect All</a>
								</div>
							  </div>
							{{ Form::open(array('url'=>'/admin/mailing/' . $mailTemplate->id .'/send', 'id'=>'sendMailModal')) }}
							{!! Form::select('userswithmails'.$mailTemplate->id.'[]', $usersWithMail, null, ['multiple' => true, 'class' => 'form-control margin', 'id' => 'userswithmails'.$mailTemplate->id]) !!}

						</div>
						<div class="modal-footer">
							
							<button type="submit" class="btn btn-warning">Send Mail to all selected Users</button>
							<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
						</div>
					{{ Form::close() }}
				</div>
			</div>

		</div>
		<script type="text/javascript">

		


		function selectalluserswithmails{{$mailTemplate->id}}() {
			  
			$("#userswithmails{{ $mailTemplate->id }} option").prop('selected', true);
			
		}

		function deselectalluserswithmails{{$mailTemplate->id}}() {
			  
			$("#userswithmails{{ $mailTemplate->id }} option").prop('selected', false);
			
		}

		$( document ).ready(function() {
			selectalluserswithmails{{$mailTemplate->id}}();
		});

		$("#selectalluserswithmails{{$mailTemplate->id}}").click(selectalluserswithmails{{$mailTemplate->id}});
		$("#deselectalluserswithmails{{$mailTemplate->id}}").click(deselectalluserswithmails{{$mailTemplate->id}});

		</script>

	@endif
@endforeach

<script type="text/javascript">
	jQuery( function() {
		jQuery( "#start_date" ).datepicker();
		jQuery( "#end_date" ).datepicker();
		
	});



</script>

@endsection
