@extends('layouts.dashboard')
@section('title', 'Update video slot')
@section('page-header', 'Update video slot')
@section('page-description', 'Update video slot')
@section('page-content')
@section('header-scripts')

    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
    <link rel="stylesheet" href="{{ asset('css/file-upload.css') }}">
    <script>
        //todo
        var static_images_path = '{{ asset('images/static/') }}';
    </script>
@endsection

@section('breadcrumb')

    <ol class="breadcrumb">
        <li><a href="{{ route('video.index') }}"><i class="fa fa-dashboard"></i> All video slots</a></li>
        <li class="active">Edit video slot</li>
    </ol>

@endsection

<div class="row">

    <div class="col-sm-12">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">video slot info</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form class="form-horizontal" action="{{ route('video_slot.update', $video_slot->id) }}" method="post"
                  enctype="multipart/form-data">
                @csrf
                <div class="box-body">
                    <div class="form-group">
                        <label for="video_id" class="col-sm-3 control-label">Video ID</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="video_id" name="video_id"
                                   placeholder="video ID" value="{{$video_slot->video_id}}" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="featured_sentence" class="col-sm-3 control-label">Featured Sentence</label>
                        <div class="col-sm-9">
                            <input required type="text" class="form-control" id="featured_sentence" name="featured_sentence"
                                   placeholder="Featured Sentence" value="{{$video_slot->featured_sentence}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="start_time" class="col-sm-3 control-label">Start at</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control  html-duration-picker" id="start_time" name="start_time" value="{{$video_slot->start_time}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="end_time" class="col-sm-3 control-label">End at</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control html-duration-picker" id="end_time" name="end_time" value="{{$video_slot->end_time}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="slots_category" class="col-sm-3 control-label">Slot Category</label>
                        <div class="col-sm-9">
                            <select name="slots_category" id="slots_category" class="form-control">
                                <option value="">-------------------</option>
                                @foreach($slots_categories as $slots_category)
                                    <option {{$video_slot->slots_category ==  $slots_category?'selected':''}} value="{{$slots_category->id}}">{{$slots_category->title}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group file-upload {{ empty($video_slot->featured_image)?"":"file-edit" }}"
                         id="file-upload">
                        <label for="featured_image" class="col-sm-3 control-label">Featured Image</label>
                        <div class="col-sm-9">
                            <div class="image-box text-center">
                                <p>Upload Image</p>
                                {{$video_slot->featured_imag}}
                                @if(!empty($video_slot->featured_image))
                                    <img src="{{ asset('images/video/'.$video_slot->featured_image) }}"
                                         class="img-thumbnail"/>
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
