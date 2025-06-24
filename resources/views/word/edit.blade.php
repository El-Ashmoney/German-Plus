@extends('layouts.dashboard')
@section('title', 'Update word')
@section('page-header', 'Update word')
@section('page-description', 'Update word')
@section('page-content')
@section('header-scripts')

    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
    <link rel="stylesheet" href="{{ asset('css/file-upload.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/iCheck/all.css') }}">

@endsection

@section('breadcrumb')

    <ol class="breadcrumb">
        <li><a href="{{ route('word.index') }}"><i class="fa fa-dashboard"></i> All words</a></li>
        <li class="active">Edit word</li>
    </ol>

@endsection

<div class="row">

    <div class="col-sm-12">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">word info</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form class="form-horizontal" action="{{ route('word.update', $word->id) }}" method="post"
                  enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="box-body">
                    <div class="form-group">
                        <label for="german" class="col-sm-3 control-label">German word</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="german" name="german" placeholder="German word"
                                   value="{{$word->german}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="arabic" class="col-sm-3 control-label">Arabic word</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="arabic" name="arabic" placeholder="Arabic word"
                                   value="{{$word->arabic}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="english" class="col-sm-3 control-label">English word</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="english" name="english"
                                   placeholder="English word" value="{{$word->english}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="note" class="col-sm-3 control-label">Note</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" id="note" name="note">{{$word->note}}</textarea>
                        </div>
                    </div>
                    <div class="form-group file-upload  {{ empty($word->sound)?"":"file-edit" }}"
                         id="file-upload">
                        <label for="word_sound" class="col-sm-3 control-label">German word sound</label>
                        <div class="col-sm-9">
                                @if(!empty($word->sound))
                                    <audio controls=""><source src="{{ asset('sounds/'.$word->sound) }}" type="audio/mpeg"></audio>
                                @endif
                                <input type="file" id="word_sound" class="form-control" name="word_sound"/>
                        </div>
                    </div>
                    <div class="form-group file-upload  {{ empty($word->sentence_sound)?"":"file-edit" }}"
                         id="file-upload">
                        <label for="sentence_sound" class="col-sm-3 control-label">German sentence sound</label>
                        <div class="col-sm-9">
                            @if(!empty($word->sentence_sound))
                                <audio controls=""><source src="{{ asset('sounds/'.$word->sentence_sound) }}" type="audio/mpeg"></audio>
                            @endif
                            <input type="file" id="sentence_sound" class="form-control" name="sentence_sound"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="ar_note" class="col-sm-3 control-label">Arabic Note</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" id="ar_note" name="ar_note">{{$word->ar_note}}</textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="category" class="col-sm-3 control-label">Category</label>
                        <div class="col-sm-9">
                            <select name="category_id" id="category" class="form-control">
                                <option value="">-------------</option>
                                @foreach($categories as $category)
                                    @if($category == $word->category)
                                        <option value="{{$category->id}}" selected>{{$category->title}}</option>
                                    @else
                                        <option value="{{$category->id}}">{{$category->title}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group file-upload {{ empty($word->image)?"":"file-edit" }}" id="file-upload">
                        <label for="image" class="col-sm-3 control-label">Image</label>
                        <div class="col-sm-9">

                            <div class="image-box text-center">
                                <p>Upload Image</p>
                                @if(!empty($word->image))
                                    <img src="{{ asset('images/words/'.$word->image) }}" alt="{{$word->title}}">
                                @else
                                    <img src="" alt="">
                                @endif
                                <p class="help-block">only jpeg,png,jpg,gif,svg and max size is 2048 KB</p>
                            </div>
                            <div class="controls">
                                <input type="file" id="image" name="image"/>
                            </div>

                        </div>
                    </div>
                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" checked name="is_valid" class="flat-red">
                                Is completed
                            </label>
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
    <script src="{{asset('assets/bower/ckeditor/ckeditor.js')}}"></script>
    <script src="{{ asset('assets/plugins/iCheck/icheck.min.js') }}"></script>

    <script>
        $(function () {
            // Replace the <textarea id="editor1"> with a CKEditor
            // instance, using default configuration.
            CKEDITOR.replace('content')

        })
        //Flat red color scheme for iCheck
        $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
            checkboxClass: 'icheckbox_flat-green',
            radioClass: 'iradio_flat-green'
        })
    </script>
    <script src="{{ asset('js/file-upload.js') }}"></script>

@endsection
