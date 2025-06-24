@extends('layouts.dashboard')
@section('title', 'Create comment')
@section('page-header', 'Create comment')
@section('page-description', 'Create new comment')
@section('page-content')
@section('header-scripts')

<link rel="stylesheet" href="{{ asset('css/global.css') }}">
<link rel="stylesheet" href="{{ asset('css/file-upload.css') }}">

@endsection

@section('breadcrumb')

<ol class="breadcrumb">
    <li><a href="{{ route('article.index') }}"><i class="fa fa-dashboard"></i> All comments</a></li>
    <li class="active">Create comment</li>
</ol>

@endsection

<div class="row">

    <div class="col-sm-12">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Comment info</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form class="form-horizontal" action="{{ route('comment.store') }}" method="post" >
                @csrf
                <div class="box-body">
                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">Name</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="name" name="name" placeholder="Name">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email" class="col-sm-3 control-label">Email</label>
                        <div class="col-sm-9">
                            <input type="email" class="form-control" id="email" name="email" placeholder="Email">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="comment" class="col-sm-3 control-label">Comment</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" id="comment" name="comment"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="approved" class="col-sm-3 control-label">Approved</label>
                        <div class="col-sm-9">
                            <select class="form-control" id="approved" name="approved">
                                <option value="1">Approved</option>
                                <option value="0">Pending</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="article_id" class="col-sm-3 control-label">Article</label>
                        <div class="col-sm-9">
                            <select class="form-control" id="article_id" name="article_id">
                                @foreach($articles as $article)
                                    <option value='{{ $article->id }}'>{{ $article->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <button type="submit" class="btn btn-info pull-right">Create</button>
                </div>
                <!-- /.box-footer -->
            </form>
            @include('partials.formerrors')
    
        </div>
    </div>

</div>


@endsection

@section('footer-scripts')
<!-- CK Editor -->
<script src="{{asset('assets/bower/ckeditor/ckeditor.js')}}"></script>
<script>
$(function () {
    // Replace the <textarea id="editor1"> with a CKEditor
    // instance, using default configuration.
    CKEDITOR.replace('content')

})
</script>
<script src="{{ asset('js/file-upload.js') }}"></script>
@endsection