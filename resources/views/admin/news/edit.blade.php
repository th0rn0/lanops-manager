@extends('layouts.default')

@section('title', 'News Feed Create')

@section('content')

    <h1>Edit Article</h1>
    {{ Form::model($news, array('route' => 'admin.news.update', $news)) }}

    	@include('layouts._partials._news.form')

		{{ Form::submit('Update') }}

	{{ Form::close() }}

@endsection