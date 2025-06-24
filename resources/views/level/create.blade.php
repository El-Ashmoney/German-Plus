@extends('layouts.dashboard')
@section('title', 'Create level')
@section('page-header', 'Create level')
@section('page-description', 'Create new level')
@section('page-content')
@section('header-scripts')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
    <link rel="stylesheet" href="{{ asset('css/file-upload.css') }}">
    <link rel="stylesheet" href="{{ asset('css/file-upload.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/iCheck/all.css') }}">
    <link rel="stylesheet" href="{{ asset('css/jquery.tagsinput.css') }}">
@endsection


@section('breadcrumb')

    <ol class="breadcrumb">
        <li><a href="{{ route('level.index') }}"><i class="fa fa-dashboard"></i> All levels</a></li>
        <li class="active">Create level</li>
    </ol>

@endsection

<div class="row">

    <div class="col-sm-12">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">level info</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form class="form-horizontal" action="{{ route('level.store') }}" method="post"
                  enctype="multipart/form-data">
                @csrf
                <div class="box-body">
                    <div class="form-group">
                        <label for="level_title" class="col-sm-3 control-label">Level title</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <input type="text" class="form-control" id="level_title" name="title"
                                       placeholder="Level title" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="level_index" class="col-sm-3 control-label">Level index</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <input type="number" class="form-control" id="level_index" name="index"
                                       placeholder="Level index">
                            </div>
                        </div>
                    </div>

                    <div class="form-group file-upload" id="file-upload">
                        <label for="image" class="col-sm-3 control-label">Level image</label>
                        <div class="col-sm-9">
                            <div class="image-box text-center">
                                <p>Upload Image</p>
                                <img src="" alt="">
                                <p class="help-block">only jpeg,png,jpg,gif,svg and max size is 2048 KB</p>
                            </div>
                            <div class="controls">
                                <input type="file" id="image" name="image"/>
                            </div>

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

    <script src="{{ asset('js/file-upload.js') }}"></script>

@endsection
