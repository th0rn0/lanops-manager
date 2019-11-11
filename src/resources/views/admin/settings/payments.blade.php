@extends ('layouts.admin-default')

@section ('page_title', 'Settings')

@section ('content')

<div class="row">
	<div class="col-lg-12">
		<h1 class="page-header">Settings - Payments</h1>
		<ol class="breadcrumb">
			<li>
				<a href="/admin/settings">Settings</a>
			</li>
			<li class="active">
				Payments
			</li>
		</ol> 
	</div>
</div>

<div class="row">
	<div class="col-lg-6 col-xs-12">
		<!-- Payment Gateways -->
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-info-circle fa-fw"></i> Payment Gateways
			</div>
			<div class="panel-body">
				<div class="row">
					@foreach ($supportedPaymentGateways as $gateway)
						<div class="col-sm-6 col-xs-12">
							<h4>{{ ucwords(str_replace('-', ' ', (str_replace('_', ' ' , $gateway)))) }}</h4>
							@if (in_array($gateway, $activePaymentGateways))
								{{ Form::open(array('url'=>'/admin/settings/payments/' . $gateway . '/disable')) }}
									<button type="submit" class="btn btn-block btn-danger">Disable</button>
								{{ Form::close() }}
							@else
								{{ Form::open(array('url'=>'/admin/settings/payments/' . $gateway . '/enable')) }}
									<button type="submit" class="btn btn-block btn-success">Enable</button>
								{{ Form::close() }}
							@endif
						</div>
					@endforeach
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-6 col-xs-12">
		<!-- Currency -->
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-money fa-fw"></i> Currency
			</div>
			<div class="panel-body">
				{{ Form::open(array('url'=>'/admin/settings/', 'onsubmit' => 'return ConfirmSubmit()')) }}
					<div class="form-group">
						{{ Form::select('currency', ['GBP' => 'GBP', 'USD' => 'USD', 'EUR' => 'EUR'], Settings::getCurrency(), array('id'=>'venue','class'=>'form-control')) }}
					</div>
					<button type="submit" class="btn btn-default">Submit</button>
				{{ Form::close() }}
			</div>
		</div>
	</div>
	<div class="col-xs-12">
		<!-- Terms & Conditions -->
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-info-circle fa-fw"></i> Terms and Conditions
			</div>
			<div class="panel-body">
				{{ Form::open(array('url'=>'/admin/settings/', 'onsubmit' => 'return ConfirmSubmit()')) }}
					<div class="form-group">
						{{ Form::label('purchase_terms_and_conditions','Purchase',array('id'=>'','class'=>'')) }}
						{{ Form::textarea('purchase_terms_and_conditions', Settings::getPurchaseTermsAndConditions() ,array('id'=>'','class'=>'form-control wysiwyg-editor')) }}
					</div>
					<button type="submit" class="btn btn-default">Submit</button>
				{{ Form::close() }}
			</div>
		</div>
	</div>
</div>
 
@endsection