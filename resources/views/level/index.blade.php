@extends('layouts.dashboard')
@section('title', 'All levels')
@section('page-header', 'All levels')
@section('page-description', 'Show all levels')
@section('header-scripts')
<meta name="csrf-token" content="{{ csrf_token() }}">

<link rel="stylesheet" href="{{ asset('assets/bower/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/global.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/iCheck/all.css') }}">

<script src = "https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<style>
    .example-modal .modal {
      position: relative;
      top: auto;
      bottom: auto;
      right: auto;
      left: auto;
      display: block;
      z-index: 1;
    }

    .example-modal .modal {
      background: transparent !important;
    }
  </style>
@endsection

@section('breadcrumb')

<ol class="breadcrumb">
    <li><a href="{{ route('admin.index') }}"><i class="fa fa-dashboard"></i> Overview</a></li>
    <li class="active">All levels</li>
</ol>

@endsection
@section('page-content')

<div class="row">

    <div class="col-xs-12">
        <div class="box box-primary" id="levels_list">
            <div class="box-header">
                <h3 class="box-title">Levels</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                @if( !$levels->isEmpty() )
                    <ul class="levels-list todo-list">
                        @foreach($levels as $level)
                            <li id="level_{{$level->id}}" data-id="{{ $level->id}}">
                                <!-- drag handle -->
                                <span class="handle">
                                    <i class="fa fa-ellipsis-v"></i>
                                    <i class="fa fa-ellipsis-v"></i>
                                </span>
                                <span class="text">{{$level->title}}</span>
                                <div class="tools">
                                    <a href="{{ route('level.edit', $level->id) }}" class="btn btn-success btn-xs">
                                        Edit
                                        <i class="fa fa-edit modal-edit" data-title="{{$level->title}}" data-id="{{$level->id}}"></i>
                                    </a>
                                    <form action="{{ route('level.destroy', $level->id) }}" method="POST" class="delete-form">
                                        @method('DELETE')
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> Delete</button>
                                    </form>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <ul class="todo-list">
                    </ul>
                    <p class="no_msg">No levels</p>
                @endif
            </div>
            <!-- /.box-body -->
            <div class="box-footer clearfix no-border">
                <button type="button" class="btn btn-default pull-right" onclick="save_order()">Save</button>
            </div>
        </div>
    </div>

</div>


<div class="modal modal-success fade" id="modal-success">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Success</h4>
        </div>
        <div class="modal-body">
          <p></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Close</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

@endsection



@section('footer-scripts')
    <script src="{{ asset('assets/bower/jquery-ui/jquery-ui.min.js') }}"></script>
    <script>

        // jQuery UI sortable
        $('.levels-list').sortable({
            placeholder         : 'sort-highlight',
            handle              : '.handle',
            forcePlaceholderSize: true,
            zIndex              : 999999,
        });

        function save_order(){
            var lavels_order = [];
            $('.levels-list li').each(function (index){
                lavels_order.push({'index': index, 'id': $(this).data('id')});
            });
            $.ajax({
                type:'POST',
                url:'{{Route("level.save_order")}}',
                data: {'orders': lavels_order},
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success:function(data) {
                    
                }
            });
        }

    </script>
@endsection
