@extends('layouts.admin.default')

@section('content')

  <div id="page-wrapper">
    <div class="row">
      <div class="col-lg-12">
        <h1 class="page-header">News</h1>
      </div>
      <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
      <div class="col-lg-8">
        <div class="panel panel-default">
          <div class="panel-heading">
            News Items
          </div>
          <!-- /.panel-heading -->
          <div class="panel-body">
            <table width="100%" class="table table-striped table-hover" id="seating_table">
              <thead>
                <th></th>
                <th>Title</th>
                <th>Author</th>
                <th>Category</th>
                <th>Tags</th>
                <th>Date Published</th>
              </thead>
              <tbody>
                @foreach($news as $news_item)
                  <tr>
                    <td>

                    </td>
                    <td>
                      <a href="/admin/news/{{ $news_item->id }}" >
                        {{ $news_item->title }}
                      </a>
                    </td>
                    <td>
                      {{ $news_item->user->username }}
                    </td>
                    <td>
                      TBC
                    </td>
                    <td>
                      TBC
                    </td>
                    <td>
                      {{ $news_item->created_at }}
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          <!-- /.panel-body -->
        </div>
      </div>
      <!-- /.col-lg-8 -->
      <div class="col-lg-4">
        <div class="panel panel-default">
          <div class="panel-heading">
            New Post
          </div>
          <!-- /.panel-heading -->
          <div class="panel-body">
            {{ Form::open(array('url'=>'/admin/news')) }}
              <div class="form-group">
                {{ Form::label('title','Title',array('id'=>'','class'=>'')) }}
                {{ Form::text('title', null, array('id'=>'title','class'=>'form-control')) }}
              </div>
                <div class="form-group">
                {{ Form::label('article','Article',array('id'=>'','class'=>'')) }}
                {{ Form::textarea('article', NULL,array('id'=>'article','class'=>'form-control')) }}
              </div>
              <button type="submit" class="btn btn-default">Submit</button>
            {{ Form::close() }}
          </div>
        </div>
      </div>
      <!-- /.col-lg-4 -->
    </div>
    <!-- /.row -->
  </div>

@endsection
