@extends ('layouts.admin-default')

@section ('page_title', 'Settings')

@section ('content')

<div class="row">
	<div class="col-lg-12">
		<h3 class="pb-2 mt-4 mb-4 border-bottom">Organization</h3>
		<ol class="breadcrumb">
			<li class="breadcrumb-item">
				<a href="/admin/settings">Settings</a>
			</li>
			<li class="breadcrumb-item active">
				Organization
			</li>
		</ol>
	</div>
</div>

@include ('layouts._partials._admin._settings.dashMini', ['active' => 'org'])

<div class="row">
	<div class="col-12">
		<!-- Name & Logo -->
		<div class="card mb-3">
			<div class="card-header">
				<i class="fa fa-wrench fa-fw"></i> Name & Logo
			</div>
			<div class="card-body">
				{{ Form::open(array('url'=>'/admin/settings/', 'onsubmit' => 'return ConfirmSubmit()', 'files' => 'true')) }}
					<div class="row">
						<div class="col-12 col-md-6">

							<div class="form-group">
								{{ Form::label('org_name','Name',array('id'=>'','class'=>'')) }}
								{{ Form::text('org_name', Settings::getOrgName() ,array('id'=>'org_name','class'=>'form-control')) }}
							</div>
							<div class="form-group">
								{{ Form::label('org_tagline','Tagline/Title',array('id'=>'','class'=>'')) }}
								{{ Form::text('org_tagline', Settings::getOrgTagline() ,array('id'=>'org_tagline','class'=>'form-control')) }}
							</div>
							<div class="form-group">
								{{ Form::label('org_logo','Logo',array('id'=>'','class'=>'')) }}
								{{ Form::file('org_logo',array('id'=>'org_logo','class'=>'form-control')) }}
							</div>
							 <div class="form-group">
								{{ Form::label('org_favicon','Favicon',array('id'=>'','class'=>'')) }}
								{{ Form::file('org_favicon',array('id'=>'org_favicon','class'=>'form-control')) }}
							</div>
							<button type="submit" class="btn btn-success btn-block">Submit</button>
						</div>
						<div class="col-12 col-md-6">
							<div class="form-group">
								@if (trim(Settings::getOrgFavicon()) != '')
									<img class="img img-fluid" src="{{ Settings::getOrgFavicon() }}" />
								@else
									No Favicon uploaded
								@endif
								@if (trim(Settings::getOrgLogo()) != '')
									<img class="img img-fluid" src="{{ Settings::getOrgLogo() }}" />
								@else
									No Logo uploaded
								@endif
							</div>
						</div>
					</div>
				{{ Form::close() }}
			</div>
		</div>
	</div>
	<div class="col-12">
		<!-- About -->
		<div class="card mb-3">
			<div class="card-header">
				<i class="fa fa-info-circle fa-fw"></i> About
			</div>
			<div class="card-body">
				{{ Form::open(array('url'=>'/admin/settings', 'onsubmit' => 'return ConfirmSubmit()')) }}
					<div class="form-group">
						{{ Form::label('about_main','Main',array('id'=>'','class'=>'')) }}
						{{ Form::textarea('about_main', Settings::getAboutMain() ,array('id'=>'about_main','class'=>'form-control wysiwyg-editor')) }}
					</div>
					<div class="form-group">
						{{ Form::label('about_short','Short',array('id'=>'','class'=>'')) }}
						{{ Form::textarea('about_short', Settings::getAboutShort() ,array('id'=>'about_short','class'=>'form-control wysiwyg-editor')) }}
					</div>
					<div class="form-group">
						{{ Form::label('about_our_aim','Our Aim',array('id'=>'','class'=>'')) }}
						{{ Form::textarea('about_our_aim', Settings::getAboutOurAim() ,array('id'=>'about_our_aim','class'=>'form-control wysiwyg-editor')) }}
					</div>
					<div class="form-group">
						{{ Form::label('about_who','Who' ,array('id'=>'','class'=>'')) }}
						{{ Form::textarea('about_who', Settings::getAboutWho() ,array('id'=>'about_who','class'=>'form-control wysiwyg-editor')) }}
					</div>
					<div class="form-group">
						{{ Form::label('legal_notice','LegalNotice' ,array('id'=>'','class'=>'')) }}
						{{ Form::textarea('legal_notice', Settings::getLegalNotice() ,array('id'=>'legal_notice','class'=>'form-control wysiwyg-editor')) }}
					</div>
					<div class="form-group">
						{{ Form::label('privacy_policy','PrivacyPolicy' ,array('id'=>'','class'=>'')) }}
						{{ Form::textarea('privacy_policy', Settings::getPrivacyPolicy() ,array('id'=>'privacy_policy','class'=>'form-control wysiwyg-editor')) }}
					</div>
					<button type="submit" class="btn btn-success btn-block">Submit</button>
				{{ Form::close() }}
			</div>
		</div>
	</div>
</div>

@endsection