	    {{ Form::label('title', 'Article Title', array('class' => 'article-title')) }}
		{{ Form::text('title') }}

		{{ Form::label('article', 'Article Content', array('class' => 'article-content')) }}
		{{ Form::textarea('article') }}