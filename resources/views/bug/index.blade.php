@extends('layouts.dashboard')
@section('title', 'All Slot categories')
@section('page-header', 'All Slot categories')
@section('page-description', 'Show all slot categories')
@section('header-scripts')

<link rel="stylesheet" href="{{ asset('assets/bower/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/global.css') }}">

@endsection

@section('breadcrumb')

<ol class="breadcrumb">
    <li><a href="{{ route('admin.index') }}"><i class="fa fa-dashboard"></i> overview</a></li>
    <li class="active">All categories</li>
</ol>

@endsection
@section('page-content')

<div class="row">

    <div class="col-xs-12">
        <div class="box box-info">
          <div class="box-header">
            <h3 class="box-title">Categories info</h3>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
            <table id="categories" class="table table-bordered table-hover">
              <thead>
              <tr>
                <th>Index</th>
                <th>User</th>
                <th>Modal type</th>
                <th>Modal ID</th>
                <th>Text</th>
              </tr>
              </thead>
              <tbody>
              @foreach ($bugs as $bug)
              <tr>
                <td>{{$bug->index}}</td>
                <td>{{ $bug->user_id }}</td>
                <td>{{ $bug->model_type }}</td>
                <td>{{ $bug->model_id }}</td>
                <td>{{ $bug->text }}</td>
              
              </tr>
              @endforeach
              </tfoot>
            </table>
          </div>
          <!-- /.box-body -->
        </div>
        <!-- /.box -->

    </div>

</div>


@endsection



@section('footer-scripts')
<script src="{{ asset('assets/bower/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/bower/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
<script>
    $(function () {
      $('#categories').DataTable()
    })
  </script>
@endsection
