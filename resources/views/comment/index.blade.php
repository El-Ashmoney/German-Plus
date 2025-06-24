@extends('layouts.dashboard')
@section('title', 'All comments')
@section('page-header', 'All comments')
@section('page-description', 'Show all comments')
@section('header-scripts')

<link rel="stylesheet" href="{{ asset('assets/bower/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/global.css') }}">

@endsection

@section('breadcrumb')

<ol class="breadcrumb">
    <li><a href="{{ route('admin.index') }}"><i class="fa fa-dashboard"></i> overview</a></li>
    <li class="active">All comments</li>
</ol>

@endsection
@section('page-content')

<div class="row">

    <div class="col-xs-12">
        <div class="box box-info">
          <div class="box-header">
            <h3 class="box-title">Comments info</h3>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
            <table id="articles" class="table table-bordered table-hover">
              <thead>
              <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Comment</th>
                <th>Article</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
              </thead>
              <tbody>
              @foreach ($comments as $comment)
              <tr>
                <td>{{ $comment->name }}</td>
                <td>{{ $comment->email }}</td>
                <td>{{ $comment->comment }}</td>
                <td>{{ $comment->Article->title }}</td>
                <td>{{ $comment->approved?'Approved':'Pending' }}</td>
                <td>
                <a href="{{ route('comment.edit', $comment->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> Edit</a>
                <form action="{{ route('comment.destroy', $comment->id) }}" method="POST" class="delete-form">
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
      $('#articles').DataTable()
    })
  </script>
@endsection