@extends('layouts.dashboard')
@section('title', 'All categories')
@section('page-header', 'All categories')
@section('page-description', 'Show all categories')
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
                <th>Title</th>
                <th>Description</th>
                <th>Action</th>
              </tr>
              </thead>
              <tbody>
              @foreach ($categories as $category)
              <tr>
                <td>{{ $category->title }}</td>
                <td>{{ $category->description }}</td>
                <td>
                <a href="{{ route('slot_category.edit', $category->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> Edit</a>
                <form action="{{ route('slot_category.destroy', $category->id) }}" method="POST" class="delete-form">
                    @method('DELETE')
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</button>
                </form>
                </td>
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
