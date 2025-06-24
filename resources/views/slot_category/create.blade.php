@extends('layouts.dashboard')
@section('title', 'Create slot category')
@section('page-header', 'Create slot category')
@section('page-description', 'Create new slot category')
@section('page-content')
@section('header-scripts')

    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
    <link rel="stylesheet" href="{{ asset('css/file-upload.css') }}">

@endsection

@section('breadcrumb')

    <ol class="breadcrumb">
        <li><a href="{{ route('slot_category.index') }}"><i class="fa fa-dashboard"></i> All slot categories</a></li>
        <li class="active">Create slot category</li>
    </ol>

@endsection

<div class="row">

    <div class="col-sm-12">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Slot Category info</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form class="form-horizontal" action="{{ route('slot_category.store') }}" method="post"  enctype="multipart/form-data">
                @csrf
                <div class="box-body">
                    <div class="form-group">
                        <label for="title" class="col-sm-3 control-label">Title</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="title" name="title" placeholder="Title">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="content" class="col-sm-3 control-label">Description</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" id="description" name="description"></textarea>
                        </div>
                    </div>

                    <div class="form-group file-upload" id="file-upload">
                        <label for="image" class="col-sm-3 control-label">Image</label>
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
