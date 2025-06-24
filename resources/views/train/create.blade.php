@extends('layouts.dashboard')
@section('title', 'Create train')
@section('page-header', 'Create train')
@section('page-description', 'Create new train')
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
        <li><a href="{{ route('train.index') }}"><i class="fa fa-dashboard"></i> All trains</a></li>
        <li class="active">Create train</li>
    </ol>

@endsection

<div class="row">

    <div class="col-sm-12">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">train info</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form class="form-horizontal" action="{{ route('train.store') }}" method="post"
                  enctype="multipart/form-data">
                @csrf
                <div class="box-body">
                    <div class="form-group">
                        <label for="word_id" class="col-sm-3 control-label">Word ID</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <input type="number" class="form-control" id="word_id" name="word_id"
                                       placeholder="Word ID" required value="{{isset($word)?$word->id:''}}">
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-primary" disabled id="fetch_word">Fetch</button>
                                </span>
                            </div>
                            <span  id="fetched_word"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="type" class="col-sm-3 control-label">Train type</label>
                        <div class="col-sm-9">
                            <select name="type" id="type" class="form-control">
                                <option value="1">Random</option>
                                <option value="2">Choice</option>
                                <option value="3">Image recognizing</option>
                                <option value="4">Meaning of German word</option>
                                <option value="5">Meaning of Arabic word</option>
                                <option value="6">Order of sentence</option>
                                <option value="7">Voice recognizing</option>
                                <option value="8">Microphone recognizing</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group" id="question_field">
                        <label for="type" class="col-sm-3 control-label" id="question">Question</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" placeholder="If empty it will be extracted from note" name="question" id="question">
                        </div>
                    </div>
                    <div class="form-group" id="random_field">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" checked name="is_choices_random" id="is_choices_random"
                                       class="flat-red">
                                Is random choices
                            </label>
                        </div>
                    </div>
                    <div class="form-group" id="choices_order_field">
                        <label for="note" class="col-sm-3 control-label">Choices ID's</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <input type="text" value="" class="tags form-control" id="choices_order" name="choices_order"
                                       placeholder="Words ID">

                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-primary" disabled id="fetch_words">Fetch</button>
                                </span>
                            </div>
                            <span  id="fetched_words"></span>
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

    <script src="{{ asset('assets/plugins/iCheck/icheck.min.js') }}"></script>
    <script src="{{ asset('js/jquery.tagsinput.min.js') }}"></script>

    <script>
        //Flat red color scheme for iCheck
        $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
            checkboxClass: 'icheckbox_flat-green',
            radioClass: 'iradio_flat-green'
        });
        $('#choices_order_field').hide();
        $('#question_field').hide();

        $("#type").change(function (){
            if($(this).val() == 6 ){
                $("#random_field").hide();
            }else{
                $("#random_field").show();
            }
        });

        $('#is_choices_random').on('ifChecked', function (event) {
            $('#choices_order_field').hide();
        });
        $("#type").change(function (){
            if($(this).val() == 2 ){
                $("#question_field").show();
            }else{
                $("#question_field").hide();
            }
        });



        $('#is_choices_random').on('ifUnchecked', function (event) {
            $('#choices_order_field').show();
        });
        $('#word_id').on('change paste keyup', function () {
            if ($(this).val() != '') {
                $('#fetch_word').removeAttr('disabled');
            } else {
                $('#fetch_word').attr('disabled', true);
            }
        })

        function fetch_word() {
            id = $('#word_id').val();
            $.ajax({
                type: 'POST',
                url: '{{Route("train.fetch_word")}}',
                data: {'id': id},
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function (data) {
                    $('#fetched_word').html( '<span class="badge badge-primary">'+ data['arabic'] + ' / ' + data['german'] + '</span>  ' );
                }
            });
        }
        function fetch_words() {
            ids = $('#choices_order').val();
            $.ajax({
                type: 'POST',
                url: '{{Route("train.fetch_words")}}',
                data: {'ids': ids},
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function (data) {
                    $('#fetched_words').html('');
                    $.each(data, function (index, value){
                        $('#fetched_words').append( '<span class="badge badge-primary">'+ value['arabic'] + ' / ' + value['german'] + '</span>  ' );
                    })
                }
            });
        }
        $("#fetch_word").click(function (){
            fetch_word();
        });

        $("#fetch_words").click(function (){
            fetch_words();
        });


        $( document ).ready(function() {

            $("#choices_order").tagsInput({width:"auto",height:"auto",defaultText:'Add ID',onChange:function (){
                if($(this).val() !== ''){
                    $("#fetch_words").removeAttr('disabled');
                }else{
                    $("#fetch_words").attr('disabled', true);
                }
            }})
        });
        @if(isset($word))
        $('#fetched_word').html( '<span class="badge badge-primary">'+ "{{$word->arabic}}" + ' / ' + "{{$word->german}}" + '</span>  ' );
        $("#fetch_word").removeAttr('disabled');

        @endif

    </script>

@endsection
