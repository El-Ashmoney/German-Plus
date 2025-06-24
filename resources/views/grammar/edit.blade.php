@extends('layouts.dashboard')
@section('title', 'Update grammar')
@section('page-header', 'Update grammar')
@section('page-description', 'Update grammar')
@section('page-content')
@section('header-scripts')

<link rel="stylesheet" href="{{ asset('css/global.css') }}">
<link rel="stylesheet" href="{{ asset('css/file-upload.css') }}">

@endsection

@section('breadcrumb')

<ol class="breadcrumb">
    <li><a href="{{ route('grammar.index') }}"><i class="fa fa-dashboard"></i> All grammars</a></li>
    <li class="active">Edit grammar</li>
</ol>

@endsection

<div class="row">

    <div class="col-sm-12">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Grammar info</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form class="form-horizontal" action="{{ route('grammar.update', $grammar->id) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="box-body">
                    <div class="form-group">
                        <label for="title" class="col-sm-3 control-label">Title</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="title" name="title" placeholder="Title" value="{{$grammar->title}}">
                        </div>
                    </div>
                 
                    <div class="form-group">
                        <label for="content" class="col-sm-3 control-label">Content</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" id="content" name="content">{{$grammar->content}}</textarea>
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