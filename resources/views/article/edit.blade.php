@extends('layouts.dashboard')
@section('title', 'Update article')
@section('page-header', 'Update article')
@section('page-description', 'Update article')
@section('page-content')
@section('header-scripts')

<link rel="stylesheet" href="{{ asset('css/global.css') }}">
<link rel="stylesheet" href="{{ asset('css/file-upload.css') }}">

@endsection

@section('breadcrumb')

<ol class="breadcrumb">
    <li><a href="{{ route('article.index') }}"><i class="fa fa-dashboard"></i> All articles</a></li>
    <li class="active">Edit article</li>
</ol>

@endsection

<div class="row">

    <div class="col-sm-12">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Article info</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form class="form-horizontal" action="{{ route('article.update', $article->id) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="box-body">
                    <div class="form-group">
                        <label for="title" class="col-sm-3 control-label">Title</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="title" name="title" placeholder="Title" value="{{$article->title}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="slug" class="col-sm-3 control-label">Slug</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="slug" name="slug" placeholder="Slug" value="{{$article->slug}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="content" class="col-sm-3 control-label">Content</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" id="content" name="content">{{$article->content}}</textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="category_id" class="col-sm-3 control-label">Category</label>
                        <div class="col-sm-9">
                            <select class="form-control" name="category_id" id="category_id">
                                @foreach($categories as $category)
                                    @if($category->id == $article->Category->id )
                                        <option selected value='{{ $category->id }}'>{{ $category->title }}</option>
                                    @else
                                        <option value='{{ $category->id }}'>{{ $category->title }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group file-upload {{ empty($article->image)?"":"file-edit" }}" id="file-upload">
                        <label for="image" class="col-sm-3 control-label">Image</label>
                        <div class="col-sm-9">

                        <div class="image-box text-center">
                            <p>Upload Image</p>
                            @if(!empty($article->image))
                                <img src="{{ asset('images/'.$article->image) }}" alt="{{$article->title}}">
                            @else
                                <img src="" alt="">
                            @endif
                            <p class="help-block">only jpeg,png,jpg,gif,svg and max size is 2048 KB</p>
                        </div>
                        <div class="controls">
                            <input type="file" id="image" name="image" />
                        </div>

                        </div>
                    </div>

                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <button type="submit" class="btn btn-info pull-right">Update</button>
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
