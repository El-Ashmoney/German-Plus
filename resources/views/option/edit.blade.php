@extends('layouts.dashboard')

@section('title', 'Options')
@section('page-header', 'Options')
@section('page-description', 'Change your options')
@section('page-content')
@section('header-scripts')

<link rel="stylesheet" href="{{ asset('css/global.css') }}">
<link rel="stylesheet" href="{{ asset('css/file-upload.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/iCheck/all.css') }}">

@endsection

@section('breadcrumb')

<ol class="breadcrumb">
    <li><a href="{{ route('word.index') }}"><i class="fa fa-dashboard"></i> Options</a></li>
    <li class="active">Edit word</li>
</ol>

@endsection

<div class="row">

    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Privacy policy</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form role="form" action="{{Route('option.update')}}" method="post">
              <div class="box-body row">
                @csrf
                <div class="form-group col-md-6">
                    <textarea class="form-control" id="privacy_policy" name="privacy_policy">{{ isset($options['privacy_policy'])?$options['privacy_policy']:"" }}</textarea>
                </div>
                  <div class="form-group col-md-6">
                      <textarea class="form-control" id="service_terms" name="service_terms">{{ isset($options['service_terms'])?$options['service_terms']:""  }}</textarea>
                  </div>
              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Update</button>
              </div>
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
    CKEDITOR.replace('privacy_policy');
})
$(function () {
    CKEDITOR.replace('service_terms');
})

</script>
<script src="{{ asset('js/file-upload.js') }}"></script>

@endsection
