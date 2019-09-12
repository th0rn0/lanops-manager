@if ($errors->any())
  	<div class="alert alert-danger">
        <ul>
          	@foreach ($errors->all() as $error)
            	<li>{{ $error }}</li>
          	@endforeach
        </ul>
  	</div>
@endif
<div class="row">
	<div class="form-group col-sm-6 col-xs-12">
		{{ Form::label('name','Ticket Name',array('id'=>'','class'=>'')) }}
		{{ 
			Form::text(
				'name',
				(@$empty ? null : @$ticket->name),
				array('id'=>'name','class'=>'form-control')
			)
		}}
	</div> 
	<div class="form-group col-sm-6 col-xs-12">
		{{ Form::label('price','Ticket Price',array('id'=>'','class'=>'')) }}
		<div class="input-group">
			<div class="input-group-addon">{{ Settings::getCurrencySymbol() }}</div>
				@if (isset($priceLock) && $priceLock)
					{{ 
						Form::text(
							'price',
							(@$empty ? null : @$ticket->price),
							array('id'=>'price','class'=>'form-control', 'disabled'=>'true')
						)
					}}
				@else 
					{{ 
						Form::text(
							'price',
							(@$empty ? null : @$ticket->price),
							array('id'=>'price','class'=>'form-control')
						)
					}}
				@endif
			<div class="input-group-addon">.00</div>
		</div>
	</div>

</div>
<div class="row">

	<div class="form-group col-md-6 col-sm-6 col-xs-12">
		{{ Form::label('quantity','Quantity',array('id'=>'','class'=>'')) }}
		{{ 
			Form::text(
				'quantity',
				(@$empty ? null : @$ticket->quantity),
				array('id'=>'quantity','class'=>'form-control')
			)
		}}
		<small>If unlimited, leave blank</small>
	</div>  
	<div class="form-group col-md-6 col-sm-6 col-xs-12">
		{{ Form::label('type','Ticket Type',array('id'=>'','class'=>'')) }}
		{{ Form::select('type', array('participant' => 'Participant', 'spectator' => 'Spectator'), @$ticket->type, array('id'=>'type','class'=>'form-control')) }}
	</div> 
	<div class="form-group col-xs-12">
		<div class="checkbox">
			<label>
				@if (@$ticket->seatable || @$empty)
					{{ Form::checkbox('seatable', 1, true) }}
				@else
					{{ Form::checkbox('seatable', 1) }}
				@endif
				<strong>Seatable</strong>
			</label>
		</div>
	</div> 

</div>
<hr>
<h3>Purchase Period</h3>
<div class="row">
	<div class="form-group col-sm-6 col-xs-12">
		{{ Form::label('sale_start_date','Start Date',array('id'=>'','class'=>'')) }}
		@if (@$empty)
			{{ Form::text(
				'sale_start_date',
				null,
				array('id'=>'sale_start_date','class'=>'form-control')
				) 
			}}
		@else
			{{ Form::text(
				'sale_start_date',
				(@$ticket->sale_start ? date('d-m-Y', strtotime($ticket->sale_start)) : null),
				array('id'=>'sale_start_date','class'=>'form-control')
				) 
			}}
		@endif
		<small>If no start leave blank</small>
	</div> 
	<div class="form-group col-sm-6 col-xs-12">
		{{ Form::label('sale_start_time','Start Time',array('id'=>'','class'=>'')) }}
		@if (@$empty)
			{{ 
				Form::text(
					'sale_start_time',
					null,
					array('id'=>'sale_start_time','class'=>'form-control')
				)
			}}
		@else
			{{ 
				Form::text(
					'sale_start_time',
					(@$ticket->sale_start ? date('H:i', strtotime($ticket->sale_start)) : null),
					array('id'=>'sale_start_time','class'=>'form-control')
				)
			}}
		@endif
		<small>If no start leave blank</small>
	</div>
	<div class="form-group col-sm-6 col-xs-12">
		{{ Form::label('sale_end_date','End Date',array('id'=>'','class'=>'')) }}
		@if(@$empty)
			{{ 
				Form::text(
					'sale_end_date',
					null,
					array('id'=>'sale_end_date','class'=>'form-control')
				)
			}}
		@else
			{{ 
				Form::text(
					'sale_end_date',
					(@$ticket->sale_end ? date('d-m-Y', strtotime($ticket->sale_end)) : null),
					array('id'=>'sale_end_date','class'=>'form-control')
				)
			}}
		@endif
		<small>If no end leave blank</small>
	</div> 
	<div class="form-group col-sm-6 col-xs-12">
		{{ Form::label('sale_end_time','End Time',array('id'=>'','class'=>'')) }}
		@if (@$empty)
			{{ 
				Form::text(
					'sale_end_time',
					null,
					array('id'=>'sale_end_time','class'=>'form-control')
				)
			}}
		@else
			{{ 
				Form::text(
					'sale_end_time',
					(@$ticket->sale_end ? date('H:i', strtotime($ticket->sale_end)) : null),
					array('id'=>'sale_end_time','class'=>'form-control')
				)
			}}
		@endif
		<small>If no end leave blank</small>
	</div>

</div>
<hr>
<button type="submit" class="btn btn-default btn-block">Submit</button>