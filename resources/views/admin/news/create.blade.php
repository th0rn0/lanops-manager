@extends('layouts.default')

@section('title', 'News Feed Create')

@section('content')

    <h1>New Article</h1>
    {{ Form::open(array('route' => 'admin.news.store')) }}

    	@include('layouts._partials._news.form')

		{{ Form::submit('Create') }}

	{{ Form::close() }}

@endsection