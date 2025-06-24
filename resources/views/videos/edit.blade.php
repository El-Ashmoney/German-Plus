@extends('layouts.dashboard')
@section('title', 'Update video')
@section('page-header', 'Update video')
@section('page-description', 'Update new video')
@section('page-content')
@section('header-scripts')

    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
    <link rel="stylesheet" href="{{ asset('css/file-upload.css') }}">

@endsection

@section('breadcrumb')

    <ol class="breadcrumb">
        <li><a href="{{ route('video.index') }}"><i class="fa fa-dashboard"></i> All videos</a></li>
        <li class="active">Update video</li>
    </ol>

@endsection

<div class="row">

    <div class="col-sm-12">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">video info</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form class="form-horizontal" action="{{ route('video.update', $video->id) }}" method="post"
                  enctype="multipart/form-data">
                @csrf
                <div class="box-body">
                    <div class="form-group">
                        <label for="video_id" class="col-sm-3 control-label">Video ID</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="video_id" name="video_id"
                                   placeholder="Youtube Video ID" value="{{$video->video_id}}">
                        </div>
                    </div>

                    <div class="form-group file-upload {{ empty($video->featured_image)?"":"file-edit" }}"
                         id="file-upload">
                        <label for="featured_image" class="col-sm-3 control-label">Featured Image</label>
                        <div class="col-sm-9">
                            <div class="image-box text-center">
                                <p>Upload Image</p>
                                @if(!empty($video->featured_image))
                                    <img src="{{ asset('images/videos/'.$video->featured_image) }}"
                                         class="img-thumbnail sm-img"/>
                                @else
                                    <img src="" alt="">
                                @endif
                                <p class="help-block">only jpeg,png,jpg,gif,svg and max size is 2048 KB</p>
                            </div>
                            <div class="controls">
                                <input type="file" id="featured_image" name="featured_image"/>
                            </div>

                        </div>
                    </div>

                    <div class="form-group file-upload  {{ empty($video->german_script)?"":"file-edit" }}"
                         id="file-upload">
                        <label for="german_script" class="col-sm-3 control-label">German Script</label>
                        <div class="col-sm-9">
                            <div class="image-box text-center" data-type="file">
                                <p>Upload File</p>
                                @if(!empty($video->german_script))
                                    <img src="{{ asset('images/static/file_image.png') }}"
                                         class="img-thumbnail sm-img"/>
                                @else
                                    <img src="" alt="">
                                @endif
                                <p class="help-block">only srt, vtt and max size is 2048 KB</p>
                            </div>
                            <div class="controls">
                                <input type="file" id="german_script" name="german_script"/>
                            </div>

                        </div>
                    </div>

                    <div class="form-group file-upload  {{ empty($video->arabic_script)?"":"file-edit" }}" id="file-upload">
                        <label for="arabic_script" class="col-sm-3 control-label">Arabic Script</label>
                        <div class="col-sm-9">
                            <div class="image-box text-center" data-type="file">
                                <p>Upload File</p>
                                @if(!empty($video->arabic_script))
                                    <img src="{{ asset('images/static/file_image.png') }}"
                                         class="img-thumbnail sm-img"/>
                                @else
                                    <img src="" alt="">
                                @endif
                                <p class="help-block">only srt, vtt and max size is 2048 KB</p>
                            </div>
                            <div class="controls">
                                <input type="file" id="arabic_script" name="arabic_script"/>
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
    <script src="{{ asset('js/file-upload.js') }}"></script>
@endsection
